<?php

namespace App\Controller;

use Dompdf\Dompdf;
use Dompdf\Options;
use DateTimeImmutable;
use App\Entity\Quote;
use App\Entity\Customer;
use App\Form\CustomerType;
use App\Entity\QuoteItem;
use App\Entity\QuoteStatus;
use App\Form\QuoteCreateType;
use App\Form\QuoteSearchType;

use App\Repository\InvoiceRepository;
use App\Repository\QuoteRepository;
use App\Service\QuoteToInvoiceConverter;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Attachment;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/quotes', name: 'quote_')]
#[IsGranted("ROLE_USER")]
class QuoteController extends AbstractController
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    #[Route('', name: 'index', methods: ['GET', 'POST'])]
    public function index(
        QuoteRepository    $quoteRepository,
        Request            $request,
        PaginatorInterface $paginator
    ): Response
    {

        $form = $this->createForm(QuoteSearchType::class, null, ["method" => "POST"]);
        $form->handleRequest($request);

        $company = null;

        if ($this->isGranted('ROLE_ADMIN')) {
            $quotes = $quoteRepository->findAll();
        } else {
            $company = $this->getUser()->getCompany();
            if (!$company) {
                throw $this->createNotFoundException('Entreprise non trouvée');
            }
            $quotes = $quoteRepository->findBy(['company' => $company]);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $searchResult = $request->request->all("quote_search");

            $isAdmin = $this->isGranted('ROLE_ADMIN') ? true : false;

            $quotes = $quoteRepository->findBySearch($searchResult, $company, $isAdmin);
        }

        $quotes = $paginator->paginate(
            $quotes,
            $request->query->getInt('page', 1),
            20
        );

        return $this->render('quote/index.html.twig', [
            'quotes' => $quotes,
            "searchForm" => $form->createView()
        ]);
    }


    #[Route('/new', name: 'new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $quote = new Quote;

        $form = $this->createForm(QuoteCreateType::class, $quote);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $quote->setCompany($this->getUser()->getCompany());


            foreach ($quote->getQuoteItems() as $item) {
                //calcul de priceIncludingTax pour chaque quoteItem
                $tax = $item->getTax()->getValue() * $item->getPriceExcludingTax() / 100;

                $item->setTaxAmount($tax);

                $item->setPriceIncludingTax($item->getPriceExcludingTax() + $tax);
            }

            $entityManager->persist($quote);
            $quote->setQuoteNumber(1);
            $entityManager->flush();

            //On set le quotenumber après le flush car avant le flush l'id n'est pas généré
            $uuid = $quote->getId()->__toString();
            $quote->setQuoteNumber(substr($uuid, strrpos($uuid, '-') + 1));

            //on flush de nouveau pour mettre à jour $quote 
            $entityManager->flush();


            return $this->redirectToRoute('quote_new'); //todo: mettre la route des devis
        }

        //Création formulaire création client

        $customer = new Customer();
        $customerForm = $this->createForm(CustomerType::class, $customer);
        $customerForm->handleRequest($request);
        if ($customerForm->isSubmitted() && $customerForm->isValid()) {
            $entityManager->persist($customer);
            $entityManager->flush();

            // Retourne l'ID du nouveau client sous forme de réponse JSON
            return new JsonResponse([
                'newClientId' => $customer->getId(),
                'newCustomerName' => $customer->getName(), // Assurez-vous que votre entité Customer a une méthode getName()
            ]);
        }

        $type = 'new';

        return $this->render('quote/new_edit.html.twig', [
            'form' => $form,
            'customerForm' => $customerForm->createView(),
            'type' => $type,
            'typeDocument' => 'quote'
        ]);
    }


    #[Route('/customer/new', name: 'customer_new', methods: ['POST'])]
    public function createCustomer(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $customer = new Customer();
        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($customer);
            $entityManager->flush();

            return new JsonResponse([
                'newClientId' => $customer->getId(),
                'newCustomerName' => $customer->getName(),
                'type_create_customer' => '#quote_create_customer'
            ]);
        }

        // Gérer l'échec de la soumission du formulaire si nécessaire
        return new JsonResponse(['error' => 'Invalid _form'], 400);
    }

    #[Route('/{id}', name: 'show')]
    public function show(Quote $quote): Response
    {

        $invoices = $quote->getInvoices();

        return $this->render('quote/show.html.twig', [
            'quote' => $quote,
            'invoices' => $invoices
        ]);
    }

    #[Route('/{id}/edit', name: 'edit')]
    public function edit(Request $request, Quote $quote, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(QuoteCreateType::class, $quote);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($quote->getQuoteItems() as $item) {
                //calcul de priceIncludingTax pour chaque quoteItem
                $tax = $item->getTax()->getValue() * $item->getPriceExcludingTax() / 100;

                $item->setTaxAmount($tax);

                $item->setPriceIncludingTax($item->getPriceExcludingTax() + $tax);
            }

            $em->flush();

            return $this->redirectToRoute('quote_show', ['id' => $quote->getId()]);
        }

        $customer = new Customer();
        $customerForm = $this->createForm(CustomerType::class, $customer);
        $customerForm->handleRequest($request);
        if ($customerForm->isSubmitted() && $customerForm->isValid()) {
            $em->persist($customer);
            $em->flush();

            // Retourne l'ID du nouveau client sous forme de réponse JSON
            return new JsonResponse([
                'newClientId' => $customer->getId(),
                'newCustomerName' => $customer->getName(), // Assurez-vous que votre entité Customer a une méthode getName()
            ]);
        }

        $type = 'edit';

        return $this->render('quote/new_edit.html.twig', [
            'form' => $form->createView(),
            'quote' => $quote,
            'customerForm' => $customerForm->createView(),
            'type' => $type
        ]);
    }


    #[Route('/convert/{id}', name: 'convert')]
    public function convert(Quote $quote, QuoteToInvoiceConverter $quoteToInvoiceConverter): Response
    {

        if (!$quote) {
            $this->addFlash('error', 'Le devis demandé n\'existe pas.');
            return $this->redirectToRoute('default_index');
        }

        $invoice = $quoteToInvoiceConverter->convert($quote);

        $this->addFlash('success', 'La facture créée à partir du devis.');

        return $this->redirectToRoute(
            'invoice_show', ['id' => $invoice->getId()],
        );
    }

    #[Route('/{id}/pdf', name: 'pdf')]
    public function pdf(QuoteRepository $quoteRepository, MailerInterface $mailer, string $id, Quote $quotes): Response
    {
        $quote = $quoteRepository->find($id);


        if (!$quote) {
            throw $this->createNotFoundException('La devis demandée n\'a pas été trouvée.');
        }

        // Configurez Dompdf selon vos besoins
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($pdfOptions);


        // Générez le HTML à partir de votre template Twig pour la devis
        $html = $this->renderView('quote/pdf.html.twig', [
            'quotes' => $quotes
        ]);
        $templateMail = $this->renderView('quote/mail.html.twig', [
            'quotes' => $quotes
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("devis-{$id}.pdf", [
            "Attachment" => false // Changez à true pour forcer le téléchargement
        ]);

        $pdfPath = sys_get_temp_dir() . '/devis-' . $id . '.pdf'; // Génère un nom de fichier unique
        file_put_contents($pdfPath, $dompdf->output()); // Sauvegarde le contenu du PDF dans le fichier

        unlink($pdfPath);
        // Envoyez le PDF au navigateur

        return new Response('', 200, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    #[Route('/{id}/sendpdf', name: 'sendpdf')]
    public function sendPdf(QuoteRepository $quoteRepository, MailerInterface $mailer, string $id, Quote $quotes): Response
    {
        $quote = $quoteRepository->find($id);


        if (!$quote) {
            throw $this->createNotFoundException('La devis demandée n\'a pas été trouvée.');
        }

        // Configurez Dompdf selon vos besoins
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($pdfOptions);


        // Générez le HTML à partir de votre template Twig pour la devis
        $html = $this->renderView('quote/pdf.html.twig', [
            'quotes' => $quotes
        ]);
        $templateMail = $this->renderView('quote/mail.html.twig', [
            'quotes' => $quotes
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("devis-{$id}.pdf", [
            "Attachment" => false // Changez à true pour forcer le téléchargement
        ]);

        $pdfPath = sys_get_temp_dir() . '/devis-' . $id . '.pdf'; // Génère un nom de fichier unique
        file_put_contents($pdfPath, $dompdf->output()); // Sauvegarde le contenu du PDF dans le fichier

        $mailCustomer = $quotes->getCustomer()->getEmail();

        $email = (new Email())
            ->from('challengesemestre@hotmail.com')
            ->to($mailCustomer)
            ->subject('Votre facture')
            ->text('Veuillez trouver ci-joint votre facture.')
            ->html($templateMail);


        $email->attachFromPath($pdfPath, 'facture.pdf', 'application/pdf');


        $this->mailer->send($email);


        unlink($pdfPath);
        // Envoyez le PDF au navigateur

        return new Response('', 200, [
            'Content-Type' => 'application/pdf',
        ]);
    }
}

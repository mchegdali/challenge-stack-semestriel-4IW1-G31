<?php

namespace App\Controller;

use Dompdf\Dompdf;
use Dompdf\Options;
use DateTimeImmutable;
use App\Entity\Invoice;
use App\Entity\Customer;
use App\Form\CustomerType;
use App\Entity\InvoiceItem;

use App\Entity\InvoiceStatus;
use App\Form\InvoiceCreateType;
use App\Form\InvoiceSearchType;
use Symfony\Component\Mime\Email;
use App\Repository\InvoiceRepository;
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

#[Route('/invoices', name: 'invoice_')]
#[IsGranted("ROLE_USER")]
class InvoiceController extends AbstractController
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    #[Route('', name: 'index', methods: ['GET', 'POST'])]
    public function index(
        InvoiceRepository $invoiceRepository,
        Request           $request,
        PaginatorInterface $paginator
    ): Response
    {

        $form = $this->createForm(InvoiceSearchType::class, null, ["method" => "POST"]);
        $form->handleRequest($request);

        $company = null;

        if ($this->isGranted('ROLE_ADMIN')) {
            $invoices = $invoiceRepository->findAll();
        }
        else{
            $company = $this->getUser()->getCompany();
            if (!$company) {
                throw $this->createNotFoundException('Entreprise non trouvée');
            }
            $invoices = $invoiceRepository->findBy(['company' => $company]);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $searchResult = $request->request->all("invoice_search");

            $isAdmin = $this->isGranted('ROLE_ADMIN') ? true : false;
            
            $invoices = $invoiceRepository->findBySearch($searchResult, $company, $isAdmin);
        }

        $invoices = $paginator->paginate(
            $invoices,
            $request->query->getInt('page', 1),
            20
        );

        return $this->render('invoice/index.html.twig', [
            'invoices' => $invoices,
            "searchForm" => $form->createView()
        ]);
    }


    #[Route('/new', name: 'new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $invoice = new Invoice;

        $form = $this->createForm(InvoiceCreateType::class, $invoice);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $invoice->setCompany($this->getUser()->getCompany());
            $currentDate = new DateTimeImmutable();
            $invoice->setDueAt($currentDate->modify('+30 days'));


            foreach ($invoice->getInvoiceItems() as $item) {
                //calcul de priceIncludingTax pour chaque invoiceItem
                $tax = $item->getTax()->getValue() * $item->getPriceExcludingTax() / 100;

                $item->setTaxAmount($tax);

                $item->setPriceIncludingTax($item->getPriceExcludingTax() + $tax);
            }

            $entityManager->persist($invoice);
            $invoice->setInvoiceNumber(1);
            $entityManager->flush();

            //On set le invoicenumber après le flush car avant le flush l'id n'est pas généré
            $uuid = $invoice->getId()->__toString();
            $invoice->setInvoiceNumber(substr($uuid, strrpos($uuid, '-') + 1));

            //on flush de nouveau pour mettre à jour $invoice 
            $entityManager->flush();


            return $this->redirectToRoute('invoice_new'); //todo: mettre la route des devis
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

        return $this->render('invoice/new.html.twig', [
            'form' => $form,
            'customerForm' => $customerForm->createView(),
            'type' => $type
        ]);
    }

    #[Route('/{id}', name: 'show')]
    public function show(Invoice $invoice): Response
    {
        return $this->render('invoice/show.html.twig', [
            'invoice' => $invoice,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit')]
    public function edit(Request $request, Invoice $invoice, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(InvoiceCreateType::class, $invoice);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('invoice_show', ['id' => $invoice->getId()]);
        }

        return $this->render('invoice/edit.html.twig', [
            'form' => $form->createView(),
            'invoice' => $invoice,
        ]);
    }

    #[Route('/{id}/pdf', name: 'invoice_pdf')]
    public function pdf(InvoiceRepository $invoiceRepository, MailerInterface $mailer, string $id, Invoice $invoices): Response
    {
        $invoice = $invoiceRepository->find($id);


        if (!$invoice) {
            throw $this->createNotFoundException('La facture demandée n\'a pas été trouvée.');
        }

        // Configurez Dompdf selon vos besoins
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($pdfOptions);


        // Générez le HTML à partir de votre template Twig pour la facture
        $html = $this->renderView('invoice/pdf.html.twig', [
            'invoices' => $invoices
        ]);
        $templateMail = $this->renderView('invoice/mail.html.twig', [
            'invoices' => $invoices
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("facture-{$id}.pdf", [
            "Attachment" => false // Changez à true pour forcer le téléchargement
        ]);

        $pdfPath = sys_get_temp_dir() . '/facture-' . $id . '.pdf'; // Génère un nom de fichier unique
        file_put_contents($pdfPath, $dompdf->output()); // Sauvegarde le contenu du PDF dans le fichier

        $mailCustomer = $invoices->getCustomer()->getEmail();
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

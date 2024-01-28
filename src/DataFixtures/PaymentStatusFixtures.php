<?php

namespace App\DataFixtures;

use App\Entity\Payment;
use App\Entity\PaymentStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PaymentStatusFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $paymentsStatuses = [
            [
                "name" => "Non payé",
                "bgColor" => "blue-50",
                "textColor" => "blue-700",
                "borderColor" => "blue-700"
            ], 
            [
                "name" => "En attente",
                "bgColor" => "orange-50",
                "textColor" => "orange-700",
                "borderColor" => "orange-700"
            ], 
            [
                "name" => "Partiellement payé",
                "bgColor" => "yellow-50",
                "textColor" => "yellow-700",
                "borderColor" => "yellow-700"
            ], 
            [
                "name" => "Payé",
                "bgColor" => "green-50",
                "textColor" => "green-700",
                "borderColor" => "green-700"
            ], 
            [
                "name" => "En retard",
                "bgColor" => "violet-50",
                "textColor" => "violet-700",
                "borderColor" => "violet-700"
            ], [
                "name" => "Litige",
                "bgColor" => "red-50",
                "textColor" => "red-700",
                "borderColor" => "red-700"
            ], [
                "name" => "Remboursé",
                "bgColor" => "grey-50",
                "textColor" => "grey-700",
                "borderColor" => "grey-700"
            ], [
                "name" => "Annulé",
                "bgColor" => "white",
                "textColor" => "black",
                "borderColor" => "black"
            ]
        ];

        foreach ($paymentsStatuses as $status) {
            $paymentStatus = new PaymentStatus();
            $paymentStatus->setName($status["name"]);
            $paymentStatus->setTextColor($status["textColor"]);
            $paymentStatus->setBgColor($status["bgColor"]);
            $paymentStatus->setBorderColor($status["borderColor"]);
            $manager->persist($paymentStatus);
        }

        $manager->flush();
    }
}

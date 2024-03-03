<?php
namespace App\Utility;

class PasswordGenerator
{
    public static function generatePassword(): string
    {
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $digits = '0123456789';
        $specialChars = '!@#$%^&*()_+-=[]{}|;:,.<>?';

        $allChars = $lowercase . $uppercase . $digits . $specialChars;

        // Mélanger les caractères
        $shuffledChars = str_shuffle($allChars);

        // Sélectionner au moins un caractère de chaque catégorie
        $password = $shuffledChars[mt_rand(0, 25)] // Une lettre minuscule
                  . $shuffledChars[mt_rand(26, 51)] // Une lettre majuscule
                  . $shuffledChars[mt_rand(52, 61)] // Un chiffre
                  . $shuffledChars[mt_rand(62, 77)]; // Un caractère spécial

        // Ajouter d'autres caractères aléatoires pour atteindre la longueur souhaitée
        $password .= substr($shuffledChars, 0, 8);

        // Mélanger à nouveau pour rendre le mot de passe plus aléatoire
        $password = str_shuffle($password);

        return $password;
    }
}

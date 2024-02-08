<?php

namespace App\Config;

enum RolesEnum: string
{
    case Utilisateur = 'ROLE_USER';
    case Comptable = 'ROLE_COMPTABLE';
    case Administrateur = 'ROLE_ADMIN';
    case Entreprise = 'ROLE_COMPANY';
}
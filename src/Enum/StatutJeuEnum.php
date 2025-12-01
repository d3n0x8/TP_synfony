<?php

namespace App\Enum;

enum StatutJeuEnum: string
{
    case POSSEDE = 'POSSEDE';
    case SOUHAITE = 'SOUHAITE';
    case EN_COURS = 'EN_COURS';
    case TERMINE = 'TERMINE';
    case ABANDONNE = 'ABANDONNE';
    case PRETE = 'PRETE';
    case VENDU = 'VENDU';
    case PLATINE = 'PLATINE';

    public function getLabel(): string
    {
        return match ($this) {
            self::POSSEDE => 'Possédé',
            self::SOUHAITE => 'Souhaité',
            self::EN_COURS => 'En cours',
            self::TERMINE => 'Terminé',
            self::ABANDONNE => 'Abandonné',
            self::PRETE => 'Prêté',
            self::VENDU => 'Vendu',
            self::PLATINE => 'Platine',
        };
    }
}
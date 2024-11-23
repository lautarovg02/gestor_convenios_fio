<?php

namespace App\Enums;

enum EntityType: string
{
    case PRIVADA_CON_FINES_DE_LUCRO = 'PRIVADA CON FINES DE LUCRO';
    case PRIVADA_SIN_FINES_DE_LUCRO = 'PRIVADA SIN FINES DE LUCRO';
    case PUBLICA_CON_FINES_DE_LUCRO = 'PÚBLICA CON FINES DE LUCRO';
    case PUBLICA_SIN_FINES_DE_LUCRO = 'PÚBLICA SIN FINES DE LUCRO';
    case MIXTA_CON_FINES_DE_LUCRO = 'MIXTA CON FINES DE LUCRO';
    case MIXTA_SIN_FINES_DE_LUCRO = 'MIXTA SIN FINES DE LUCRO';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}


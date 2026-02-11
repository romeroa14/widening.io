<?php

namespace App\Modules\Odontology\Enums;

enum TipoServicio: string
{
    case CONSULTA = 'consulta';
    case UNICO = 'unico';
    case RECURRENTE = 'recurrente';
}

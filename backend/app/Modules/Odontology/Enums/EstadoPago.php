<?php

namespace App\Modules\Odontology\Enums;

enum EstadoPago: string
{
    case APROBADO = 'aprobado';
    case RECHAZADO = 'rechazado';
    case PENDIENTE = 'pendiente';
}

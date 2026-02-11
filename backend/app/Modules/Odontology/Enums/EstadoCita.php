<?php

namespace App\Modules\Odontology\Enums;

enum EstadoCita: string
{
    case PENDIENTE = 'pendiente';
    case CONFIRMADA = 'confirmada';
    case CANCELADA = 'cancelada';
    case COMPLETADA = 'completada';
}

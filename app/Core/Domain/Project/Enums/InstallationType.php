<?php

namespace App\Core\Domain\Project\Enums;

enum InstallationType: string
{
    case FIBROCIMENTO_MADEIRA = 'Fibrocimento (Madeira)';
    case FIBROCIMENTO_METALICO = 'Fibrocimento (Metálico)';
    case CERAMICO = 'Cerâmico';
    case METALICO = 'Metálico';
    case LAJE = 'Laje';
    case SOLO = 'Solo';
}

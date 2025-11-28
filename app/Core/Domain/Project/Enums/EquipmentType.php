<?php

namespace App\Core\Domain\Project\Enums;

enum EquipmentType: string
{
    case MODULO = 'Módulo';
    case INVERSOR = 'Inversor';
    case MICROINVERSOR = 'Microinversor';
    case ESTRUTURA = 'Estrutura';
    case CABO_VERMELHO = 'Cabo vermelho';
    case CABO_PRETO = 'Cabo preto';
    case STRING_BOX = 'String Box';
    case CABO_TRONCO = 'Cabo Tronco';
    case ENDCAP = 'Endcap';
}

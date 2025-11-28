<?php

namespace App\Core\Domain\Project\ValueObjects;

use App\Core\Domain\Project\Enums\EquipmentType;
use InvalidArgumentException;

class Equipment
{
    public function __construct(
        private EquipmentType $type,
        private int $quantity
    ) {
        if ($quantity <= 0) {
            throw new InvalidArgumentException('Equipment quantity must be greater than zero');
        }
    }

    public function getType(): EquipmentType
    {
        return $this->type;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function updateQuantity(int $quantity): void
    {
        if ($quantity <= 0) {
            throw new InvalidArgumentException('Equipment quantity must be greater than zero');
        }
        $this->quantity = $quantity;
    }

    public function __toString(): string
    {
        return $this->type->value;
    }
}

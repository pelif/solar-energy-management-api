<?php

namespace App\Core\Domain\Client\ValueObjects;

use InvalidArgumentException;

class CpfCnpj
{
    private string $value;
    private string $type; // 'CPF' or 'CNPJ'

    public function __construct(string $value)
    {
        $sanitized = $this->sanitize($value);

        if (!$this->isValid($sanitized)) {
            throw new InvalidArgumentException("Invalid CPF/CNPJ: $value");
        }

        $this->value = $sanitized;
        $this->type = strlen($sanitized) === 11 ? 'CPF' : 'CNPJ';
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function __toString(): string
    {
        return $this->format();
    }

    public function format(): string
    {
        if ($this->type === 'CPF') {
            return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $this->value);
        }
        return preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", $this->value);
    }

    private function sanitize(string $value): string
    {
        return preg_replace('/\D/', '', $value);
    }

    private function isValid(string $value): bool
    {
        if (strlen($value) === 11) {
            return $this->validateCpf($value);
        }
        if (strlen($value) === 14) {
            return $this->validateCnpj($value);
        }
        return false;
    }

    private function validateCpf(string $cpf): bool
    {
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }
        return true;
    }

    private function validateCnpj(string $cnpj): bool
    {
        if (preg_match('/(\d)\1{13}/', $cnpj)) {
            return false;
        }

        $b = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        for ($i = 0, $n = 0; $i < 12; $n += $cnpj[$i] * $b[++$i]);
        if ($cnpj[12] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
            return false;
        }

        for ($i = 0, $n = 0; $i <= 12; $n += $cnpj[$i] * $b[$i++]);
        if ($cnpj[13] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
            return false;
        }
        return true;
    }
}

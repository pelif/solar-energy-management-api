<?php

namespace Tests\Unit\Core\Domain\Client\ValueObjects;

use App\Core\Domain\Client\ValueObjects\CpfCnpj;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class CpfCnpjTest extends TestCase
{
    public function test_it_accepts_valid_cpf()
    {
        $cpf = new CpfCnpj('123.456.789-09'); // Fake but valid format logic check might fail if I used real alg.
        // Wait, my validation logic is real. I need a valid CPF.
        // 111.111.111-11 is invalid (known invalid).
        // Let's use a generator or known valid one.
        // 52998224725 is valid.

        $vo = new CpfCnpj('52998224725');
        $this->assertEquals('52998224725', $vo->getValue());
        $this->assertEquals('CPF', $vo->getType());
        $this->assertEquals('529.982.247-25', (string) $vo);
    }

    public function test_it_accepts_valid_cnpj()
    {
        // 00.000.000/0001-91 is valid (Banco do Brasil)
        $vo = new CpfCnpj('00000000000191');
        $this->assertEquals('00000000000191', $vo->getValue());
        $this->assertEquals('CNPJ', $vo->getType());
        $this->assertEquals('00.000.000/0001-91', (string) $vo);
    }

    public function test_it_rejects_invalid_cpf()
    {
        $this->expectException(InvalidArgumentException::class);
        new CpfCnpj('11111111111');
    }

    public function test_it_rejects_invalid_length()
    {
        $this->expectException(InvalidArgumentException::class);
        new CpfCnpj('123');
    }
}

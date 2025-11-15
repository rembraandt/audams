<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../app/PengumumanLogic.php';

final class PengumumanTest extends TestCase
{
    #[Test]
    public function testJudulKosong(): void
    {
        $this->assertEquals(
            "Judul tidak boleh kosong.",
            validatePengumuman("", "Isi")
        );
    }

    #[Test]
    public function testJudulKurangDari5(): void
    {
        $this->assertEquals(
            "Judul minimal 5 karakter.",
            validatePengumuman("Abc", "Isi")
        );
    }

    #[Test]
    public function testJudulLebihDari100(): void
    {
        $judul = str_repeat("A", 101);

        $this->assertEquals(
            "Judul maksimal 100 karakter.",
            validatePengumuman($judul, "Isi")
        );
    }

    #[Test]
    public function testJudulHanyaAngka(): void
    {
        $this->assertEquals(
            "Judul tidak boleh hanya berisi angka.",
            validatePengumuman("12345", "Isi")
        );
    }

    #[Test]
    public function testIsiKosong(): void
    {
        $this->assertEquals(
            "Isi pengumuman tidak boleh kosong.",
            validatePengumuman("Judul Valid", "")
        );
    }

    #[Test]
    public function testValid(): void
    {
        $this->assertEquals(
            "OK",
            validatePengumuman("Judul valid", "Isi valid")
        );
    }
}

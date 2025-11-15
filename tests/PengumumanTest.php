<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../app/PengumumanLogic.php';

class PengumumanTest extends TestCase
{
    public function testJudulKosong()
    {
        $this->assertEquals("Judul tidak boleh kosong.", 
            validatePengumuman("", "Isi")
        );
    }

    public function testJudulKurangDari5()
    {
        $this->assertEquals("Judul minimal 5 karakter.", 
            validatePengumuman("Abc", "Isi")
        );
    }

    public function testJudulLebihDari100()
    {
        $judul = str_repeat("A", 101);
        $this->assertEquals("Judul maksimal 100 karakter.", 
            validatePengumuman($judul, "Isi")
        );
    }

    public function testJudulHanyaAngka()
    {
        $this->assertEquals("Judul tidak boleh hanya berisi angka.", 
            validatePengumuman("12345", "Isi")
        );
    }

    public function testIsiKosong()
    {
        $this->assertEquals("Isi pengumuman tidak boleh kosong.", 
            validatePengumuman("Judul Valid", "")
        );
    }

    public function testValid()
    {
        $this->assertEquals("OK", 
            validatePengumuman("Judul valid", "Isi valid")
        );
    }
}

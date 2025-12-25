<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../app/RegisterLogic.php';
require_once __DIR__ . '/DummyDB.php';

class RegisterLogicTest extends TestCase
{
    public function testRegisterSukses()
    {
        $cekEmail = new class { public $num_rows = 0; };

        $conn = new DummyDB([
            $cekEmail,
            true
        ]);

        $result = registerUser($conn, "Asep", "a@mail.com", "123", "siswa");

        $this->assertEquals("SUKSES", $result);
    }

    public function testRegisterEmailSudahAda()
    {
        $cekEmail = new class { public $num_rows = 1; };

        $conn = new DummyDB([$cekEmail]);

        $result = registerUser($conn, "Asep", "a@mail.com", "123", "siswa");

        $this->assertEquals("EMAIL_ADA", $result);
    }

    public function testRegisterFieldKosong()
    {
        $conn = new DummyDB([]);

        $result = registerUser($conn, "", "", "", "");

        $this->assertEquals("FIELD_KOSONG", $result);
    }
}

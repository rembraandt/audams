<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../app/LoginLogic.php';
require_once __DIR__ . '/DummyDB.php';

class LoginLogicTest extends TestCase
{
    public function testLoginBerhasil()
    {
        $fakeResult = new class {
            public $num_rows = 1;
            public function fetch_assoc() {
                return ['email' => 'test@mail.com'];
            }
        };

        $conn = new DummyDB([$fakeResult]);

        $result = loginUser($conn, 'test@mail.com', '123');

        $this->assertNotNull($result);
    }

    public function testLoginGagal()
    {
        $fakeResult = new class {
            public $num_rows = 0;
        };

        $conn = new DummyDB([$fakeResult]);

        $result = loginUser($conn, 'salah@mail.com', 'xxx');

        $this->assertNull($result);
    }
}

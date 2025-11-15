<?php

function validatePengumuman($judul, $isi)
{
    if ($judul == "") {
        return "Judul tidak boleh kosong.";
    }

    if (strlen($judul) < 5) {
        return "Judul minimal 5 karakter.";
    }

    if (strlen($judul) > 100) {
        return "Judul maksimal 100 karakter.";
    }

    if (preg_match('/^[0-9]+$/', $judul)) {
        return "Judul tidak boleh hanya berisi angka.";
    }

    if ($isi == "") {
        return "Isi pengumuman tidak boleh kosong.";
    }

    return "OK";  
}

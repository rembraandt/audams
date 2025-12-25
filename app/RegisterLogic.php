<?php

function registerUser($conn, $name, $email, $password, $role)
{
    if (empty($name) || empty($email) || empty($password) || empty($role)) {
        return "FIELD_KOSONG";
    }

    $check = $conn->query("SELECT * FROM users WHERE email='$email'");
    if ($check->num_rows > 0) {
        return "EMAIL_ADA";
    }

    $sql = "INSERT INTO users (name,email,password,role)
            VALUES ('$name','$email','$password','$role')";

    if ($conn->query($sql)) {
        return "SUKSES";
    }

    return "GAGAL";
}

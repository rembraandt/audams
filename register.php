<?php
include 'db.php';
session_start();

$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role     = $_POST['role'];

    // Validasi field satu per satu
    if (empty($name)) {
        $error = "Nama tidak boleh kosong.";
    } elseif (empty($email)) {
        $error = "Email tidak boleh kosong.";
    } elseif (empty($password)) {
        $error = "Password tidak boleh kosong.";
    } elseif (empty($role)) {
        $error = "Role harus dipilih.";
    } else {
        // Cek apakah email sudah terdaftar
        $check = $conn->query("SELECT * FROM users WHERE email='$email'");
        if ($check->num_rows > 0) {
            $error = "Email sudah terdaftar, silakan gunakan email lain.";
        } else {
            // Insert data
            $sql = "INSERT INTO users (name,email,password,role) 
                    VALUES ('$name','$email','$password','$role')";
            if ($conn->query($sql) === TRUE) {
                $success = "Registrasi berhasil! Anda akan diarahkan ke halaman login...";
                echo "<meta http-equiv='refresh' content='2;url=login.php'>";
            } else {
                $error = "Gagal daftar: " . $conn->error;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register | Web Sekolah</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-r from-[#00a99d] to-teal-600">

  <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8">
    <h2 class="text-3xl font-bold text-center text-[#00a99d] mb-6">Register</h2>

    <!-- Notifikasi -->
    <?php if (!empty($error)) : ?>
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-center">
        <?= $error ?>
      </div>
    <?php endif; ?>

    <?php if (!empty($success)) : ?>
      <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-center">
        <?= $success ?>
      </div>
    <?php endif; ?>

    <!-- Tambahkan novalidate agar browser tidak blokir form -->
    <form method="POST" novalidate class="space-y-5">
      <div>
        <label class="block text-sm font-medium text-gray-700">Nama</label>
        <input type="text" name="name" placeholder="Masukkan nama lengkap"
          class="w-full px-4 py-2 mt-1 border rounded-lg focus:ring-2 focus:ring-[#00a99d] focus:outline-none border-gray-300" />
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Email</label>
        <input type="email" name="email" placeholder="Masukkan email"
          class="w-full px-4 py-2 mt-1 border rounded-lg focus:ring-2 focus:ring-[#00a99d] focus:outline-none border-gray-300" />
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Password</label>
        <input type="password" name="password" placeholder="Masukkan password"
          class="w-full px-4 py-2 mt-1 border rounded-lg focus:ring-2 focus:ring-[#00a99d] focus:outline-none border-gray-300" />
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Role</label>
        <select name="role"
          class="w-full px-4 py-2 mt-1 border rounded-lg bg-white focus:ring-2 focus:ring-[#00a99d] focus:outline-none border-gray-300">
          <option value="">-- Pilih Role --</option>
          <option value="siswa">Siswa</option>
          <option value="guru">Guru</option>
        </select>
      </div>

      <button type="submit" 
        class="w-full bg-[#00a99d] text-white py-2 rounded-lg font-semibold shadow-md hover:bg-teal-700 transition duration-300">
        Register
      </button>
    </form>

    <div class="text-center mt-6">
      <a href="index.php" 
         class="text-sm text-gray-600 hover:text-[#00a99d] transition font-medium">
        ← Kembali ke Halaman Utama
      </a>
    </div>
  </div>

</body>
</html>

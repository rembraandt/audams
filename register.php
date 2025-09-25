<?php
include 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $sql = "INSERT INTO users (name,email,password,role) VALUES ('$name','$email','$password','$role')";
    if ($conn->query($sql) === TRUE) {
        header("Location: login.php");
        exit;
    } else {
        $error = "Gagal daftar: " . $conn->error;
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

    <?php if (!empty($error)) : ?>
      <p class="text-red-500 text-center mb-4 font-medium"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST" class="space-y-5">
      <!-- Input Nama -->
      <div>
        <label class="block text-sm font-medium text-gray-700">Nama</label>
        <input type="text" name="name" placeholder="Masukkan nama lengkap" required
          class="w-full px-4 py-2 mt-1 border rounded-lg focus:ring-2 focus:ring-[#00a99d] focus:outline-none border-gray-300" />
      </div>

      <!-- Input Email -->
      <div>
        <label class="block text-sm font-medium text-gray-700">Email</label>
        <input type="email" name="email" placeholder="Masukkan email" required
          class="w-full px-4 py-2 mt-1 border rounded-lg focus:ring-2 focus:ring-[#00a99d] focus:outline-none border-gray-300" />
      </div>

      <!-- Input Password -->
      <div>
        <label class="block text-sm font-medium text-gray-700">Password</label>
        <input type="password" name="password" placeholder="Masukkan password" required
          class="w-full px-4 py-2 mt-1 border rounded-lg focus:ring-2 focus:ring-[#00a99d] focus:outline-none border-gray-300" />
      </div>

      <!-- Pilih Role -->
      <div>
        <label class="block text-sm font-medium text-gray-700">Role</label>
        <select name="role" required
          class="w-full px-4 py-2 mt-1 border rounded-lg bg-white focus:ring-2 focus:ring-[#00a99d] focus:outline-none border-gray-300">
          <option value="siswa">Siswa</option>
          <option value="guru">Guru</option>
        </select>
      </div>

      <!-- Tombol Register -->
      <button type="submit" 
        class="w-full bg-[#00a99d] text-white py-2 rounded-lg font-semibold shadow-md hover:bg-teal-700 transition duration-300">
        Register
      </button>
    </form>

    <!-- Tombol Kembali -->
    <div class="text-center mt-6">
      <a href="index.php" 
         class="text-sm text-gray-600 hover:text-[#00a99d] transition font-medium">
        ← Kembali ke Halaman Utama
      </a>
    </div>
  </div>

</body>
</html>

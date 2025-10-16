<?php
session_start();
include 'db.php';

// ===== Tambahan: Cek apakah sedang edit =====
$editData = null;
if (isset($_GET['edit'])) {
    $editId = intval($_GET['edit']);
    $result = $conn->query("SELECT * FROM pengumuman WHERE id=$editId");
    $editData = $result->fetch_assoc();
}

// ===== Tambahan: Variabel error =====
$errorMessage = "";

// Tambah atau Update pengumuman (hanya jika login)
if (isset($_SESSION['user']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul = trim($_POST['judul']);
    $isi = trim($_POST['isi']);
    $foto = null;
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    // 🔹 Validasi manual: jika judul kosong
    if ($judul == "") {
        $errorMessage = "Judul tidak boleh kosong.";
    } else {
        // Proses upload foto
        if (!empty($_FILES['foto']['name'])) {
            $targetDir = "uploads/";
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            $fileName = time() . "_" . basename($_FILES['foto']['name']);
            $targetFile = $targetDir . $fileName;

            if (move_uploaded_file($_FILES['foto']['tmp_name'], $targetFile)) {
                $foto = $fileName;
            }
        }

        // ===== Update atau Insert =====
        if ($id > 0) {
            if ($foto) {
                $sql = "UPDATE pengumuman SET judul='$judul', isi='$isi', foto='$foto' WHERE id=$id";
            } else {
                $sql = "UPDATE pengumuman SET judul='$judul', isi='$isi' WHERE id=$id";
            }
            $_SESSION['success'] = "Pengumuman berhasil diupdate!";
        } else {
            $sql = "INSERT INTO pengumuman (judul, isi, foto, tanggal) VALUES ('$judul', '$isi', '$foto', NOW())";
            $_SESSION['success'] = "Pengumuman berhasil ditambahkan!";
        }

        $conn->query($sql);
        header("Location: pengumuman.php");
        exit;
    }
}

// Ambil semua pengumuman
$pengumuman = $conn->query("SELECT * FROM pengumuman ORDER BY tanggal DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Pengumuman - SMA AUDAMS</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="assets/css/pengumuman.css">
</head>
<body class="bg-gray-50">

  <!-- 🔹 Tambahan: Toast Error -->
  <?php if (!empty($errorMessage)): ?>
  <div id="toast-error" class="fixed top-5 right-5 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg transition transform">
    <?= $errorMessage; ?>
  </div>
  <script>
    setTimeout(() => {
      const toast = document.getElementById("toast-error");
      if (toast) {
        toast.style.opacity = "0";
        setTimeout(() => toast.remove(), 500);
      }
    }, 3000);
  </script>
  <?php endif; ?>

  <!-- Toast Notifikasi Sukses -->
  <?php if (isset($_SESSION['success'])): ?>
  <div id="toast-success" class="fixed top-5 right-5 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg transition transform">
    <?= $_SESSION['success']; ?>
  </div>
  <script>
    setTimeout(() => {
      const toast = document.getElementById("toast-success");
      if (toast) {
        toast.style.opacity = "0";
        setTimeout(() => toast.remove(), 500);
      }
    }, 3000);
  </script>
  <?php unset($_SESSION['success']); endif; ?>

  <!-- Navbar -->
  <header>
      <div class="logo">
          <img src="assets/logo/logo.jpg" alt="Logo SMAN 2 Batu">
      </div>
      <nav>
          <ul>
              <li><a href="index.php">BERANDA</a></li>
              <li><a href="profile.php">PROFIL</a></li>
              <li><a href="kesiswaan.php">KESISWAAN</a></li>
              <li><a href="kurikulum.php">KURIKULUM</a></li>
              <li><a href="galeri.php">GALERI</a></li>
              <?php if (isset($_SESSION['user'])): ?>
                  <li><a href="pengumuman.php">PENGUMUMAN</a></li>
                  <li><a href="logout.php">LOGOUT</a></li>
              <?php else: ?>
                  <li><a href="login.php">LOGIN</a></li>
                  <li><a href="register.php">REGISTER</a></li>
              <?php endif; ?>
          </ul>
      </nav>
  </header>

  <!-- Main Content -->
  <main class="max-w-4xl mx-auto py-10 px-4">
    <h2 class="text-3xl font-bold text-[#00a99d] mb-8 text-center">Pengumuman / Berita Sekolah</h2>

    <?php if(isset($_SESSION['user'])): ?>
    <!-- Form Tambah / Update Pengumuman -->
    <div class="bg-white shadow rounded-lg p-6 mb-10">
      <h3 class="text-xl font-semibold mb-4 text-gray-700">
        <?= $editData ? 'Edit Pengumuman' : 'Tambah Pengumuman' ?>
      </h3>

      <!-- 🔹 Nonaktifkan validasi HTML -->
      <form method="POST" enctype="multipart/form-data" class="space-y-4" novalidate>
        <?php if($editData): ?>
          <input type="hidden" name="id" value="<?= $editData['id'] ?>">
        <?php endif; ?>

        <input type="text" name="judul" placeholder="Judul Pengumuman"
          value="<?= $editData ? htmlspecialchars($editData['judul']) : '' ?>"
          class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#00a99d] focus:outline-none border-gray-300" />
        
        <textarea name="isi" placeholder="Isi Pengumuman"
          class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#00a99d] focus:outline-none border-gray-300"><?= $editData ? htmlspecialchars($editData['isi']) : '' ?></textarea>
        
        <input type="file" name="foto" accept="image/*"
          class="w-full text-sm text-gray-600 border border-gray-300 rounded-lg file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-[#00a99d] file:text-white hover:file:bg-teal-700" />
        
        <?php if($editData && $editData['foto']): ?>
          <p class="text-sm text-gray-500">Foto lama: <?= htmlspecialchars($editData['foto']) ?></p>
        <?php endif; ?>

        <button type="submit"
          class="w-full <?= $editData ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-[#00a99d] hover:bg-teal-700' ?> text-white py-2 rounded-lg font-semibold shadow-md transition">
          <?= $editData ? 'Update' : 'Tambah' ?>
        </button>
      </form>
    </div>
    <?php endif; ?>

    <!-- Daftar Pengumuman -->
    <div class="space-y-6">
      <?php while($row = $pengumuman->fetch_assoc()): ?>
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6 pengumuman-item">
          <h3 class="text-xl font-bold text-gray-800 mb-3"><?= htmlspecialchars($row['judul']) ?></h3>
          
          <?php if (!empty($row['foto'])): ?>
            <img src="uploads/<?= htmlspecialchars($row['foto']) ?>" 
                 alt="Foto Pengumuman" 
                 class="max-w-xs mb-4 rounded-lg shadow">
          <?php endif; ?>

          <p class="text-gray-700 mb-3"><?= nl2br(htmlspecialchars($row['isi'])) ?></p>
          <small class="text-gray-500 block mb-3">Dibuat: <?= $row['tanggal'] ?></small>

          <?php if(isset($_SESSION['user'])): ?>
            <a href="hapus_pengumuman.php?id=<?= $row['id'] ?>" 
               class="btn-delete inline-block px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-700 text-sm">
               Hapus
            </a>

            <a href="pengumuman.php?edit=<?= $row['id'] ?>" 
               class="inline-block px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 text-sm ml-2">
               Edit
            </a>
          <?php endif; ?>
        </div>
      <?php endwhile; ?>
    </div>
  </main>

  <!-- Modal Konfirmasi Hapus -->
  <div id="confirmModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg p-6 w-96 text-center">
      <h3 class="text-lg font-bold mb-4">Konfirmasi</h3>
      <p id="confirmMessage" class="mb-6">Apakah Anda yakin?</p>
      <div class="flex justify-center space-x-4">
        <button id="cancelBtn" class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500">Batal</button>
        <a id="confirmBtn" href="#" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">Ya, Hapus</a>
      </div>
    </div>
  </div>

  <script>
    // Modal konfirmasi hapus
    document.querySelectorAll('.btn-delete').forEach(btn => {
      btn.addEventListener('click', function(e) {
        e.preventDefault();
        const url = this.getAttribute('href');
        document.getElementById('confirmMessage').innerText = "Yakin ingin menghapus pengumuman ini?";
        document.getElementById('confirmBtn').setAttribute('href', url);
        document.getElementById('confirmModal').classList.remove('hidden');
        document.getElementById('confirmModal').classList.add('flex');
      });
    });

    // Tutup modal
    document.getElementById('cancelBtn').addEventListener('click', function() {
      document.getElementById('confirmModal').classList.add('hidden');
      document.getElementById('confirmModal').classList.remove('flex');
    });
  </script>

</body>
</html>

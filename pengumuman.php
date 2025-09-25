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

// Tambah atau Update pengumuman (hanya jika login)
if (isset($_SESSION['user']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul = $_POST['judul'];
    $isi = $_POST['isi'];
    $foto = null;
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

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

    // ===== Tambahan: Jika form adalah update =====
    if ($id > 0) {
        if ($foto) {
            $sql = "UPDATE pengumuman SET judul='$judul', isi='$isi', foto='$foto' WHERE id=$id";
        } else {
            $sql = "UPDATE pengumuman SET judul='$judul', isi='$isi' WHERE id=$id";
        }
    } else {
        // Kode asli INSERT
        $sql = "INSERT INTO pengumuman (judul, isi, foto, tanggal) VALUES ('$judul', '$isi', '$foto', NOW())";
    }

    $conn->query($sql);

    // balik ke halaman ini lagi (refresh)
    header("Location: pengumuman.php");
    exit;
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

  <!-- Navbar -->
    <header>
        <div class="logo">
            <img src="assets/logo.jpg" alt="Logo SMAN 2 Batu">
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

      <form method="POST" enctype="multipart/form-data" class="space-y-4">
        <?php if($editData): ?>
          <input type="hidden" name="id" value="<?= $editData['id'] ?>">
        <?php endif; ?>

        <input type="text" name="judul" placeholder="Judul Pengumuman" required
          value="<?= $editData ? htmlspecialchars($editData['judul']) : '' ?>"
          class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#00a99d] focus:outline-none border-gray-300" />
        
        <textarea name="isi" placeholder="Isi Pengumuman" required
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
               onclick="return confirm('Yakin ingin menghapus pengumuman ini?')"
               class="inline-block px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-700 text-sm">
               Hapus
            </a>

            <!-- ===== Tambahan: Tombol Edit ===== -->
            <a href="pengumuman.php?edit=<?= $row['id'] ?>" 
               class="inline-block px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 text-sm ml-2">
               Edit
            </a>
          <?php endif; ?>
        </div>
      <?php endwhile; ?>
    </div>
  </main>

  <footer>
        <div class="footer-container">
            <div class="footer-section audams">
                <h3>AUDAMS</h3>
                <div id="map" style="width:100%; height:150px;">
                    <iframe width="425" height="350" src="https://www.openstreetmap.org/export/embed.html?bbox=112.55149841308595%2C-7.908149640968878%2C112.55651950836183%2C-7.904318660414285&amp;layer=mapnik&amp;marker=-7.906231498424838%2C112.55400896072388" style="border: 1px solid black"></iframe><br/><small><a href="https://www.openstreetmap.org/?mlat=-7.906231&amp;mlon=112.554009#map=18/-7.906234/112.554009">View Larger Map</a></small>
                </div>
            </div>
            
            <div class="footer-section contact-us">
                <h3>KONTAK KAMI</h3>
                <p>Email: <a href="mailto:smanduabatu@gmail.com">smanduabatu@gmail.com</a></p>
                <p>Alamat: Jalan Hasanuddin Junrejo Batu</p>
                <p>Telepon: 0341-465454</p>
                <p>Fax: 0341-465454</p>
            </div>
    
            <div class="footer-section visitor-stats">
                <h3>Statistik Pengunjung</h3>
                <p>Today's visitors: 7</p>
                <p>Today's page views: 7</p>
                <p>Total visitors: 13,994</p>
                <p>Total page views: 19,305</p>
            </div>
        </div>
    
        <div class="footer-social-media">
            <h3>MEDIA SOSIAL KAMI</h3>
            <div class="social-icons">
                <a href="https://www.youtube.com/redirect?event=channel_description&redir_token=QUFFLUhqbkxUUUJpdlMwUm1xT3g5NFpaZmUtWUt5NkV2d3xBQ3Jtc0tuMVVUZFR4R2tYQjV1TDRjcUhVQ0NXZVRzLU5reGNuNnFNUGtHWlhjYXRTTjN6X1FSYjNuNFlDemxJcGlOV3V6RWdHUGxiTjhRcEdNR0VHNmVTS29yaXhSLU9PLWw4UWVDWGNMRzVuR0YyRE1zbUdHVQ&q=https%3A%2F%2Fwww.facebook.com%2FSMAN.2.BATU%2F"><i data-feather="facebook"></i></a>
                <a href="https://www.instagram.com/audams_heroik?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw=="><i data-feather="instagram"></i></a>
                <a href="https://www.youtube.com/@sman2batuofficial503"><i data-feather="youtube"></i></a>
            </div>
        </div>
    </footer>

    <button id="backToTop" style="display: none; position: fixed; bottom: 30px; right: 30px; padding: 10px 20px; font-size: 16px; background-color: #00a99d; color: white; border: none; border-radius: 5px; cursor: pointer;">Back to Top</button>


    <script>
        feather.replace();

// Mengatur tombol agar muncul saat halaman di-scroll ke bawah
window.onscroll = function() {
    const backToTopButton = document.getElementById('backToTop');
    if (document.body.scrollTop > 200 || document.documentElement.scrollTop > 200) {
        backToTopButton.style.display = "block"
    } else {
        backToTopButton.style.display = "none";
    }
};

// Fungsi untuk menggulir kembali ke atas saat tombol diklik
document.getElementById('backToTop').addEventListener('click', function() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
});

document.querySelector('.btn-primary').addEventListener('click', function(e) {
    e.preventDefault();
    alert('Anda mengklik tombol Selengkapnya! Informasi lebih lanjut akan segera tersedia.');
});

feather.replace();

document.addEventListener('DOMContentLoaded', function() {
    const currentDate = new Date();
    document.querySelector('.footer-social-media').insertAdjacentHTML(
        'beforeend',
        `<p>Tanggal Hari Ini: ${currentDate.toLocaleDateString()}</p>`
    );
});
    </script>
</body>
</html>

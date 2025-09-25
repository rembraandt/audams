<?php 
session_start();
include 'db.php'; // koneksi ke database audams

// Proses hapus foto (hanya login)
if (isset($_SESSION['user']) && isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $result = $conn->query("SELECT nama_file FROM galeri WHERE id=$id");

    if ($result && $row = $result->fetch_assoc()) {
        $fileToDelete = "assets/images/" . $row['nama_file'];

        // cek apakah nama_file ada dan bukan kosong
        if (!empty($row['nama_file']) && file_exists($fileToDelete)) {
            unlink($fileToDelete);
        }

        $conn->query("DELETE FROM galeri WHERE id=$id");
        $msg = "Foto berhasil dihapus!";
    }
}


// Proses upload gambar (jika login)
if (isset($_SESSION['user']) && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['foto'])) {
    if ($_FILES['foto']['error'] == 0) {
        $targetDir = "assets/images/";
        $nama_file = time() . "_" . basename($_FILES["foto"]["name"]); // supaya unik
        $targetFile = $targetDir . $nama_file;

        // Validasi tipe file
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        if (in_array($_FILES["foto"]["type"], $allowedTypes)) {
            if (move_uploaded_file($_FILES["foto"]["tmp_name"], $targetFile)) {
                $conn->query("INSERT INTO galeri (nama_file) VALUES ('$nama_file')");
                $msg = "Foto berhasil diupload!";
            } else {
                $msg = "Gagal mengupload foto.";
            }
        } else {
            $msg = "Hanya file JPG dan PNG yang diperbolehkan.";
        }
    }
}

// Ambil semua foto dari tabel galeri
$galleryFiles = $conn->query("SELECT * FROM galeri ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Galeri - SMAN 2 Batu</title>
    <link rel="stylesheet" href="assets/css/galeri.css">
    <link rel="stylesheet" href="assets/css/global.css">
    <style>
        .gallery-item {
            position: relative;
        }
        .delete-btn {
            position: absolute;
            top: 8px;
            right: 8px;
            background: red;
            color: white;
            border: none;
            padding: 5px 8px;
            font-size: 12px;
            border-radius: 5px;
            cursor: pointer;
            opacity: 0.8;
            transition: 0.2s;
        }
        .delete-btn:hover {
            opacity: 1;
        }
    </style>
</head>
<body>
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
                <li><a class="active" href="galeri.php">GALERI</a></li>
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

    <section class="hero">
        <h1>Galeri</h1>
        <p>Kumpulan dokumentasi kegiatan SMAN 2 Batu.</p>
    </section>

    <main class="gallery-container">
        <?php if (!empty($msg)) echo "<p style='color:green; text-align:center;'>$msg</p>"; ?>

        <?php if (isset($_SESSION['user'])): ?>
            <div class="card">
                <h2>Tambah Foto</h2>
                <form method="POST" enctype="multipart/form-data">
                    <input type="file" name="foto" accept="image/*" required>
                    <button type="submit">Upload</button>
                </form>
            </div>
        <?php endif; ?>

        <div class="gallery">
            <?php while($row = $galleryFiles->fetch_assoc()): ?>
                <div class="gallery-item">
                    <img src="assets/images/<?= htmlspecialchars($row['nama_file']) ?>" alt="Foto Galeri">
                    <?php if (isset($_SESSION['user'])): ?>
                        <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Yakin hapus foto ini?')">
                            <button class="delete-btn">❌</button>
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


// Show alert when "Selengkapnya" button is clicked
document.querySelector('.btn-primary').addEventListener('click', function(e) {
    e.preventDefault();
    alert('Anda mengklik tombol Selengkapnya! Informasi lebih lanjut akan segera tersedia.');
});

// Feather icons replacement for social media icons
feather.replace();

// Displaying current date in the footer
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

<?php
session_start();
include 'db.php';

// Ambil 3 pengumuman terbaru
$pengumuman = $conn->query("SELECT * FROM pengumuman ORDER BY tanggal DESC LIMIT 3");
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>SMAN 2 Batu - Audams Amartya</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://unpkg.com/feather-icons"></script>
</head>

<body>
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


    <!-- kode HTML kamu tetap sama di bawah sini -->
    <section class="hero">
        <div class="hero-content">
            <h1>WE ARE AUDAMS</h1>
            <p>"Terwujudnya Lulusan Berkarakter Profil Pelajar Pancasila yang Unggul Prestasi, Berdaya Saing, dan Berwawasan Lingkungan"</p>
            <div class="hero-buttons">
                <a href="#" class="btn btn-primary">Selengkapnya</a>
                <a href="#" class="btn btn-secondary">Kontak</a>
            </div>
        </div>
    </section>

    <section class="about">
        <h2>TENTANG KAMI</h2>
        <p>SMA Negeri 2 Batu merupakan salah satu SMA Negeri di Kota Batu, berlokasi di dekat Polres Kota Batu dan Gedung DPRD Kota Batu, yaitu di Jalan Hasanuddin 01 Junrejo Kota Batu. SMA Negeri 2 Batu terus berupaya meningkatkan kualitas dengan tujuan yaitu Terwujudnya Lulusan Berkarakter Profil Pelajar Pancasila yang Unggul Prestasi, Berdaya Saing, dan Berwawasan Lingkungan...</p>
    </section>

    <section class="pengumuman-preview">
    <h2>PENGUMUMAN TERBARU</h2>
    <div class="pengumuman-list">
        <?php if ($pengumuman->num_rows > 0): ?>
            <?php while($row = $pengumuman->fetch_assoc()): ?>
                <div class="pengumuman-item">
                    <h3><?= htmlspecialchars($row['judul']) ?></h3>
                    <?php if (!empty($row['foto'])): ?>
                        <img src="uploads/<?= htmlspecialchars($row['foto']) ?>" 
                             alt="Foto Pengumuman" 
                             style="max-width:150px; border-radius:6px; margin:10px 0;">
                    <?php endif; ?>
                    <p><?= nl2br(htmlspecialchars(substr($row['isi'], 0, 100))) ?>...</p>
                    <small><?= $row['tanggal'] ?></small><br>
                    <a href="pengumuman.php">Lihat Selengkapnya</a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Belum ada pengumuman.</p>
        <?php endif; ?>
    </div>
</section>


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




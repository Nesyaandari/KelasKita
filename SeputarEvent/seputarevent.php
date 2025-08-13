<?php
// Koneksi ke database
$host = "localhost:3307";
$user = "root"; // ganti sesuai konfigurasi
$pass = "";
$dbname = "kelaskita"; // ganti sesuai database

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Proses upload file
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['poster'])) {
    $targetDir = "uploads/"; // folder penyimpanan
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $filename = basename($_FILES["poster"]["name"]);
    $targetFile = $targetDir . $filename;
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Validasi format file
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($fileType, $allowedTypes)) {
        echo "<script>alert('Hanya file gambar yang diperbolehkan!');</script>";
    } else {
        if (move_uploaded_file($_FILES["poster"]["tmp_name"], $targetFile)) {
            // Simpan data ke database
            $stmt = $conn->prepare("INSERT INTO events (filename) VALUES (?)");
            $stmt->bind_param("s", $filename);
            $stmt->execute();
            $stmt->close();

            // Redirect untuk mencegah POST resubmission
            header("Location: seputarevent.php?upload=success");
            exit;
        } else {
            echo "<script>alert('Gagal mengunggah file!');</script>";
        }
    }
}

// Notifikasi sukses upload
if (isset($_GET['upload']) && $_GET['upload'] === 'success') {
   
}

// Ambil data event dari database
$events = $conn->query("SELECT * FROM events WHERE status='approved' ORDER BY uploaded_at DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Seputar Event</title>
  <link rel="stylesheet" href="seputarevent.css" />
</head>

<body>

  <!-- banner diatas navbar -->
  <div class="merdeka-banner">
    <div class="merdeka-text">Dirgahayu Republik Indonesia ke-80! Merdeka! </div>
  </div>

  <!-- Floating Indonesian Flag -->
  <div class="flag-overlay">
    <div class="flag-red"></div>
    <div class="flag-white"></div>
  </div>

  <!-- Navbar -->
  <header>
    <img src="../images/LOGO.png" alt="Logo" class="logo swing" />
    <nav>
      <a href="../beranda.html">Beranda</a>
      <a href="../Materi/materi.php">Materi</a>
      <a href="../Branding/branding.php">Branding</a>
      <a class="active" href="#">Seputar Event</a>
    </nav>
    <div class="auth" id="authSection">
      <a href="../signup.php" class="signup">Sign Up</a>
      <a href="../login.php" class="login">Login</a>
    </div>
  </header>

  <!-- Hero Section -->
  <section class="hero">
    <h2><span class="icon">📅</span> SEPUTAR <span class="highlight">EVENT</span></h2>
    <p><span class="orange">Temukan dan ikuti</span> event menarik seputar komunitas dan <span class="orange">pembelajaran</span></p>

    <!-- Form unggah -->
    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="poster" accept="image/*" required>
        <button type="submit" class="upload-btn">Unggah Event</button>
    </form>
  </section>

  <!-- Event Cards -->
  <section class="event-gallery">
    <div class="event-gallery" id="eventGallery">
      <?php while ($row = $events->fetch_assoc()) { ?>
        <div class="event-card">
          <img src="uploads/<?php echo htmlspecialchars($row['filename']); ?>" alt="Event Poster" style="width:100%; border-radius:8px;">
          <p>Status: <?php echo htmlspecialchars($row['status']); ?></p>
          <small>Diunggah: <?php echo $row['uploaded_at']; ?></small>
        </div>
      <?php } ?>
    </div>
  </section>

  <!-- Footer -->
  <footer>
    <div class="footer-container">
      <div class="footer-left">
        <img src="../images/LOGO.png" alt="Logo" class="logo swing" />
        <p>Wadah berbagi pengetahuan, karya, dan kolaborasi di bidang teknologi dan informatika. Bersama kita tumbuh,
          bersama kita berinovasi.</p>
        <hr />
        <small>© 2025 Semua Hak Dilindungi</small>
      </div>

      <div class="footer-center">
        <h4>Follow us</h4>
        <div class="social-icons">
          <img src="../images/facebook-icon.png" alt="Facebook" />
          <img src="../images/telegram-icon.png" alt="Telegram" />
          <img src="../images/instagram-icon.png" alt="Instagram" />
        </div>
        <h4>Call us</h4>
        <p>085778409829</p>
      </div>

      <div class="footer-right">
        <h4>Komunitas</h4>
        <ul>
          <li><a href="#">Tentang kami</a></li>
          <li><a href="#">Visi dan misi</a></li>
          <li><a href="#">Dampak</a></li>
        </ul>
      </div>

      <div class="footer-right">
        <h4>Fitur</h4>
        <ul>
          <li><a href="#">Beranda</a></li>
          <li><a href="#">Materi</a></li>
          <li><a href="#">Branding</a></li>
          <li><a href="#">Event</a></li>
        </ul>
      </div>
    </div>

    <div class="footer-bottom">
      <a href="#">Privacy Policy</a>
      <a href="#">Terms of Use</a>
      <a href="#">Sales and Refunds</a>
      <a href="#">Legal</a>
      <a href="#">Site Map</a>
    </div>
  </footer>
</body>
</html>

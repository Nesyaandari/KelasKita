<?php
// ===== Koneksi Database =====
$host = "localhost:3307";
$user = "root";
$pass = "";
$dbname = "kelaskita";
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// ===== Proses Upload Materi =====
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['upload_materi'])) {
    $title = $_POST['title'];
    $uploader = "anonim"; // nanti bisa diganti $_SESSION['username'] kalau ada login

    $targetDir = "materi_uploads/";
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $fileName = basename($_FILES["file"]["name"]);
    $targetFilePath = $targetDir . $fileName;
    $allowedTypes = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'zip', 'rar'];
    $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

    if (in_array($fileType, $allowedTypes)) {
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)) {
    $status = "pending";
    $stmt = $conn->prepare("INSERT INTO materi (title, file, uploader, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $title, $fileName, $uploader, $status);
    $stmt->execute();
    echo "<script>alert('Materi berhasil diunggah dan menunggu persetujuan admin.'); window.location.href='materi.php';</script>";
}
}
}

// ===== Proses Download =====
if (isset($_GET['download'])) {
    $id = intval($_GET['download']);
    $result = $conn->query("SELECT * FROM materi WHERE id = $id AND status = 'approved'");

    if ($row = $result->fetch_assoc()) {
        $filePath = "materi_uploads/" . $row['file'];
        if (file_exists($filePath)) {
            // Tambah hitungan download
            $conn->query("UPDATE materi SET downloads = downloads + 1 WHERE id = $id");

            // Kirim file ke browser
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
            header('Content-Length: ' . filesize($filePath));
            flush();
            readfile($filePath);
            exit;
        } else {
            echo "<script>alert('File tidak ditemukan.'); window.location.href='materi.php';</script>";
        }
    } else {
        echo "<script>alert('Materi tidak ditemukan atau belum disetujui.'); window.location.href='materi.php';</script>";
    }
}

// ===== Function untuk generate preview file =====
function generateFilePreview($fileName) {
    $filePath = "materi_uploads/" . $fileName;
    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    
    if ($ext == 'pdf' && file_exists($filePath)) {
        // Untuk PDF, tampilkan preview dengan embed
        return '<embed src="' . $filePath . '#toolbar=0&navpanes=0&scrollbar=0" width="100%" height="200" type="application/pdf" style="border-radius: 8px;">';
    } else {
        // Untuk file lain, tampilkan thumbnail placeholder
        $iconMap = [
            'doc' => '../images/word-icon.png',
            'docx' => '../images/word-icon.png',
            'ppt' => '../images/ppt-icon.png',
            'pptx' => '../images/ppt-icon.png',
            'zip' => '../images/zip-icon.png',
            'rar' => '../images/zip-icon.png'
        ];
        $icon = isset($iconMap[$ext]) ? $iconMap[$ext] : '../images/file-icon.png';
        return '<img src="' . $icon . '" alt="File Preview" style="width: 80px; height: 80px; object-fit: contain;">';
    }
}

?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Materi - Komunitas Informatika</title>
  <link rel="stylesheet" href="materi.css" />
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
  <!-- Floating Garuda Symbol -->
  <div class="garuda-float">
    <img src="../images/chat.png" alt="Chat" style="width:32px; height:32px;">
  </div>

  <!-- Navbar -->
  <header>
    <img src="../images/LOGO.png" alt="Logo" class="logo swing" />
    <nav>
      <a href="../beranda.html">Beranda</a>
      <a class="active" href="#">Materi</a>
      <a href="../Branding/branding.php">Branding</a>
      <a href="../SeputarEvent/seputarevent.php">Seputar Event</a>
    </nav>
    <div class="auth" id="authSection">
      <a href="signup.php" class="signup">Sign Up</a>
      <a href="login.php" class="login">Login</a>
    </div>
  </header>

  <!-- Sidebar -->
  <div id="sidebar" class="sidebar">
    <div class="sidebar-header">
        <img src="../images/logo.png" alt="KelasKita" class="sidebar-logo">
    </div>
    <ul class="sidebar-menu">
        <li id="menuProfile"><img src="../images/profileuser.png" class="icon"> Profil</li>
        <li id="menuSettings"><img src="../images/settings.png" class="icon"> Settings</li>
        <li id="menuLogout"><img src="../images/logout.png" class="icon"> Log out</li>
    </ul>
  </div>
  <div id="sidebarOverlay" class="sidebar-overlay"></div>

  <!-- Hero Section -->
  <section class="hero">
    <div class="hero-content">
      <div class="hero-title-row">
        <span class="cipratan-kiri">
          <img src="../images/kiri.png" alt="cipratan kiri" />
        </span>
        <span class="icon-box-bg">
          <img src="../images/book.png" alt="icon box" class="icon-box-img" />
        </span>
        <span class="hero-title">
          <b>Ruang Belajar Digital <span class="highlight">Komunitas Informatika</span></b>
        </span>
        <span class="cipratan-kanan">
          <img src="../images/kanan.png" alt="cipratan kanan" />
        </span>
      </div>
      <p class="hero-desc">
        Berbagi dan akses materi pembelajaran informatika terlengkap. Upload, download, dan kolaborasi dengan komunitas IT Indonesia.
      </p>
      <div class="upload-container">
        <button class="upload-btn" onclick="openForm()">Unggah Materi</button>
      </div>
    </div>
  </section>

  <!-- Search Bar -->
  <section class="search-section">
    <div class="search-bar-container">
      <div class="search-input-wrapper">
        <input type="text" class="search-input" placeholder="Cari materi, atau topik . . ." />
        <span class="search-icon">
          <img src="../images/search-icon.png" alt="Search Icon" />
        </span>
      </div>

      <div class="search-select-wrapper">
        <select class="search-select">
          <option value="">Tipe</option>
          <option value="pdf">.pdf</option>
          <option value="ppt">.ppt</option>
        </select>
      </div>
    </div>
  </section>

  <!-- Materi Grid -->
  <section class="materi-grid">
    <?php
    $result = $conn->query("SELECT * FROM materi WHERE status = 'approved' ORDER BY upload_date DESC");

    while ($row = $result->fetch_assoc()):
    ?>
    <div class="card">
      <div class="file-preview">
        <?php echo generateFilePreview($row['file']); ?>
      </div>
      <div class="card-content">
        <div class="meta">
          <span class="tag"><?php echo strtoupper(pathinfo($row['file'], PATHINFO_EXTENSION)); ?></span>
          <span class="download">⬇ <?php echo $row['downloads']; ?>x diunduh</span>
        </div>
        <h3><?php echo htmlspecialchars($row['title']); ?></h3>
        <p class="uploader">Diunggah oleh: <?php echo htmlspecialchars($row['uploader']); ?></p>
        <p class="date">Tanggal unggah: <?php echo date('d M Y', strtotime($row['upload_date'])); ?></p>
        <a href="materi.php?download=<?php echo $row['id']; ?>" class="download-btn">Download Materi</a>
      </div>
    </div>
    <?php endwhile; ?>
  </section>

  <!-- Footer -->
  <footer>
    <div class="footer-container">
      <div class="footer-left">
        <img src="../images/logo.png" alt="Logo" class="logo swing" />
        <p>Wadah berbagi pengetahuan, karya, dan kolaborasi di bidang teknologi dan informatika. Bersama kita tumbuh, bersama kita berinovasi.</p>
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

  <!-- Form unggah sebagai popup -->
  <div class="form-overlay" id="formOverlay">
    <div class="form-popup">
      <button class="close-form" onclick="closeForm()">&times;</button>
      <h2>Unggah Materi Pembelajaran</h2>
      <form action="materi.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="upload_materi" value="1">
        <div class="form-group">
          <label for="title">Judul Materi *</label>
          <input type="text" id="title" name="title" required placeholder="Masukkan judul materi">
        </div>

        <div class="form-group">
          <label for="fileInput">Upload File *</label>
          <input type="file" id="fileInput" name="file" accept=".pdf,.doc,.docx,.ppt,.pptx,.zip,.rar" required>
        </div>

        <div class="form-group">
          <input type="checkbox" id="terms" name="terms" required>
          <label for="terms">Saya setuju dengan <a href="#">syarat dan ketentuan</a> serta <a href="#">kebijakan privasi</a></label>
        </div>

        <div class="form-actions">
          <button type="button" class="btn btn-secondary" onclick="closeForm()">Batal</button>
          <button type="submit" class="btn btn-primary">Unggah Materi</button>
        </div>
      </form>
    </div>
  </div>

  <script src="../sidebar.js"></script>
  <script src="materi.js"></script>
  <script src="../auth.js"></script>


</body>
</html>
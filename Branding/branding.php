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

header("Cache-Control: no-cache, must-revalidate");

// ===== Fungsi Update Status (Admin Only) =====
if (isset($_GET['approve']) || isset($_GET['reject'])) {
    $id = intval($_GET['approve'] ?? $_GET['reject']);
    $newStatus = isset($_GET['approve']) ? 'approved' : 'rejected';
    $stmt = $conn->prepare("UPDATE branding SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $newStatus, $id);
    $stmt->execute();
    header("Location: branding.php");
    exit;
}

// ===== Upload Proyek =====
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['projectTitle']);
    $desc = trim($_POST['projectDesc']);
    $category = $_POST['projectCategory'];

    // Folder upload
    $targetDir = "uploadsbranding/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $fileName = basename($_FILES['projectImage']['name']);
    $filePath = $targetDir . time() . "_" . preg_replace('/\s+/', '_', $fileName);

    if (move_uploaded_file($_FILES['projectImage']['tmp_name'], $filePath)) {
        // Prepared statement, status default pending
        $stmt = $conn->prepare("INSERT INTO branding (title, description, category, image_path, status) 
                                VALUES (?, ?, ?, ?, 'pending')");
        $stmt->bind_param("ssss", $title, $desc, $category, $filePath);
        $stmt->execute();
    }
    header("Location: branding.php");
    exit;
}

// ===== Ambil data proyek =====
$result = $conn->query("SELECT * FROM branding WHERE status = 'approved' ORDER BY created_at DESC");
$projects = [];
while ($row = $result->fetch_assoc()) {
    $projects[] = $row;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Kumpulan Projek - Kelas Kita</title>
  <link rel="stylesheet" href="branding.css" />
</head>

<body>

  <!-- Banner -->
  <div class="merdeka-banner">
    <div class="merdeka-text">Dirgahayu Republik Indonesia ke-80! Merdeka!</div>
  </div>

  <!-- Flag -->
  <div class="flag-overlay">
    <div class="flag-red"></div>
    <div class="flag-white"></div>
  </div>

  <!-- Chat icon -->
  <div class="garuda-float">
    <img src="../images/chat.png" alt="Chat" style="width:32px; height:32px;">
  </div>

  <!-- Navbar -->
  <header>
    <img src="../images/LOGO.png" alt="Logo" class="logo swing" />
    <nav>
      <a href="../beranda.html">Beranda</a>
      <a href="../Materi/materi.php">Materi</a>
      <a class="active" href="#">Branding</a>
      <a href="../SeputarEvent/seputarevent.php">Seputar Event</a>
    </nav>
    <div class="auth" id="authSection">
      <a href="../signup.php" class="signup">Sign Up</a>
      <a href="../login.php?v=1">Login</a>
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

  <!-- Content -->
  <main class="content">
    <div class="section-header">
      <div>
        <h1>Kumpulan Projek</h1>
        <p>Jelajahi karya inovatif proyek informatika kami</p>
      </div>
      <button id="uploadBtn" class="upload-btn">Unggah Proyek</button>
    </div>

    <!-- Search bar -->
    <div class="search-wrapper">
      <img src="../images/search-icon.png" alt="Search Icon" class="search-icon" />
      <input type="text" class="search" placeholder="Cari project disini . . ." />
    </div>

    <!-- Category filter -->
    <div class="category-buttons">
      <button class="category-btn active" data-category="all">All</button>
      <button class="category-btn" data-category="mobile">Mobile Development</button>
      <button class="category-btn" data-category="web">Web Development</button>
      <button class="category-btn" data-category="uiux">UI/UX Design</button>
    </div>

    <!-- Project Grid -->
    <section class="project-grid">
      <?php if (count($projects) === 0): ?>
        <p style="grid-column: 1/-1; text-align:center; color:#777;">Belum ada proyek yang diunggah.</p>
      <?php else: ?>
        <?php foreach ($projects as $p): ?>
          <div class="project-card" data-category="<?= $p['category'] ?>">
            <img src="<?= $p['image_path'] ?>" alt="<?= htmlspecialchars($p['title']) ?>">
            <h3><?= htmlspecialchars($p['title']) ?></h3>
            <p><?= htmlspecialchars($p['description']) ?></p>
            <small>Status: <strong><?= htmlspecialchars($p['status']) ?></strong></small>

            <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
              <div style="margin-top:8px;">
                <a href="?approve=<?= $p['id'] ?>" style="color:green;">✔ Approve</a> |
                <a href="?reject=<?= $p['id'] ?>" style="color:red;">✖ Reject</a>
              </div>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </section>
  </main>

  <!-- Modal (default hidden) -->
  <div id="uploadModal" class="modal" style="display: none;">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h2>Unggah Proyek</h2>
      <form method="POST" enctype="multipart/form-data">
        <label for="projectImage">Upload Gambar:</label>
        <input type="file" name="projectImage" id="projectImage" accept="image/*" required />

        <label for="projectTitle">Judul Proyek:</label>
        <input type="text" name="projectTitle" id="projectTitle" required />

        <label for="projectDesc">Deskripsi Proyek:</label>
        <textarea name="projectDesc" id="projectDesc" rows="3" required></textarea>

        <label for="projectCategory">Kategori:</label>
        <select name="projectCategory" id="projectCategory" required>
          <option value="mobile">Mobile Development</option>
          <option value="web">Web Development</option>
          <option value="uiux">UI/UX Design</option>
        </select>

        <button type="submit" class="submit-btn">Tambahkan</button>
      </form>
    </div>
  </div>

  <footer>
    <!-- Footer seperti semula -->
  </footer>

  <!-- JavaScript untuk mengontrol modal -->
  <script>
    // Get modal elements
    const modal = document.getElementById('uploadModal');
    const uploadBtn = document.getElementById('uploadBtn');
    const closeBtn = document.querySelector('.close');

    // Show modal when upload button clicked
    uploadBtn.addEventListener('click', function() {
      modal.style.display = 'block';
    });

    // Hide modal when close button clicked
    closeBtn.addEventListener('click', function() {
      modal.style.display = 'none';
    });

    // Hide modal when clicking outside modal content
    window.addEventListener('click', function(event) {
      if (event.target === modal) {
        modal.style.display = 'none';
      }
    });
  </script>

  <script src="../sidebar.js"></script>
  <script src="../auth.js"></script>
  <script src="branding.js"></script>
</body>
</html>
<?php
// ====== Koneksi database ======
$host = "localhost:3307";
$user = "root";
$pass = "";
$dbname = "kelaskita";
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// ====== API Load Event ======
if (isset($_GET['action']) && $_GET['action'] === 'load') {
    $result = $conn->query("SELECT * FROM events ORDER BY uploaded_at DESC");
    $events = [];
    while ($row = $result->fetch_assoc()) {
        $events[] = [
            "id" => $row['id'],
            "filename" => $row['filename'],
            "status" => $row['status'],
            "file_url" => "uploads/" . $row['filename']
        ];
    }
    header('Content-Type: application/json');
    echo json_encode(["status" => "success", "events" => $events]);
    exit;
}

// ====== API Approve / Reject Event ======
if (isset($_POST['action']) && $_POST['action'] === 'update') {
    $id = intval($_POST['id']);
    $status = $_POST['status'];
    $allowed = ['approved', 'rejected'];

    if (!in_array($status, $allowed)) {
        echo json_encode(["status" => "error", "message" => "Status tidak valid"]);
        exit;
    }

    $stmt = $conn->prepare("UPDATE events SET status=? WHERE id=?");
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();

    header('Content-Type: application/json');
    if ($stmt->affected_rows > 0) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Tidak ada perubahan"]);
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Dashboard - Kelas Kita</title>
  <link rel="stylesheet" href="admin.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
</head>
<body>

  <!-- Sidebar -->
  <aside class="sidebar">
    <div class="sidebar-header">
      <img src="../images/LOGO.png" alt="Logo Kelas Kita" class="logo-img">
      <h2>KELAS KITA</h2>
    </div>
    <nav>
      <ul>
        <li class="menu-item" data-section="materi"><i class="fas fa-book-open"></i> Materi</li>
        <li class="menu-item active" data-section="branding"><i class="fas fa-paint-brush"></i> Branding</li>
        <li class="menu-item" data-section="event"><i class="fas fa-calendar-alt"></i> Event</li>
        <li class="menu-item" data-section="users"><i class="fas fa-users"></i> Users</li>
      </ul>
    </nav>
  </aside>

  <!-- Main Content -->
  <main class="main-content">
    <header class="topbar">
      <h1 id="sectionTitle">Materi</h1>
      <div class="filter">
        <label for="statusFilter">Filter Status:</label>
        <select id="statusFilter">
          <option value="all">Semua</option>
          <option value="pending">Pending</option>
          <option value="approved">Disetujui</option>
          <option value="rejected">Ditolak</option>
        </select>
      </div>
    </header>

    <!-- Event Section -->
    <section class="upload-section" id="event" style="display:none;"></section>
  </main>

<script src="admin.js"></script>
</body>
</html>

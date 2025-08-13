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

// ====== API Load Materi ======
if (isset($_GET['action']) && $_GET['action'] === 'load_materi') {
    $result = $conn->query("SELECT * FROM materi ORDER BY upload_date DESC");
    $materi = [];
    while ($row = $result->fetch_assoc()) {
        // Perbaikan path file
        $file_path = "../Materi/materi_uploads/" . $row['file'];
        // Cek apakah file ada, jika tidak gunakan placeholder
        if (!file_exists($file_path)) {
            $file_path = "../images/default.png";
        }
        
        $materi[] = [
            "id" => $row['id'],
            "title" => $row['title'],
            "file" => $row['file'],
            "uploader" => $row['uploader'],
            "status" => $row['status'],
            "file_url" => $file_path,
            "upload_date" => $row['upload_date'] ?? date('Y-m-d H:i:s')
        ];
    }
    header('Content-Type: application/json');
    echo json_encode(["status" => "success", "materi" => $materi]);
    exit;
}

// ====== API Approve / Reject Materi ======
if (isset($_POST['action']) && $_POST['action'] === 'update_materi') {
    $id = intval($_POST['id']);
    $status = $_POST['status'];
    $allowed = ['approved', 'rejected'];

    if (!in_array($status, $allowed)) {
        echo json_encode(["status" => "error", "message" => "Status tidak valid"]);
        exit;
    }

    $stmt = $conn->prepare("UPDATE materi SET status=? WHERE id=?");
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

// ====== API Load Branding ======
if (isset($_GET['action']) && $_GET['action'] === 'load_branding') {
    $result = $conn->query("SELECT * FROM branding ORDER BY created_at DESC");
    $branding = [];
    while ($row = $result->fetch_assoc()) {
        // Perbaikan path gambar branding
        $image_path = $row['image_path'];
        
        // Jika path tidak dimulai dengan http/https, tambahkan path relatif
        if (!preg_match('/^https?:\/\//', $image_path)) {
            // Cek berbagai kemungkinan lokasi file
            $possible_paths = [
                "../Branding/" . $image_path,
                "../Branding/uploads/" . $image_path,
                "../uploads/" . $image_path,
                $image_path
            ];
            
            $final_path = "../images/default.png"; // default
            foreach ($possible_paths as $path) {
                if (file_exists($path)) {
                    $final_path = $path;
                    break;
                }
            }
            $image_path = $final_path;
        }
        
        $branding[] = [
            "id" => $row['id'],
            "title" => $row['title'],
            "description" => $row['description'],
            "image_path" => $image_path,
            "status" => $row['status'],
            "created_at" => $row['created_at'] ?? date('Y-m-d H:i:s')
        ];
    }
    header('Content-Type: application/json');
    echo json_encode(["status" => "success", "branding" => $branding]);
    exit;
}

// ====== API Approve / Reject Branding ======
if (isset($_POST['action']) && $_POST['action'] === 'update_branding') {
    $id = intval($_POST['id']);
    $status = $_POST['status'];
    $allowed = ['approved', 'rejected'];

    if (!in_array($status, $allowed)) {
        echo json_encode(["status" => "error", "message" => "Status tidak valid"]);
        exit;
    }

    $stmt = $conn->prepare("UPDATE branding SET status=? WHERE id=?");
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

// ====== API Load Event ======
if (isset($_GET['action']) && $_GET['action'] === 'load') {
    $result = $conn->query("SELECT * FROM events ORDER BY uploaded_at DESC");
    $events = [];
    while ($row = $result->fetch_assoc()) {
        // Perbaikan path gambar event
        $file_path = "uploads/" . $row['filename'];
        
        // Cek berbagai kemungkinan lokasi file
        $possible_paths = [
            "../SeputarEvent/uploads/" . $row['filename'],
            "../uploads/" . $row['filename'],
            "uploads/" . $row['filename']
        ];
        
        $final_path = "../images/default.png"; // default
        foreach ($possible_paths as $path) {
            if (file_exists($path)) {
                $final_path = $path;
                break;
            }
        }
        
        $events[] = [
            "id" => $row['id'],
            "filename" => $row['filename'],
            "status" => $row['status'],
            "file_url" => $final_path,
            "uploaded_at" => $row['uploaded_at'] ?? date('Y-m-d H:i:s')
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
      <h1 id="sectionTitle">Branding</h1>
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

    <section class="upload-section" id="materi" style="display:none;"></section>
    <section class="upload-section" id="branding" style="display:none;"></section>
    <section class="upload-section" id="event" style="display:none;"></section>
  </main>

<script src="admin.js"></script>
</body>
</html>
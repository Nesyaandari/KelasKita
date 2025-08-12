<?php
// Koneksi database
$conn = new mysqli("localhost:3307", "root", "", "kelaskita");
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Koneksi gagal: " . $conn->connect_error]));
}

// Pastikan ada file yang diupload
if (!isset($_FILES['poster'])) {
    echo json_encode(["status" => "error", "message" => "Tidak ada file yang dikirim"]);
    exit;
}

$targetDir = "uploads/";
if (!file_exists($targetDir)) {
    mkdir($targetDir, 0777, true);
}

$filename = time() . "_" . basename($_FILES['poster']['name']);
$targetFile = $targetDir . $filename;

if (move_uploaded_file($_FILES['poster']['tmp_name'], $targetFile)) {
    // Simpan ke database
     $stmt = $conn->prepare("INSERT INTO events (filename) VALUES (?)");
    $stmt->bind_param("s", $filename);
    $stmt->execute();
    $stmt->close();

    echo json_encode(["status" => "success", "message" => "File berhasil diunggah", "filename" => $filename]);
} else {
    echo json_encode(["status" => "error", "message" => "Gagal mengunggah file"]);
}

if (move_uploaded_file($_FILES['poster']['tmp_name'], $targetFile)) {
    // Simpan ke database dengan status pending
    $stmt = $conn->prepare("INSERT INTO events (filename, status) VALUES (?, 'pending')");
    $stmt->bind_param("s", $filename);
    $stmt->execute();
    $stmt->close();

    echo json_encode([
        "status" => "success",
        "message" => "File berhasil diunggah (menunggu persetujuan admin)",
        "filename" => $filename
    ]);
}

$events[] = [
    "id" => $row['id'],
    "filename" => $row['filename'],
    "status" => $row['status'],
    "file_url" => "uploads/" . $row['filename']
];


$conn->close();
?>

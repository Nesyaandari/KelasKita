<?php
header("Content-Type: application/json");

// Koneksi database
$conn = new mysqli("localhost:3307", "root", "", "kelaskita");
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Koneksi gagal: " . $conn->connect_error]);
    exit;
}

// Ambil semua data poster terbaru (urut dari paling baru)
$result = $conn->query("SELECT filename FROM events WHERE status = 'approved' ORDER BY uploaded_at DESC");


$events = [];
while ($row = $result->fetch_assoc()) {
    $events[] = $row['filename'];
}

$conn->close();

// Kirim hasil dalam bentuk JSON
echo json_encode([
    "status" => "success",
    "events" => $events
]);
?>

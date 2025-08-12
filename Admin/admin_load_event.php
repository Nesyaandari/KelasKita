<?php
$conn = new mysqli("localhost:3307", "root", "", "kelaskita");
$result = $conn->query("SELECT id, filename, status FROM events ORDER BY uploaded_at DESC");

$events = [];
while ($row = $result->fetch_assoc()) {
    $events[] = $row;
}
echo json_encode(["status" => "success", "events" => $events]);
?>

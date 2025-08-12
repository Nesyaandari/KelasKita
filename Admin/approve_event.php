<?php
$conn = new mysqli("localhost:3307", "root", "", "kelaskita");

$id = intval($_POST['id']);
$status = $_POST['status']; // approved / rejected

$stmt = $conn->prepare("UPDATE events SET status = ? WHERE id = ?");
$stmt->bind_param("si", $status, $id);
$stmt->execute();
$stmt->close();

echo json_encode(["status" => "success", "message" => "Status diperbarui"]);
?>

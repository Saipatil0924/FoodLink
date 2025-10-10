// In get_donor_donations.php
<?php
require_once 'db.php'; // This file provides $conn (MySQLi)
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $donorId = isset($_GET['donorId']) ? intval($_GET['donorId']) : 0;

    if (!$donorId) {
        echo json_encode(['success' => false, 'message' => 'Donor ID required']);
        exit;
    }

    // FIX: Rewritten for MySQLi
    $stmt = $conn->prepare("SELECT * FROM donations WHERE donor_id = ? ORDER BY created_at DESC");
    $stmt->bind_param("i", $donorId);
    $stmt->execute();
    $result = $stmt->get_result();
    $donations = $result->fetch_all(MYSQLI_ASSOC);

    echo json_encode(['success' => true, 'data' => $donations]);

    $stmt->close();
    $conn->close();
}
?>
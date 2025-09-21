<?php
// php/get_donor_donations.php
require_once 'db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $donorId = isset($_GET['donorId']) ? $_GET['donorId'] : null;
    
    if (!$donorId) {
        echo json_encode(['success' => false, 'message' => 'Donor ID required']);
        exit;
    }
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM donations WHERE donor_id = ? ORDER BY created_at DESC");
        $stmt->execute([$donorId]);
        $donations = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode(['success' => true, 'data' => $donations]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error fetching donations: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
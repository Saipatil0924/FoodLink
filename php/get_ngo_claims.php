<?php
// php/get_ngo_claims.php
require_once 'db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $ngoId = isset($_GET['ngoId']) ? $_GET['ngoId'] : null;
    
    if (!$ngoId) {
        echo json_encode(['success' => false, 'message' => 'NGO ID required']);
        exit;
    }
    
    try {
        $stmt = $pdo->prepare("SELECT d.*, u.name as donor_name 
                              FROM donations d 
                              JOIN users u ON d.donor_id = u.id 
                              WHERE d.ngo_id = ? 
                              ORDER BY d.pickup_time DESC");
        $stmt->execute([$ngoId]);
        $claims = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode(['success' => true, 'data' => $claims]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error fetching claims: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
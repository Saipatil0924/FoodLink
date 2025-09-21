<?php
// php/update_status.php
require_once 'db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $donationId = $data['donationId'];
    $status = $data['status'];
    
    try {
        $validStatuses = ['available', 'claimed', 'picked_up', 'completed', 'cancelled'];
        
        if (!in_array($status, $validStatuses)) {
            echo json_encode(['success' => false, 'message' => 'Invalid status']);
            exit;
        }
        
        $stmt = $pdo->prepare("UPDATE donations SET status = ? WHERE id = ?");
        $stmt->execute([$status, $donationId]);
        
        echo json_encode(['success' => true, 'message' => 'Status updated successfully']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error updating status: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
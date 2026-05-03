<?php
function logActivity($pdo, $action, $target) {
    $stmt = $pdo->prepare("INSERT INTO activity_logs (user_id, action, target_pet) VALUES (?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $action, $target]);
}
?>
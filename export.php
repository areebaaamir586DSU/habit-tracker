<?php
require_once __DIR__ . '/config.php';
requireAuth();

header('Content-Type: application/json');
header('Content-Disposition: attachment; filename="habit-tracker-export-' . date('Y-m-d') . '.json"');

$db = getDb();
$userId = $_SESSION['user_id'];

$stmt = $db->prepare('SELECT data_key, data_value FROM user_data WHERE user_id = :uid');
$stmt->bindValue(':uid', $userId, SQLITE3_INTEGER);
$result = $stmt->execute();

$data = ['exported_at' => date('c'), 'version' => '2.0'];
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $data[$row['data_key']] = json_decode($row['data_value'], true) ?? $row['data_value'];
}

$db->close();
echo json_encode($data, JSON_PRETTY_PRINT);

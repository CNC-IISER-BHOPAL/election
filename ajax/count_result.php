<?php
require 'db.php';
session_start();

$electionId = $_GET['election_id'] ?? null;

if (!$electionId) {
    die(json_encode(['status' => 'error', 'message' => 'Invalid election ID.']));
}

try {
    $stmt = $pdo->prepare("SELECT security_auth_key, start_time, end_time, status FROM elections WHERE id = :election_id");
    $stmt->execute([':election_id' => $electionId]);
    $election = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$election) {
        die(json_encode(['status' => 'error', 'message' => 'Election not found.']));
    }
    if ($election['status'] != 1) {
        die(json_encode(['status' => 'error', 'message' => 'This election is not active.']));
    }

    $currentTime = time();
    $endTime = strtotime($election['end_time']);
    $startTime = strtotime($election['start_time']);
    if ($currentTime < $endTime) {
        die(json_encode(['status' => 'error', 'message' => 'Election is still ongoing. Results will be available after the end time.']));
    }
    if ($currentTime < $startTime) {
        die(json_encode(['status' => 'error', 'message' => 'Election has not started yet.']));
    }
    function decryptData($data, $key) {
        return openssl_decrypt(base64_decode($data), 'AES-256-CBC', $key, 0, substr($key, 0, 16));
    }
    $stmt = $pdo->prepare("
        SELECT position_id, candidate_id, COUNT(*) as vote_count 
        FROM submit_votes 
        WHERE election_id = :election_id 
        GROUP BY position_id, candidate_id
    ");
    $stmt->execute([':election_id' => $electionId]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $positions = [];

    foreach ($results as $result) {
        try {
            $decryptedPositionId = decryptData($result['position_id'], $election['security_auth_key']);
            $decryptedCandidateId = decryptData($result['candidate_id'], $election['security_auth_key']);
            $stmt = $pdo->prepare("SELECT position_name FROM positions WHERE id = :position_id AND election_id = :election_id");
            $stmt->execute([
                ':position_id' => $decryptedPositionId,
                ':election_id' => $electionId
            ]);
            $positionName = $stmt->fetchColumn();

            if (!$positionName) {
                continue; 
            }
            $stmt = $pdo->prepare("SELECT name FROM members WHERE id = :candidate_id");
            $stmt->execute([':candidate_id' => $decryptedCandidateId]);
            $candidateName = $stmt->fetchColumn();
            if (!isset($positions[$decryptedPositionId])) {
                $positions[$decryptedPositionId] = [
                    'position_name' => $positionName,
                    'candidates' => []
                ];
            }
            $positions[$decryptedPositionId]['candidates'][] = [
                'candidate_name' => $candidateName,
                'vote_count' => $result['vote_count']
            ];
        } catch (Exception $e) {
     
            error_log("Decryption error for election {$electionId}: " . $e->getMessage());
            continue;
        }
    }
    echo json_encode([
        'status' => 'success',
        'data' => $positions,
        'election_info' => [
            'start_time' => $election['start_time'],
            'end_time' => $election['end_time'],
            'status' => $election['status']
        ]
    ]);

} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Failed to retrieve election results.']);
}
?>
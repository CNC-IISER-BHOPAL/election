<?php
require 'db.php';
session_start();
function encryptData($data, $key) {
    return base64_encode(openssl_encrypt($data, 'AES-256-CBC', $key, 0, substr($key, 0, 16)));
}
$positionIds = $_POST['position_ids'] ?? [];
$electionIds = $_POST['election_ids'] ?? [];

if (empty($positionIds) || empty($electionIds)) {
    die(json_encode(['status' => 'error', 'message' => 'Invalid voting data.']));
}
if (!isset($_SESSION['system_key'])) {
    die(json_encode(['status' => 'error', 'message' => 'System not authenticated.']));
}

$systemId = $_SESSION['system_key']['id'];
$votes = [];
try {
    $pdo->beginTransaction();
    $stmt = $pdo->prepare("
        SELECT id FROM votings_log 
        WHERE system_id = :system_id 
        AND election_id IN (".implode(',', array_map('intval', array_unique($electionIds))).") 
        AND is_voted = 0 
        LIMIT 1
        FOR UPDATE
    ");
    $stmt->execute([':system_id' => $systemId]);
    $eligibleVote = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$eligibleVote) {
        $pdo->rollBack();
        die(json_encode(['status' => 'error', 'message' => 'No eligible vote found for this system.']));
    }
    $uniqueElectionIds = array_unique($electionIds);
    $placeholders = implode(',', array_fill(0, count($uniqueElectionIds), '?'));
    
    $stmt = $pdo->prepare("SELECT id, security_auth_key, start_time, end_time, status FROM elections WHERE id IN ($placeholders)");
    $stmt->execute($uniqueElectionIds);
    $electionsData = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $electionsLookup = [];
    foreach ($electionsData as $election) {
        $electionsLookup[$election['id']] = $election;
    }
    $currentTime = time();
    foreach ($positionIds as $index => $positionId) {
        $candidateId = $_POST['position_'.$positionId] ?? null;
        if (!$candidateId) {
            $pdo->rollBack();
            die(json_encode(['status' => 'error', 'message' => 'Please select a candidate for each position.']));
        }

        $electionId = $electionIds[$index];
        
        if (!isset($electionsLookup[$electionId])) {
            $pdo->rollBack();
            die(json_encode(['status' => 'error', 'message' => 'Invalid election data.']));
        }
        
        $election = $electionsLookup[$electionId];
        if ($election['status'] != 1) {
            $pdo->rollBack();
            die(json_encode(['status' => 'error', 'message' => 'This election is not currently active.']));
        }
        $startTime = strtotime($election['start_time']);
        $endTime = strtotime($election['end_time']);
        
        if ($currentTime < $startTime) {
            $pdo->rollBack();
            die(json_encode(['status' => 'error', 'message' => 'Voting for this election has not started yet.']));
        }
        
        if ($currentTime > $endTime) {
            $pdo->rollBack();
            die(json_encode(['status' => 'error', 'message' => 'Voting for this election has ended.']));
        }
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM positions WHERE id = :position_id AND election_id = :election_id");
        $stmt->execute([':position_id' => $positionId, ':election_id' => $electionId]);
        $positionExists = $stmt->fetchColumn();

        if (!$positionExists) {
            $pdo->rollBack();
            die(json_encode(['status' => 'error', 'message' => 'Invalid position or election data.']));
        }
        $votes[] = [
            'position_id' => encryptData($positionId, $election['security_auth_key']),
            'election_id' => $electionId,
            'candidate_id' => encryptData($candidateId, $election['security_auth_key'])
        ];
    }
    $stmt = $pdo->prepare("UPDATE votings_log SET is_voted = 1 WHERE id = :id");
    $stmt->execute([':id' => $eligibleVote['id']]);
    $stmt = $pdo->prepare("UPDATE elections_system SET is_free = 0 WHERE id = :system_id");
    $stmt->execute([':system_id' => $systemId]);
    $stmt = $pdo->prepare("INSERT INTO submit_votes (election_id, position_id, candidate_id, voted_at) 
                          VALUES (:election_id, :position_id, :candidate_id, NOW())");
    foreach ($votes as $vote) {
        $stmt->execute([
            ':election_id' => $vote['election_id'],
            ':position_id' => $vote['position_id'],
            ':candidate_id' => $vote['candidate_id']
        ]);
    }
    $pdo->commit();
    echo json_encode(['status' => 'success', 'message' => 'Your vote has been recorded successfully.']);
    } catch (PDOException $e) {
    $pdo->rollBack();
    error_log("Voting error: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Failed to record your vote. Please try again.']);
}
?>
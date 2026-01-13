<?php
// --- HIBAKERESÉS BEKAPCSOLÁSA (Teszteléshez) ---
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// scores.php - Memória Mester ranglista API
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *"); // Opcionális: CORS engedélyezése

// --- ADATBÁZIS BEÁLLÍTÁSOK ---
// Ezt töltsd ki a Nethely-s adataiddal!
$db_host = 'localhost';
$db_user = 'FELHASZNÁLÓNÉV';
$db_pass = 'JELSZÓ';
$db_name = 'ADATBÁZISNÉV';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    // Részletes hibaüzenet kiírása
    die(json_encode(['error' => 'Kapcsolódási hiba: ' . $conn->connect_error]));
}

// Tábla létrehozása, ha még nem létezne
$conn->query("CREATE TABLE IF NOT EXISTS memory_scores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    playerName VARCHAR(50) NOT NULL,
    difficulty VARCHAR(50),
    totalMoves INT,
    incorrectMoves INT,
    timeElapsed INT,
    score FLOAT,
    isTimeBased TINYINT(1),
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// MENTÉS (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!$data) {
        echo json_encode(['error' => 'Nincs adat']);
        exit;
    }

    $playerName = $conn->real_escape_string($data['playerName']);
    $difficulty = $conn->real_escape_string($data['difficulty']);
    $totalMoves = (int)$data['totalMoves'];
    $incorrectMoves = (int)$data['incorrectMoves'];
    $timeElapsed = (int)$data['timeElapsed'];
    $score = (float)$data['score'];
    $isTimeBased = (int)$data['isTimeBased'];

    $sql = "INSERT INTO memory_scores (playerName, difficulty, totalMoves, incorrectMoves, timeElapsed, score, isTimeBased) 
            VALUES ('$playerName', '$difficulty', $totalMoves, $incorrectMoves, $timeElapsed, $score, $isTimeBased)";

    if ($conn->query($sql)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => $conn->error]);
    }
} 
// LEKÉRDEZÉS (GET)
else {
    $result = $conn->query("SELECT * FROM memory_scores ORDER BY score DESC LIMIT 10");
    $scores = [];
    while ($row = $result->fetch_assoc()) {
        // Konvertáljuk vissza a típusokat
        $row['score'] = (float)$row['score'];
        $row['totalMoves'] = (int)$row['totalMoves'];
        $row['isTimeBased'] = (bool)$row['isTimeBased'];
        $scores[] = $row;
    }
    echo json_encode($scores);
}

$conn->close();
?>

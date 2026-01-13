<?php
// test_db.php - Diagnosztikai eszköz
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>1. Lépés: PHP Működik!</h1>";
echo "<p>PHP verzió: " . phpversion() . "</p>";

// Adatok - Töltsd ki a Nethely-s adatokkal!
$db_host = 'localhost'; 
$db_user = 'FELHASZNÁLÓNÉV'; 
$db_pass = 'JELSZÓ'; 
$db_name = 'ADATBÁZISNÉV';

echo "<h2>2. Lépés: Csatlakozás az adatbázishoz...</h2>";
echo "Host: $db_host <br>";
echo "User: $db_user <br>";
echo "DB: $db_name <br>";

try {
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if ($conn->connect_error) {
        throw new Exception("Kapcsolódási hiba: " . $conn->connect_error);
    }
    echo "<h2 style='color:green'>Sikeres csatlakozás! ✅</h2>";
    echo "<p>Host info: " . $conn->host_info . "</p>";
    
    $conn->close();
} catch (Exception $e) {
    echo "<h2 style='color:red'>HIBA TÖRTÉNT! ❌</h2>";
    echo "<p>Hibaüzenet: <strong>" . $e->getMessage() . "</strong></p>";
}
?>
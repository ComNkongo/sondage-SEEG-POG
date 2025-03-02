<?php
// Charger les variables d'environnement à partir du fichier .env
require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Définir les constantes pour une utilisation plus simple
define('MAIL_USERNAME', getenv('MAIL_USERNAME'));
define('MAIL_PASSWORD', getenv('MAIL_PASSWORD'));

// Connexion à la base de données
$host = getenv('DB_HOST') ?: "localhost";
$dbname = getenv('DB_NAME') ?: "sondage_seeg";
$username = getenv('DB_USERNAME') ?: "root";
$password = getenv('DB_PASSWORD') ?: "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Décommenter pour tester les variables chargées
/*
echo '<pre>';
print_r($_ENV);
echo '</pre>';
exit();
*/

?>

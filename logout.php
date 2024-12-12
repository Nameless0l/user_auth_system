<?php
session_start();

// Supprimer le cookie de connexion automatique
if (isset($_COOKIE['remember_token'])) {
    require_once 'db.php';
    $database = new Database();
    $db = $database->getConnection();
    
    // Supprimer le token de la base de données
    $stmt = $db->prepare("UPDATE users SET remember_token = NULL WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    
    // Supprimer le cookie
    setcookie('remember_token', '', time() - 3600, '/');
}

// Détruire toutes les variables de session
$_SESSION = array();

// Détruire la session
session_destroy();

// Rediriger vers la page de connexion
header("Location: login.php");
exit();
?>

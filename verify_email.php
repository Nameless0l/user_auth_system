<?php
require_once 'db.php';

session_start();

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    $database = new Database();
    $db = $database->getConnection();
    
    // Vérifier le token
    $stmt = $db->prepare("SELECT id FROM users WHERE verification_token = ?");
    $stmt->execute([$token]);
    $user = $stmt->fetch();
    
    if ($user) {
        // Marquer l'email comme vérifié et supprimer le token
        $stmt = $db->prepare("UPDATE users SET email_verified = TRUE, verification_token = NULL WHERE id = ?");
        $stmt->execute([$user['id']]);
        
        $_SESSION['success_message'] = "Votre email a été vérifié avec succès. Vous pouvez maintenant vous connecter.";
        header("Location: login.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Le lien de vérification est invalide ou a expiré.";
        header("Location: login.php");
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}

<?php
require_once 'db.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $database = new Database();
    $db = $database->getConnection();
    
    $email = trim($_POST['email']);
    
    // Vérifier si l'email existe
    $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if ($user) {
        // Générer un token unique
        $reset_token = bin2hex(random_bytes(32));
        $reset_token_expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        // Sauvegarder le token dans la base de données
        $stmt = $db->prepare("UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE email = ?");
        $stmt->execute([$reset_token, $reset_token_expiry, $email]);
        
        // Envoyer l'email (dans un environnement réel)
        $reset_link = "http://" . $_SERVER['HTTP_HOST'] . "/reset_password.php?token=" . $reset_token;
        
        // Pour le développement, on affiche simplement le lien
        $success_message = "Un lien de réinitialisation a été envoyé à votre adresse email.<br>
                          (Pour le développement : <a href='$reset_link'>$reset_link</a>)";
    } else {
        $error = "Aucun compte n'est associé à cette adresse email.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Réinitialisation du mot de passe</h2>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($success_message)): ?>
            <div class="alert alert-success">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
                <div class="form-text">
                    Entrez l'adresse email associée à votre compte pour recevoir un lien de réinitialisation.
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Envoyer le lien de réinitialisation</button>
        </form>
        
        <div class="mt-3">
            <a href="login.php">Retour à la connexion</a>
        </div>
    </div>
</body>
</html>

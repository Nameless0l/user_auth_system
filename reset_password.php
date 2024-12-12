<?php
require_once 'db.php';

session_start();

$token = isset($_GET['token']) ? $_GET['token'] : '';
$valid_token = false;

if ($token) {
    $database = new Database();
    $db = $database->getConnection();
    
    // Vérifier si le token est valide et n'a pas expiré
    $stmt = $db->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_token_expiry > NOW()");
    $stmt->execute([$token]);
    $user = $stmt->fetch();
    
    if ($user) {
        $valid_token = true;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && $valid_token) {
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    if ($password === $confirm_password) {
        if (strlen($password) >= 8) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Mettre à jour le mot de passe et supprimer le token
            $stmt = $db->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE reset_token = ?");
            $stmt->execute([$hashed_password, $token]);
            
            $_SESSION['success_message'] = "Votre mot de passe a été réinitialisé avec succès.";
            header("Location: login.php");
            exit();
        } else {
            $error = "Le mot de passe doit contenir au moins 8 caractères.";
        }
    } else {
        $error = "Les mots de passe ne correspondent pas.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation du mot de passe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Réinitialisation du mot de passe</h2>
        
        <?php if (!$valid_token): ?>
            <div class="alert alert-danger">
                Le lien de réinitialisation est invalide ou a expiré.
            </div>
            <a href="forgot_password.php" class="btn btn-primary">Demander un nouveau lien</a>
        <?php else: ?>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?token=" . $token); ?>" method="post" id="resetForm">
                <div class="mb-3">
                    <label for="password" class="form-label">Nouveau mot de passe</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>

                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirmer le mot de passe</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                </div>

                <button type="submit" class="btn btn-primary">Réinitialiser le mot de passe</button>
            </form>
        <?php endif; ?>
    </div>

    <script>
        document.getElementById('resetForm')?.addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            let isValid = true;
            let errors = [];

            if (password !== confirmPassword) {
                errors.push('Les mots de passe ne correspondent pas');
                isValid = false;
            }

            if (password.length < 8) {
                errors.push('Le mot de passe doit contenir au moins 8 caractères');
                isValid = false;
            }

            if (!/[A-Z]/.test(password)) {
                errors.push('Le mot de passe doit contenir au moins une majuscule');
                isValid = false;
            }

            if (!/[0-9]/.test(password)) {
                errors.push('Le mot de passe doit contenir au moins un chiffre');
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
                alert(errors.join('\n'));
            }
        });
    </script>
</body>
</html>

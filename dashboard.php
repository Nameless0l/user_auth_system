<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Mon Application</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Déconnexion</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <h1>Bienvenue, <?php echo htmlspecialchars($_SESSION['username']); ?> !</h1>
                <div class="card mt-4">
                    <div class="card-body">
                        <h5 class="card-title">Votre tableau de bord</h5>
                        <p class="card-text">
                            Vous êtes maintenant connecté à votre espace personnel.
                            Ici, vous pouvez gérer vos informations et accéder à toutes les fonctionnalités de l'application.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

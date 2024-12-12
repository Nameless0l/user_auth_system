<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

class Mailer {
    private $mail;

    public function __construct() {
        $this->mail = new PHPMailer(true);
        
        // Configuration du serveur SMTP
        $this->mail->isSMTP();
        $this->mail->Host = 'smtp.gmail.com'; // À modifier selon votre serveur SMTP
        $this->mail->SMTPAuth = true;
        $this->mail->Username = 'votre_email@gmail.com'; // À modifier
        $this->mail->Password = 'votre_mot_de_passe'; // À modifier
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mail->Port = 587;
        
        // Configuration de l'expéditeur
        $this->mail->setFrom('votre_email@gmail.com', 'Nom de votre site');
        $this->mail->CharSet = 'UTF-8';
    }

    public function sendVerificationEmail($email, $token) {
        try {
            $this->mail->addAddress($email);
            $this->mail->isHTML(true);
            $this->mail->Subject = 'Vérification de votre compte';
            
            $verificationLink = "http://" . $_SERVER['HTTP_HOST'] . 
                              "/verify_email.php?token=" . $token;
            
            $this->mail->Body = "
                <h2>Vérification de votre compte</h2>
                <p>Cliquez sur le lien suivant pour vérifier votre compte :</p>
                <p><a href='$verificationLink'>Vérifier mon compte</a></p>
            ";
            
            $this->mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Erreur d'envoi d'email: " . $e->getMessage());
            return false;
        }
    }
}

<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/phpmailer/phpmailer/src/Exception.php';
require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../vendor/phpmailer/phpmailer/src/SMTP.php';
session_start();
include("../config/dbconfig.php"); // Assuming this file contains the Database class
$db = Database::connect();
if (isset($_GET['id']) && isset($_GET['apogee']) && isset($_GET['typedemande'])) {
    // Récupérer les valeurs
    $id = $_GET['id'];
    $apogee = $_GET['apogee'];
    $typedemande = $_GET['typedemande'];
    // Requête SQL pour récupérer l'ID de l'étudiant à partir de l'apogée
    $query = "SELECT idstudent, Email, fullName FROM etudiants WHERE apogee = :apogee";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':apogee', $apogee, PDO::PARAM_STR);
    $stmt->execute();
    $etudiant = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($etudiant) {
        $idStudent = $etudiant['idstudent'];
        $email = $etudiant['Email'];
        $fullName = $etudiant['fullName'];
        // Requête SQL pour récupérer les PDFs de la base de données
        $query = "SELECT filename, pdf_content FROM demande_accepte WHERE idstudent = :idStudent AND type_demande = :typeDemande";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':idStudent', $idStudent, PDO::PARAM_INT);
        $stmt->bindParam(':typeDemande', $typedemande, PDO::PARAM_STR); // Assurez-vous d'utiliser le type de données approprié
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
            // Envoyer l'e-mail
            $sujet = 'Traitement de votre reclamation';
            $message = 'Vous trouverez ci-joint le document que vous avez demandé.';
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Port = 587;
                $mail->Username = 'ensategenielogiciel@gmail.com';
                $mail->Password = 'mlwh vvem svyo vozd';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $filename = $row['filename'] . ".pdf";

                $mail->addStringAttachment($row['pdf_content'], $filename);
                $mail->setFrom('ensategenielogiciel@gmail.com', 'Scolarite ');
                $mail->addAddress($email, $fullName);
                $mail->isHTML(true);
                $mail->Subject = $sujet;
                $mail->Body = $message;
                $mail->send();
            } catch (Exception $e) {
                
            }
        }
        $deleteQuery = "DELETE FROM reclamations WHERE id = :id";
        $deleteStmt = $db->prepare($deleteQuery);
        $deleteStmt->bindValue(':id', $id, PDO::PARAM_INT);
        $deleteStmt->execute();
        header('Location: space_reclamation.php');        
}
?>

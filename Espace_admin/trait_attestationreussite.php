<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/phpmailer/phpmailer/src/Exception.php';
require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../vendor/phpmailer/phpmailer/src/SMTP.php';
header('Content-Type: text/html; charset=utf-8');
ini_set('default_charset', 'UTF-8');
mb_internal_encoding('UTF-8');
require('../fpdf186/fpdf.php');
session_start();
include("../config/dbconfig.php");
$db = Database::connect();
$db->exec("set names utf8");
function generatePDF($demande, $etudiant) {
    $pdf = new FPDF('p', 'mm', 'A4');
    $pdf->AddPage();
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 6, 'ROYAUME DU MAROC', 0, 1, 'R');
    $pdf->Cell(0, 10, 'UNIVERSITE ABDELMALEK ESSAADI- ENSA Tetouan', 0, 1, 'R');
    // Ajouter le logo de l'école
    $logoPath = '../ensate.png'; // Remplacez par le chemin de votre logo
    $pdf->Image($logoPath, 10, 10, 60); // Ajustez les coordonnées et la taille du logo selon vos besoins
    // Définir une police pour le titre
    $pdf->SetFont('Arial', 'B', 25);
    // Positionner le titre au milieu de la page (ajustez la valeur du décalage Y selon vos besoins)
    $pdf->SetY(50); // Par exemple, décalage de 80 mm vers le bas
    $pdf->Cell(0, 10, 'Attestation de reussite', 0, 1, 'C'); // Centrer le titre
    // Ajouter un saut de ligne après le titre
    $pdf->Ln(10);
    $pdf->SetFont('Times', '', 13);   
    $pdf->SetFont('Times', '', 13);
    $pdf->MultiCell(0, 10, 'Le directeur de l\'ENSA Tetouan atteste que l\'etudiant(e) ' . $etudiant['fullName'] . ', inscrit(e) a l\'Universite Abdelmalek Essaadi-ENSA de Tetouan, poursuit ses etudes a l\'Ecole Nationale des Sciences Appliquees de Tetouan pour l\'annee universitaire ' . $demande['annee'] . '.');
    $pdf->Ln(5);
    $pdf->SetX(30);
    $pdf->Cell(0, 10, 'Diplome:ingenieur d\'etat  ', 0, 1);
    $pdf->SetX(30);
    $pdf->Cell(0, 10, 'Filiere : ' .$etudiant['niveau'], 0, 1);
    $pdf->Cell(0, 6, 'Monsieur Kamal REKLAOUI', 0, 1, 'R');
    $filename = 'attestation_reussite_' . $etudiant['fullName'] . '.pdf';
    $filepath = '../Document/attestation reussite' . $filename;
    // Save the file on the server
    $pdf->Output($filepath, 'F');
    // Return an array with file path and content
    return array('filepath' => $filepath, 'filename' => $filename, 'content' => file_get_contents($filepath));
}
$result = $db->query("SELECT * FROM demande WHERE typedemande = 'attestation de réussite'");
$demandes = $result->fetchAll(PDO::FETCH_ASSOC);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accepter'])) {
        $demandeId = $_POST['demande_id'];
        // Récupérer les informations de la demande
        $resultDemande = $db->query("SELECT * FROM demande WHERE id_demande = $demandeId");
        $demande = $resultDemande->fetch(PDO::FETCH_ASSOC);
        // Vérifier si la requête a renvoyé des résultats
        if ($demande) {
            $idStudent = $demande['idstudent'];
            // Récupérer les informations de l'étudiant
            $resultStudent = $db->query("SELECT * FROM etudiants WHERE idstudent = $idStudent");
            $date_demande= date("Y-m-d");
            // Vérifier si la requête a renvoyé des résultats
            if ($resultStudent) {
                $etudiant = $resultStudent->fetch(PDO::FETCH_ASSOC);           
                $demande = array_map('utf8_decode', $demande);
                $etudiant = array_map('utf8_decode', $etudiant);
                $pdfData = generatePDF($demande, $etudiant);
                // Insérer dans la table demande_accepte
                $stmt = $db->prepare("INSERT INTO demande_accepte (idstudent, date_demande,  type_demande, filename, pdf_content) VALUES (?, '$date_demande',?, ?, ?)");
                $stmt->bindValue(1, $etudiant['idstudent']);
                $typeDemande = 'Attestation de reussite';
                $stmt->bindValue(2, $typeDemande);
                $stmt->bindValue(3, $pdfData['filename']);
                $stmt->bindValue(4, $pdfData['content'], PDO::PARAM_LOB);
                $stmt->execute();
                $sujet = 'Attestation de reussite ';
                $message = 'Vous trouvez ci joint l\'attestation de réussite que vous avez demandé' ;
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Port = 587;
                    $mail->Username = 'ensategenielogiciel@gmail.com';
                    $mail->Password = 'mlwh vvem svyo vozd';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->addAttachment( $pdfData['filepath']);
                    $mail->setFrom('ensategenielogiciel@gmail.com', 'Scolarite ');
                    $mail->addAddress($etudiant['Email'], $etudiant['fullName']);
                    $mail->isHTML(true);                                  //Set email format to HTML
                    $mail->Subject = $sujet;
                    $mail->Body    = $message ;
                    $mail->send();
                    echo '<div class="success-message">';
                    echo '<p>Message envoyé avec succès !</p>';
                    echo 'E-mail envoyé à : <span>' . $etudiant['Email'] . '</span> Nom : <span>' . $etudiant['fullName'] . '</span>';
                    echo '</div>';
                } catch (Exception $e) {
                    echo '<div class="success-message">';
                    echo "Le message n'a pas pu être envoyé. Mailer Error: {$mail->ErrorInfo}";
                    echo '</div>';                
                }
                unlink($pdfData['filepath']);
                // Supprimer de la table demande
                $stmtDelete = $db->prepare("DELETE FROM demande WHERE id_demande = ?");
                $stmtDelete->bindParam(1, $demandeId);
                $stmtDelete->execute();
            } /*else {
                echo "Erreur lors de la récupération des données de l'étudiant.";
            } */
        }/* else {
            echo "Erreur lors de la récupération des données de la demande."; */
    }
    if (isset($_POST['refuser'])) {
        $demandeId = $_POST['demande_id'];
        // Récupérer les informations de la demande et de l'étudiant
        $stmtDemande = $db->prepare("SELECT * FROM demande WHERE id_demande = ?");
        $stmtDemande->execute([$demandeId]);
        $demande = $stmtDemande->fetch(PDO::FETCH_ASSOC);
        // Vérifier si la demande existe
        if ($demande) {
            $idStudent = $demande['idstudent'];
            $stmtStudent = $db->prepare("SELECT * FROM etudiants WHERE idstudent = ?");
            $stmtStudent->execute([$idStudent]);
            $etudiant = $stmtStudent->fetch(PDO::FETCH_ASSOC);
            // Afficher le formulaire de refus
            echo '<div class="refuse-form">';
            echo '<section class="wrapper">';
            echo '<header>Refus de  la demande </header>';
            echo '<form action="" method="post">';
            echo '<div class="field input">';
            echo '<input type="hidden" name="demande_id" value="' . $demandeId . '">';
            echo '<label>Motif de refus</label> <input type="text" name="motif_refus" placeholder="Saisissez le motif de votre refus" >';
            echo '</div>';
            echo '<div class="field button">';
            echo '<input type="submit" name="confirmer_refus" value="Envoyer">';
            echo '</div>';
            echo '</form>';
            echo '</section>';
            echo '</div>';
        }
    }
    // Traitement du motif de refus
    if (isset($_POST['confirmer_refus'])) {
        $demandeId = $_POST['demande_id'];
        // Récupérer les informations de la demande et de l'étudiant
        $stmtDemande = $db->prepare("SELECT * FROM demande WHERE id_demande = ?");
        $stmtDemande->execute([$demandeId]);
        $demande = $stmtDemande->fetch(PDO::FETCH_ASSOC);
        $motifRefus = $_POST['motif_refus'];
        $idStudent = $demande['idstudent'];
        $stmtStudent = $db->prepare("SELECT * FROM etudiants WHERE idstudent = ?");
        $stmtStudent->execute([$idStudent]);
        $etudiant = $stmtStudent->fetch(PDO::FETCH_ASSOC);
        $date_demande= date("Y-m-d");
        if ($demande) {
        // Insertion du motif de refus dans la base de données
            $stmt = $db->prepare("INSERT INTO demande_refus (motifrefus,idstudent,type_demande, date_demande) VALUES (?,?,'attestation de reussite', '$date_demande')");
            $stmt->bindValue(1, $motifRefus);
            $stmt->bindValue(2, $idStudent);
            $stmt->execute();
        }
        $sujet='Refus de la demande d\'attestation de scolarite ';
        $message = 'Votre demande d\'attestation de scolarite , Motif de refus : ' . $motifRefus;
       // Envoyer un e-mail
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Port = 587;
            $mail->Username = 'ensategenielogiciel@gmail.com';
            $mail->Password = 'mlwh vvem svyo vozd';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->setFrom('ensategenielogiciel@gmail.com', 'Scolarite');
            $mail->addAddress($etudiant['Email'], $etudiant['fullName']);
            $mail->isHTML(true);
            $mail->Subject = $sujet;
            $mail->Body = $message;
            $mail->send();
            echo '<div class="success-message">';
            echo '<p>Message envoyé avec succès !</p>';
            echo 'E-mail envoyé à : <span>' . $etudiant['Email'] . '</span> Nom : <span>' . $etudiant['fullName'] . '</span>';
            echo '</div>';        
        } catch (Exception $e) {
            echo '<div class="success-message">';
            echo "Le message n'a pas pu être envoyé. Mailer Error: {$mail->ErrorInfo}";
            echo '</div>';        
        }
        $stmtDelete = $db->prepare("DELETE FROM demande WHERE id_demande = ?");
        $stmtDelete->bindParam(1, $demandeId);
        $stmtDelete->execute();
    }
}
$result = $db->query("SELECT * FROM demande WHERE typedemande = 'attestation de réussite' ORDER BY id_demande DESC");
$demandes = $result->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style11.css">
    <title>Liste des demandes d'attestation de réussite</title>
</head>
<body>
    <div class="container">
        <div class="navigation">
            <ul>
                <li>
                    <a href="#">
                        <span class="icon">
                        </span>
                    </a>
                </li>
                <li>
                    <a href="admin_dashboard2.php">
                        <span class="icon"><ion-icon name="home-outline"></ion-icon> </span>
                        <span class="title">Tableau de bord</span>
                    </a>
                </li>
                <li>
                    <a href="Demandes_1.php">
                        <span class="icon"><ion-icon name="mail-unread-outline"></ion-icon> </span>
                        <span class="title">Demandes</span>
                    </a>
                </li>
                <li>
                    <a href="space_reclamation.php">
                        <span class="icon"><ion-icon name="alert-circle-outline"></ion-icon></span>
                        <span class="title">Réclamations</span>
                    </a>
                </li>
                <li>
                    <a href="history3.php">
                        <span class="icon"><ion-icon name="time-outline"></ion-icon>                        </span>
                        <span class="title">Historique</span>
                        </a>
                    </li>        
                <li>
                    <a href="deconnexion_admin.php">
                        <span class="icon"><ion-icon name="log-out-outline"></ion-icon></span>
                        <span class="title">Déconnexion</span>
                    </a>
                </li>
            </ul>
        </div>
    <div class="content" > 
    <h1 class="title3">Liste des demandes d'attestation de réussite </h1>
    <table class="table table-striped" id="tableData">
        <thread>
            <tr>
                <th>Appoge</th>
                <th>Nom_d'étudiant</th>
                <th>Niveau</th>
                <th>Email_d'étudiant</th>
                <th>Année_universitaire</th>
                <th>Traitement_demande</th>
            </tr>
        </thread>
        <?php foreach ($demandes as $demande): ?>
            <tr>
                <?php
                    // Récupérer les informations de l'étudiant
                    $idStudent = $demande['idstudent'];
                    $resultStudent = $db->query("SELECT * FROM etudiants WHERE idstudent = $idStudent ");
                    $etudiant = $resultStudent->fetch(PDO::FETCH_ASSOC);
                ?>
                <td><?php echo $etudiant['apogee']; ?></td>
                <td><?php echo $etudiant['fullName'] ; ?></td>
                <td><?php echo $etudiant['niveau']; ?></td>
                <td><?php echo $etudiant['Email']; ?></td>
                <td><?php echo $demande['annee']; ?></td>
                <td>
                <form action="" method="post"> <!-- Remove the action attribute or set it to an empty string -->
                    <input type="hidden" name="demande_id" value="<?php echo $demande['id_demande']; ?>">
                    <input class="ppp2" type="submit" name="accepter" value="Accepter">
                    <input class="ppp3" type="submit" name="refuser" value="Refuser">
                </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <script>
        function accepterDemande(demandeId) {
            // Utilisez AJAX pour envoyer une demande au serveur
            // Exemple avec fetch API
            fetch('traitement_demande.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'demande_id=' + demandeId + '&action=accepter',
            }).then(response => {
                // Traitez la réponse si nécessaire
                console.log('Demande acceptée avec succès');
            });
        }

        function refuserDemande(demandeId) {
            // Utilisez AJAX pour envoyer une demande au serveur
            // Exemple avec fetch API
            fetch('traitement_demande.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'demande_id=' + demandeId + '&action=refuser',
            }).then(response => {
                console.log('Demande refusée avec succès');
            });
        }
    </script>
    <style>
            .success-message {
                position: fixed;
                top: 50%;
                left: 60%;
                transform: translate(-50%, -50%);
                z-index: 9999;
                background-color: #fff;
                max-width: 450px;
                width: calc(100% - 40px); /* Largeur de la div moins les marges horizontales */
                padding: 20px;
                border-radius: 16px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
                text-align: center;
            }
            .success-message span {
                font-weight: bold;
                color: black;
                display: block;
            }
            .refuse-form {
                position: fixed;
                top: 50%;
                left: 60%;
                transform: translate(-50%, -50%);
                z-index: 9999;
                background-color: #fff;
                max-width: 450px;
                width: 100%; /* Largeur de la div moins les marges horizontales */
                padding: 20px;
                border-radius: 16px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
                text-align: center; /* Centrer le contenu de la div */
            }
            /* styler les formulaire de connexion */
            .refuse-form{
                padding: 25px 30px;
            }
            .refuse-form header{
                font-size: 25px;
                font-weight: 600;
                padding-bottom: 10px;
                border-bottom: 1px solid #e6e6e6;
            }
            .refuse-form form{
                margin: 20px 0;
            }
            .refuse-form form .field{
                display: flex;
                margin-bottom: 10px;
                flex-direction: column;
                position: relative;
            }
            .refuse-form form .field label{
                margin-bottom: 1px;
            }
            .refuse-form form .input input{
                height: 100px;
                width: 100%;
                font-size: 16px;
                padding: 0 10px;
                border-radius: 5px;
                border: 1px solid #ccc;
            }
            .refuse-form form .field input{
                outline: none;
            }
            .refuse-form form .image input{
                font-size: 17px;
            }
            .refuse-form form .button input{
                height: 35px;
                border: none;
                color: #fff;
                font-size: 17px;
                background: #333;
                border-radius: 5px;
                cursor: pointer;
                margin-top: 13px;
            }
            .refuse-form form .button input:hover{
                background: #444;
            }
            .refuse-form form .field i{
                position: absolute;
                right: 15px;
                top: 70%;
                color: #ccc;
                cursor: pointer;
                transform: translateY(-50%);
            }

        </style>

    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

</body>
</html>



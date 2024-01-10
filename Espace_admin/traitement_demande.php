<?php
session_start();
require_once 'dbconfig.php';

if (isset($_SESSION['id'])) {
    $userId = $_SESSION['id'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['Email'];
        $apogee = $_POST['appogee'];
        $selectedType = $_POST['Type'];
        $annee = $_POST['annee'];

        // Validation des données (exemple avec la vérification de l'email et de l'apogée)
        $isValid = false;

        // Validation de l'email
        $emailQuery = "SELECT * FROM etudiants WHERE idstudent = :userId AND Email = :Email";
        $emailStmt = $db->prepare($emailQuery);
        $emailStmt->bindParam(':userId', $userId);
        $emailStmt->bindParam(':Email', $email);
        $emailStmt->execute();

        if ($emailStmt->rowCount() > 0) {
            // L'email est valide
            $isValid = true;
        }

        // Validation de l'apogée
        $apogeeQuery = "SELECT * FROM etudiants WHERE idstudent = :userId AND apogee = :apogee";
        $apogeeStmt = $db->prepare($apogeeQuery);
        $apogeeStmt->bindParam(':userId', $userId);
        $apogeeStmt->bindParam(':apogee', $apogee);
        $apogeeStmt->execute();

        if ($apogeeStmt->rowCount() > 0) {
            // L'apogée est valide
            $isValid = true;
        }

        if ($isValid) {
            // Utilisation d'une transaction pour garantir l'intégrité des données
            $db->beginTransaction();

            try {
                $query = $db->prepare("INSERT INTO demande (id_demande, annee, typedemande, traitee, etat, idstudent) 
                                       VALUES (NULL, ?, ?, '0', 'en cours', ?)");
                $query->execute([$annee, $selectedType, $userId]);

                $db->commit(); // Valider la transaction si tout s'est bien passé

                // Message de succès
                echo json_encode(['success' => true, 'message' => 'Demande traitée avec succès!']);
            } catch (PDOException $e) {
                $db->rollback(); // Annuler la transaction en cas d'erreur
                echo json_encode(['success' => false, 'message' => 'Erreur lors du traitement de la demande']);
            }
        } else {
            // Message d'erreur si les données ne sont pas valides
            echo json_encode(['success' => false, 'message' => 'Données invalides']);
        }
    }
}
?>

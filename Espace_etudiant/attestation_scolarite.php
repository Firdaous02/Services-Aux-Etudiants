<?php
include('../config/dbconfig.php'); // Inclure le fichier dbconfig.php contenant la classe Database

// Récupération des données envoyées depuis le formulaire en format JSON
$data = json_decode(file_get_contents('php://input'), true);

// Utilisation de la classe Database pour établir la connexion
$connection = Database::connect();

if ($connection) {
    $email = $data['Email']; // Récupérer l'email depuis le formulaire
    $appogee = $data['appogee']; // Récupérer le code apogée depuis le formulaire

    // Requête pour récupérer l'identifiant de l'étudiant à partir de l'email et du code apogée
    $query = "SELECT idstudent FROM etudiants WHERE Email = '$email' AND apogee = '$appogee'";
    $result = $connection->query($query);

    if ($result && $result->rowCount() > 0) {
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $idstudent = $row['idstudent'];

        // Récupération des autres données du formulaire
        $annee = $data['annee']; // Année provenant du formulaire
        $typedemande = $data['selectedType']; // Type de demande provenant du formulaire

        $date_demande= date("Y-m-d");//date de la demande
        // Exemple d'insertion dans la table demande
        $sql = "INSERT INTO demande (date_demande, annee, typedemande, etat, idstudent) 
                VALUES ('$date_demande', '$annee', '$typedemande', 'en attente', '$idstudent')";

        $result = $connection->query($sql);

        if ($result) {
            $response = array(
                'success' => true,
                'message' => 'Demande insérée avec succès!'
            );
        } else {
            $response = array(
                'success' => false,
                'message' => 'Erreur lors de l\'insertion de la demande: '
            );
        }
    } else {
        $response = array(
            'success' => false,
            'message' => 'Aucun étudiant correspondant trouvé.'
        );
    }

    // Fermer la connexion à la base de données
    Database::disconnect();
} else {
    $response = array(
        'success' => false,
        'message' => 'Erreur de connexion à la base de données.'
    );
}

// Envoyer une réponse JSON au script JavaScript
header('Content-Type: application/json');
echo json_encode($response);
?>

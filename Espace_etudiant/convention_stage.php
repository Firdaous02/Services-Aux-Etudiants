<?php
include('../config/dbconfig.php'); // Inclure le fichier dbconfig.php contenant la classe Database

// Récupération des données envoyées depuis le formulaire en format JSON
$data = json_decode(file_get_contents('php://input'), true);

// Utilisation de la classe Database pour établir la connexion
$connection = Database::connect();

if ($connection) {
    $email = isset($data['Email']) ? $data['Email'] : '';
    $appogee = isset($data['appogee']) ? $data['appogee'] : '';

    // Requête pour récupérer l'identifiant de l'étudiant à partir de l'email et du code apogée
    $query = "SELECT idstudent FROM etudiants WHERE Email = '$email' AND apogee = '$appogee'";
    $result = $connection->query($query);

    if ($result && $result->rowCount() > 0) {
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $idstudent = $row['idstudent'];

        // Récupération des autres données du formulaire
        $annee = isset($data['annee']) ? $data['annee'] : '';
        $type_stage = isset($data['type_stage']) ? $data['type_stage'] : '';
        $datedebut = isset($data['datedebut']) ? $data['datedebut'] : '';
        $datefin = isset($data['datefin']) ? $data['datefin'] : '';
        $nom_entreprise = isset($data['nom_entreprise']) ? $data['nom_entreprise'] : '';
        $adresse_entreprise = isset($data['adresse_entreprise']) ? $data['adresse_entreprise'] : '';
        $nom_encadrant = isset($data['nom_encadrant']) ? $data['nom_encadrant'] : '';
        $email_encadrant = isset($data['email_encadrant']) ? $data['email_encadrant'] : '';
        $tel_encadrant = isset($data['tel_encadrant']) ? $data['tel_encadrant'] : '';

        $date_demande= date("Y-m-d");//date de la demande
        // Exemple d'insertion dans la table demande
        $sql = "INSERT INTO demande (date_demande, annee, typedemande, etat, idstudent, type_stage, datedebut, datefin, nom_entreprise, adresse_entreprise, nom_encadrant, email_encadrant, tel_encadrant) 
                VALUES ('$date_demande', '$annee', 'Convention de Stage', 'en attente', '$idstudent', '$type_stage', '$datedebut', '$datefin', '$nom_entreprise', '$adresse_entreprise', '$nom_encadrant', '$email_encadrant', '$tel_encadrant')";

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

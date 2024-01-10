<?php
// Inclure le fichier de configuration de la base de données et établir une connexion
include_once "../config/dbconfig2.php";

// Vérifier si l'ID du fichier PDF est présent dans la requête GET
if (isset($_GET['id'])) {
    // Récupérer l'ID du fichier PDF depuis la requête GET
    $pdfId = $_GET['id'];

    // Requête pour récupérer le PDF à partir du BLOB en fonction de son ID
    $query = "SELECT * FROM demande_accepte WHERE id = $pdfId";
    $result = mysqli_query($conn, $query);

    if ($result) {
        // Obtenir le résultat de la requête
        $row = mysqli_fetch_assoc($result);

        // Récupérer le contenu du BLOB (PDF)
        $pdfContent = $row['pdf_content'];
        $filename = $row['filename'];

        // Entête pour indiquer que le contenu est un fichier PDF
        header("Content-type: application/pdf");
        // Entête pour indiquer que le contenu doit être ouvert dans le navigateur
        header("Content-Disposition: inline; filename= $filename.pdf");

        // Afficher le contenu du PDF (BLOB) pour l'affichage dans le navigateur
        echo $pdfContent;

        // Terminer l'exécution du script après l'affichage
        exit();
    }
}
// Redirection ou gestion d'erreur si l'ID du PDF n'est pas valide
?>

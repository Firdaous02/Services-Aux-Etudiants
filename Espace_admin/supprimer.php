<?php
session_start();
include_once '../config/dbconfig2.php';
if(isset($_GET['id']) AND !empty($_GET['id'])){//On vérifie la récuperation de l'id de la réclamation par la méthode GET
    $getid = ($_GET['id']);//On stocke l'id
    $req = "SELECT * FROM reclamations WHERE id = {$getid}";
    $getrec = mysqli_query($conn, $req);//On récupère la réclamtion de l'utilisateur 
    
    if(mysqli_num_rows($getrec) > 0){//Si la réclamation est trouvée on la supprime de la table
        $delete = "DELETE FROM reclamations WHERE id = {$getid}";
        $query2 = mysqli_query($conn, $delete);
    }else{
        echo "Aucune réclamation trouvé";
    }

}else{
    echo "L'identifiant n'a pas été récupéré";
}
header('Location: space_reclamation.php');
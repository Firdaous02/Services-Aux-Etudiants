<?php 
session_start();
if(!isset($_SESSION['id'])){//Si l'admin n'est pas connecté on le redirige vers la page de connexion
    header("location: login.php");
  }
?>
<!DOCTYPE html>
<html >
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>espace_demande</title>
    <link rel="stylesheet" href="style_reclamation.css">
    <script src="https://kit.fontawesome.com/1e94604817.js" crossorigin="anonymous"></script>
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
                        <a href="admin_dashboard.php">
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
            <h1 class="title3">Les réclamations non traitées</h1> 
            <table class="table table-striped" id="tableData">
                <thead>
                    <tr>
                        <th>Appoge</th>
                        <th>Nom_d'étudiant</th>
                        <th>Date_réclamation </th>
                        <th>Type_de_document</th>
                        <th>Réclamation</th>
                        <th>Traitement_de_la_demande</th>
                        
                    </tr>
                </thead>
                <?php
                    include_once "../config/dbconfig2.php";
                    $req2 = "SELECT reclamations.*, etudiants.* FROM reclamations INNER JOIN etudiants  ON reclamations.idstudent = etudiants.idstudent WHERE reclamations.statut='en attente' ORDER BY reclamations.id DESC";
                    $getInf = mysqli_query($conn, $req2);
                    if(mysqli_num_rows($getInf) > 0){
                    while($row = mysqli_fetch_assoc($getInf)){
                ?>
                <tr>
                    <td><?=$row["apogee"]?></td>
                    <td><?=$row["fullName"]?></td>
                    <td><?=$row["date_recla"]?></td>
                    <td><?= $row["typedemande"]?></td> 
                    <td><?=$row["texte_reclamation"]?></td>
                    <td>
                        <a href="renvoyer.php?id=<?= $row['id'] ?>&apogee=<?= $row['apogee'] ?>&typedemande=<?= $row['typedemande'] ?>"><button class="ppp4">Renvoyer</button></a>
                        <a href="supprimer.php?id=<?= $row['id']; ?>"><button class="ppp5"  >supprimer</button> </a> 
                    </td>
                </tr>
            <?php
                }
            }
            ?>  
        </table>  
    </div>
</div>
    <style>
       .ppp4{
            margin:10px 0;
	        padding:10px 10px;
	        border-radius:20px;
	        display:inline-block;
            text-align: center;
            background-color: #bbb ;
            color: black;
        }
        .ppp5{
            margin:10px 0;
	        padding:10px 10px;
	        border:1px solid #bbb;
	        border-radius:20px;
	        display:inline-block;
            text-align: center;
            background-color: black;
            color: white;
        }
        button:hover{
            text-align: center;
            background-color: black;
            color: white;
            border: 1px solid black;
        }
    </style>
     <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

</body>
</html>
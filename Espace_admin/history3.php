<?php
include_once "../config/dbconfig2.php";
session_start();
if(!isset($_SESSION['id'])){//Si l'admin n'est pas connecté on le redirige vers la page de connexion
    header("location: login.php");
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Historique</title>
    <link rel="stylesheet" href="style12.css">
    <script src="https://kit.fontawesome.com/1e94604817.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
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

        <div class="content">
            <h1 class="titre3">Historique</h1>
            <form action="" method="GET">
                <div class="search">
                    <label>
                        <input type="text" name="s" id="searchInput" placeholder="Rechercher ici" autocomplete="off" value="<?php echo isset($_GET['s']) ? $_GET['s'] : ''; ?>">
                        <i id="icon" class="fa-solid fa-magnifying-glass" style="color: #000000;"></i>
                        <i id="icon2" class="fa-solid fa-circle-xmark" style="color: #000000;" onclick="effacerInput()"></i>
                    </label>
                </div>
                <script>
                    function effacerInput() {
                        document.getElementById('searchInput').value = '';
                    }
                </script>
            </form>
            <table class="table table-striped" id="tableData">
                <thead>
                    <tr>
                        <th>Appoge</th>
                        <th>Nom Complet</th>
                        <th>Type de document</th>
                        <th>Date</th>
                        <th>Etat</th>
                        <th>Plus de détails</th>
                    </tr>
                </thead>
                <?php
if (isset($_GET['s'])) {
    $searchTerm = $_GET['s'];
    if ($searchTerm == 'acceptée') {
        $req_combined = " SELECT 'acceptee' AS etat, demande_accepte.*, etudiants.*, demande_accepte.type_demande AS type_demande_combined, '' AS motifrefus FROM demande_accepte INNER JOIN etudiants ON demande_accepte.idstudent = etudiants.idstudent ORDER BY date_demande DESC";
    } elseif ($searchTerm == 'refusée') {
        $req_combined = " SELECT 'refusee' AS etat, demande_refus.*, etudiants.*, demande_refus.type_demande AS type_demande_combined, demande_refus.motifrefus FROM demande_refus INNER JOIN etudiants ON demande_refus.idstudent = etudiants.idstudent ORDER BY date_demande DESC";
    }else{
    $searchTerm = mysqli_real_escape_string($conn, $searchTerm);
    $req_combined = "
        (SELECT 'acceptee' AS etat, demande_accepte.*, etudiants.*, demande_accepte.type_demande AS type_demande_combined, '' AS motifrefus
        FROM demande_accepte 
        INNER JOIN etudiants ON demande_accepte.idstudent = etudiants.idstudent
        WHERE (etudiants.apogee LIKE '%$searchTerm%') OR (etudiants.fullName LIKE '%$searchTerm%') OR (demande_accepte.type_demande LIKE '%$searchTerm%') OR (demande_accepte.date_demande LIKE '%$searchTerm%'))
        UNION 
        (SELECT 'refusee' AS etat, demande_refus.*, etudiants.*, demande_refus.type_demande AS type_demande_combined, demande_refus.motifrefus
        FROM demande_refus 
        INNER JOIN etudiants ON demande_refus.idstudent = etudiants.idstudent
        WHERE (etudiants.apogee LIKE '%$searchTerm%') OR (etudiants.fullName LIKE '%$searchTerm%') OR (demande_refus.type_demande LIKE '%$searchTerm%') OR (demande_refus.date_demande LIKE '%$searchTerm%'))
        ORDER BY date_demande DESC"; 
    }
} else {
    $req_combined = "
        (SELECT 'acceptee' AS etat, demande_accepte.*, etudiants.*, demande_accepte.type_demande AS type_demande_combined, '' AS motifrefus
        FROM demande_accepte 
        INNER JOIN etudiants ON demande_accepte.idstudent = etudiants.idstudent) 
        UNION 
        (SELECT 'refusee' AS etat, demande_refus.*, etudiants.*, demande_refus.type_demande AS type_demande_combined, demande_refus.motifrefus
        FROM demande_refus 
        INNER JOIN etudiants ON demande_refus.idstudent = etudiants.idstudent) 
        ORDER BY date_demande DESC";
}
$getInf_combined = mysqli_query($conn, $req_combined);

if ($getInf_combined) {
    while ($row = mysqli_fetch_assoc($getInf_combined)) {
        ?>
        <tr>
            <td><?= $row["apogee"] ?></td>
            <td><?= $row["fullName"] ?></td>
            <td><?= $row["type_demande_combined"] ?></td>
            <td><?= $row["date_demande"] ?></td>
            <td>
            <?php if ($row["etat"] === 'acceptee') {
                echo 'acceptée';
            } else {
                echo 'refusée';
            } ?>
        </td>
            <td>
                <?php if ($row["etat"] === 'acceptee') { ?>
                    <button class="btn btn-primary" onclick="window.open('telecharger_pdf.php?id=<?= $row['id'] ?>', '_blank')">Télécharger PDF</button>
                <?php } else { ?>
                    <?php if (!empty($row['motifrefus'])) { ?>
                        <button class="btn btn-secondary" data-toggle="modal" data-target="#motifRefusModal" data-motif="<?= $row['motifrefus'] ?>">Motif de refus</button>
                    <?php } else { ?>
                        <span>Aucun motif de refus</span>
                    <?php } ?>
                <?php } ?>
            </td>
        </tr>
        <?php
    }
} else {
    ?>
    <tr>
        <td colspan="5">Aucun enregistrement trouvé.</td>
    </tr>
    <?php
}
?>
</table>


        </div>
    </div>
    <div class="modal fade" id="motifRefusModal" tabindex="-1" role="dialog" aria-labelledby="motifRefusModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="motifRefusModalLabel">Voici le motif de refus que vous avez envoyé</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="motif-refus-content"></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Capturer le clic sur le bouton "Motif de refus"
            $(document).on('click', '.btn-secondary', function() {
                var motifRefus = $(this).data('motif');
                $('.motif-refus-content').text(motifRefus); // Afficher le motif de refus dans la modal
                $('#motifRefusModal').modal('show'); // Afficher la fenêtre modale
            });
        });
    </script>

    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
   
     <style>
        .modal {
            display: none; 
            position: fixed;
            top: 30%;
            left: 10%;
            z-index: 1050;
            width: 100%;
            height: 100%;
            overflow: hidden;
            outline: 0;
        }
        .modal-content {
            position: relative;
            display: flex;
            flex-direction: column;
            width: 80%;
            margin: auto;
            background-color: #fff;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }
        .modal-header {
            padding: 15px;
            border-bottom: 1px solid #ccc;
        }
        .modal-title {
            margin: 0;
        }
        .close {
            position: absolute;
            top: 15px;
            right: 15px;
            font-size: 24px;
            font-weight: bold;
            cursor: pointer;
        }
        .modal-body {
            padding: 15px;
        }
        .motif-refus-content {
            /* style  pour le contenu du motif de refus */
        }

        .btn-primary {
            background-color: #215d85;
            color: #fff;
        }
        .btn-primary:hover {
            background-color: #fff;
            border-color: #215d85;
            color: #215d85;
        }
        .btn-secondary {
            background-color: #222;
            color: #fff;
        }
        .btn-secondary:hover {
            background-color: #fff;
            border-color: #222;
            color: #222;
        }
        </style>
</body>
</html>

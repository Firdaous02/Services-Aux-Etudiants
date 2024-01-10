<?php 
include_once '../config/dbconfig2.php';
session_start();
if(!isset($_SESSION['id'])){//Si l'admin n'est pas connecté on le redirige vers la page de connexion
    header("location: login.php");
  }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrateur</title>
    <!-- ======= Styles ====== -->
    <link rel="stylesheet" href="style_admin_dashboard.css">
    <script src="https://code.jquery.com/jquery-3.1.1.min.js" ></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" ></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
</head>

<body>
    
    <!-- =============== Navigation ================ -->
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
                        <span class="icon">
                            <ion-icon name="home-outline"></ion-icon>
                        </span>
                        <span class="title">Tableau de bord</span>
                    </a>
                </li>

                <li>
                    <a href="Demandes_1.php" >
                        <span class="icon">
                        <ion-icon name="mail-unread-outline"></ion-icon>
                        </span>
                        <span class="title">Demandes</span>
                    </a>
                </li>

                <li>
                    <a href="space_reclamation.php" >
                        <span class="icon">
                        <ion-icon name="alert-circle-outline"></ion-icon>
                        </span>
                        <span class="title">Réclamations</span>
                    </a>
                </li>
                <li>
                    <a href="history3.php" >
                        <span class="icon">
                        <ion-icon name="time-outline"></ion-icon>                        </span>
                        <span class="title">Historique</span>
                    </a>
                </li>        

                <li>
                    <a href="deconnexion_admin.php">
                        <span class="icon">
                            <ion-icon name="log-out-outline"></ion-icon>
                        </span>
                        <span class="title">Déconnexion</span>
                    </a>
                </li>
            </ul>
        </div>
    <div class="container">
    <h1 class="title3">Bienvenue dans l'espace d'admin </h1>

        <!-- ========================= Main ==================== -->
        <div class="main">
            <div class="topbar">

              <div class="cardBox2">
                    <div class="card2">
                        <div> <h3>Nombre total des demandes:</h3>
                        <div class="cardName2"> <?php
                        $sql16="SELECT * from demande";
                        $result16=$conn-> query($sql16);
                        $count16=0;
                        if ($result16-> num_rows > 0){
                            while ($row16=$result16-> fetch_assoc()) {
                    
                                $count16=$count16+1;
                            }
                        }
                        echo $count16;
                        ?>
                        </div>
                        </div>
                    </div>
                    <div class="card2">
                      <div> <h3>Nombre total des réclamations:</h3>
                            <div class="cardName2"> <?php
                                $sql17="SELECT * from reclamations";
                                $result17=$conn-> query($sql17);
                                $count17=0;
                                if ($result17-> num_rows > 0){
                                    while ($row17=$result17-> fetch_assoc()) {
                                        $count17=$count17+1;
                                    }
                                }
                                echo $count17;
                                ?>
                            </div>
                        </div>
                    </div> 
                </div>

            </div>

            <!-- ======================= Cards ================== -->
            <div class="cardBox">
                <div class="card">
                    <div>
                        <div class= "document"><h2>Attestation de scolarité</h2></div>
                        <div class="numbers"><?php
                        $sql="SELECT * from demande where typedemande='attestation de scolarité'";
                        $result=$conn-> query($sql);
                        $count=0;
                        if ($result-> num_rows > 0){
                            while ($row=$result-> fetch_assoc()) {
                    
                                $count=$count+1;
                            }
                        }
                        echo $count;
                    ?></div><br/>
                    <div class="cardName">
                        <div class="main1">En_attente</div>
                        <div class="main2"><?php
                        $sql1="SELECT * from demande where etat = 'en attente' and typedemande='attestation de scolarité'";
                        $result1=$conn-> query($sql1);
                        $count1=0;
                        if ($result1-> num_rows > 0){
                            while ($row1=$result1-> fetch_assoc()) {
                    
                                $count1=$count1+1;
                            }
                        }
                        echo $count1; ?>
                        </div>
                    </div><br/>

                        <div class="cardName">
                            <div class="main1">Validées</div>
                            <div class="main2"><?php
                            $sql2="SELECT * from demande_accepte where type_demande='attestation de scolarité'";
                            $result2=$conn-> query($sql2);
                            $count2=0;
                            if ($result2-> num_rows > 0){
                                while ($row2=$result2-> fetch_assoc()) {
                    
                                    $count2=$count2+1;
                                }
                            }
                            echo $count2; ?>
                            </div>    
                        </div><br/>
                        <div class="cardName">
                            <div class="main1">Refusées</div>
                            <div class="main2"><?php
                            $sql3="SELECT * from demande_refus where type_demande='attestation de scolarité'";
                            $result3=$conn-> query($sql3);
                            $count3=0;
                            if ($result3-> num_rows > 0){
                                while ($row3=$result3-> fetch_assoc()) {
                    
                                    $count3=$count3+1;
                                }
                            }
                            echo $count3; ?>
                            </div>
                        </div>

                    </div>


                </div>

                <div class="card">
                    <div>
                        <div class= "document"><h2>Attestation de réussite</h2></div>
                        <div class="numbers"><?php
                        $sql4="SELECT * from demande where typedemande='attestation de réussite'";
                        $result4=$conn-> query($sql4);
                        $count4=0;
                        if ($result4-> num_rows > 0){
                            while ($row4=$result4-> fetch_assoc()) {
                    
                                $count4=$count4+1;
                            }
                        }
                        echo $count4;
                    ?></div><br/>
                        <div class="cardName">
                            <div class="main1">En_attente</div>
                            <div class="main2"><?php
                                $sql5="SELECT * from demande where etat = 'en attente' and typedemande='attestation de réussite'";
                                $result5=$conn-> query($sql5);
                                $count5=0;
                                if ($result5-> num_rows > 0){
                                    while ($row5=$result5-> fetch_assoc()) {
                    
                                        $count5=$count5+1;
                                    }
                                }
                                echo $count5; ?>
                            </div>    
                        </div><br/>
                        <div class="cardName">
                            <div class="main1">Validées</div>
                            <div class="main2"><?php
                                $sql6="SELECT * from demande_accepte where type_demande='attestation de réussite'";
                                $result6=$conn-> query($sql6);
                                $count6=0;
                                if ($result6-> num_rows > 0){
                                    while ($row6=$result6-> fetch_assoc()) {
                    
                                        $count6=$count6+1;
                                    }
                                }
                                echo $count6; ?>
                            </div>    
                        </div><br/>
                        <div class="cardName">
                            <div class="main1">Refusées</div>
                            <div class="main2"><?php
                                $sql7="SELECT * from demande_refus where type_demande='attestation de réussite'";
                                $result7=$conn-> query($sql7);
                                $count7=0;
                                if ($result7-> num_rows > 0){
                                    while ($row7=$result7-> fetch_assoc()) {
                    
                                        $count7=$count7+1;
                                    }
                                }
                                echo $count7; ?>
                            </div>    
                        </div>
                    </div>

 
                </div>

                <div class="card">
                    <div>
                        <div class= "document"><h2>Relevé de notes</h2></div>
                        <div class="numbers"><?php
                        $sql8="SELECT * from demande where typedemande='relevé de notes'";
                        $result8=$conn-> query($sql8);
                        $count8=0;
                        if ($result8-> num_rows > 0){
                            while ($row8=$result8-> fetch_assoc()) {
                    
                                $count8=$count8+1;
                            }
                        }
                        echo $count8;
                    ?></div><br/>
                        <div class="cardName">
                        <div class="main1">En_attente</div>
                        <div class="main2"><?php
                            $sql9="SELECT * from demande where typedemande='relevé de notes' and etat = 'en attente'";
                            $result9=$conn-> query($sql9);
                            $count9=0;
                            if ($result9-> num_rows > 0){
                                while ($row9=$result9-> fetch_assoc()) {
                    
                                    $count9=$count9+1;
                                }
                            }
                            echo $count9;
                            ?>
                        </div>    
                    </div><br/>
                        <div class="cardName">
                        <div class="main1">Validées</div>
                        <div class="main2"><?php
                            $sql10="SELECT * from demande_accepte where type_demande='relevé de notes'";
                            $result10=$conn-> query($sql10);
                            $count10=0;
                            if ($result10-> num_rows > 0){
                                while ($row10=$result10-> fetch_assoc()) {
                    
                                    $count10=$count10+1;
                                }
                            }
                            echo $count10;
                            ?>
                        </div>    
                    </div><br/>
                        <div class="cardName">
                        <div class="main1">Refusées</div>
                        <div class="main2"><?php
                            $sql11="SELECT * from demande_refus where type_demande='relevé de notes'";
                            $result11=$conn-> query($sql11);
                            $count11=0;
                            if ($result11-> num_rows > 0){
                                while ($row11=$result11-> fetch_assoc()) {
                    
                                    $count11=$count11+1;
                                }
                            }
                            echo $count11;
                            ?>
                        </div>    
                    </div>                   
                </div>


                </div>

                <div class="card">
                    <div>
                        <div class= "document"><h2>Convention de stage</h2></div>
                        <div class="numbers"><?php
                        $sql12="SELECT * from demande where typedemande='convention de stage'";
                        $result12=$conn-> query($sql12);
                        $count12=0;
                        if ($result12-> num_rows > 0){
                            while ($row12=$result12-> fetch_assoc()) {
                    
                                $count12=$count12+1;
                            }
                        }
                        echo $count12;
                    ?></div><br/>
                        <div class="cardName">
                        <div class="main1">En_attente</div>
                        <div class="main2"><?php
                            $sql13="SELECT * from demande where typedemande='convention de stage' AND etat = 'en attente'";
                            $result13=$conn-> query($sql13);
                            $count13=0;
                            if ($result13-> num_rows > 0){
                                while ($row13=$result13-> fetch_assoc()) {
                    
                                    $count13=$count13+1;
                                }
                            }
                            echo $count13;
                            ?>
                        </div>    
                    </div><br/>
                        <div class="cardName">
                        <div class="main1">Validées</div>
                        <div class="main2"><?php
                            $sql14="SELECT * from demande_accepte where type_demande='convention de stage'";
                            $result14=$conn-> query($sql14);
                            $count14=0;
                            if ($result14-> num_rows > 0){
                                while ($row14=$result14-> fetch_assoc()) {
                    
                                    $count14=$count14+1;
                                }
                            }
                            echo $count14;
                            ?></div>    
                        </div><br/>
                        <div class="cardName">
                        <div class="main1">Refusées</div>
                        <div class="main2"><?php
                            $sql15="SELECT * from demande_refus where type_demande='convention de stage'";
                            $result15=$conn-> query($sql15);
                            $count15=0;
                            if ($result15-> num_rows > 0){
                                while ($row15=$result15-> fetch_assoc()) {
                    
                                 $count15=$count15+1;
                                }
                            }
                            echo $count15;
                            ?></div>    
                        </div>                   
                    </div>


                </div>
            </div>

        </div>
    </div>
    <!-- =========== Scripts =========  -->
   <script src="main.js"></script>

    <!-- ====== ionicons ======= -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>
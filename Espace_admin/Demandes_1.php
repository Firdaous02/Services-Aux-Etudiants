<?php 
include_once '../config/dbconfig2.php';
session_start();
if(!isset($_SESSION['id'])){//Si l'admin n'est pas connecté on le redirige vers la page de connexion
    header("location: login.php");
  }
?>
<!DOCTYPE html>
<html >

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrateur</title>
    <!-- ======= Styles ====== -->
    <link rel="stylesheet" href="style_Demandes_1.css">
</head>

<body>
    <?php  include 'side.php'  ?>
    
    <div class="container">
        <!-- ========================= Main ==================== -->
        <div class="main">
            <div class="topbar">
                <h1 id= "titre">Bienvenu dans l'espace des demandes</h1>
  
            </div>
            <div class="pas">
                <br><p id="paragraphe"> Vous pouvez consulter les demandes des étudiants et les traiter, choisissez le type de la demande que vous voulez ici: </p> <br>
            </div>

            <!-- ======================= Cards ================== -->
            <div class="cardBox">
                <a href="trait_attestationscolarite.php"><div class="card">
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
                                ?>
                            </div><br/>
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
                        </div>
                    </div> </a>

                <a href="trait_attestationreussite.php"><div class="card">
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
                    </div> 
                </div> </a>

                <a href="trait_releve.php"> <div class="card">
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
                                ?>
                            </div><br/>
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
                </div>
                </div> </a>

                <a href="trait_convention.php"> <div class="card">
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
                    </div>
                </div> </a>
            </div> 

        </div>
    </div>

    <!-- ====== ionicons ======= -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>
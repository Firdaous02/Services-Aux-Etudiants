<?php
include("../config/dbconfig.php");
session_start();
$error='';
if(isset($_SESSION['id'])){//Si l'admin est déja connecté on le redirige vers son tableau de bord
  header("location: admin_dashboard.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") { // Vérifier si le formulaire a été soumis
    $db = Database::connect();

    $email = $_POST['email'];
    $password = $_POST['password'];

   
    $query = $db->prepare("SELECT * FROM admin WHERE email = ? AND password = ?");
    $query->execute([$email, $password]);
    $user = $query->fetch(PDO::FETCH_ASSOC);
    
    if ($user && $user['password'] == $password) {
        
        $_SESSION['id'] =$user['id'];;
        
        header("Location: admin_dashboard.php ?id=" . $user['id']);
        
        exit();
    } else {
        $error = "ll";
    }
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
    <title>Page d'acceuil</title>
    <link rel="stylesheet" href="style_login.css">
    <script src="https://kit.fontawesome.com/1e94604817.js" crossorigin="anonymous"></script>
</head>
<body>
  <header>
    <nav>
      <ul>
        <li><img  src="ensate.png" alt=""></li>
        <li class="li1"><a href="../index.php">Accueil</a></li>
        <li class="li1"><a href="../index.php #footer">Contact</a></li>
        <li class="li1"><a href="../index.php #work">A propos</a></li>
    </ul>
    </nav>
  </header>
  <div class="div1">
    <h1>Espace Administration</h1>
  </div>
  <div class="container">
    <main>
      <form method="POST">
        <strong><label class="ppp22" for="mail">Email Institutionnel :</label></strong><br>
        <input type="email" id="mail" placeholder="prenom.nom@etu.uae.ac.ma" name="email" ><br>
        <strong><label class="ppp22" >Mot de passe :</label></strong><br>
        <input type="text" id="password" placeholder="Mot de passe" name="password" ><br>
        <button class="ppp12" type="submit">S'authentifier</button>
      </form>
        <?php if (($error)=="ll"): ?>
            <div class="error-message"><?php echo "Adresse email ou mot de passe incorrect."; ?></div>
        <?php endif; ?>
        <style>
    .error-message {
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
        color: #721c24;
        padding: 10px;
        margin: 10px 0;
        border-radius: 5px;
    }
</style>

    </main>
    <div class="image3">
      <img src="./imgs/pic5.png" alt="Description de l'image">
    </div>
  </div>
  <div class="container1">
    
    <div class="prag_1">

    </div> 
  </div>
  
</body>
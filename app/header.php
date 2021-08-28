<?php
$lien_connexion= (basename($_SERVER['PHP_SELF'])=='login.php')?'connecter_active':'';
$lien_inscription=(basename($_SERVER['PHP_SELF'])=='inscription.php')?'inscrire_active':'';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>shopping</title>
   <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div>
    <header class="topbar">
        <div class="topbar-logo">
        <a href="index.php">Accueil</a>
        <a href="contact.php">Contact</a>
       </div>
       <?php if(isset($commander)) :?>
       <nav class="nav_name">
            <div><?=$_SESSION['nom']?></div>
            <div><?=$_SESSION['prenom']?></div>
       </nav>
        <?php else :?>
        <nav class="topbar-nav">          
        <span class="connecte <?=$lien_connexion ?>"><a href="login.php" >Connexion</a></span>           
        <span class="inscription <?=$lien_inscription?>"><a href="inscription.php">S'inscrire</a></span>   
        </nav>
        <?php endif ?>
        

    </header>

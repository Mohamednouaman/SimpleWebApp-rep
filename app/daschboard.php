<?php
$lien=false;
$lieng=null;
session_start();
if(empty($_SESSION['admin'])){
    header('Location:login.php');
    exit();
}
try{
    $connect_admin=new PDO('mysql:host=localhost;dbname=client;charset=utf8','root',null,
    [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]);
   $name_admin=$connect_admin->prepare("SELECT Nom FROM Utilisateur WHERE Email=:email");
   $name_admin->execute(['email'=>'admin@gmail.com']);  
   $admin_table=$name_admin->fetchAll();
   $admin_name=$admin_table[0];    
 
}catch(PDOException $e){
    die('error:'.$e->getMessage());
}
if(!empty($_GET['lien']) and $_GET['lien']=='produit'){

$lien=true;
if(!empty($_GET['lieng']) and ($_GET['lieng']=='ajouter' || $_GET['lieng']=='supprimer')){
  if($_GET['lieng']=='ajouter'){
      $lieng='ajouter';
  }elseif($_GET['lieng']=='supprimer'){
      $lieng='supprimer';
  }
}
}
if(!empty($_POST)){
if(!empty($_POST['name']) and !empty($_POST['categorie']) and !empty($_POST['price']) and !empty($_FILES['image']) ){
   try{
    $connect=new PDO("mysql:host=localhost;charset=utf8",'root');
    $connect->exec('CREATE DATABASE IF NOT EXISTS produits');
    $create=new PDO("mysql:host=localhost;dbname=produits;charset=utf8",'root',null,[
    PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION
]);
$table=$_POST['categorie'];

$create->exec("CREATE TABLE IF NOT EXISTS $table(
    id INTEGER AUTO_INCREMENT PRIMARY KEY ,
    `Nom de produit` VARCHAR(40) NOT NULL,
    `Prix de produit` FLOAT(20) NOT NULL, 
    `image de produit` VARCHAR(100) NOT NULL
)");
          $tableau=$_FILES['image'];
          $format=['png','jpeg','jpg'];
          $file=$tableau['tmp_name'];
          $name=$tableau['name'];
          $explode=explode('.',$name);
          $key=strtolower(end($explode));
          if(in_array($key,$format)){ 
  
              $destination='img_produits'.DIRECTORY_SEPARATOR.$name;
              move_uploaded_file($file,$destination);
              $resultat=$create->prepare("INSERT INTO $table(`Nom de produit`,`Prix de produit`,`Image de produit`)
                           VALUES(?,?,?)");
              $test=$resultat->execute([$_POST['name'],$_POST['price'],$destination]);
              if($test){
                   $successmessage="Le produit a été bien ajouter";}
            }else{
                  $errormessage="le format de l'image invalid";
              }
              
}catch(PDOException $e){
    die("error".$e->getMessage());
}
}else{
    $errormessage="Veuillez renseigner toute les champs";
}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>daschboard</title>
</head>
<body>
    <header class="header-bord">
        <div class="div-space-admin">Espace Administrateur</div>
        <div class="div-space-img-name">
            <div class="div-space-name">Bienvenue, <span><?=$admin_name['Nom']??''?></span></div>
        </div>
    </header>
<div class="inside-image">
<img src="../icones/boutika.PNG" alt="" class="boutika-admin">
<a href="logout.php" class="sedeconnecter" >Déconnexion</a>
</div>
<div class="admin-container">
<aside class="aside-admin">
    <a href="daschboard.php" class="aside-admin-acceuil">Accueil</a>
    <a href="#">Contact</a>
    <a href="daschboard.php?lien=produit">Produits</a>
    <a href="#">Utilisateurs</a>
    <a href="#">Articles</a>
    <a href="#">Outils</a>
</aside>
<main class="main-admin"> 
    <?php if($lien) :?>
        <h3>Gestion des produits</h3>
        <hr>
        <?php if(isset($lieng)) :?>
            <?php if($lieng=='ajouter'): ?>
                <?php $class="container-add" ?>
                    <h5>Ajouter des produits:</h5>
                     <?php if(isset($successmessage)) :?>
                        <div class="add-success">
                            <?=$successmessage ?>
                        </div>
                     <?php elseif(isset($errormessage)):?>
                        <div class="add-danger">
                            <?=$errormessage?>
                        </div>   
                     <?php endif ?>   
                    <div class="container-form">
                    <form action=""  enctype="multipart/form-data"  method="post" class="form-produit">
                        <div class="<?=$class ?>">
                            <div>
                                <label for="name">Nom de produit:</label>         
                                <input type="text" name="name" id="name" placeholder="Nom de produit" autofocus>  
                            </div>
                            <div>               
                                <label for="price">Prix de produit:</label>
                                <input type="text" name="price" id="price" placeholder="Prix de produit">
                            </div> 
                            <div class="last-input-add">                          
                                <label for="image">Image de produit:</label>
                                <input type="file" name="image" id="image" >
                            </div>      
                        </div>
                        <div  class="select-produit">
                            <label for="select">Selectionnez le catégorie de produit:</label>
                            <select name="categorie" id="select">
                                <option value="mobile">Téléphones & Tablettes        </option>                         
                                <option value="computer">Ordinateurs & Informatique  </option>  
                                <option value="home"> Electroménager & Maison        </option>     
                                <option value="game"> Jeux Vidéo & Consoles          </option>        
                                <option value="parfum"> Parfums & Coffrets           </option>           
                                <option value="bijou"> Accessoires & Bijoux          </option>         
                                <option value="clothe"> Vetements & Chaussures       </option>       
                                <option value="book"> Livres & Romans                </option> 
                            </select>
                        </div>
                        <button type="submit">Ajouter</button>
                    </form>
                    <h6 class="delete-product">
                          <a href="daschboard.php?lien=produit&lieng=supprimer" >Supprimer un produit</a>
                    </h6>
                </div>
           <?php elseif($lieng=='supprimer') :?>
                 <?php $class="container-delete" ?>
                  <h5 class="title-delete">Supprimer des produits:</h5>
                  <div class="container-form">
                      <?php $form="form-pro-delete" ?>
                  <form action="" method="post" class="form-produit <?=$form ?>">
                      <div class="container-add  <?=$class ?>">
                          <div>               
                              <label for="price">Mot-clé:</label>
                              <input type="decimal" name="keyd" id="price" placeholder="Mot-clé">
                          </div>  
                      </div>
                      <?php $select="select-produit-delete" ?>
                      <div  class="select-produit <?=$select?>">
                          <label for="select">Selectionnez le catégorie de produit:</label>
                          <select name="categoried" id="select">
                              <option value="mobile">Téléphones & Tablettes        </option>                         
                              <option value="computer">Ordinateurs & Informatique  </option>  
                              <option value="home"> Electroménager & Maison        </option>     
                              <option value="game"> Jeux Vidéo & Consoles          </option>        
                              <option value="parfum"> Parfums & Coffrets           </option>           
                              <option value="bijou"> Accessoires & Bijoux          </option>         
                              <option value="clothe"> Vetements & Chaussures       </option>       
                              <option value="book"> Livres & Romans                </option> 
                          </select>
                      </div>
                      <button type="submit">Supprimer</button>
                  </form>
                  <h6 class="add-product">
                  <a href="daschboard.php?lien=produit&lieng=ajouter" >Ajouter un nouveau produit</a>
                  </h6>
                </div>
            <?php endif ?>
        <?php else: ?>
        <div class="gestion">
              <a href="daschboard.php?lien=produit&lieng=ajouter">Ajouter un nouveau produit</a>
              <a href="daschboard.php?lien=produit&lieng=supprimer">Supprimer un produit</a>
         </div>
        <?php endif ?>
    <?php else :?>             
         <div class="panneau-admin">Bienvenue dans le Panneau d'Administration</div>
    <?php endif ?> 
</main>
</div>
</body>
</html>
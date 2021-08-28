<?php
include 'header.php';
$error=null;
$success=null;
try{
    $connect=new PDO("mysql:host=localhost;charset=utf8",'root',null,[
        PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
   //$connect->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    $connect->exec('CREATE DATABASE IF NOT EXISTS client');
    $cree=new PDO("mysql:host=localhost;dbname=client;charset=utf8",'root',null,[
        PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]);
    $cree->exec("CREATE TABLE IF NOT EXISTS Utilisateur(
         Nom VARCHAR(30) NOT NULL,
         Prénom VARCHAR(30) NOT NULL,
        `Mot de passe` VARCHAR(20) NOT NULL,
         Email VARCHAR(30) PRIMARY KEY)");
    if(!empty($_POST['nom']) and !empty($_POST['prenom']) and !empty($_POST['email']) and !empty($_POST['password']))
       {
               $resultat=$cree->prepare('SELECT * FROM Utilisateur');
               $resultat->execute();
               $utilisateur=$resultat->fetchAll();
               foreach($utilisateur as $tableau)
               {
                if(in_array($_POST['password'],$tableau) || in_array($_POST['email'],$tableau))
                {
                    if(in_array($_POST['email'],$tableau)){
                        $error="Cet email est déja utilisé";
                    }else{
                        $error="Cet mot de passe est déja utilisé";}
                }}
                if(!$error){
                $resultat=$cree->prepare("INSERT INTO utilisateur(Nom,Prénom,`Mot de passe`,Email) VALUES(?,?,?,?)");
                $resultat->execute([$_POST['nom'],$_POST['prenom'],$_POST['password'],$_POST['email']]);
                $success="Vous êtes bien inscrit";}
        }}catch(PDOException $e){
                  die('Error:'.$e->getMessage());
      }

?>

<?php if(isset($success)): ?>
    <div class="name-inscription">
        Bonjour <strong><?=htmlentities($_POST['nom'].' '.htmlentities($_POST['prenom']))?></strong>,
    </div>
    <div class="success">
        <?=$success?>
    </div>
    <div class="link-connexion">
        Vous pouvez maintenant connecter à <a href="login.php">Votre compte</a>
   </div>
<?php else :  ?>
    <?php if(isset($error)) :?>
    <div class="error">
        <?=$error ?>
    </div>
      </div>

      <form action="" method="post" class="form-inscrit">
      <fieldset>
      <legend class="legend-inscrit">S'inscrire</legend>
      <input  type="text" name="nom" placeholder="Nom" id="nom"  class="input-inscrit" > 
      <input  type="text" name="prenom" placeholder="Prénom" class="input-inscrit"> 
      <input  type="email" name="email" placeholder="E-mail" class="input-inscrit"> 
      <input  type="password" name="password" placeholder="Mot de passe" class="input-inscrit">
      <button type="submit" >Envoyer</button>
      </fieldset>
      </form>
      <?php else : ?>
        </div>
      <form action="" method="post" class="form-inscrit">
      <fieldset>
      <legend class="legend-inscrit">S'inscrire</legend>
      <input  type="text" name="nom" placeholder="Nom" id="nom" class="input-inscrit" autofocus required> 
      <input  type="text" name="prenom" placeholder="Prénom" class="input-inscrit" required> 
      <input  type="email" name="email" placeholder="E-mail" class="input-inscrit" required> 
      <input  type="password" name="password" placeholder="Mot de passe" class="input-inscrit" required>
      <button type="submit" >Envoyer</button>
      </fieldset>
      </form>
      <?php endif ?>
<?php endif ?>
<?php include 'footer.php' ?>

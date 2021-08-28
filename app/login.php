<?php
if(!empty($_POST)){
if(!empty($_POST['email']) and !empty($_POST['password'])){

     try{      
    $connect=new PDO("mysql:host=localhost;dbname=client;charset=utf8",'root',null,[
        PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC
    ]);

    $resultat= $connect->prepare("SELECT * FROM Utilisateur WHERE Email=:email and `Mot de passe`=:password");
    $resultat->execute([
                     'email'=>$_POST['email'],
                     'password'=>$_POST['password']
                       ]);
    $res=$resultat->fetchAll();
    if(!empty($res)){
    $table=$res[0];
    if($table['Mot de passe']==$_POST['password'] and $table['Email']==$_POST['email']){
        if(password_verify($table['Mot de passe'],'$2y$10$/4zFXLax35oNWVd4FPZgAeJCaoh9WU1xgth20LogxM4npwqh3yssC') && $table['Email']=='admin@gmail.com'){
               session_start();
               $_SESSION['admin']=1;
               header('Location:daschboard.php');
               exit();
        }else{
         session_start();
         $_SESSION['nom']=$table['Nom'];
         $_SESSION['prenom']=$table['Prénom'];
         header('Location:commande.php');
        }}
       }else{
        $error="Identifiant incorrect";
    }
    } catch(PDOException $e){
        die("error:".$e->getMessage());
    }          

}elseif(empty($_POST['email'])){
    $error="Veuillez saisir votre email et votre mot de passe";
}else{
    $error="Veuillez saisir votre mot de passe";
}
}
if(session_status()==PHP_SESSION_NONE){
    session_start();
if(!empty($_SESSION['nom']) and !empty($_SESSION['prenom'])){
    header("Location:commande.php");
    exit();
}}
include 'header.php';

?>
<img src="../icones/boutika.PNG" alt="" class="boutika">
<h3 class="title-inscription">
          Cette plateforme e-Commerce faite pour vous
      </h3>
      <div class="div-b_inscription">
          Boutika vous accompagne partout et gere tous les aspects du e-commerce.
      </div>
<h2 class="title-connexion">Connectez-vous à votre compte</h2>
<?php if(isset($error)) :?>
    <div class="error">
    <?=$error ?>        
    </div>
    <?php endif ?>    
<form action="" method="POST" class="form-connexion">
<fieldset>
<legend class="legend-connexion">Se connecter</legend>
<input type="email"  inputmode="text" name='email' placeholder="E-mail" autofocus class="input-connexion"> 
<input type="password" name='password' placeholder="Mot de passe" class="input-connexion">
<div><a href="#">Mot de passe oublié ?</a><a href="inscription.php">Créez un nouveau compte</a></div>
<button type="submit">Connexion</button>
</fieldset>
</form>
</div>
</div>
</body>
<?php include 'footer.php' ?>
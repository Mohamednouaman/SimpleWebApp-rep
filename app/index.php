<?php
session_start();
define('CATEGORIE',[
'Téléphones et Tablettes',
'Ordinateurs et Informatique',
'Electroménager et Maison',
'Jeux Vidéo et Consoles',
'Parfums et Coffrets',
'Accessoires et Bijoux',
'Vetements et Chaussures',
'Livres et Romans'
]);
define('PRODUITS',[
        'mobile',
        'computer',
        'home',
        'game',
        'parfum',
        'bijou',
        'clothe',
        'book'
]);
$bool=null;
$b=true;
$table_key=[0,1,2,3,4,5,6,7];
require_once 'functions.php';
if(!empty($_GET['liste']) and $_GET['liste']=='active'){
if(isset($_GET) and key_exists('id',$_GET)){
     if(verify($table_key,$_GET['id']) ){
      $id=$_GET['id'];
}
}
if(isset($id)){
try{
    $connect=new PDO("mysql:host=localhost;dbname=produits;charset=utf8",'root',null,
    [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]);
    $name_table_product=function_group(PRODUITS,$id);
    $resultat=$connect->prepare("SELECT * FROM $name_table_product");
    $resultat->execute();
    $produits=$resultat->fetchAll();
    $number_product=count($produits);

}catch(PDOException $e){
    $b=false;
    //die("error:".$e->getMessage());
}
if(!empty($_GET['key']) and !empty($_GET['nt']) ){
    foreach($produits as $value_product){
        if($name_table_product==$_GET['nt'] and $value_product['id']==$_GET['key']){
                            $_SESSION[$_GET['nt']][$_GET['key']]=$_GET['key'];
                            $res=$connect->prepare("SELECT * FROM $name_table_product WHERE id=:id");
                            $res->execute(['id'=>$_GET['key']]);
                            $prod=$res->fetchAll();
                            $table_panier=$prod[0];
                            $key_aide=$_GET['key'].$_GET['nt'];
                            if(empty($_SESSION['panier'])){
                                $_SESSION['panier']=0; 
                            }                           
                            if(!in_array($key_aide,$_SESSION)){
                                $_SESSION[$key_aide]=$key_aide;
                                $_SESSION['panier']+=$table_panier['Prix de produit'];
                            }
        }}
}}}
if(!empty($_GET['liste']) and $_GET['liste']=='active'){
   $var=1;
}

include 'header.php';

?>

<img src="../icones/boutika.PNG" alt="" class="boutika">
<a href="achat.php" class="lien_panier">
<div class="panier">
    <div>
    <span>Mon panier</span>
    <span><?= !empty($_SESSION['panier'])? $_SESSION['panier'] :'0.00'?> MAD</span>
    </div>      
</div>
</a>
<div class="container">
<div class="sidebar">
<a href="index.php?liste=active" class="lien-div-list">
<div class="div-list">TOUTES LES CATÉGORIES</div>
</a>
<?php if(isset($var)) :?>
    <div class="liste">
       <a href="index.php?liste=active&id=0" class="mobile <?=(isset($id) and $id==0)?'lien_active':'' ?>">  <div >Téléphones & Tablettes      </div><div  class="classe">></div></a>
       <a href="index.php?liste=active&id=1" class="computer <?=(isset($id) and $id==1)?'lien_active':'' ?>"><div >Ordinateurs & Informatique  </div><div  class="classe">></div></a>
       <a href="index.php?liste=active&id=2" class="home <?=(isset($id) and $id==2)?'lien_active':'' ?>">    <div >Electroménager & Maison     </div><div  class="classe">></div></a>
       <a href="index.php?liste=active&id=3" class="game <?=(isset($id) and $id==3)?'lien_active':'' ?>">    <div >Jeux Vidéo & Consoles       </div><div  class="classe">></div></a>
       <a href="index.php?liste=active&id=4" class="parfum <?=(isset($id) and $id==4)?'lien_active':'' ?>">  <div >Parfums & Coffrets          </div><div  class="classe">></div></a>
       <a href="index.php?liste=active&id=5" class="bijou <?=(isset($id) and $id==5)?'lien_active':'' ?>">   <div >Accessoires & Bijoux        </div><div  class="classe">></div></a>
       <a href="index.php?liste=active&id=6" class="clothe <?=(isset($id) and $id==6)?'lien_active':'' ?>">  <div >Vetements & Chaussures      </div><div  class="classe">></div></a>
       <a href="index.php?liste=active&id=7" class="book <?=(isset($id) and $id==7)?'lien_active':'' ?>">  <div >Livres & Romans</div><div  class="classe">></div></a>
    </div>
<?php else :?>
    <div class="image-pub">
        <img src="../images/nouveaute.jpg" alt="">
    </div>
<?php endif ?>
</div>
<?php if(isset($id) && $b):?>
    <div class="product-info">
    <h3 class="div-title-categorie">
        <?=function_group(CATEGORIE,$id) ?>
    </h3>
    <div class="number_product">
        <div>
        il ya <strong><?=$number_product ?></strong> produit<?=($number_product>=2)?'s':''?> 
        </div>
        <form action="" method="GET">
            <label for="select">Trier par:</label>
            <select name="tier" id="select">
                <option value="price">De plus cher au moins cher</option>
                <option value="qualite">De moins cher au plus cher</option>
            </select>
        </form>
        </div>
    <div class="div-container_product">
        <?php foreach($produits as $produit) :?>
        <div>            
            <img src="../<?=htmlentities($produit['image de produit'])?>" class="image_product" alt="">
            <div class="name_product"> <?=htmlentities($produit['Nom de produit'])?></div>
            <div class="price_product"><?=htmlentities($produit['Prix de produit'])?> MAD</div>
            <a href="index.php?liste=active&id=<?=$id?>&nt=<?=$name_table_product ?>&key=<?=$produit['id']?>" class="lien_container">
                <img src="../<?=htmlentities($produit['image de produit'])?>"  alt="">
                <div class="ajouter_au_panier">Ajouter au panier</div>
            </a>
        </div>
        <?php endforeach ?>
    </div>
    </div>
 <?php else :?>   
<main class="img-main">
    <a href="#"><img src="../images/main.png" class="big-img"></a>
</main>
<aside class="aside">
<a href="#"><img src="../images/aside1.jpg" alt="" class="img-aside"></a>
<a href="#"><img src="../images/aside2.jpg" alt="" class="img-aside"></a>
</aside>
<?php endif ?>
</div>
<?php include 'footer.php' ?>

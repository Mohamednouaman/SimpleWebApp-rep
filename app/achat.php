<?php
session_start();
$prix_total=0;
$number_article=0;
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
    try{

        $connect=new PDO('mysql:host=localhost;dbname=produits;charset=utf8','root',null,
        [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,       
        PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC
        ]);
        }catch(PDOException $e){
        die('Error:'.$e->getMessage());
    }     
 if(empty($_SESSION['panier'])){
    $error_panier="il n'y a plus d'articles dans votre panier";
}


include 'header.php' ?>

<img src="../icones/boutika.PNG" alt="" class="boutika"> 
<?php if(isset($error_panier)):?>
    <div class="head-t_panier">PANIER</div>
    <div class="error_panier"><?=$error_panier?></div>
    <a href="index.php?liste=active&id=<?=rand(0,7)?>" class="commancer_achat"><span><<</span>Commancer mes achats </a>
    <div class="sidebar_commande sidebar_panier-vide">
        <div>
           <div><?=$number_article?> article<?=($number_article>=2)?'s':''?></div>
           <div><?=$prix_total ?> MAD</div>
        </div>
        <div>
            <div>Livraison</div>
            <div>00,0 MAD</div>
        </div>
        <div>
          <div>total TCC :</div>
          <div><strong><?=$prix_total?> MAD</strong></div>
        </div>
        <a href="commande.php">Commander</a>   
    </div>
<?php else :?>    
<div class="main_panier">
   <div class="panier_container">
       <div class="head_panier">PANIER</div>   
       <form action="" method="post" class="form_panier">
       <table class="table_panier">
        <?php foreach($_SESSION as $key=>$value) :?>
            <?php if(!in_array($key,PRODUITS)) :?>
                <?php continue ?>
            <?php endif ?>    
            <?php foreach($value as $value_id) :?>
              <?php $cle_post=$key.$value_id; ?>
               <?php $resultat=$connect->prepare("SELECT * FROM  $key WHERE  id=:id"); ?>
                  <?php $resultat->execute(['id'=>$value_id]); ?>   
                    <?php $panier_produit=$resultat->fetchAll();?> 
                            <?php foreach($panier_produit as $panier_produit_value): ?>
                                <tr>
                                    <td class="first_cellule">
                                    <div class="div-image_panier">
                                       <img class="img_panier" src="../<?=htmlentities($panier_produit_value['image de produit']) ?>" alt="">
                                    </div>
                                    </td>
                                    <?php $key_cammande=$panier_produit_value['Nom de produit'].$value_id; ?>
                                    <?php $_SESSION[$key_cammande]=( !empty($_POST[$cle_post]) and $_POST[$cle_post]>0 )? $_POST[$cle_post]:1 ?>   
                   
                                    <td class="second_cellule"> 
                                       <div><?=htmlentities($panier_produit_value['Nom de produit'])?></div>
                                        <div><?= (!empty($_POST[$cle_post]) and $_POST[$cle_post]>0 )?htmlentities($panier_produit_value['Prix de produit']).'*'.$_POST[$cle_post]:htmlentities($panier_produit_value['Prix de produit'])?> MAD</div>
                                        <?php $prix_panier=(!empty($_POST[$cle_post]) and $_POST[$cle_post]>0 )?$panier_produit_value['Prix de produit']*$_POST[$cle_post]:$panier_produit_value['Prix de produit']  ?>                                        
                                    </td>
                                    <td class="third_cellule">
                                           <label for="number">Nombre de produit volu :</label>
                                           <input type="number" name="<?=$cle_post?>" id="number" placeholder="Tapez le nombre de produit" class="input_panier" value="<?=$_POST[$cle_post]??''?>">
                                                          
                                    </td>                                
                                </tr>
                                <?php $prix_total+=$prix_panier?>
                                <?php if($prix_total):?>
                                    <?php $_SESSION['panier']=$prix_total ?>
                                <?php endif ?>   
                            <?php endforeach ?>    
            <?php endforeach ?>
            <?php $number_article +=count($value)  ?>                  
        <?php endforeach ?>  
        <tr class="last_cellule">
            <td></td>
            <td></td>
             <td ><button type="submit" class="button_panier" >Valider</button></td>      
        </tr>
        </table>
        </form>   
             <a href="index.php?liste=active&id=<?=rand(0,7)?>" class="contunier_achat"><span><<</span>Contunier mes achats</a>  
    </div>                     
    <div class="sidebar_commande">
        <div>
           <div><?=$number_article?> article<?=($number_article>=2)?'s':''?></div>
           <div><?=$prix_total ?> MAD</div>
        </div>
        <div>
            <div>Livraison</div>
            <div>00,0 MAD</div>
        </div>
        <div>
          <div>total TCC :</div>
          <div><strong><?=$prix_total?> MAD</strong></div>
        </div>
        <a href="commande.php">Commander</a>   
    </div>
</div>
<?php endif ?>





<?php include 'footer.php' ?>
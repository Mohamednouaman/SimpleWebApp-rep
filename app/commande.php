<?php
session_start();
if(empty($_SESSION['nom']) and empty($_SESSION['prenom'])){
    header("Location:login.php");
    exit();
}
try{
$connect=new PDO('mysql:host=localhost;dbname=produits;charset=utf8','root',null,
[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,       
PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC
]);
}catch(PDOException $e){
die('Error:'.$e->getMessage());}
$commander=1;	
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

if(isset($_POST['payer'])){
  $seccuss="Votre opération a été bien effectueé";}
 if(!empty($_POST['facture'])){

require_once('tcpdf/tcpdf.php');
//============================================================+
// File name   : example_002.php
// Begin       : 2008-03-04
// Last Update : 2013-05-14
//
// Description : Example 002 for TCPDF class
//               Removing Header and Footer
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: Removing Header and Footer
 * @author Nicola Asuni
 * @since 2008-03-04    
 */

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle('Facture Client');
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

$pdf->SetFont('times','','12','','',true);
$pdf->AddPage(); 
$var_date=date('d/m/Y');
$first_name=$_SESSION['nom'];
$last_name=$_SESSION['prenom']; 
$html=<<<HTML
     <style>
      .facture{
        font-weight:bold;
        font-size:16px;
        color:grey;
      }
      .name{
        margin-buttom:20px;
      }
     </style>
     <div class="facture">Facture</div>    
     <div>Date : $var_date</div> 
     <div class="name">Nom et Prénom du client: <strong>$first_name $last_name</strong></div>
     <div></div>

HTML;
$pdf->writeHTML($html);
$pdf->setFont('','B',14);

$pdf->Cell(80,10,'Nom de produit',1,0,'C');
$pdf->Cell(40,10,'Quantité',1,0,'C');
$pdf->Cell(40,10,'Prix de produit',1,1,'C');
$pdf->setFont('','',14);

 foreach($_SESSION as $key=>$value) {
   if(!in_array($key,PRODUITS)) {
       continue ;
   }
   foreach($value as $value_id) {
      $resultat=$connect->prepare("SELECT * FROM  $key WHERE  id=:id");
          $resultat->execute(['id'=>$value_id]);   
           $panier_produit=$resultat->fetchAll();
                   foreach($panier_produit as $panier_produit_value){     
                           $key_cammande=$panier_produit_value['Nom de produit'].$value_id;
                           $number_article +=$_SESSION[$key_cammande];
                           $pdf->Cell(80,10,$panier_produit_value['Nom de produit'],1,0,'C');
                           $pdf->Cell(40,10,$_SESSION[$key_cammande],1,0,'C');      
                           $pdf->Cell(40,10,$panier_produit_value['Prix de produit']*$_SESSION[$key_cammande],1,1,'C');
                           
                          }                                                   
  
                    }                              
    }                     $livraison=0.25*$number_article;
                          $total_ttc=$_SESSION['panier']+$livraison; 
                          $pdf->Cell(120,10,'Participation Forfaitaire aux Frais de traitement',1,0,'C');
                          $pdf->Cell(40,10,$livraison,1,1,'C');
                          $pdf->Cell(120,10,'Total TTC',1,0,'C');
                          $pdf->Cell(40,10,$total_ttc,1,1,'C');
$pdf->Output('facture.pdf');
  }
include 'header.php' ;
?>
<img src="../icones/boutika.PNG" alt="" class="boutika boutika-commande"> 
<a class="deconnexion_client" href="logout.php">Se déconnecter</a>
<?php if(isset($seccuss)):?>
  <div class="success success_commande">
    <?=$seccuss?>
  </div>    
  <form action="" method="post" class="form_facture">
    <div class="div_form_facture">
            <button type="submit" name="facture" value="telecharger"><span>Telecharger Votre Facture</span></button>
    </div> 
  </form>      
<?php else: ?>  
<?php if(empty($_SESSION['panier'])):?>  
 <div class="vide-product">Aucun produit commandé</div> 
 <hr class="hr-commande"> 
 <div class="vide-product-add">- Ajoutez d'abord a votre<a href="index.php?liste=active&id=<?=rand(0,7)?>"> Panier</a></div>
 <?php else :?>   
<h3 class="commander_product">
  Produits Commandés :
</h3>
<div class="container_tpanier_paiment">
<table class="table_panier table_panier-commande">
        <?php foreach($_SESSION as $key=>$value) :?>
            <?php if(!in_array($key,PRODUITS)) :?>
                <?php continue ?>
            <?php endif ?>    
            <?php foreach($value as $value_id) :?>
               <?php $resultat=$connect->prepare("SELECT * FROM  $key WHERE  id=:id"); ?>
                  <?php $resultat->execute(['id'=>$value_id]); ?>   
                    <?php $panier_produit=$resultat->fetchAll();?> 
                            <?php foreach($panier_produit as $panier_produit_value): ?>      
                                  <tr>
                                    <td class="first_cellule first_cellule-commande">
                                    <div class="div-image_panier">
                                       <img class="img_panier" src="../<?=htmlentities($panier_produit_value['image de produit']) ?>" alt="">
                                    </div>
                                    </td> 
                                    <td class="second_cellule second_cellule-commande">
                                       <div><?=htmlentities($panier_produit_value['Nom de produit'])?></div>
                                        <div><?=htmlentities($panier_produit_value['Prix de produit'])?> MAD</div>                                                                               
                                    </td>    
                                    <td class="third_cellule third_cellule_commande">
                                    <?php $key_cammande=$panier_produit_value['Nom de produit'].$value_id ?>
                                      Quantité : <?=$_SESSION[$key_cammande]  ?>
                                    </td>     
                                    <?php $number_article +=$_SESSION[$key_cammande]  ?>                  
                                </tr>
                            <?php endforeach ?>    
            <?php endforeach ?>                              
        <?php endforeach ?>  
  </table>
  <?php $livraison=0.25*$number_article ?>
  <?php $m_total= $_SESSION['panier']+$livraison ?>
<div class="container_paiment">
  <div  class="sidbar_commande">
  <div class="container_article_participation">
    <div  class="sidbar_commande-article">
      <div>Montant total des articles</div>
      <div><?= $_SESSION['panier']?> MAD</div>
    </div>
    <div  class="sidbar_commande-participation">
      <div>Participation Forfaitaire aux Frais de traitement</div>
      <div><?=$livraison?> MAD</div>
    </div>  
  </div>
    <div  class="sidbar_commande-total">
        <div>Montant total de votre commande</div>
        <div><?=$m_total?> MAD</div>
     </div>
  </div>
    <div class="div_form_commande">
      <form action="" method="post">
        <div class="select_form-commande">
        <label for="mode">Choisir le mode de paiment:</label>
        <select name="payer" id="mode">
          <option value="paypal">Paypal</option>
          <option value="cartevisa">Carte visa</option>
        </select>
        </div>
        <div class="div-commande">
        <button type="submit">Payer</button>
        </div>
      </form>
    </div>
  </div>
</div>
</div>
<?php endif ?>
<?php endif ?>

<?php include 'footer.php' ?>

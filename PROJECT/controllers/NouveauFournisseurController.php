<?php
use PROJECT\models\FournisseursModel;
use PROJECT\models\ProduitModel;

require_once __DIR__ . '/../models/FournisseursModel.php';
require_once __DIR__ . '/../models/ProduitModel.php';

$modelFournisseur = new FournisseursModel();
$modelProduit = new ProduitModel();


$nom=$_POST["nom"];
$contact=$_POST["contact"];

$idFournisseur = $modelFournisseur->nouveauFournisseur($nom,$contact);

$idProduitsVendus = $_POST["idProduitsVendus"];
$listId=explode(",",$idProduitsVendus);
foreach($listId as $id) {
    if($modelProduit->isProduit($id)) {
       $modelFournisseur->addProduitVendu($idFournisseur,$id);
    }
}

header("Location: ../views/BaseDeDonneesVue.php?user=".$_SESSION["user"]);
?>
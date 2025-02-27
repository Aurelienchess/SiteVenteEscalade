<?php
use PROJECT\models\ProduitModel;
use PROJECT\models\ArticlesDisposModel;

require_once __DIR__ . '/../models/ProduitModel.php';
require_once __DIR__ . '/../models/ArticlesDisposModel.php';

$modelProduit = new ProduitModel();
$articlesDisposModel = new ArticlesDisposModel();

$titre=$_POST["titre"];
$reference=$_POST["reference"];
$prixPublic=$_POST["prixPublic"];
$prixAchat=$_POST["prixAchat"];
$prixHTC=$_POST["prixHTC"];
$descriptif=$_POST["descriptif"];
$image=$_POST["image"];
$categorie=$_POST["categorie"];

$modelProduit->nouveauProduit($titre,$reference,$prixPublic,$prixAchat,$prixHTC,$descriptif,$image,$categorie);
$articlesDisposModel->setListArticles();
$articlesDisposModel->changePage(1);

header("Location: ../views/BaseDeDonneesVue.php?user=".$_SESSION["user"]);
?>
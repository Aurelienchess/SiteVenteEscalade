<?php
use PROJECT\models\ProduitModel;
use PROJECT\models\PanierModel;
use PROJECT\models\ArticlesDisposModel;


require_once __DIR__ . '/../models/ProduitModel.php';
require_once __DIR__ . '/../models/PanierModel.php';
require_once __DIR__ . '/../models/ArticlesDisposModel.php';

$modelProduit = new ProduitModel();
$modelPanier = new PanierModel();
$articlesDisposModel = new ArticlesDisposModel();

$idProduit = $_POST["idProduit"];

$test = $modelProduit->restaurerProduit($idProduit);
$articlesDisposModel->setListArticles();
$articlesDisposModel->changePage(1);

header("Location: ../views/BaseDeDonneesVue.php?user=".$_SESSION["user"]);
?>


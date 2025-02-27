<?php
namespace PROJECT\controllers;
use PROJECT\models\GestionStockModel;
use PROJECT\models\PanierModel;


require_once __DIR__ . '/../models/GestionStockModel.php';
require_once __DIR__ . '/../models/PanierModel.php';



$modelStock = new GestionStockModel();
$modelPanier = new PanierModel();

$id = $_POST["idProduit"];
$quantite = $modelPanier->getQuantiteProduit($id);

$modelStock->miseAJour($id, -$quantite);
$modelPanier->supprimerProduit($id);

header("Location: ../views/PanierVue.php?user=".$_SESSION["user"]);?>
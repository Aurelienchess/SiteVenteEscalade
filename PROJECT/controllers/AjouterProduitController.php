<?php

namespace PROJECT\controllers;
use PROJECT\models\GestionStockModel;
use PROJECT\models\PanierModel;


require_once __DIR__ . '/../models/GestionStockModel.php';
require_once __DIR__ . '/../models/PanierModel.php';


$modelStock = new GestionStockModel();
$modelPanier = new PanierModel();

$produit = $_POST["idProduit"];
$modelStock->miseAJour($produit);

$modelPanier->ajouterProduit($produit, 1);

header("Location: ../views/AccueilVue.php?user=".$_SESSION["user"]."page=1");
?>
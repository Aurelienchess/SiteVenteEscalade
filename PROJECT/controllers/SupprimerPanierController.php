<?php
namespace PROJECT\controllers;
use PROJECT\models\GestionStockModel;
use PROJECT\models\PanierModel;


require_once __DIR__ . '/../models/GestionStockModel.php';
require_once __DIR__ . '/../models/PanierModel.php';


$modelStock = new GestionStockModel();
$modelPanier = new PanierModel();

$panierASupprimer = $modelPanier->obtenirPanier();
foreach($panierASupprimer as $key=>$quantite) {
    $modelStock->miseAJour($key, -$quantite);
}

$modelPanier->viderPanier();

header("Location: ../views/PanierVue.php?user=".$_SESSION["user"]);?>
<?php

namespace PROJECT\controllers;
use PROJECT\models\GestionStockModel;
use PROJECT\models\PanierModel;


require_once __DIR__ . '/../models/GestionStockModel.php';
require_once __DIR__ . '/../models/PanierModel.php';

$modelStock = new GestionStockModel();
$modelPanier = new PanierModel();

$id = $_POST["idProduit"];
$action = $_POST["action"];
$quantite = $modelPanier->getQuantiteProduit($id);

if($action === 'ajouter') {
    $modelStock->miseAJour($id, 1);
    $modelPanier->modifierQuantite($id, $quantite+1);
}

if($action === 'enlever') {
    $modelStock->miseAJour($id, -1);
    $modelPanier->modifierQuantite($id, $quantite-1);
}

header("Location: ../views/PanierVue.php?user=".$_SESSION["user"]);

?>
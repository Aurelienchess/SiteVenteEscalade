<?php
use PROJECT\models\Model;
require_once __DIR__ . '/models/Model.php';
$model = new Model();
session_start();

$_SESSION["pagination"] = 6;
$allProduits = $model->getAll("Produit","*",[],null, null, "ASC",$_SESSION["pagination"],0);

$_SESSION['listProduits'] = $allProduits;

header("Location: views/AccueilVue.php?page=1");

?>
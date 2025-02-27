<?php

namespace PROJECT\controllers;
use PROJECT\models\Model;
use PROJECT\models\ArticlesDisposModel;
require_once __DIR__ . '/../models/Model.php';
require_once __DIR__ . '/../models/ArticlesDisposModel.php';
$model = new Model();
$modelArticlesDispos = new ArticlesDisposModel();

if($_POST["categorie"] == "toutes") {
    $categorie = [];
}
else {
    $categorie = ["categorie"=>$_POST["categorie"]];
}
$recherche = $_POST["recherche"];
$ordre = explode(" ",$_POST["ordre"]);

$produits = $model->getAll(
        'Produit',  
        "*",
        $categorie,
        $recherche,
        $ordre[0],
        $ordre[1]);

$test1 = $modelArticlesDispos->setListArticles($produits);
$modelArticlesDispos->changePage(1);

$test = $test1[0]["titre"];

if (!empty($_SESSION['user'])) {
    header("Location: ../views/AccueilVue.php?user=".$_SESSION["user"]."&page=1&test:$test");
}
else {
    header("Location: ../views/AccueilVue.php?&page=1");
}
?>
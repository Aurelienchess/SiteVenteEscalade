<?php

use PROJECT\models\ArticlesDisposModel;
require_once __DIR__ . '/../models/ArticlesDisposModel.php';
$modelArticlesDispos = new ArticlesDisposModel();

$page = $_GET["page"];
$modelArticlesDispos->changePage($page);

if (!empty($_SESSION['user'])) {
    header("Location: ../views/AccueilVue.php?user=".$_SESSION["user"]."&page=".$page);
}
else {
    header("Location: ../views/AccueilVue.php?&page=".$page);
}
?>
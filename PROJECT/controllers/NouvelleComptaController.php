<?php
use PROJECT\models\Model;
use PROJECT\models\ComptaModel;
require_once __DIR__ . '/../models/ComptaModel.php';
require_once __DIR__ . '/../models/Model.php';
$model = new Model();
$comptaModel = new ComptaModel();

$date = date("Y-m-d");

$idCompta = $comptaModel->getLastCompta();
$infosCompta = $comptaModel->getComptaById($idCompta);
$resultat = $infosCompta["resultat"];

$model->insert("Compta", ["date_creation"=>$date, "resultat"=>$resultat]);

header("Location: ../views/ComptaVue.php?user=".$_SESSION["user"]."test".$resultat);
?>


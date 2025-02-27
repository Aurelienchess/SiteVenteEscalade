<?php
use PROJECT\models\ComptaModel;


require_once __DIR__ . '/../models/ComptaModel.php';

$comptaModel = new ComptaModel();

$idAchat = $_POST["idAchat"];
$comptaModel->restaurerAchat($idAchat);

header("Location: ../views/BaseDeDonneesVue.php?user=".$_SESSION["user"]);

?>

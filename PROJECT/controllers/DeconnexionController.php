<?php
use PROJECT\models\PanierModel;
use PROJECT\models\ClientModel;

require_once __DIR__ . '/../models/ClientModel.php';
require_once __DIR__ . '/../models/PanierModel.php';

$ClientModel = new ClientModel();
$ClientModel->setId("");

$PanierModel = new PanierModel();
$PanierModel->viderPanier();

header("Location: ../views/ConnexionVue.php");
?>
<?php
use PROJECT\models\FournisseursModel;


require_once __DIR__ . '/../models/FournisseursModel.php';

$fournisseurModel = new FournisseursModel();

$idFournisseur = $_POST["idFournisseur"];
$fournisseurModel->restaurerFournisseur($idFournisseur);

header("Location: ../views/BaseDeDonneesVue.php?user=".$_SESSION["user"]);

?>
<?php
namespace PROJECT\controllers;
use PROJECT\models\ClientModel;
use PROJECT\models\Model;

require_once __DIR__ . '/../models/ClientModel.php';
require_once __DIR__ . '/../models/Model.php';


$ClientModel = new ClientModel();
$model = new Model();

if (!isset($_POST["email"]) || !isset($_POST["mdp"])) {
    header("Location: ../views/ConnexionVue.php?error=missing_parameters");
    exit();
}

$email = $_POST["email"];
$mdp = $_POST["mdp"];

$tentativeConnexion = $ClientModel->verifierConnexion($email, $mdp);

if (!$tentativeConnexion) {
    header("Location: ../views/ConnexionVue.php?error=invalid_credentials");
    exit();
} else {
    if (isset($_POST['rememberMe'])) {
        $model->setUserCookie($email, $mdp);
    }
    
    $ClientModel->setId($tentativeConnexion);
    
    header("Location: ../views/AccueilVue.php?user=".$ClientModel->getId($email)."&page=1");
    exit();
}
?>


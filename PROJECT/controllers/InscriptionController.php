<?php
namespace PROJECT\controllers;
use PROJECT\models\ClientModel;

require_once __DIR__ . '/../models/ClientModel.php';

$modelClient = new ClientModel();
$emailRegex = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";

if (!isset($_POST["email"]) || !isset($_POST["mdp"]) || !isset($_POST["adresse"])) {
    header("Location: ../views/InscriptionVue.php?error=missing_parameters");
    exit();
}

$email = $_POST["email"];
$mdp = $_POST["mdp"];
$adresse = $_POST["adresse"];
$nom = $_POST["nom"];
$prenom = $_POST["prenom"];

if (isset($_POST["admin"])) {
    $admin = true;
} else {
    $admin = false;
}


if (!$modelClient->verifierUnicité($email) || !preg_match($emailRegex, $_POST["email"])) {
    //On renvoit à notre page de connexion, avec un message d'erreur
    header("Location: ../views/InscriptionVue.php?error=invalid_email");
    exit();
} else {
    $modelClient->setId($modelClient->nouveauClient($email, $mdp, $nom, $prenom, $adresse, $admin));
    
    header("Location: ../views/AccueilVue.php?user=".$_SESSION["user"]."&page=1&admin".$admin);
    exit();
}
?>
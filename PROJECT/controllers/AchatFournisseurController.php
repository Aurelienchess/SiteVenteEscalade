<?php

namespace PROJECT\controllers;
use PHPMailer\PHPMailer\PHPMailer;
use PROJECT\models\GestionStockModel;
use PROJECT\models\ComptaModel;
use PROJECT\models\AchatModel;
use PROJECT\models\ProduitModel;
use PROJECT\models\ClientModel;
use PROJECT\models\FournisseursModel;
use PROJECT\views\MailVue;

require_once __DIR__ . '/../library/PHPMailer.php';
require_once __DIR__ . '/../library/SMTP.php';
require_once __DIR__ . '/../library/Exception.php';
require_once __DIR__ . '/../models/GestionStockModel.php';
require_once __DIR__ . '/../models/ComptaModel.php';
require_once __DIR__ . '/../models/AchatModel.php';
require_once __DIR__ . '/../models/ProduitModel.php';
require_once __DIR__ . '/../models/ClientModel.php';
require_once __DIR__ . '/../models/FournisseursModel.php';
require_once __DIR__ . '/../views/MailVue.php';


$modelStock = new GestionStockModel();
$modelCompta = new ComptaModel();
$modelAchat = new AchatModel();
$modelProduit = new ProduitModel();
$modelClient = new ClientModel();
$mailVue = new MailVue();

$id_produit = intval($_POST["idProduit"]);

$quantiteAchetee = intval($_POST["quantite"]);
$id_fournisseur = intval($_POST["idFournisseur"]);

$modelStock->miseAJour($id_produit,-$quantiteAchetee);

$id_compta = $modelCompta->getLastCompta();

$infosProduit = $modelProduit->getContenuById($id_produit);
$montant = $infosProduit["prix_achat"]*$quantiteAchetee;

$date = date("Y-m-d");
$modelAchat->nouvelAchat($id_compta, $id_produit,$id_fournisseur,$quantiteAchetee,$montant, $date);

$modelCompta->calculerNouveauMontant($id_compta);


$listAdmin = $modelClient->getAdmin();
$article = $infosProduit["titre"];
$prixArticle = $infosProduit["prix_achat"];

$idUser=$_SESSION["user"];
$infosUser = $modelClient->getById($idUser);
$nomUser = $infosUser["nom"];
$prenomUser = $infosUser["prenom"];

$modelFournisseur = new FournisseursModel();
$infosFournisseur = $modelFournisseur->getFournisseurById($id_fournisseur);
$nomFournisseur = $infosFournisseur["nom"];

foreach ($listAdmin as $admin) {
    $mailAdmin = setMail();

    $mailAdmin->addAddress($admin["email"], $admin["nom"]);

    $mailAdmin->Subject = 'Demande de réapprovisionnement - Zéro Gravité';

    $mailAdmin->isHTML(true);

    $mailAdmin->Body = $mailVue->getEmailReapro($nomFournisseur, $date, $article, $prixArticle, $quantiteAchetee, $montant, $nomUser,$prenomUser);

    // Options SSL
    $mailAdmin->SMTPOptions = [
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true,
        ],
    ];

    // Envoyer l'email à l'admin
    $mailAdmin->send();

}

function setMail() {
    $mail = new PHPMailer(true);

    //Utilisation d'une adresse email secondaire pour ça
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';  // Serveur SMTP de Gmail
    $mail->SMTPAuth = true;
    $mail->Username = 'leandre.moret10alt@gmail.com';  
    $mail->Password = 'eyeu dsnl izpl dghb ';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  // Sécurisation TLS
    $mail->Port = 587;  // Port SMTP pour Gmail

    $mail->setFrom('leandre.moret10alt@gmail.com', 'Chez Coutton & Moret');
    return $mail;
}

header("Location: ../views/FournisseursVue.php?user=".$_SESSION["user"]);

?>
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PROJECT\models\Model;
use PROJECT\models\FacturationModel;
use PROJECT\models\ClientModel;
use PROJECT\models\ProduitModel;
use PROJECT\models\GestionStockModel;
use PROJECT\views\MailVue;
use PROJECT\models\PanierModel;
use PROJECT\models\ComptaModel;


require_once __DIR__ . '/../library/PHPMailer.php';
require_once __DIR__ . '/../library/SMTP.php';
require_once __DIR__ . '/../library/Exception.php';
require_once __DIR__ . '/../models/PanierModel.php';
require_once __DIR__ . '/../models/ClientModel.php';
require_once __DIR__ . '/../models/Model.php';
require_once __DIR__ . '/../models/ProduitModel.php';
require_once __DIR__ . '/../models/FacturationModel.php';
require_once __DIR__ . '/../views/MailVue.php';
require_once __DIR__ . '/../models/GestionStockModel.php';
require_once __DIR__ . '/../models/ComptaModel.php';




#------Mise à jour des tables--------#
$date = date("Y-m-d");
$modelPanier = new PanierModel();
$modelFacturation = new FacturationModel();
$modelProduit = new ProduitModel();
$model = new Model();
$modelCompta = new ComptaModel();
$idCompta = $modelCompta->getLastCompta();

$prixTotal = $modelPanier->calculerTotal();

$idFacturation = $modelFacturation->nouvelleFacture($_SESSION["user"], $date, $prixTotal, $idCompta);

$panierValide = $modelPanier->obtenirPanier();


foreach($panierValide as $idProduit => $quantite) {
    $produitInfos = $modelProduit->getContenuById($idProduit);
    $idProduit = $produitInfos["id"];
    $prix= $produitInfos["prix_public"];
    
    $model->insert("Panier", ["id_produit"=>$idProduit,"id_facturation"=>$idFacturation,"quantite"=>$quantite,"prix"=>$prix]);
}

$modelCompta->calculerNouveauChiffreDaffaire($idCompta);


#------Envoi des mails--------#

$mailUser = setMail();

$modelClient = new ClientModel();
$infoClient=$modelClient->getById($_SESSION["user"]);
$nomClient = $infoClient["nom"];
$prenomClient = $infoClient["prenom"];
$emailClient = $infoClient["email"];
$adresseClient = $infoClient["adresse"];

$mailUser->setFrom('leandre.moret10alt@gmail.com', 'Zéro Gravité');


#On envoi le mail au client
$mailUser->addAddress($emailClient, $nomClient); // Adresse du client

$mailUser->Subject = 'Confirmation de commande - Zéro Gravité';

$mailUser->isHTML(true);

$mailVue = new MailVue();

$mailUser->Body = $mailVue->getEmailClient($panierValide, $idFacturation, $date, $nomClient, $prenomClient, $adresseClient, $prixTotal);

// Envoyer l'email

$mailUser->SMTPOptions = [
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true,
    ],
];


$mailUser->send();


$listAdmin = $modelClient->getAdmin();

foreach ($listAdmin as $admin) {
    $mailAdmin = setMail();

    $mailAdmin->addAddress($admin["email"], $admin["nom"]);

    $mailAdmin->Subject = 'Nouvelle commande recue - Zéro Gravité';

    $mailAdmin->isHTML(true);

    $mailAdmin->Body = $mailVue->getMailAdmin($panierValide, $idFacturation, $date, $prixTotal, $nomClient, $prenomClient, $adresseClient);

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

    $modelStock = new GestionStockModel();
    
    if ($modelStock->newCritiqueState($panierValide)) {
        $mailAdminStock = setMail();

        $mailAdminStock->addAddress($admin["email"], $admin["nom"]);

        $mailAdminStock->Subject = 'Nouvelle commande recue - Zéro Gravité';

        $mailAdminStock->isHTML(true);

        $mailAdminStock->Body = $mailVue->getMailRupture($panierValide);

        // Options SSL
        $mailAdminStock->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            ],
        ];

        // Envoyer l'email à l'admin
        $mailAdminStock->send();
    }
}

$modelPanier->viderPanier();

header("Location: ../views/ValidationCommandeVue.php?user=".$_SESSION["user"]."&idFacturation=".$idFacturation);

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

    $mail->setFrom('leandre.moret10alt@gmail.com', 'Zéro Gravité');
    return $mail;
}

?>
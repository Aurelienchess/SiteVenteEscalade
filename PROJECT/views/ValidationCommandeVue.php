<?php
use PROJECT\models\ClientModel;
use PROJECT\models\ProduitModel;
use PROJECT\models\FacturationModel;

?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Validation</title>
  <link rel="stylesheet" href="../css/style.css">
  <script src="script.js"></script>
  <style>
                    body { font-family: Arial, sans-serif; font-size: 16px; color: #333; }
                    .container { width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ccc; background-color: #f9f9f9; }
                    .header { text-align: center; margin-bottom: 20px; }
                    .order-details { margin-top: 20px; }
                    .order-details table { width: 100%; border-collapse: collapse; }
                    .order-details th, .order-details td { padding: 10px; border: 1px solid #ddd; }
                    .total { font-weight: bold; }
                    .footer { margin-top: 30px; text-align: center; font-size: 14px; color: #888; }
    </style>
</head>
<body>
    <header>
    <?php 
        require_once __DIR__ . '/../models/ClientModel.php';
        $clientModel = new ClientModel();
        
        if (!empty($_GET['user'])) {
            $idUser = $_GET['user'];
            if (!$clientModel->isAdmin($_GET['user'])) {
                echo "<a href='AccueilVue.php?user=$idUser&page=1' class='logo'><img src='../img/logo.jpg' alt=''></a>
                <nav>
            <ul class='navbar'>
            <li></li>
            </ul>
        </nav>
            <a href='../controllers/DeconnexionController.php?user=$idUser'class='deconnexion'><img src='../img/deconnexion.png' alt=''></a>
            <a href='PanierVue.php?user=$idUser'class='panier'><img src='../img/panier.png' alt=''></a>
            <a href='MonCompteVue.php?user=$idUser'class='user'><img src='../img/user.png' alt=''></a>
        ";
            }
            else {
                echo "<a href='AccueilVue.php?user=$idUser&page=1' class='logo'><img src='../img/logo.jpg' alt=''></a>
                <nav>
            <ul class='navbar'>
            <li><a href='ComptaVue.php?user=$idUser'>Comptabilite</a></li>
            <li><a href='FournisseursVue.php?user=$idUser'>Fournisseurs</a></li>
            <li><a href='BaseDeDonneesVue.php?user=$idUser&tableRecherche=Client>Base de données</a></li>
            </ul>
        </nav>
            <a href='../controllers/DeconnexionController.php?user=$idUser'class='deconnexion'><img src='../img/deconnexion.png' alt=''></a>
            <a href='PanierVue.php?user=$idUser'class='panier'><img src='../img/panier.png' alt=''></a>
            <a href='MonCompteVue.php?user=$idUser'class='user'><img src='../img/user.png' alt=''></a>

        ";
            }
        }
        else {
            echo "<a href='AccueilVue.php?page=1'class='logo' ><img src='../img/logo.jpg' alt=''></a>
            <nav>
            <ul class='navbar'>
            <li><a href='ConnexionVue.php'>Se connecter</a></li>
            <li><a href='InscriptionVue.php'>Créer un compte</a></li>
            </ul>
        </nav>";
        }
        ?>
    </header>
    <main>
        
        <?php

    require_once __DIR__ . '/../models/ProduitModel.php';
    require_once __DIR__ . '/../models/FacturationModel.php';

        $idFacturation = $_GET["idFacturation"];

        $modelClient = new ClientModel();
        $infoClient=$modelClient->getById($_SESSION["user"]);
        $nomClient = $infoClient["nom"];
        $prenomClient = $infoClient["prenom"];
        $adresseClient = $infoClient["adresse"];
        $date = date("Y-m-d");

        $modelFacture = new FacturationModel();
        $prixTotal = $modelFacture->getPrixTotalFacture($idFacturation);
        $prixTotalHTC = $prixTotal*0.80;
        
        $allProduitsFacture = $modelFacture->getProduitsByFacture($idFacturation);
        $detailArticles="";

        $modelProduit= new ProduitModel();
        foreach ($allProduitsFacture as $produit) {
            
            $produitInfos = $modelProduit->getContenuById($produit["id_produit"]);
            $titre = $produitInfos["titre"];
            $quantite = $produit["quantite"];
            $prix = $produit["prix"];
            $prixHTC = $produitInfos["prix_HTC"];
            $total = $quantite*$prix;
            $detailArticles .= "<tr>
                                    <th>$titre</th>
                                    <th>$quantite</th>
                                    <th>$prixHTC"."€</th>
                                    <th>$prix"."€</th>
                                    <th>$total"."€</th>
                                </tr>";
        }
        
        echo "<div class='content'>
                    <div class='header'>
                        <h2>Merci pour votre commande !</h2>
                        <p>Votre commande a été confirmée et sera livrée dans les plus brefs délais.</p>
                    </div>
                    <div class='order-details'>
                        <h3>Récapitulatif de votre commande</h3>
                        <p><strong>Numéro de commande :</strong> $idFacturation</p>
                        <p><strong>Date de commande :</strong> $date</p>
            
                        <h4>Détails des articles :</h4>
                        <table>
                            <tr>
                                    <th>Articles</th>
                                    <th>Quantite</th>
                                    <th>Prix(hors-taxes)</th>
                                    <th>Prix</th>
                                    <th>Total</th>
                            </tr>
                            $detailArticles
                        </table>
                        <p class='total'>Total de la commande (taxes non-comprises) : $prixTotalHTC"."€</p>
                        <p class='total'>Total de la commande (taxes comprises) : $prixTotal"."€</p>
                    </div>
                    <div class='shipping-info'>
                        <h4>Adresse de livraison :</h4>
                        <p>$nomClient $prenomClient</p>
                        <p>$adresseClient</p>
                    </div>
                </div>";

        ?>
	        
    </main>
</body>
</html>

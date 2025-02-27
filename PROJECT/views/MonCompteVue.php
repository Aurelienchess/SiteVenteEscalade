<?php
use PROJECT\models\ClientModel;
use PROJECT\models\FacturationModel;
use PROJECT\models\ProduitModel;


?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Compte</title>
  <link rel="stylesheet" href="../css/style.css">
  <script src="script.js"></script>
  <style>
    
    .content{
        margin-left: 25vw;
    }

    .card ul{
        margin-top: 3vw;
        line-height: 2vw;
        text-align: left;
    }

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
            <li><a href='BaseDeDonneesVue.php?user=$idUser&tableRecherche=Client'>Base de données</a></li>
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
    <div class="content">
        <!-- Client Information Section -->
        <section class="card">
            <h2>Vos informations</h2>
            <?php
            $infoClient=$clientModel->getById($_GET["user"]);
            $nomClient = $infoClient["nom"];
            $prenomClient = $infoClient["prenom"];
            $emailClient = $infoClient["email"];
            $adresseClient = $infoClient["adresse"];

            echo"<ul>
                <li><strong>Nom:</strong> $nomClient</li>
                <li><strong>Prenom:</strong> $prenomClient</li>
                <li><strong>Email:</strong> $emailClient</li>
                <li><strong>Adresse:</strong> $adresseClient</li>
            </ul>";
            ?>
        </section>


                    <?php
                    require_once __DIR__ . '/../models/FacturationModel.php';
                    $factureModel = new FacturationModel();
                    require_once __DIR__ . '/../models/ProduitModel.php';
                    $modelProduit = new ProduitModel();
                    
                    $facturesClient = $factureModel->getFacturesByClient($_GET["user"]);
                    if (!empty($facturesClient)) {
                        
                        echo "<section id='orders'>
            <h2>Historique des commandes</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID Commande</th>
                        <th>Date</th>
                        <th>Prix Total</th>
                        <th>Détails</th>
                    </tr>
                </thead>
                <tbody>";
                       
                        foreach($facturesClient as $facture) {
                            $idFacture = $facture["id"];
                            $date = $facture["date_creation"];
                            $prixTotal = $facture["prix_total"];
                            echo "<tr>
                            <td>$idFacture</td>
                            <td>$date</td>
                            <td>$prixTotal</td>
                            <td>
                                <details>
                                    <summary>Voir articles</summary>
                                    <ul>";
                                        $produitsFacture = $factureModel->getProduitsByFacture($idFacture);
                                        foreach($produitsFacture as $produit) {
                                            $quantite = $produit["quantite"];
                                            $prix = $produit["prix"];
    
                                            $infoProduit = $modelProduit->getContenuById($produit["id_produit"]);
                                            $nom = $infoProduit["titre"];
    
                                            echo "<li>$nom - $quantite x $prix"."€</li>";
                                        }
                                    echo"</ul>
                                </details>
                            </td>
                        </tr>";
                        }
                        echo "</tbody>
            </table>
        </section>";
                    } else {
                        echo"
            <section>Aucune commandes pour l'instant.</section>";
                    }

                    ?>
    </div>
    </main>
</body>
</html>

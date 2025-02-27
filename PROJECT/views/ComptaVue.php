<?php
use PROJECT\models\ClientModel;
use PROJECT\models\ComptaModel;
use PROJECT\models\FacturationModel;
use PROJECT\models\ProduitModel;
use PROJECT\models\FournisseursModel;

require_once __DIR__ . '/../models/ComptaModel.php';
require_once __DIR__ . '/../models/ClientModel.php';
require_once __DIR__ . '/../models/FacturationModel.php';
require_once __DIR__ . '/../models/ProduitModel.php';
require_once __DIR__ . '/../models/FournisseursModel.php';


?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Comptabilite</title>
  <link rel="stylesheet" href="../css/style.css">
  <script src="script.js"></script>
  <style>
    .content {
    display: grid;
    align-items: stretch;
    justify-items: stretch;
    gap: 0;
    margin: 0 auto;
    padding: 0;
    }

    main{
        margin: 12vw;
    }

    .card {
        width: 90%;
        padding: 0;
    }

    .card ul{
        margin-top: 4vw;
    }

    .add-to-cart{
        width: 60%;
        margin-top: 4vw;
    }

  </style>
</head>
<body>
    <header>
        <?php 
        
        if (isset($_GET['isValider'])) {
            echo '<script>alert("Votre commande a été validée");</script>';
        }
        
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
            <li><a href='ComptaVue.php?user=$idUser' class='active'>Comptabilite</a></li>
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
            <h2>Etat de la Comptabilite</h2>
            <?php
            $modelCompta = new ComptaModel();

            $idCompta = $modelCompta->getLastCompta();
            $infosCompta = $modelCompta->getComptaById($idCompta);

            $dateCreationCompta = $infosCompta["date_creation"];
            $caCompta = $infosCompta["chiffre_daffaire"];
            $montantCompta = $infosCompta["montant"];
            $resultatCompta = $infosCompta["resultat"];

            echo"<ul>
                <li><strong>Date début compta: </strong> $dateCreationCompta</li>
                <li><strong>Chiffre d'affaire: </strong> $caCompta</li>
                <li><strong>Montant dépensé: </strong> $montantCompta</li>";

            if ($resultatCompta>=0) {
                echo "<li><strong>Bénéfice: </strong> $resultatCompta</li>";
            }
            else {
                echo "<li><strong>Déficit: </strong> $resultatCompta</li>";
            }
            echo "<a href='../controllers/NouvelleComptaController.php?user=$idUser' class='add-to-cart'>Démarrer une nouvelle comptabilité</a></ul>";
            ?>
        </section>

        <section id="orders">
            <h2>Historique des ventes</h2>
            
            <?php
            $facturesCompta = $modelCompta->getVentes($idCompta);
            $achatsCompta = $modelCompta->getAchats($idCompta);
            
            if(!$facturesCompta) {
                echo "<h3>Aucune vente pour l'instant enregistrée dans cette comptabilité</h3>";
            }
            else {
                echo "<table>
                <thead>
                    <tr>
                        <th>ID Ventes</th>
                        <th>Date</th>
                        <th>Prix Total</th>
                        <th>Détails</th>
                    </tr>
                </thead>
                <tbody>";
                
                $factureModel = new FacturationModel();
                $modelProduit = new ProduitModel();
                
                foreach($facturesCompta as $facture) {
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
                        </td>";
                }
                echo "</tr>
                </tbody>
            </table>";
            }
            ?>

		</section>
        <section id="orders">
            <h2>Historique des achats</h2>
            
            <?php 
            if (!$achatsCompta) {
                echo "<h3>Aucun achat pour l'instant enregistré dans cette comptabilité</h3>";
            }
            else {
            echo "<table>
                <thead>
                    <tr>
                        <th>ID achat</th>
                        <th>Fournisseur</th>
                        <th>Date</th>
                        <th>Article</th>
                        <th>Quantite</th>
                        <th>Prix total</th>
                    </tr>
                </thead>
                <tbody>";
                $modelFournisseur = new FournisseursModel();
                
                foreach($achatsCompta as $achat) {
                    if($achat["statut"]) {
                        $idAchat = $achat["id_achat"];
                        $date = $achat["date_achat"];
                        $quantite = $achat["quantite"];
                        $prixTotal = $achat["montant"];
                        
                        $infosProduit = $modelProduit->getContenuById($achat["id_produit"]);
                        $titre = $infosProduit["titre"];
                        
                        $infosFournisseur = $modelFournisseur->getFournisseurById($achat["id_fournisseur"]);
                        $nomFournisseur = $infosFournisseur["nom"];
                        
                        echo "<tr>
                                <td>$idAchat</td>
                                <td>$nomFournisseur</td>
                                <td>$date</td>
                                <td>$titre</td>
                                <td>$quantite</td>
                                <td>$prixTotal</td>";
                    }
                }
                echo "</tr>
                </tbody>
            </table>";
            }
            ?>
           
        </section>
    </div>
    </main>
</body>
</html>

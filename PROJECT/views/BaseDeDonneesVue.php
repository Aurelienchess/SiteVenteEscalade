<?php
use PROJECT\models\ClientModel;
use PROJECT\models\FournisseursModel;
use PROJECT\models\ProduitModel;
use PROJECT\models\GestionStockModel;
use PROJECT\models\AchatModel;
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Données</title>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/style.css">
  <script src="script.js"></script>
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
            <li><a href='BaseDeDonneesVue.php?user=$idUser&tableRecherche=Client'class = 'active'>Base de données</a></li>
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
        
        <h1>Administration du Magasin Virtuel</h1>

        <h2>Produits du magasin</h2>

        <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Titre</th>
                        <th>Reference</th>
                        <th>Descriptif</th>
                        <th>Prix public</th>
                        <th>Prix achat</th>
                        <th>Prix HTC</th>
                        <th>Catégorie</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    require_once __DIR__ . '/../models/ProduitModel.php';
                    $modelProduit = new ProduitModel();
                    
                    $allProduits = $modelProduit->getAllProduit();
                    
                    foreach($allProduits as $produit) {
                        $id=$produit["id"];
                        $titre = $produit["titre"];
                        $reference = $produit["reference"];
                        $descriptif = $produit["descriptif"];
                        $prix_public = $produit["prix_public"];
                        $prix_achat=$produit["prix_achat"];
                        $prix_HTC=$produit["prix_HTC"];
                        $categorie=$produit["categorie"];
                        $statut=$produit["vendable"];

                        echo "<tr>
                        <td>$id</td>
                        <td>$titre</td>
                        <td>$reference</td>
                        <td>
                            <details>
                                <summary>Voir descriptif</summary>
                                <p>$descriptif</p>
                            </details>
                        </td>
                        <td>$prix_public"."€</td>
                        <td>$prix_achat"."€</td>
                        <td>$prix_HTC"."€</td>
                        <td>$categorie</td><td>";
                        if($statut) {
                            echo "<form action='../controllers/EnleverProduitController.php' method='POST'>
                            <input type='hidden' name='idProduit' value='$id'>
                            <button type='submit' class='add-to-cart'>Supprimer</button>
                        </form>";
                        }
                        else {
                            echo "<form action='../controllers/RemettreProduitController.php' method='POST'>
                            <input type='hidden' name='idProduit' value='$id'>
                            <button type='submit' class='add-to-cart'>Restaurer</button>
                        </form>";
                        }
                    echo"</td></tr>";
                        
                    }
                    
                    ?>

                </tbody>
            </table>
            
            <h2>Ajouter Produit</h2>
            <form action='../controllers/NouveauProduitController.php' method = 'POST' class='creerForm'>
            
            <p> Titre :
                <input name='titre' type='text' />
            </p>
            <p> Reference :
                <input name='reference' type='text' />
            </p>
            <p> Prix public (en €) :
                <input name='prixPublic' type='text' />
            </p>
            <p> Prix d'achat (en €) :
                <input name='prixAchat' type='text' />
            </p>
            <p> Prix HTC (en €) :
                <input name='prixHTC' type='text' />
            </p>
            <p> Categorie (divers, materiel_securite, equipement_base, accessoires) :
                <input name='categorie' type='text' />
            </p>
            <p> Descriptif :
                <input name='descriptif' type='text' />
            </p>
            <p> Image (URL de l'image téléchargée et mise dans le dossier img) :
                <input name='image' type='text' />
            </p>
            <p>
                <input type="submit" name="Confirmer" value="Confirmer" />
            </p>
            
            </form>

        <h2>Fournisseurs</h2>
        
        <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Contact</th>
                        <th>Produits Vendus</th>
                        <th>Statut</th>

                </thead>
                <tbody>
                    <?php
                    require_once __DIR__ . '/../models/FournisseursModel.php';
                    $modelFournisseur = new FournisseursModel();
                    
                    $allFournisseurs = $modelFournisseur->getAllFournisseurs();
                    
                    foreach($allFournisseurs as $fournisseur) {
                        $id=$fournisseur["id_fournisseur"];
                        $nom=$fournisseur["nom"];
                        $contact=$fournisseur["contact"];
                        $statut=$fournisseur["statut"];
                        
                        $produits_vendus = $modelFournisseur->getProduitsVendus($id);

                        echo "<tr>
                        <td>$nom</td>
                        <td>$contact</td>
                        <td>
                            <details>
                                <summary>Voir produits vendus</summary>";
                        foreach($produits_vendus as $produit_vendu) {
                            $produit = $modelProduit->getContenuById($produit_vendu["id_produit"]);
                            echo "<p>".$produit["titre"]."</p>";
                        }
                            echo "</details>
                        </td><td>";
                        if($statut) {
                            echo "<form action='../controllers/EnleverFournisseurController.php' method='POST'>
                            <input type='hidden' name='idFournisseur' value='$id'>
                            <button type='submit' class='add-to-cart'>Supprimer</button>
                        </form>";
                        }
                        else {
                            echo "<form action='../controllers/RemettreFournisseurController.php' method='POST'>
                            <input type='hidden' name='idFournisseur' value='$id'>
                            <button type='submit' class='add-to-cart'>Restaurer</button>
                        </form>";
                        }
                    echo"</td></tr>";
                        
                    }
                    
                    ?>

                </tbody>
            </table>
            
            <h2>Ajouter Fournisseur</h2>

            <form action='../controllers/NouveauFournisseurController.php' method = 'POST' class='creerForm'>
            
            <p> Nom :
                <input name='nom' type='text' />
            </p>
            <p> Contact :
                <input name='contact' type='text' />
            </p>
            <p> ID des produits vendus (séparez par des virgules) :
                <input name='idProduitsVendus' type='text' />
            </p>
            <p>
                <input type="submit" name="Confirmer" value="Confirmer" />
            </p>
            
            </form>
            
            <h2>Factures auprès de Fournisseurs</h2>
        
        <table>
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Quantité</th>
                        <th>Montant</th>
                        <th>Fournisseur</th>
                        <th>Date Achat</th>
                        <th>Statut</th>

                </thead>
                <tbody>
                    <?php
                    require_once __DIR__ . '/../models/AchatModel.php';
                    $modelAchat = new AchatModel();
                    
                    $allAchats = $modelAchat->getAllAchats();
                    
                    foreach($allAchats as $achat) {
                        $id = $achat["id_achat"];
                        $infosProduit = $modelProduit->getContenuById($achat["id_produit"]);
                        $infosFournisseur = $modelFournisseur->getFournisseurById($achat["id_fournisseur"]);
                        
                        $nom_Produit = $infosProduit["titre"];
                        $quantite = $achat["quantite"];
                        $montant = $achat["montant"];
                        $nom_Fournisseur = $infosFournisseur["nom"];
                        $date = $achat["date_achat"];
                        $statut = $achat["statut"];

                        echo "<tr>
                        <td>$nom_Produit</td>
                        <td>$quantite</td>
                        <td>$montant</td>
                        <td>$nom_Fournisseur</td>
                        <td>$date</td><td>";
                        if($statut) {
                            echo "<form action='../controllers/EnleverAchatController.php' method='POST'>
                            <input type='hidden' name='idAchat' value='$id'>
                            <button type='submit' class='add-to-cart'>Supprimer</button>
                        </form>";
                        }
                        else {
                            echo "<form action='../controllers/RemettreAchatController.php' method='POST'>
                            <input type='hidden' name='idAchat' value='$id'>
                            <button type='submit' class='add-to-cart'>Restaurer</button>
                        </form>";
                        }
                    echo"</td></tr>";
                        
                    }
                    
                    ?>

                </tbody>
            </table>

	        
    </main>
</body>
</html>


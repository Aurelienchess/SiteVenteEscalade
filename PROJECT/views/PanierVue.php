<?php
use PROJECT\models\ClientModel;
use PROJECT\models\ProduitModel;
use PROJECT\models\PanierModel;
use PROJECT\models\GestionStockModel;

?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panier</title>
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
        <section class="content">
        
        <?php 
        require_once __DIR__ . '/../models/ProduitModel.php';
        require_once __DIR__ . '/../models/PanierModel.php';
        require_once __DIR__ . '/../models/GestionStockModel.php';
        $modelProduit = new ProduitModel();
        $modelPanier = new PanierModel();
        $modelStock = new GestionStockModel();
        
        // Obtenir le contenu du panier
        $panier = $modelPanier->obtenirPanier(); // Doit renvoyer un tableau associatif id => quantité

        if (!empty($panier)) {
            $prixTotal = $modelPanier->calculerTotal();
            $prixTotal = $prixTotal."€";
            foreach ($panier as $idProduit => $quantite) {
                $produit = $modelProduit->getContenuById($idProduit); // Obtenir les détails du produit
                if ($produit) { // Vérifie si le produit existe
                    $prixPublic = htmlspecialchars($produit["prix_public"]) . "€";
                    $titre = htmlspecialchars($produit["titre"]);
                    $image = htmlspecialchars("../img/" . $produit["image"]);
                    $afficheQuantite = "x" . intval($quantite);
                    $isDispo = $modelStock->isInStock($idProduit);

                    echo "<div class='card'>
                        <img src='$image' alt='Image de $titre'>
                        <ul>
                            <li><strong>$titre</strong></li>
                            <li>Prix : $prixPublic</li>
                            <li>Quantité : $afficheQuantite</li>
                        </ul>
                        <form action='../controllers/ModifierQuantitePanierController.php' method='POST'>
                            <input type='hidden' name='idProduit' value='$idProduit'>
                            
                        <button type='submit' name='action' value='enlever' class='quantity-btn' style='background-color:rgb(189, 216, 224); color: #333;'>-</button>";
                        if ($isDispo) {
                            echo "<button type='submit' name='action' value='ajouter' class='quantity-btn' style='background-color: rgb(189, 216, 224); color: #333;'>+</button>";
                        } else {
                            echo "<button type='submit' name='action' value='ajouter' class='quantity-btn' style='background-color: rgb(176, 183, 212); color: #333;' disabled>+</button>";
                        }
                        echo "</form>
                        <form action='../controllers/SupprimerElemPanierController.php' method='POST'>
                            <input type='hidden' name='idProduit' value='$idProduit'>
                            <button type='submit' class='add-to-cart' style='background-color:rgb(237, 199, 199); color: #333;'>Supprimer</button>
                        </form>
                        
                    </div>";                  
                }
                    
            }
            echo "
                            </section><footer><form action='../controllers/SupprimerPanierController.php' method='POST' style='margin-top: 20px;'>
                                <button type='submit' class='action_panier' style='background-color:rgb(179, 142, 142); color: #333;'>Vider le Panier</button>
                            </form>
                            <div>Prix total de votre panier : $prixTotal</div>
                            <form action='../controllers/ValiderPanierController.php' method='POST' style='margin-top: 20px;'>
                                <button type='submit' class='action_panier' style='background-color:rgb(132, 173, 127); color: #333;'>Valider le Panier</button>
                            </form> </footer>
                            ";  
            
        } else {
            echo"<style>
                        main{
                            margin-top: 16vw;
                        }
                        .content {
                            display: inline-block;
                            color: #333;
                        }
                 </style>
            <p>Votre panier est vide.</p></section>";
        }
        ?>
    </main>

</body>
</html>

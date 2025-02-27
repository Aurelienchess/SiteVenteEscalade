<?php
use PROJECT\models\ClientModel;
use PROJECT\models\ProduitModel;
use PROJECT\models\GestionStockModel;
use PROJECT\models\FournisseursModel;

require_once __DIR__ . '/../models/ClientModel.php';
require_once __DIR__ . '/../models/ProduitModel.php';
require_once __DIR__ . '/../models/GestionStockModel.php';
require_once __DIR__ . '/../models/FournisseursModel.php';
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Fournisseurs</title>
  <link rel="stylesheet" href="../css/style.css">
  <script src="script.js"></script>
  <style>
    .card img{
        width: 100%;
        margin: 0px;
    }

    .card h2{
        margin: 0px;;
    }

    .card{
        height: 420px;
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
            <li><a href='FournisseursVue.php?user=$idUser' class = 'active'>Fournisseurs</a></li>
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

        $modelProduit = new ProduitModel();
        $modelStock = new GestionStockModel();
        $modelFournisseurs = new FournisseursModel();

        $allProduits = $modelProduit->getAllProduit();

        foreach ($allProduits as $produit) {
            
                $idProduit = $produit["id"];
                $idFournisseur = $modelFournisseurs->getIdByProduit($idProduit);
                
                $infosFournisseur = $modelFournisseurs->getFournisseurById($idFournisseur);
                if($infosFournisseur!==false) {
                    
                    if($infosFournisseur["statut"]) {
                        
                        $image = "../img/".$produit["image"];
                        $titre = $produit["titre"];
        
                        $infosStock = $modelStock->getStock($idProduit);
                        $quantite = $infosStock["quantite"];
                        $seuilCritique = $infosStock["seuil_critique"];
        
                        $prix_achat = $produit["prix_achat"];
        
                        $nomFournisseur = $infosFournisseur["nom"];
                        $contactFournisseur = $infosFournisseur["contact"];
                        $idFournisseur = $infosFournisseur["id_fournisseur"];
        
                        echo "<section class='card'>
                        <h2><img src=$image alt=''></h2>
                        <ul>
                            <li><strong>Nom produit:</strong> $titre</li>
        
                            <li><strong>Quantite en stock:</strong> $quantite</li>";
        
                            if ($seuilCritique==0) {
                                echo "<li><strong>Seuil critique:</strong> Non-atteint</li>";
                            }
                            else {
                                echo "<li style='color: red;'><strong>Seuil critique:</strong> Atteint</li>";
                            }
                            echo "<li><strong>Prix achat:</strong> $prix_achat"."€</li>
        
                            <li><strong>Fournisseur:</strong> $nomFournisseur</li>
                            <li><strong>Contact:</strong> $contactFournisseur</li>
                            
                        </ul>
        
                        <form action='../controllers/AchatFournisseurController.php' method='POST'>
                            <input type='hidden' name='idProduit' value='$idProduit'>
                            <input type='hidden' name='idFournisseur' value='$idFournisseur'>
                            <p> Choisissez une quantité :
        
                            <input 
                            type='number' 
                            id='quantite' 
                            name='quantite' 
                            min='1' 
                            max='100' 
                            required 
                            placeholder='1-100'
                            >
        
                            </p>
                            <button type='submit' class='add-to-cart'>Se réapprovisionner</button>
                            </form>
                    </section>";
                    }
            }
        }

        ?>

    </main>
</body>
</html>
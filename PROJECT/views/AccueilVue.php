<?php
use PROJECT\models\ClientModel;
use PROJECT\models\GestionStockModel;
use PROJECT\models\ArticlesDisposModel;

?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Accueil</title>
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

        <section>


            <form action="../controllers/TrierProduitController.php" method="POST" class="recherche">
                <input type="text" name="recherche">
                <label for="categorie">Catégorie :</label>
                <select name="categorie" id="categorie">
                    <option value="toutes">Toutes les catégories</option>
                    <option value="equipement_base">Équipement de base</option>
                    <option value="materiel_securite">Matériel de sécurité</option>
                    <option value="accessoires">Accessoires</option>
                    <option value="divers">Divers</option>
                </select>

                <label for="ordre">Ordre :</label>
                <select name="ordre" id="ordre">
                    <option value="titre ASC">Alphabétique (A-Z)</option>
                    <option value="titre DESC">Alphabétique (Z-A)</option>
                    <option value="prix_public ASC">Prix (croissant)</option>
                    <option value="prix_public DESC">Prix (décroissant)</option>
                </select>

                <button type="submit">Rechercher</button>
            </form>
        </section>

        <section class="content">

        <?php 
        require_once __DIR__ . '/../models/GestionStockModel.php';
        require_once __DIR__ . '/../models/ArticlesDisposModel.php';
        $modelStock=new GestionStockModel();
        $articlesDisposModel=new ArticlesDisposModel();
        
        $allProduits = $articlesDisposModel->getListPaginee();
        
        foreach($allProduits as $produit) {
            $test = $produit["vendable"];
            if($test) {
                $id = $produit["id"];
                $champs = $produit["categorie"];
                $prixPublic = $produit["prix_public"] . "€";
                $titre = $produit["titre"];
                $descriptif = $produit["descriptif"];
                
                switch($champs) {
                    case "equipement_base":
                        $champs = "Equipement de base";
                        break;
                    case "materiel_securite":
                        $champs = "Matériel de sécurité";
                        break;
                    case "accessoires":
                        $champs = "Accessoires";
                        break;
                    case "divers":
                        $champs = "Divers";
                        break;
                }
                
                $image = "../img/".$produit["image"];
                $isDispo = $modelStock->isInStock($id);
                
                echo "<div class='card'>
                
                <ul>
                    <li><b>$titre</b></li>
                    <img src='$image' alt=''>
                    <li>$champs</li>
                    <li>$prixPublic</li>
                    <li>$descriptif</li>
                </ul>";
                
                if ($isDispo) {
                    if (empty($_GET['user'])) {
                        echo "<a href='ConnexionVue.php?error=no_account' class='add-to-cart'>Ajouter au panier</a>";
                        echo"   <style>.add-to-cart {
                                    width: 40%;
                                    height: 5%;
                                    }
                                </style>";
                    } else {
                        echo "<form action='../controllers/AjouterProduitController.php' method='POST'>
                                <input type='hidden' name='idProduit' value='$id'>
                                <button type='submit' class='add-to-cart'>Ajouter au panier</button>
                            </form>";
                    }
                } else {
                    echo "<button type='submit' class='add-to-cart' style='background-color: grey; color: white;' disabled>Ajouter au panier</button>";
                }
                
                echo "</div>";
            }
        }
        
        ?>
	        
        </section>
    </main>
    <footer class="pagination">
    <?php
    $allProduits = $articlesDisposModel->getListArticles();
    $nbProduitTotal = count($allProduits);
    $pageMax = ceil($nbProduitTotal / $articlesDisposModel->getNbPagine());
    $page = $_GET["page"];
    
    if ($page > 1 || $page < $pageMax) {
        echo "<div class='pagination-container'>";
        if ($page > 1) {
            $pagePrecedente = intval($page) - 1;
            echo "<div><a href='../controllers/ChangerPageController.php?page=$pagePrecedente' class='add-to-cart' style='height: 20px; width:100%;'>Page précédente</a></div>";
        }
        if ($page < $pageMax && $pageMax != 1) {
            $pageSuivante = intval($page) + 1;
            echo "<div><a href='../controllers/ChangerPageController.php?page=$pageSuivante' class='add-to-cart' style='height: 20px; width:100%;e'>Page suivante</a></div>";
        }
        echo "</div>";
    }
    ?>
</footer>

</body>
</html>
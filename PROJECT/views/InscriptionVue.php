<?php
use PROJECT\models\ClientModel;
?>

<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inscription</title>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/connexion.css">
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
            <li><a href='InscriptionVue.php' class = 'active'>Créer un compte</a></li>
            </ul>
        </nav>";
        }
        ?>
    </header>
    <form action="../controllers/InscriptionController.php" method="POST">
         <p> Email :
         <input name="email" type="text" />
         </p>
         <p> Nom :
         <input name="nom" type="text" />
         </p>
         <p> Prénom :
         <input name="prenom" type="text" />
         </p>
         <p> Adresse :
         <input name="adresse" type="text" />
         </p>
         <p> Mot de passe :
         <input name="mdp" type="password" />
         </p>
         <p> Confirmez mot de passe :
         <input name="mdp" type="password" />
         </p>
         <p class="checkbox-container">
           <input type="checkbox" id="admin" name="admin">
           <label for="admin">Être admin (pour évaluation)</label>
         </p>
         <p>
         <input type="submit" name="Confirmer" value="Confirmer" />
         </p>
        <?php
        if (!empty($_GET['invalid_email'])) {
            if ($_GET['error'] === 'invalid_credentials') {
                echo "<p style='color: red;'>Un compte avec cette adresse email a déjà été créé.</p>";
            } elseif ($_GET['error'] === 'missing_parameters') {
                echo "<p style='color: red;'>Veuillez remplir tous les champs.</p>";
            }
        }
        ?>
    
    </form>
    <p class="create-account">
        Déjà un compte ? <a href="ConnexionVue.php">Connectez vous !</a>
    </p>
</body>
</html>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion</title>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/connexion.css">
  <script src="script.js"></script>
</head>
<body>
    <header>
    <?php 
        use PROJECT\models\ClientModel;

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
            <li><a href='ConnexionVue.php' class = 'active'>Se connecter</a></li>
            <li><a href='InscriptionVue.php'>Créer un compte</a></li>
            </ul>
        </nav>";
        }
        ?>
    </header>
    <form action="../controllers/ConnexionController.php" method="POST">
    	<?php 
    	$userName = $clientModel->getUserNameCookie();
    	$mdp = $clientModel->getMdpCookie();
    	echo "<p> Email :
         <input name='email' type='text' value='$userName' />
         </p>
         <p> Mot de passe :
         <input name='mdp' type='password' value='$mdp' />
         </p>";
    	
    	
    	?>
         <p class="checkbox-container">
           <input type="checkbox" id="rememberMe" name="rememberMe">
           <label for="rememberMe">Se souvenir de moi</label>
         </p>
         <p>
         <input type="submit" name="Connexion" value="Connexion" />
         </p>
        <?php
        if (!empty($_GET['error'])) {
            if ($_GET['error'] === 'invalid_credentials') {
                echo "<p style='color: red;'>Email ou mot de passe incorrect.</p>";
            } elseif ($_GET['error'] === 'missing_parameters') {
                echo "<p style='color: red;'>Veuillez remplir tous les champs.</p>";
            } elseif ($_GET['error'] === 'no_account') {
                echo "<p style='color: red;'>Veuillez vous connecter ou créer un compte pour acheter des articles.</p>";
            }
        }
        ?>
    
    </form>
    <p class="create-account">
        Pas de compte ? <a href="InscriptionVue.php">Créez-en un !</a>
    </p>
</body>
</html>
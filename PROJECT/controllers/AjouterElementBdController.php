<?php
use PROJECT\models\Model;
use PROJECT\models\ClientModel;

require_once __DIR__ . '/../models/Model.php'; // Inclure le modèle
require_once __DIR__ . '/../models/ClientModel.php'; // Inclure le modèle Client

// Récupérer les données POST du formulaire
$table = $_POST['table'];
$colonnes = $_POST['colonnes'];

// Instancier le modèle
$model = new PROJECT\models\Model();

try {
    $colonnesArray = explode(",", $colonnes);// Split des colonnes (clé:valeur)
    $data = [];
    
    foreach ($colonnesArray as $colonne) {
        list($key, $value) = explode(":", $colonne);
        $data[$key] = $value;
    }
    
    $model->insert($table, $data);

    header("Location: ../views/BaseDeDonneesVue.php?user=" . $_SESSION['user'] . "&action=ajouter");
    exit();
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}
?>


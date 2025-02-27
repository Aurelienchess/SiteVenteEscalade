<?php
namespace PROJECT\models;
use PROJECT\models\Model;
use InvalidArgumentException;
use Exception;

class AchatModel {
    
    private $model;
    
    public function __construct() {
        $this->model = new Model();
    }
    
    public function nouvelAchat($id_compta, $id_produit, $idfournisseur, $quantite, $montant, $date) {
    
        $result = $this->model->insert("Achats", [
            "id_compta" => $id_compta,
            "id_produit" => $id_produit,
            "id_fournisseur" => $idfournisseur,
            "quantite" => $quantite,
            "montant" => $montant,
            "date_achat" => $date
        ]);
    
        return $result;
    }
    
    public function getAllAchats() {
        return $this->model->getAll("Achats");
    }
    
}
?>
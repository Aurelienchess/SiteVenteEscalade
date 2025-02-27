<?php
namespace PROJECT\models;
use PROJECT\models\Model;
require_once __DIR__ . '/../models/Model.php';

class ProduitModel {
    
    private $model;
    
    public function __construct() {
        $this->model = new Model();
    }
    
    public function getContenuById($produitId) {
        $produitsBD = $this->model->getAll("Produit");
        foreach($produitsBD as $produit) {
            if($produit['id']==$produitId) {
                return $produit;
            }
        }
        return false;
    }

    public function isProduit($produitId) {
        $produitsBD = $this->model->getAll("Produit");
        foreach($produitsBD as $produit) {
            if($produit['id']==$produitId) {
                return true;
            }
        }
        return false;
    }
    
    public function getIdByRef($produitRef) {
        $produitsBD = $this->model->getAll("Produit");
        foreach($produitsBD as $produit) {
            if($produit['reference']==$produitRef) {
                return $produit['id'];
            }
        }
        return false;
    }
    
    public function getAllProduit() {
        return $this->model->getAll("Produit");
    }

    public function retirerProduit($idProduit) {
        $data = ['vendable' => 0];
        $where = ['id' => $idProduit];
        return $this->model->update('Produit', $data, $where);
    }

    public function restaurerProduit($idProduit) {
        $data = ['vendable' => 1];
        $where = ['id' => $idProduit];
        return $this->model->update('Produit', $data, $where);
    }

    public function nouveauProduit($titre,$reference,$prixPublic,$prixAchat,$prixHTC,$descriptif,$image,$categorie) {
        $this->model->insert("Produit", [
            "reference" => $reference,
            "prix_public" => $prixPublic,
            "prix_achat" => $prixAchat,
            "titre" => $titre,
            "descriptif" => $descriptif,
            "image" => $image,
            "prix_HTC" => $prixHTC,
            "categorie" => $categorie
        ]);
    }
    


}
?>
<?php
namespace PROJECT\models;

use PROJECT\models\Model;
require_once __DIR__ . '/../models/Model.php';


class GestionStockModel {
    
    private $model;
    
    public function __construct() {
        $this->model = new Model();
    }
    
    //quantite mise de base pour un ajout de produit dans le panier. Pour rajouter des produits, chiffre négatif, pour en enlever, chiffres positifs
    public function miseAJour($produit, $quantite = 1) {
        // Récupère le stock pour le produit donné
        $stock = $this->getStock($produit);
        
        if (!$stock) {
            // Si le produit n'existe pas dans le stock, retourne false
            return false;
        }
        
        // Calcule la nouvelle quantité
        $nouvelleQuantite = $stock["quantite"] - $quantite;
        
        if ($nouvelleQuantite < 0) {
            // Si la quantité demandée dépasse le stock disponible, retourne false
            return false;
        }
        
        // Vérifie si la quantité atteint le seuil critique
        $seuilCritique = ($nouvelleQuantite == 0);
        
        // Met à jour les colonnes du produit dans la table Gestion_Stock
        $this->model->update(
            "Gestion_Stock",
            [
                "quantite" => $nouvelleQuantite,
                "seuil_critique" => $seuilCritique ? 1 : 0
            ],
            [
                "id_produit" => $produit
            ]
            );
        
        return true;
    }
    
    
    //renvoie la ligne du tableau Get_Colonne correspondant au produit passé en paramètre
    public function getStock($produit) {
        $stocks = $this->model->getAll("Gestion_Stock");
        foreach($stocks as $stock) {
            if(strval($stock['id_produit'])==strval($produit)) {
                return $stock;
            }
        }
        return false;
    }
    
    public function isInStock($idProduit) {
        $stock = $this->getStock($idProduit);
        if (!$stock) {
            // Si le produit n'existe pas dans le stock, retourne false
            return false;
        }
        
        if($stock["seuil_critique"]===0) {
            return true;
        }
        else {
            return false;
        }
    }

    //après une commande, vérifier si un des articles du panier fini en état crtique à cause de ça
    public function newCritiqueState($panierValide) {
        foreach ($panierValide as $idProduit=>$quantite) {
            if (!$this->isInStock($idProduit)) {
                return true;
            }
        }
        return false;
    }
}
?>
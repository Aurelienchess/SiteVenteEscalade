<?php
namespace PROJECT\models;

class FacturationModel
{
    private $model;
    
    public function __construct() {
        $this->model = new Model();
    }
    
    public function nouvelleFacture($idUser, $date, $prixTotal, $idCompta) {
        return $this->model->insert("Facturation", ["id_client"=>$idUser, "date_creation"=>$date,"prix_total"=>$prixTotal,"id_compta"=>$idCompta]);
    }

    public function getFacturesByClient($idUser) {
        $allFacturation = $this->model->getAll("Facturation");
        $userFactures = array_filter($allFacturation, function ($facture) use ($idUser) {
            return $facture['id_client'] == $idUser;
        });
    
        return $userFactures;
    }

    public function getProduitsByFacture($idFacture) {
        $getAllProduit = $this->model->getAll("Panier");
        $factureProduits = array_filter($getAllProduit, function ($produit) use ($idFacture) {
            return $produit['id_facturation'] == $idFacture;
        });

        return $factureProduits;
    }

    public function getPrixTotalFacture($idFacture) {
        $allFacturation = $this->model->getAll("Facturation");
        $userFactures = array_filter($allFacturation, function ($facture) use ($idFacture) {
            return $facture['id'] == $idFacture;
        });
    
        $userFactures = reset($userFactures); // Récupère le premier élément du tableau
    
        return $userFactures ? $userFactures["prix_total"] : null; // Vérifie que l'élément existe
    }
    
}


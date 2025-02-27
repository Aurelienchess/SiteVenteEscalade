<?php
namespace PROJECT\models;

use PROJECT\models\Model;
require_once __DIR__ . '/../models/Model.php';

class FournisseursModel {
    
    private $model;
    
    public function __construct() {
        $this->model = new Model();
        
    }
    

    public function getFournisseurById($fournisseursId) {
        $fournisseursBD = $this->model->getAll("Fournisseur");
        foreach($fournisseursBD as $fournisseurs) {
            if($fournisseurs['id_fournisseur']==$fournisseursId) {
                return $fournisseurs;
            }
        }
        return false;
    }
    public function getIdByProduit($idProduit) {
        $produitVendusBD = $this->model->getAll("Produits_Vendus");
        foreach($produitVendusBD as $produit) {
            if($produit['id_produit']==$idProduit) {
                return $produit['id_fournisseur'];
            }
        }
    }
    
    public function getAllFournisseurs() {
        return $this->model->getAll("Fournisseur");
    }
    
    public function getProduitsVendus($fournisseursId) {
        $produitVendusBD = $this->model->getAll("Produits_Vendus");
        $result = [];
        foreach($produitVendusBD as $produit) {
            if($produit['id_fournisseur']==$fournisseursId) {
                $result[] = $produit;
            }
        }
        return $result;
    }
    
    public function retirerFournisseur($idFournisseur) {
        $data = ['statut' => 0];
        $where = ['id_fournisseur' => $idFournisseur];
        return $this->model->update('Fournisseur', $data, $where);
    }

    public function restaurerFournisseur($idFournisseur) {
        $data = ['statut' => 1];
        $where = ['id_fournisseur' => $idFournisseur];
        return $this->model->update('Fournisseur', $data, $where);
    }

    public function nouveauFournisseur($nom,$contact) {
        $this->model->insert("Fournisseur", [
            "nom" => $nom,
            "contact" => $contact
        ]);

        $allFournisseurs = $this->model->getAll('Fournisseur');
        $dernierFournisseur= end($allFournisseurs);
        return $dernierFournisseur['id_fournisseur'];
    }

    public function addProduitVendu($idFournisseur, $idProduit) {
        return $this->model->insert("Produits_Vendus", [
            "id_fournisseur" => $idFournisseur,
            "id_produit" => $idProduit
        ]);
    }

}
?>
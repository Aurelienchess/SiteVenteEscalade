<?php
namespace PROJECT\models;
use PROJECT\models\Model;
require_once __DIR__ . '/../models/Model.php';


class ComptaModel {
    
    private $model;
    
    public function __construct() {
        $this->model = new Model();
    }
    
    public function getLastCompta() {
        $allCompta = $this->model->getAll('Compta');
        $derniereCompta= end($allCompta);
        return $derniereCompta['id_compta'];
    }

    public function getComptaById($idCompta) {
        $allCompta = $this->model->getAll('Compta');
        foreach($allCompta as $compta) {
            if ($compta["id_compta"] == $idCompta) {
                return $compta;
            }
        }
        return false;
    }
    
    public function getVentes($idCompta) {
        $ventes = $this->model->getAll("Facturation");
        if ($ventes === false || empty($ventes)) {
            return false;
        }
    
        $ventesCompta = [];
        foreach ($ventes as $vente) {
            if ($vente['id_compta'] == $idCompta) { 
                $ventesCompta[] = $vente; 
            }
        }
    
        return empty($ventesCompta) ? false : $ventesCompta;
    }
    
    public function getAchats($idCompta) {
        $achats = $this->model->getAll("Achats");
        if ($achats === false || empty($achats)) {
            return false;
        }
    
        $achatCompta = [];
        foreach ($achats as $achat) {
            if ($achat['id_compta'] == $idCompta) { 
                $achatCompta[] = $achat; 
            }
        }
    
        return empty($achatCompta) ? false : $achatCompta;
    }

    public function calculerNouveauChiffreDaffaire($idCompta) {
        $facturations = $this->getVentes($idCompta);
        $nouveauTotal = 0;

        foreach($facturations as $facturation) {
            $nouveauTotal += $facturation["prix_total"];
        }

        $this->model->update("Compta", ["chiffre_daffaire"=>$nouveauTotal], ["id_compta"=>$idCompta]);
        $this->calculerNouveauResultat($idCompta);
    }

    public function calculerNouveauMontant($idCompta) {
        $achats = $this->getAchats($idCompta);
        $nouveauTotal = 0;

        foreach($achats as $achat) {
            if($achat["statut"]) {
                $nouveauTotal += $achat["montant"];
            }
        }

        $this->model->update("Compta", ["montant"=>$nouveauTotal], ["id_compta"=>$idCompta]);
        $this->calculerNouveauResultat($idCompta);
    }

    public function calculerNouveauResultat($idCompta) {
        $compta = $this->getComptaById($idCompta);
        $montant = $compta["montant"];
        $chiffreDaffaire = $compta["chiffre_daffaire"];
        $nouveauTotal = $chiffreDaffaire-$montant;

        $this->model->update("Compta", ["resultat"=>$nouveauTotal], ["id_compta"=>$idCompta]);
    }

    public function retirerAchat($idAchat) {
        $data = ['statut' => 0];
        $where = ['id_achat' => $idAchat];
        $this->model->update('Achats', $data, $where);
        $this->calculerNouveauMontant($this->getLastCompta());
    }

    public function restaurerAchat($idAchat) {
        $data = ['statut' => 1];
        $where = ['id_achat' => $idAchat];
        $this->model->update('Achats', $data, $where);
        $this->calculerNouveauMontant($this->getLastCompta());
    }
}
?>
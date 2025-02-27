<?php
namespace PROJECT\models;
use PROJECT\models\ProduitModel;


class PanierModel {
        
    public function __construct() {
        self::initPanier(); // Appelle initPanier pour initialiser dès la création
    }
    
    // Méthode pour initialiser la session et le panier
    private static function initPanier() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['panier'])) {
            $_SESSION['panier'] = [];
        }
    }
    
    public static function getQuantiteProduit($idProduit) {
        self::initPanier();
        if (isset($_SESSION['panier'][$idProduit])) {
            return $_SESSION['panier'][$idProduit];
        }
    }
    
    public static function ajouterProduit($idProduit, $quantite) {
        self::initPanier();
        if (isset($_SESSION['panier'][$idProduit])) {
            $_SESSION['panier'][$idProduit] += $quantite;
        } else {
            $_SESSION['panier'][$idProduit] = $quantite;
        }
    }
    
    public static function supprimerProduit($idProduit) {
        self::initPanier();
        if (isset($_SESSION['panier'][$idProduit])) {
            unset($_SESSION['panier'][$idProduit]);
        }
    }
    
    public static function modifierQuantite($idProduit, $quantite) {
        self::initPanier();
        if ($quantite > 0) {
            $_SESSION['panier'][$idProduit] = $quantite;
        } else {
            self::supprimerProduit($idProduit); // Si quantité <= 0, on supprime le produit
        }
    }
    
    public static function viderPanier() {
        self::initPanier();
        $_SESSION['panier'] = [];
    }
    
    public static function obtenirPanier() {
        self::initPanier();
        return $_SESSION['panier'];
    }
    
    public static function calculerTotal() {
        self::initPanier();
        $modelProduit = new ProduitModel();
        $total = 0;
        foreach ($_SESSION['panier'] as $idProduit => $quantite) {
            $donnesProduit = $modelProduit->getContenuById($idProduit);
            $prix = $donnesProduit['prix_public'];
            $total += $prix * $quantite;
        }
        return $total;
    }
}
?>

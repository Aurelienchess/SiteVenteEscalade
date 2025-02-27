<?php
namespace PROJECT\models;
use PROJECT\models\ProduitModel;
use PROJECT\models\Model;


class ArticlesDisposModel {
    
    public function __construct() {
        self::initListArticles();
        self::initListPaginee();
    }

    // Méthode pour initialiser la session et la liste d'articles
    public static function initListArticles() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['listArticles'])) {
            require_once __DIR__ . '/../models/ProduitModel.php';
            $produitModel = new ProduitModel();
            $allProduits = $produitModel->getAllProduit();
            $list = self::enleverArticlesNonVendables($allProduits);
            $_SESSION['listArticles'] = $list;
        }
    }

    public static function enleverArticlesNonVendables($listProduits) {
        $result = [];
        foreach($listProduits as $produit) {
            if ($produit["vendable"]) {
                $result[] = $produit;
            }
        }
        return $result;
    }

    public static function setListArticles($produits=[]) {
        self::initListArticles();

        if(empty($produits)) {
            require_once __DIR__ . '/../models/ProduitModel.php';
            $produitModel = new ProduitModel();
            $produits=$produitModel->getAllProduit();
        }
        
        $nouvelleListe = self::enleverArticlesNonVendables($produits);
        $_SESSION['listArticles'] = $nouvelleListe;
        return $nouvelleListe;
    }

    public static function getListArticles() {
        self::initListArticles();
        return $_SESSION["listArticles"];
    }

    public static function initListPaginee() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['listPaginee'])) {
            $_SESSION['listPaginee'] = array_slice(self::getListArticles(),0,self::getNbPagine());
        }
    }

    public static function getListPaginee() {
        self::initListPaginee();
        return $_SESSION["listPaginee"];
    }

    public static function changePage($page) {
        self::initListPaginee();
        $offset = ($page-1) * self::getNbPagine();
        $_SESSION['listPaginee'] = array_slice(self::getListArticles(), $offset, self::getNbPagine());
    }

    public static function getNbPagine() {
        return 6;
    }
}
?>
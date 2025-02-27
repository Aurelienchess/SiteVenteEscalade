<?php

namespace PROJECT\views;

use PROJECT\models\ProduitModel;
use PROJECT\models\GestionStockModel;

require_once __DIR__ . '/../models/ProduitModel.php';
require_once __DIR__ . '/../models/GestionStockModel.php';


class MailVue {
    
    private $modelProduit;
    private $gestionStockModel;
    
    public function __construct() {
        $this->modelProduit = new ProduitModel();
        $this->gestionStockModel = new GestionStockModel();
    }    
    
    public function getEmailClient($panierValide, $idFacturation, $date, $nomClient, $prenomClient, $adresseClient, $prixTotal) {
        $detailArticles = $this->generateDetailArticles($panierValide);
        $prixTotalHTC = $prixTotal*0.80;
        
        return "
            <html>
            <head>
                <title>Confirmation de commande</title>
                <style>
                    body { font-family: Arial, sans-serif; font-size: 16px; color: #333; }
                    .container { width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ccc; background-color: #f9f9f9; }
                    .header { text-align: center; margin-bottom: 20px; }
                    .order-details { margin-top: 20px; }
                    .order-details table { width: 100%; border-collapse: collapse; }
                    .order-details th, .order-details td { padding: 10px; border: 1px solid #ddd; }
                    .total { font-weight: bold; }
                    .footer { margin-top: 30px; text-align: center; font-size: 14px; color: #888; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h2>Merci pour votre commande !</h2>
                        <p>Votre commande a été confirmée et sera livrée dans les plus brefs délais.</p>
                    </div>
                    <div class='order-details'>
                        <h3>Récapitulatif de votre commande</h3>
                        <p><strong>Numéro de commande :</strong> $idFacturation</p>
                        <p><strong>Date de commande :</strong> $date</p>
            
                        <h4>Détails des articles :</h4>
                        <table>
                            <tr>
                                    <th>Articles</th>
                                    <th>Quantite</th>
                                    <th>Prix (hors-taxes)</th>
                                    <th>Prix</th>
                                    <th>Total</th>
                            </tr>
                            $detailArticles
                        </table>
                        <p class='total'>Total de la commande (taxes non-comprises) : $prixTotalHTC €</p>
                        <p class='total'>Total de la commande (taxes comprises) : $prixTotal €</p>
                    </div>
                    <div class='shipping-info'>
                        <h4>Adresse de livraison :</h4>
                        <p>$nomClient $prenomClient</p>
                        <p>$adresseClient</p>
                    </div>
                </div>
            </body>
            </html>
        ";
    }
    
    public function getMailAdmin($panierValide, $idFacturation, $date, $prixTotal, $nomClient, $prenomClient, $adresseClient) {
        $detailArticles = $this->generateDetailArticles($panierValide);
        $prixTotalHTC = $prixTotal*0.80;
        return "
            <html>
            <head>
                <title>Nouvelle commande reçue</title>
                <style>
                    body { font-family: Arial, sans-serif; font-size: 16px; color: #333; }
                    .container { width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ccc; background-color: #f9f9f9; }
                    .header { text-align: center; margin-bottom: 20px; }
                    .order-details { margin-top: 20px; }
                    .order-details table { width: 100%; border-collapse: collapse; }
                    .order-details th, .order-details td { padding: 10px; border: 1px solid #ddd; }
                    .total { font-weight: bold; }
                    .footer { margin-top: 30px; text-align: center; font-size: 14px; color: #888; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h2>Nouvelle commande reçue</h2>
                        <p>Une commande a été passée par $prenomClient $nomClient.</p>
                    </div>
                    <div class='order-details'>
                        <h3>Détails de la commande</h3>
                        <p><strong>Numéro de commande :</strong> $idFacturation</p>
                        <p><strong>Date de commande :</strong> $date</p>
            
                        <h4>Articles commandés :</h4>
                        <table>
                            <tr>
                                    <th>Articles</th>
                                    <th>Quantite</th>
                                    <th>Prix(hors-taxes)</th>
                                    <th>Prix</th>
                                    <th>Total</th>
                            </tr>
                            $detailArticles
                        </table>
                        <p class='total'>Total de la commande (taxes non-comprises) : $prixTotalHTC"."€</p>
                        <p class='total'>Total de la commande (taxes comprises) : $prixTotal"."€</p>
                    </div>
                    <div class='shipping-info'>
                        <h4>Adresse de livraison :</h4>
                        <p>$nomClient $prenomClient</p>
                        <p>$adresseClient</p>
                    </div>
                </div>
            </body>
            </html>
        ";
    }

    public function getMailRupture($panierValide) {
        $detailArticles = $this->generateDetailArticles($panierValide);
        $articlesEnRupture = "";

        // Parcours des articles pour vérifier les ruptures de stock
        foreach ($panierValide as $idProduit => $quantite) {
            // Vérification du stock
            if (!$this->gestionStockModel->isInStock($idProduit)) {
                $produitInfos = $this->modelProduit->getContenuById($idProduit);
                $articlesEnRupture .= "<tr>";
                $articlesEnRupture .= "<td>" . htmlspecialchars($produitInfos["titre"]) . "</td>";
                $articlesEnRupture .= "<td>" . $quantite . "</td>";
                $articlesEnRupture .= "</tr>";
            }
        }

    // Si aucun article en rupture, pas besoin d'envoyer ce mail
    if (empty($articlesEnRupture)) {
        return null;
    }
        
        return "
        <html>
        <head>
            <title>Rupture de stock - Notification</title>
            <style>
                body { font-family: Arial, sans-serif; font-size: 16px; color: #333; }
                .container { width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ccc; background-color: #f9f9f9; }
                .header { text-align: center; margin-bottom: 20px; }
                .details { margin-top: 20px; }
                .details table { width: 100%; border-collapse: collapse; }
                .details th, .details td { padding: 10px; border: 1px solid #ddd; }
                .footer { margin-top: 30px; text-align: center; font-size: 14px; color: #888; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>Notification de rupture de stock</h2>
                    <p>Des articles sont en rupture de stock suite à une commande.</p>
                </div>
                <div class='details'>
                    <h3>Détails des articles en rupture :</h3>
                    <table>
                        <tr>
                            <th>Article</th>
                            <th>Quantité commandée</th>
                        </tr>
                        $articlesEnRupture
                    </table>
                </div>
                <div class='footer'>
                    <p>Merci de vérifier les stocks et de prendre les mesures nécessaires.</p>
                </div>
            </div>
        </body>
        </html>
    ";
    }

    public function getEmailReapro($nomFournisseur, $date, $article, $prixArticle, $quantite, $prixTotal, $nomUser,$prenomUser) {        
        return "
            <html>
            <head>
                <title>Demande de reapprovisionnement</title>
                <style>
                    body { font-family: Arial, sans-serif; font-size: 16px; color: #333; }
                    .container { width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ccc; background-color: #f9f9f9; }
                    .header { text-align: center; margin-bottom: 20px; }
                    .order-details { margin-top: 20px; }
                    .order-details table { width: 100%; border-collapse: collapse; }
                    .order-details th, .order-details td { padding: 10px; border: 1px solid #ddd; }
                    .total { font-weight: bold; }
                    .footer { margin-top: 30px; text-align: center; font-size: 14px; color: #888; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h2>Une demande de réapprovisionnement viens d'être émise</h2>
                        <p></p>
                    </div>
                    <div class='order-details'>
                        <h3>Informations sur l'utilisateur ayant demandé le réapprovisionnement</h3>
                        <p><strong>Nom :</strong> $nomUser</p>
                        <p><strong>Prenom :</strong> $prenomUser</p>
                        <p><strong>Date de demande :</strong> $date</p>
            
                        <h4>Détails du réapprovisionnement :</h4>
                        <table>
                            <tr>
                                    <th>Article</th>
                                    <th>Quantite</th>
                                    <th>Fournisseur</th>
                                    <th>Prix</th>
                            </tr>
                            <tr>
                                <th>$article</th>
                                <th>$quantite</th>
                                <th>$nomFournisseur</th>
                                <th>$prixArticle</th>
                            </tr>
                        </table>
                        <p class='total'>Total du réapprovisionnement : $prixTotal €</p>
                    </div>
                </div>
            </body>
            </html>
        ";
    }
    
    private function generateDetailArticles($panierValide) {
        $detailArticles = "";
        foreach ($panierValide as $idProduit => $quantite) {
            $produitInfos = $this->modelProduit->getContenuById($idProduit);
            $prix = $produitInfos["prix_public"];
            $prixHTC = $produitInfos["prix_HTC"];
            $titre = $produitInfos["titre"];
            $total = $prix * $quantite;
            
            $detailArticles .= "<tr>
                                    <th>$titre</th>
                                    <th>$quantite</th>
                                    <th>$prixHTC"."€</th>
                                    <th>$prix"."€</th>
                                    <th>$total"."€</th>
                                </tr>";
        }
        return $detailArticles;
    }
}

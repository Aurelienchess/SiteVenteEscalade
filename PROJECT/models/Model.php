<?php

namespace PROJECT\models;

use Exception;
use PDO;
use PDOException;

class Model {
    
    private $db;
    
    public function __construct() {
        try {
            $this->db = new PDO('mysql:host=linserv-info-01.campus.unice.fr;dbname=ml306280_çaVaEtreCoutton', 'ml306280', 'ml306280');
        } catch (Exception $e) {
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        }
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
    }
    
    #-----------------------------------#
    #---Gestion de la base de données---#
    #-----------------------------------#
    
    //Exemple d'utilisation de la fonction :   $produits = $model->getAll(
                                            //  'produits',
                                            //  ['categorie' => 'accessoires'],
                                            //  'casque',
                                            //  'prix_public',
                                            //  'DESC',
                                            //  10,
                                            //  20);

    public function getAll($table, $column = "*", $conditions = [], $searchTerm = null, $orderBy = null, $orderDirection = 'ASC', $limit = null, $offset = null) {
        $query = "SELECT * FROM $table";

        $whereClauses = [];

        // Ajout des conditions WHERE
        if (!empty($conditions)) {
            foreach ($conditions as $column => $value) {
                $whereClauses[] = "$column = :$column";
            }
        }

        // Ajout de la recherche textuelle
        if ($searchTerm) {
            $whereClauses[] = "titre LIKE :searchTerm";
        }

        if (!empty($whereClauses)) {
            $query .= " WHERE " . implode(' AND ', $whereClauses);
        }

        // Ajout de ORDER BY
        if ($orderBy) {
            $query .= " ORDER BY $orderBy $orderDirection";
        }

        // Ajout de LIMIT et OFFSET
        if ($limit !== null) {
            $query .= " LIMIT :limit";
            if ($offset !== null) {
                $query .= " OFFSET :offset";
            }
        }

        $stmt = $this->db->prepare($query);

        // Liaison des paramètres
        foreach ($conditions as $column => $value) {
            $stmt->bindValue(":$column", $value);
        }
        if ($searchTerm) {
            $stmt->bindValue(':searchTerm', '%' . $searchTerm . '%');
        }
        if ($limit !== null) {
            $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        }
        if ($offset !== null) {
            $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    

    
    #insert classique en SQL. On précise en arguments la table et les données qu'on va insérer. Les données devront avoir la forme : [colonne1 => valeur1, colonne2 => valeur2]
    public function insert($table, $data) {
        try {
            $columns = implode(", ", array_keys($data));
            $placeholders = ":" . implode(", :", array_keys($data));
            $query = $this->db->prepare("INSERT INTO $table ($columns) VALUES ($placeholders)");
            $query->execute($data);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
            return "Erreur : " . $e->getMessage();
        }
    }
    
    #update classique en SQL. On a évidemment le nom de la table en argument. Puis les données qu'on va insérer et la condition (ici limitée à ==) et les deux sous la forme [clé => valeur]
    public function update($table, $data, $where) {
        try {
            $fields = "";
            foreach ($data as $key => $value) {
                $fields .= "$key = :$key, ";
            }
            $fields = rtrim($fields, ", ");
            
            $whereClause = "";
            foreach ($where as $key => $value) {
                $whereClause .= "$key = :where_$key AND ";
            }
            $whereClause = rtrim($whereClause, " AND ");
            
            $query = $this->db->prepare("UPDATE $table SET $fields WHERE $whereClause");
            foreach ($data as $key => $value) {
                $query->bindValue(":$key", $value);
            }
            foreach ($where as $key => $value) {
                $query->bindValue(":where_$key", $value);
            }
            return $query->execute();
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
            return false;
        }
    }
    
    #delete classique en SQL. En arguments, le nom de table et les conditions, sachant que qu'on est limité à == et avec une forme de [clé => valeur]
    public function delete($table, $where) {
        try {
            $whereClause = "";
            foreach ($where as $key => $value) {
                $whereClause .= "$key = :$key AND ";
            }
            $whereClause = rtrim($whereClause, " AND ");
            
            $query = $this->db->prepare("DELETE FROM $table WHERE $whereClause");
            foreach ($where as $key => $value) {
                $query->bindValue(":$key", $value);
            }
            return $query->execute();
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
            return false;
        }
    }
    
    #-----------------------------------#
    #--------Gestion des cookies--------#
    #-----------------------------------#
    
    #Définir un cookie pour l'utilisateur
    public function setUserCookie($userId, $password, $duration = 1) {
        $this->clearUserCookie();
        $expiry = time() + (3600*$duration); #attention : on exprime ici la durée en heures
        setcookie('user_id', $userId, $expiry, "/");
        setcookie('user_password', $password, $expiry, "/");
    }
    
    #Récupérer les informations utilisateur depuis les cookies
    public function getUserCookie() {
        if (isset($_COOKIE['user_id']) && isset($_COOKIE['user_password'])) {
            return [
                'id' => $_COOKIE['user_id'],
                'password' => $_COOKIE['user_password']
            ];
        }
        return null;
    }
    
    #Supprimer les cookies utilisateur
    public function clearUserCookie() {
        setcookie('user_id', '', time() - 3600, "/");
        setcookie('user_password', '', time() - 3600, "/");
    }
    
    #-----------------------------------#
    #---Gestion des variables session---#
    #-----------------------------------#
    
    #Premiere valeur sauvegardée : l'ID de l'utilisateur actuel (pour se référer à la table client si besoin de ses données)
    
    #Récupérer les informations de l'utilisateur
    public function getUserID() {
        return isset($_SESSION['user']) ? $_SESSION['user'] : null;
    }
    
    #Définir les informations de l'utilisateur
    public function setUserID($userId) {
        $_SESSION['user'] = $userId;
    }
    
    #Supprimer les données utilisateur (déconnexion)
    public function clearUserData() {
        unset($_SESSION['user']);
    }
    
    #Deuxième valeur sauvegardée : le panier de l'utilisateur actuel de la forme [idProduit => quantité]
    
    #Ajouter ou mettre à jour un produit dans le panier
    public function addToCart($productId, $quantity) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        
        #On part du principe que le client choisit une valeur précise, dont on met à jour la quantité ou on rajoute le produit
        $_SESSION['cart'][$productId] = $quantity;
    }
    
    #Récupérer le panier complet
    public function getCart() {
        return isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
    }
    
    #Supprimer un produit du panier, s'il existe
    public function removeFromCart($productId) {
        if (isset($_SESSION['cart'][$productId])) {
            unset($_SESSION['cart'][$productId]);
            return true;
        }
        return false;
    }
    
    #Vider le panier
    public function clearCart() {
        unset($_SESSION['cart']);
    }
    
}
?>
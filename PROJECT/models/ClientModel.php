<?php

namespace PROJECT\models;
require_once __DIR__ . '/../models/Model.php';


class ClientModel {
    
    private $model;
    
    public function __construct() {
        $this->model = new Model();
    }
    
    public function getId($email) {
        $clients = $this->model->getAll("Client", "id, email, mdp");
        
        if ($clients === false) {
            return false;
        }
        
        foreach ($clients as $client) {
            if ($client['email'] === $email) {
                return $client["id"];
            }
        }
        
        return false;
    }
    
    public function getById($id) {
        $clients = $this->model->getAll("Client");
        if ($clients === false) {
            return false;
        }
        
        foreach ($clients as $client) {
            if ($client["id"] === $_SESSION['user']) {
                return $client;
            }
        }
        
        return false;
    }
    
    public function setId($userId) {
        $_SESSION['user'] = $userId;
    }
    
    public function nouveauClient($email,$mdp,$nom,$prenom,$adresse, $admin){
        if($this->model->insert("Client", ["nom"=>$nom, "prenom"=>$prenom, "email"=>$email, "adresse"=>$adresse, "mdp"=>$mdp, "admin"=>$admin])) {
            return $this->getId($email);
        }
        return false;
    }
    
    function verifierConnexion($email, $mdp) {
        $clients = $this->model->getAll("Client", "id, email, mdp");
        
        if ($clients === false) {
            return false;
        }
        
        foreach ($clients as $client) {
            if ($client["email"] === $email) {
                if ($client["mdp"] === $mdp) {
                    return $client["id"];
                }
            }
        }
        
        return false;
    }
    
    public function setCookie($email,$mdp) {
        $this->model->setUserCookie($email,$mdp);
    }
    
    public function isDefinedCookie() {
        if (null !== $this->model->getUserCookie()) {
            return true;
        }
        return false;
    }
    
    public function getUserNameCookie() {
        if ($this->isDefinedCookie()) {
            $cookies = $this->model->getUserCookie();
            return $cookies["id"];
        }
        else {
            return "";
        }
    }
    
    public function getMdpCookie() {
        if ($this->isDefinedCookie()) {
            $cookies = $this->model->getUserCookie();
            return $cookies["password"];
        }
        else {
            return "";
        }
    }
    
    //Fonction pour vérifier si les données que l'utilisateur n'a pas déjà un compte (en se basant sur son adresse email)
    public function verifierUnicité($email) {
        $clientsBD = $this->model->getAll("Client", "email");
        
        if ($clientsBD === false) {
            return false;
        }
        
        foreach ($clientsBD as $client) {
            if ($client['email'] === $email) {
                return false;
            }
        }
        
        return true;
    }
    
    public function isAdmin($id) {
        $client = $this->getById("Client", $id);
        return $client['admin'];
    }

    public function getAdmin() {
        // On récupère tous les clients depuis la base de données
        $clients = $this->model->getAll("Client");
        
        if ($clients === false) {
            return false;
        }
        
        $admins = [];
        
        // On filtre les clients pour ne récupérer que ceux qui sont admins
        foreach ($clients as $client) {
            if ($client['admin'] == 1) { // Si l'attribut admin vaut 1, c'est un admin
                $admins[] = $client; // On ajoute l'admin à la liste
            }
        }
        
        // Si la liste des admins est vide, on retourne false
        if (empty($admins)) {
            return false;
        }
        
        return $admins; // Retourne la liste des admins
    }
    
}
?>
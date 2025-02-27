CREATE TABLE IF NOT EXISTS Client (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255),
    prenom VARCHAR(255),
    email VARCHAR(255),
    mdp VARCHAR(255),
    adresse VARCHAR(255),
    admin BOOLEAN DEFAULT FALSE
);

CREATE TABLE IF NOT EXISTS Fournisseur (
    id_fournisseur INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255),
    contact VARCHAR(255),
    statut BOOLEAN DEFAULT TRUE
);

CREATE TABLE IF NOT EXISTS Produit (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reference VARCHAR(255),
    prix_public DECIMAL(10, 2),
    prix_achat DECIMAL(10, 2),
    titre VARCHAR(255),
    descriptif TEXT,
    image VARCHAR(255),
    prix_HTC DECIMAL(10,2),
    categorie VARCHAR(255),
    vendable BOOLEAN DEFAULT TRUE
);

CREATE TABLE IF NOT EXISTS Produits_Vendus (
    id_fournisseur INT,
    id_produit INT,
    FOREIGN KEY (id_fournisseur) REFERENCES Fournisseur(id_fournisseur),
    FOREIGN KEY (id_produit) REFERENCES Produit(id)
);

CREATE TABLE IF NOT EXISTS Gestion_Stock (
    id_produit INT PRIMARY KEY,
    quantite INT,
    seuil_critique BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (id_produit) REFERENCES Produit(id)
);

CREATE TABLE IF NOT EXISTS Compta (
    id_compta INT AUTO_INCREMENT PRIMARY KEY,
    chiffre_daffaire DECIMAL(10, 2),
    montant DECIMAL(10, 2),
    resultat DECIMAL(10,2),
    date_creation DATE
);

CREATE TABLE IF NOT EXISTS Facturation (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_client INT,
    date_creation DATE,
    prix_total DECIMAL(10, 2),
    id_compta INT,
    FOREIGN KEY (id_compta) REFERENCES Compta(id_compta),
    FOREIGN KEY (id_client) REFERENCES Client(id)
);

CREATE TABLE IF NOT EXISTS Panier (
    id_produit INT,
    id_facturation INT,
    quantite INT,
    prix DECIMAL(10, 2),
    PRIMARY KEY (id_produit, id_facturation),
    FOREIGN KEY (id_produit) REFERENCES Produit(id),
    FOREIGN KEY (id_facturation) REFERENCES Facturation(id)
);

CREATE TABLE IF NOT EXISTS Achats (
    id_achat INT AUTO_INCREMENT PRIMARY KEY,
    id_compta int,
    id_produit int,
    id_fournisseur int,
    quantite int,
    montant decimal(10,2),
    date_achat DATE,
    statut BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (id_compta) REFERENCES Compta(id_compta),
    FOREIGN KEY (id_produit) REFERENCES Produit(id),
    FOREIGN KEY (id_fournisseur) REFERENCES Fournisseur(id_fournisseur)
);

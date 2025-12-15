CREATE DATABASE Ecomobil;
use Ecomobil;

CREATE TABLE Tarif (
    id_Tarif INT PRIMARY KEY AUTO_INCREMENT,
    Tarif_Horaire Decimal(8,2),
    Tarif_Demi_Journee Decimal(8,2),
    Tarif_Journee decimal(8,2)
);

CREATE TABLE Type_Vehicule (
    id_Type_Vehicule INT PRIMARY KEY AUTO_INCREMENT,
    libelle_Type varchar(50),
    nb_place int,
    id_Tarif int,
    FOREIGN KEY (id_Tarif) REFERENCES Tarif(id_Tarif)
);

CREATE TABLE Agence_location (
    id_Agence INT PRIMARY KEY AUTO_INCREMENT,
    nom_Agence VARCHAR(100),
    Adresse VARCHAR(255),
    Telephone VARCHAR(15),
    Mail VARCHAR(100),
    Heure_ouverture TIME
);

CREATE TABLE Vehicule (
    id_Vehicule INT PRIMARY KEY AUTO_INCREMENT,
    num_serie VARCHAR(50) unique,
    Marque VARCHAR(50),
    Modele VARCHAR(50),
    Annee INT,
    Statut VARCHAR(20),
    id_type_vehicule INT,
    id_agence INT,
    FOREIGN KEY (id_type_Vehicule) REFERENCES type_Vehicule(id_type_Vehicule),
    FOREIGN KEY (id_agence) REFERENCES Agence_location(id_agence)
);

CREATE TABLE Client_connecter (
    id_Client INT PRIMARY KEY AUTO_INCREMENT,
    Nom VARCHAR(50),
    Prenom VARCHAR(50),
    Telephone VARCHAR(15),
    Adresse VARCHAR(255),
    Mail VARCHAR(100),
    Mot_de_Passe_Securiser VARCHAR(255),
    Date_de_Creation DATETIME
);

CREATE TABLE Participants (
	id_Participants INT PRIMARY KEY AUTO_INCREMENT,
    Nom VARCHAR(50),
    Prenom VARCHAR(50),
    id_reservation INT,
    id_vehicule INT,
    FOREIGN KEY (id_Reservation) REFERENCES Reservation(id_Reservation),
    FOREIGN KEY (id_vehicule) REFERENCES vehicule(id_vehicule)
);
    
    
CREATE TABLE Reservation (
    id_Reservation INT PRIMARY KEY AUTO_INCREMENT,
    Date_Reservation DATETIME,
    Duree INT,
    Demande_speciale TEXT,
    date_debut_location DATETIME,
    montant_totale Decimal(10,2),
    date_fin_location DATETIME,
    statut_reservation VARCHAR(20),
    id_client INT,
    id_vehicule INT,
    id_tarif INT,
    FOREIGN KEY (id_Client) REFERENCES Client_connecter(id_Client),
    FOREIGN KEY (id_Vehicule) REFERENCES Vehicule(id_Vehicule),
    FOREIGN KEY (id_Tarif) REFERENCES Tarif(id_Tarif)
);

CREATE TABLE Logs (
    id_Log INT AUTO_INCREMENT PRIMARY KEY,
    message VARCHAR(255) NOT NULL,
    date_log DATETIME DEFAULT CURRENT_TIMESTAMP
);
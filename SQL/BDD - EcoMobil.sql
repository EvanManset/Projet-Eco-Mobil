drop database airsio;
create database ecomobil;
use ecomobil;
-- table tarif
create table tarif (
    id_tarif int primary key auto_increment,
    tarif_horaire decimal(8,2),
    tarif_demi_journee decimal(8,2),
    tarif_journee decimal(8,2)
);

-- table type vehicule
create table type_vehicule (
    id_type_vehicule int primary key auto_increment,
    libelle_type varchar(50),
    nb_place int,
    id_tarif int,
    foreign key (id_tarif) references tarif(id_tarif)
);

-- table agence de location
create table agence_location (
    id_agence int primary key auto_increment,
    nom_agence varchar(100),
    adresse varchar(255),
    telephone varchar(15),
    mail varchar(100),
    heure_ouverture time
);

-- table vehicule
create table vehicule (
    id_vehicule int primary key auto_increment,
    num_serie varchar(50) unique,
    marque varchar(50),
    modele varchar(50),
    annee int,
    statut varchar(20),
    id_type_vehicule int,
    id_agence int,
    foreign key (id_type_vehicule) references type_vehicule(id_type_vehicule),
    foreign key (id_agence) references agence_location(id_agence)
);

-- table client connecter
create table client_connecter (
    id_client int primary key auto_increment,
    nom varchar(50),
    prenom varchar(50),
    telephone varchar(15),
    adresse varchar(255),
    mail varchar(100),
    mot_de_passe_securiser varchar(255),
    date_de_creation datetime
);

-- table reservation
create table reservation (
    id_reservation int primary key auto_increment,
    date_reservation datetime,
    duree int,
    demande_speciale text,
    date_debut_location datetime,
    montant_totale decimal(10,2),
    date_fin_location datetime,
    statut_reservation varchar(20),
    id_client int,
    id_vehicule int,
    id_tarif int,
    foreign key (id_client) references client_connecter(id_client),
    foreign key (id_vehicule) references vehicule(id_vehicule),
    foreign key (id_tarif) references tarif(id_tarif)
);

-- table participants
create table participants (
    id_participants int primary key auto_increment,
    nom varchar(50),
    prenom varchar(50),
    age int,
    id_reservation int,
    foreign key (id_reservation) references reservation(id_reservation)
);
<?php
// Model.php

// 1. Connexion à la base de données
function dbconnect()
{
    try {
        $bdd = new PDO('mysql:host=localhost;dbname=Ecomobil;charset=utf8', 'root', '',
            array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        return $bdd;
    } catch (Exception $e) {
        die('Erreur de connexion : ' . $e->getMessage());
    }
}

// Vérifie si un email existe (retourne true/false)
function EmailExists($Mail) {
    $bdd = dbconnect();
    // COUNT(*) est plus rapide que de tout sélectionner
    $req = "SELECT COUNT(*) AS count FROM client_connecter WHERE Mail = :mail";
    $res = $bdd->prepare($req);
    $res->execute(['mail' => $Mail]);
    $row = $res->fetch(PDO::FETCH_ASSOC);
    return $row['count'] > 0;
}

// Ajoute un utilisateur
function AddUser($Mail, $MdpHash, $Nom, $Prenom, $Telephone, $Adresse) {
    $bdd = dbconnect();
    // Requête préparée (INSERT) : Les :mail, :mdp, etc. sont des marqueurs sécurisés
    $req = "INSERT INTO client_connecter (Mail, Mot_de_Passe_Securiser, Nom, Prenom, Telephone, Adresse, Date_de_Creation) 
            VALUES (:mail, :mdp, :nom, :prenom, :tel, :adr, NOW())";
    $res = $bdd->prepare($req);
    // Exécution avec le tableau de données correspondant aux marqueurs
    return $res->execute([
        'mail' => $Mail,
        'mdp' => $MdpHash,
        'nom' => $Nom,
        'prenom' => $Prenom,
        'tel' => $Telephone,
        'adr' => $Adresse
    ]);
}

// Récupère toutes les infos d'un utilisateur
function GetUserByMail($Mail)
{
    $bdd = dbconnect();
    $req = "SELECT * FROM client_connecter WHERE Mail = :mail";
    $res = $bdd->prepare($req);
    $res->execute(['mail' => $Mail]);
    return $res->fetch(PDO::FETCH_ASSOC); // Retourne un tableau associatif ou false
}

// Récupère juste l'ID (plus léger quand on n'a pas besoin du reste)
function getIdClientByMail($mail)
{
    $bdd = dbconnect();
    $req = "SELECT id_Client FROM client_connecter WHERE Mail = :mail";
    $res = $bdd->prepare($req);
    $res->execute(['mail' => $mail]);
    $row = $res->fetch(PDO::FETCH_ASSOC);
    return $row ? $row['id_Client'] : null;
}

// Récupère le tarif horaire
function GetPrixTarif($id_tarif) {
    $bdd = dbconnect();
    $req = "SELECT Tarif_Horaire FROM tarif WHERE id_Tarif = :id";
    $res = $bdd->prepare($req);
    $res->execute(['id' => $id_tarif]);
    $row = $res->fetch(PDO::FETCH_ASSOC);
    // floatval assure qu'on retourne bien un nombre à virgule
    return $row ? floatval($row['Tarif_Horaire']) : 0.0;
}

// ====================================================================
// Algorithme complexe : Trouver un véhicule
// 1. Sélectionne tous les véhicules du bon type et de la bonne agence
// 2. Boucle sur chaque véhicule pour voir s'il est libre aux dates demandées
// ====================================================================
function FindVehiculeDisponible($nom_agence, $libelle_type, $date_debut, $date_fin)
{
    $bdd = dbconnect();
    // Gestion du formatage (ex: "Berline_Luxe" -> "Berline Luxe")
    $libelle_type = str_replace('_', ' ', $libelle_type);

    // Jointure SQL pour relier Vehicule -> Agence et Vehicule -> Type
    $req = "SELECT v.id_Vehicule, tv.id_Tarif
            FROM vehicule v
            INNER JOIN agence_location a ON v.id_agence = a.id_Agence
            INNER JOIN type_vehicule tv ON v.id_type_vehicule = tv.id_Type_Vehicule
            WHERE a.nom_Agence = :nom_agence 
            AND tv.libelle_Type = :libelle_type
            AND v.statut = 'disponible'"; // On ne prend que les véhicules physiquement 'disponibles'

    $res = $bdd->prepare($req);
    $res->execute(['nom_agence' => $nom_agence, 'libelle_type' => $libelle_type]);
    $vehicules = $res->fetchAll(PDO::FETCH_ASSOC);

    if (empty($vehicules)) return null; // Aucun véhicule de ce type dans cette agence

    // Pour chaque véhicule trouvé, on vérifie son calendrier de réservations
    foreach ($vehicules as $vehicule) {
        if (IsVehiculeLibre($vehicule['id_Vehicule'], $date_debut, $date_fin)) {
            return $vehicule; // On retourne le premier qui est libre
        }
    }
    return null; // Tous occupés
}

// Vérifie si un véhicule précis a des conflits de réservation
function IsVehiculeLibre($id_vehicule, $date_debut, $date_fin)
{
    $bdd = dbconnect();
    // Logique de chevauchement de dates :
    // Une réservation existe SI (Debut_Existant < Fin_Demandée) ET (Fin_Existante > Debut_Demandé)
    $req = "SELECT COUNT(*) as nb FROM reservation
            WHERE id_vehicule = :id_vehicule
            AND statut_reservation IN ('Réservée', 'En cours', 'confirmée')
            AND ((date_debut_location < :date_fin AND date_fin_location > :date_debut))";
    $res = $bdd->prepare($req);
    $res->execute(['id_vehicule' => $id_vehicule, 'date_debut' => $date_debut, 'date_fin' => $date_fin]);
    $row = $res->fetch(PDO::FETCH_ASSOC);

    // Si nb == 0, aucune réservation ne chevauche, donc c'est libre.
    return $row['nb'] == 0;
}

// Enregistre la réservation
function AddReservation($id_client, $id_vehicule, $id_tarif, $debut, $fin, $duree, $speciale, $montant)
{
    $bdd = dbconnect();
    $req = "INSERT INTO reservation (Date_Reservation, Duree, Demande_speciale, date_debut_location, montant_totale, date_fin_location, statut_reservation, id_client, id_vehicule, id_tarif) 
            VALUES (NOW(), :duree, :speciale, :debut, :montant, :fin, 'Réservée', :client, :vehicule, :tarif)";
    $res = $bdd->prepare($req);
    return $res->execute([
        'duree' => $duree, 'speciale' => $speciale, 'debut' => $debut,
        'montant' => $montant, 'fin' => $fin, 'client' => $id_client,
        'vehicule' => $id_vehicule, 'tarif' => $id_tarif
    ]);
}
?>
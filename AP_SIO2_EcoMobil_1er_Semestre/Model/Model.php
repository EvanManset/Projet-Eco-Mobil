<?php

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

// ---------------------------------------------------------
// GESTION UTILISATEURS
// ---------------------------------------------------------

function EmailExists($Mail) {
    $bdd = dbconnect();
    $req = "SELECT COUNT(*) AS count FROM client_connecter WHERE Mail = :mail";
    $res = $bdd->prepare($req);
    $res->execute(['mail' => $Mail]);
    $row = $res->fetch(PDO::FETCH_ASSOC);
    return $row['count'] > 0;
}

function AddUser($Mail, $MdpHash, $Nom, $Prenom, $Telephone, $Adresse) {
    $bdd = dbconnect();
    $req = "INSERT INTO client_connecter (Mail, Mot_de_Passe_Securiser, Nom, Prenom, Telephone, Adresse, Date_de_Creation) 
            VALUES (:mail, :mdp, :nom, :prenom, :tel, :adr, NOW())";
    $res = $bdd->prepare($req);
    return $res->execute([
        'mail' => $Mail,
        'mdp' => $MdpHash,
        'nom' => $Nom,
        'prenom' => $Prenom,
        'tel' => $Telephone,
        'adr' => $Adresse
    ]);
}

function UpdateUserPassword($Mail, $NewMdpHash) {
    $bdd = dbconnect();
    $req = "UPDATE client_connecter SET Mot_de_Passe_Securiser = :mdp WHERE Mail = :mail";
    $res = $bdd->prepare($req);
    return $res->execute(['mdp' => $NewMdpHash, 'mail' => $Mail]);
}

function GetUserByMail($Mail)
{
    $bdd = dbconnect();
    $req = "SELECT * FROM client_connecter WHERE Mail = :mail";
    $res = $bdd->prepare($req);
    $res->execute(['mail' => $Mail]);
    return $res->fetch(PDO::FETCH_ASSOC);
}

function getIdClientByMail($mail)
{
    $bdd = dbconnect();
    $req = "SELECT id_Client FROM client_connecter WHERE Mail = :mail";
    $res = $bdd->prepare($req);
    $res->execute(['mail' => $mail]);
    $row = $res->fetch(PDO::FETCH_ASSOC);
    return $row ? $row['id_Client'] : null;
}

// ---------------------------------------------------------
// GESTION RÉSERVATIONS
// ---------------------------------------------------------

function GetPrixTarif($id_tarif) {
    $bdd = dbconnect();
    $req = "SELECT Tarif_Horaire FROM tarif WHERE id_Tarif = :id";
    $res = $bdd->prepare($req);
    $res->execute(['id' => $id_tarif]);
    $row = $res->fetch(PDO::FETCH_ASSOC);
    return $row ? floatval($row['Tarif_Horaire']) : 0.0;
}

// Ajoute la réservation principale (SANS véhicule)
function AddReservation($id_client, $id_tarif, $debut, $fin, $duree, $speciale, $montant)
{
    $bdd = dbconnect();
    $req = "INSERT INTO reservation (Date_Reservation, Duree, Demande_speciale, date_debut_location, montant_totale, date_fin_location, statut_reservation, id_client, id_tarif) 
            VALUES (NOW(), :duree, :speciale, :debut, :montant, :fin, 'Réservée', :client, :tarif)";
    $res = $bdd->prepare($req);
    $result = $res->execute([
        'duree' => $duree, 'speciale' => $speciale, 'debut' => $debut,
        'montant' => $montant, 'fin' => $fin, 'client' => $id_client,
        'tarif' => $id_tarif
    ]);

    // Retourne l'ID de la réservation créée ou false
    if ($result) {
        return $bdd->lastInsertId();
    }
    return false;
}

// Ajoute un participant
function AddParticipant($nom, $prenom, $id_reservation, $id_vehicule) {
    $bdd = dbconnect();
    $req = "INSERT INTO Participants (Nom, Prenom, id_reservation, id_vehicule) 
            VALUES (:nom, :prenom, :id_reservation, :id_vehicule)";
    $res = $bdd->prepare($req);
    return $res->execute([
        'nom' => $nom,
        'prenom' => $prenom,
        'id_reservation' => $id_reservation,
        'id_vehicule' => $id_vehicule
    ]);
}

// Récupère l'historique
function GetReservationsClient($id_client)
{
    $bdd = dbconnect();

    $req = "SELECT r.*, 
            a.nom_Agence, 
            tv.libelle_Type,
            COUNT(DISTINCT p.id_Participants) as nb_participants,
            GROUP_CONCAT(CONCAT(v.Marque, ' ', v.Modele) SEPARATOR ', ') as vehicules_list
            FROM reservation r
            LEFT JOIN Participants p ON r.id_Reservation = p.id_reservation
            LEFT JOIN Vehicule v ON p.id_vehicule = v.id_Vehicule
            LEFT JOIN Agence_location a ON v.id_Agence = a.id_Agence
            LEFT JOIN Type_Vehicule tv ON v.id_type_vehicule = tv.id_Type_Vehicule
            WHERE r.id_client = :id_client
            GROUP BY r.id_Reservation
            ORDER BY r.date_debut_location DESC";

    $res = $bdd->prepare($req);
    $res->execute(['id_client' => $id_client]);

    return $res->fetchAll(PDO::FETCH_ASSOC);
}

// ---------------------------------------------------------
// RECHERCHE & STOCK
// ---------------------------------------------------------

function GetVehiculesDisponibles($nom_agence, $libelle_type, $date_debut, $date_fin, $quantite_demandee)
{
    $bdd = dbconnect();
    $libelle_type = str_replace('_', ' ', $libelle_type);

    $req = "SELECT v.id_Vehicule, v.Marque, v.Modele, tv.id_Tarif
            FROM vehicule v
            INNER JOIN agence_location a ON v.id_agence = a.id_Agence
            INNER JOIN type_vehicule tv ON v.id_type_vehicule = tv.id_Type_Vehicule
            WHERE a.nom_Agence = :nom_agence 
            AND tv.libelle_Type = :libelle_type
            AND v.statut = 'disponible'";

    $res = $bdd->prepare($req);
    $res->execute(['nom_agence' => $nom_agence, 'libelle_type' => $libelle_type]);
    $tous_vehicules = $res->fetchAll(PDO::FETCH_ASSOC);

    $vehicules_libres = [];

    foreach ($tous_vehicules as $v) {
        if (IsVehiculeLibre($v['id_Vehicule'], $date_debut, $date_fin)) {
            $vehicules_libres[] = $v;
        }
        if (count($vehicules_libres) >= $quantite_demandee) {
            break;
        }
    }

    return $vehicules_libres;
}

// Vérifie la disponibilité ABSOLUE (Sans dates)
function IsVehiculeLibre($id_vehicule, $date_debut, $date_fin)
{
    $bdd = dbconnect();
    // On ignore les dates passées en paramètre.
    // Si le véhicule est dans la table Participants lié à une résa active, il est pris.
    $req = "SELECT COUNT(*) as nb 
            FROM Participants p
            INNER JOIN Reservation r ON p.id_reservation = r.id_Reservation
            WHERE p.id_vehicule = :id_vehicule
            AND r.statut_reservation IN ('Réservée', 'En cours', 'confirmée')";

    $res = $bdd->prepare($req);
    $res->execute(['id_vehicule' => $id_vehicule]);
    $row = $res->fetch(PDO::FETCH_ASSOC);

    return $row['nb'] == 0;
}

// MODIFICATION : Calcul le stock dynamique réel
function GetDispoParType()
{
    $bdd = dbconnect();

    // Compte tous les véhicules disponibles (statut table véhicule)
    // MOINS ceux qui sont actuellement liés à une réservation active dans la table Participants
    $req = "SELECT tv.libelle_Type, COUNT(v.id_Vehicule) as nb
            FROM vehicule v
            INNER JOIN type_vehicule tv ON v.id_type_vehicule = tv.id_Type_Vehicule
            WHERE v.statut = 'disponible' 
            AND v.id_Vehicule NOT IN (
                SELECT p.id_vehicule 
                FROM Participants p
                INNER JOIN Reservation r ON p.id_reservation = r.id_Reservation
                WHERE r.statut_reservation IN ('Réservée', 'En cours', 'confirmée')
            )
            GROUP BY tv.libelle_Type";

    $res = $bdd->prepare($req);
    $res->execute();
    return $res->fetchAll(PDO::FETCH_ASSOC);
}
?>
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

// ============================================================
// GESTION UTILISATEURS
// ============================================================

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
    $success = $res->execute([
        'mail' => $Mail,
        'mdp' => $MdpHash,
        'nom' => $Nom,
        'prenom' => $Prenom,
        'tel' => $Telephone,
        'adr' => $Adresse
    ]);

    // LOG : Inscription
    if ($success) {
        AddLog("Nouveau client inscrit : $Nom $Prenom");
    }
    return $success;
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

// ============================================================
// GESTION RÉSERVATIONS (CLIENT)
// ============================================================

function GetPrixTarif($id_tarif) {
    $bdd = dbconnect();
    $req = "SELECT Tarif_Horaire FROM tarif WHERE id_Tarif = :id";
    $res = $bdd->prepare($req);
    $res->execute(['id' => $id_tarif]);
    $row = $res->fetch(PDO::FETCH_ASSOC);
    return $row ? floatval($row['Tarif_Horaire']) : 0.0;
}

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

    // LOG : Création réservation
    if ($result) {
        $idRes = $bdd->lastInsertId();
        AddLog("Nouvelle réservation #$idRes créée");
        return $idRes;
    }
    return false;
}

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
            LEFT JOIN vehicule v ON p.id_vehicule = v.id_Vehicule
            LEFT JOIN agence_location a ON v.id_Agence = a.id_Agence
            LEFT JOIN type_vehicule tv ON v.id_type_vehicule = tv.id_Type_Vehicule
            WHERE r.id_client = :id_client
            GROUP BY r.id_Reservation
            ORDER BY r.date_debut_location DESC";

    $res = $bdd->prepare($req);
    $res->execute(['id_client' => $id_client]);

    return $res->fetchAll(PDO::FETCH_ASSOC);
}

// ============================================================
// RECHERCHE & STOCK
// ============================================================

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

function IsVehiculeLibre($id_vehicule, $date_debut, $date_fin)
{
    $bdd = dbconnect();
    $req = "SELECT COUNT(*) as nb 
            FROM Participants p
            INNER JOIN reservation r ON p.id_reservation = r.id_Reservation
            WHERE p.id_vehicule = :id_vehicule
            AND r.statut_reservation IN ('Réservée', 'En cours', 'confirmée')";

    $res = $bdd->prepare($req);
    $res->execute(['id_vehicule' => $id_vehicule]);
    $row = $res->fetch(PDO::FETCH_ASSOC);

    return $row['nb'] == 0;
}

function GetDispoParType()
{
    $bdd = dbconnect();
    $req = "SELECT tv.libelle_Type, COUNT(v.id_Vehicule) as nb
            FROM vehicule v
            INNER JOIN type_vehicule tv ON v.id_type_vehicule = tv.id_Type_Vehicule
            WHERE v.statut = 'disponible' 
            AND v.id_Vehicule NOT IN (
                SELECT p.id_vehicule 
                FROM Participants p
                INNER JOIN reservation r ON p.id_reservation = r.id_Reservation
                WHERE r.statut_reservation IN ('Réservée', 'En cours', 'confirmée')
                AND p.id_vehicule IS NOT NULL
            )
            GROUP BY tv.libelle_Type";

    $res = $bdd->prepare($req);
    $res->execute();
    return $res->fetchAll(PDO::FETCH_ASSOC);
}


// ============================================================
// PARTIE ADMIN : STATISTIQUES, DASHBOARD & ACTIONS
// ============================================================

// 1. Stats Globales
function GetAdminStatsGlobales() {
    $bdd = dbconnect();

    $caTotal = $bdd->query("SELECT SUM(montant_totale) FROM reservation WHERE statut_reservation != 'Annulée'")->fetchColumn();

    $nbTotal = $bdd->query("SELECT COUNT(p.id_Participants) 
                            FROM Participants p 
                            JOIN reservation r ON p.id_reservation = r.id_Reservation 
                            WHERE r.statut_reservation != 'Annulée'")->fetchColumn();

    $resGlobal = ['total_locs' => $nbTotal, 'ca_total' => $caTotal];

    $reqType = "SELECT 
                    T.libelle_Type,
                    SUM(T.nb_participants) as nb_locs,
                    SUM(T.montant_totale) as ca_type
                FROM (
                    SELECT 
                        tv.libelle_Type,
                        r.montant_totale,
                        COUNT(p.id_Participants) as nb_participants
                    FROM reservation r
                    JOIN type_vehicule tv ON r.id_tarif = tv.id_Tarif
                    LEFT JOIN Participants p ON r.id_Reservation = p.id_reservation
                    WHERE r.statut_reservation != 'Annulée'
                    GROUP BY r.id_Reservation
                ) as T
                GROUP BY T.libelle_Type";

    $resType = $bdd->query($reqType)->fetchAll(PDO::FETCH_ASSOC);

    return ['global' => $resGlobal, 'par_type' => $resType];
}

// 2. État des Agences
function GetAdminAgencesStatus() {
    $bdd = dbconnect();
    $req = "SELECT 
                a.nom_Agence, a.Adresse,
                COUNT(v.id_Vehicule) as total_vehicules,
                (SELECT COUNT(*) 
                 FROM Participants p 
                 JOIN reservation r ON p.id_reservation = r.id_Reservation
                 JOIN vehicule v2 ON p.id_vehicule = v2.id_Vehicule
                 WHERE v2.id_agence = a.id_Agence 
                 AND r.statut_reservation IN ('Réservée', 'En cours', 'Confirmée')
                ) as vehicules_loues
            FROM agence_location a
            LEFT JOIN vehicule v ON a.id_Agence = v.id_agence
            WHERE a.nom_Agence NOT LIKE '%Meylan%' 
              AND a.nom_Agence NOT LIKE '%Lyon%' 
              AND a.nom_Agence NOT LIKE '%Bron%'
            GROUP BY a.id_Agence";

    return $bdd->query($req)->fetchAll(PDO::FETCH_ASSOC);
}

// 3. Réservations Récentes
function GetReservationsRecentes() {
    $bdd = dbconnect();
    $req = "SELECT r.id_Reservation, c.Nom, c.Prenom, tv.libelle_Type, a.nom_Agence, r.statut_reservation
            FROM reservation r
            JOIN client_connecter c ON r.id_client = c.id_Client
            -- On passe par les participants pour trouver le véhicule et son type précis
            LEFT JOIN Participants p ON r.id_Reservation = p.id_reservation
            LEFT JOIN vehicule v ON p.id_vehicule = v.id_Vehicule
            LEFT JOIN type_vehicule tv ON v.id_type_vehicule = tv.id_Type_Vehicule
            LEFT JOIN agence_location a ON v.id_agence = a.id_Agence
            GROUP BY r.id_Reservation
            ORDER BY r.id_Reservation DESC LIMIT 10";
    return $bdd->query($req)->fetchAll(PDO::FETCH_ASSOC);
}

// 4. Participants Récents
function GetParticipantsRecents() {
    $bdd = dbconnect();
    $req = "SELECT p.id_Reservation, p.Nom, p.Prenom, v.Marque, v.Modele, r.statut_reservation
            FROM Participants p
            JOIN vehicule v ON p.id_vehicule = v.id_Vehicule
            JOIN reservation r ON p.id_reservation = r.id_Reservation
            ORDER BY p.id_Reservation DESC LIMIT 10";
    return $bdd->query($req)->fetchAll(PDO::FETCH_ASSOC);
}

// 5. État du Parc
function GetAdminParcStatus() {
    $bdd = dbconnect();
    $req = "SELECT 
                tv.libelle_Type, 
                COUNT(v.id_Vehicule) as total,
                SUM(
                    CASE WHEN v.id_Vehicule IN (
                        SELECT p.id_vehicule
                        FROM Participants p
                        JOIN reservation r ON p.id_reservation = r.id_Reservation
                        WHERE r.statut_reservation IN ('Réservée', 'En cours', 'Confirmée', 'confirmée')
                    ) THEN 0 ELSE 1 END
                ) as dispo
            FROM vehicule v
            JOIN type_vehicule tv ON v.id_type_vehicule = tv.id_Type_Vehicule
            GROUP BY tv.libelle_Type";
    return $bdd->query($req)->fetchAll(PDO::FETCH_ASSOC);
}


// ============================================================
// ACTIONS ADMIN : EDIT / DELETE AVEC LOGS
// ============================================================

// Récupérer une réservation
function GetOneReservation($id) {
    $bdd = dbconnect();
    $req = $bdd->prepare("
        SELECT r.*, c.Nom, c.Prenom, tv.libelle_Type, a.nom_Agence 
        FROM reservation r
        JOIN client_connecter c ON r.id_client = c.id_Client
        LEFT JOIN Participants p ON r.id_Reservation = p.id_reservation
        LEFT JOIN vehicule v ON p.id_vehicule = v.id_Vehicule
        LEFT JOIN type_vehicule tv ON v.id_type_vehicule = tv.id_Type_Vehicule
        LEFT JOIN agence_location a ON v.id_agence = a.id_Agence
        WHERE r.id_Reservation = :id
        GROUP BY r.id_Reservation
    ");
    $req->execute(['id' => $id]);
    return $req->fetch(PDO::FETCH_ASSOC);
}

// Mettre à jour le statut
function AdminUpdateStatutReservation($id, $nouveauStatut) {
    $bdd = dbconnect();
    $req = $bdd->prepare("UPDATE reservation SET statut_reservation = :statut WHERE id_Reservation = :id");
    $success = $req->execute(['statut' => $nouveauStatut, 'id' => $id]);

    // LOG : Mise à jour
    if ($success) {
        AddLog("Admin : Modification statut #$id vers '$nouveauStatut'");
    }
    return $success;
}

// Supprimer une réservation
function AdminDeleteReservation($id) {
    $bdd = dbconnect();

    // 1. On récupère les infos du client AVANT de supprimer pour le log
    $info = GetOneReservation($id);
    $nomClient = $info ? ($info['Nom'] . " " . $info['Prenom']) : "Inconnu";

    // 2. Suppressions
    $bdd->prepare("DELETE FROM Participants WHERE id_reservation = :id")->execute(['id' => $id]);
    $reqRes = $bdd->prepare("DELETE FROM reservation WHERE id_Reservation = :id");
    $success = $reqRes->execute(['id' => $id]);

    // LOG : Suppression
    if ($success) {
        AddLog("Admin : SUPPRESSION réservation #$id (Client : $nomClient)");
    }
    return $success;
}

// ============================================================
// GESTION DES LOGS (NOUVEAU SYSTÈME RÉEL)
// ============================================================

// Ajouter un message dans l'historique
function AddLog($message) {
    $bdd = dbconnect();
    $req = $bdd->prepare("INSERT INTO Logs (message, date_log) VALUES (:msg, NOW())");
    $req->execute(['msg' => $message]);
}

// Lire l'historique pour le dashboard
function GetLogs() {
    $bdd = dbconnect();
    // On récupère les 7 derniers logs réels
    return $bdd->query("SELECT * FROM Logs ORDER BY date_log DESC LIMIT 7")->fetchAll(PDO::FETCH_ASSOC);
}
?>
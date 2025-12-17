<?php

date_default_timezone_set('Europe/Paris');

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
    $req = $bdd->prepare("SELECT COUNT(*) FROM client_connecter WHERE Mail = ?");
    $req->execute([$Mail]);
    return $req->fetchColumn() > 0;
}

function AddUser($Mail, $MdpHash, $Nom, $Prenom, $Telephone, $Adresse) {
    $bdd = dbconnect();
    $req = $bdd->prepare("INSERT INTO client_connecter (Mail, Mot_de_Passe_Securiser, Nom, Prenom, Telephone, Adresse, Date_de_Creation) 
            VALUES (?, ?, ?, ?, ?, ?, NOW())");
    $success = $req->execute([$Mail, $MdpHash, $Nom, $Prenom, $Telephone, $Adresse]);

    if ($success) {
        AddLog("Nouveau client inscrit : $Nom $Prenom");
    }
    return $success;
}

function UpdateUserPassword($Mail, $NewMdpHash) {
    $bdd = dbconnect();
    $req = $bdd->prepare("UPDATE client_connecter SET Mot_de_Passe_Securiser = ? WHERE Mail = ?");
    return $req->execute([$NewMdpHash, $Mail]);
}

function GetUserByMail($Mail) {
    $bdd = dbconnect();
    $req = $bdd->prepare("SELECT * FROM client_connecter WHERE Mail = ?");
    $req->execute([$Mail]);
    return $req->fetch(PDO::FETCH_ASSOC);
}

function getIdClientByMail($mail) {
    $bdd = dbconnect();
    $req = $bdd->prepare("SELECT id_Client FROM client_connecter WHERE Mail = ?");
    $req->execute([$mail]);
    $res = $req->fetch(PDO::FETCH_ASSOC);
    return $res ? $res['id_Client'] : null;
}

// ============================================================
// GESTION RÉSERVATIONS
// ============================================================

function GetPrixTarif($id_tarif) {
    $bdd = dbconnect();
    $req = $bdd->prepare("SELECT Tarif_Horaire FROM tarif WHERE id_Tarif = ?");
    $req->execute([$id_tarif]);
    return $req->fetchColumn() ?: 0.0;
}

function AddReservation($id_client, $id_tarif, $debut, $fin, $duree, $speciale, $montant) {
    $bdd = dbconnect();
    $req = $bdd->prepare("INSERT INTO reservation (Date_Reservation, Duree, Demande_speciale, date_debut_location, montant_totale, date_fin_location, statut_reservation, id_client, id_tarif) 
            VALUES (NOW(), ?, ?, ?, ?, ?, 'Réservée', ?, ?)");
    $result = $req->execute([$duree, $speciale, $debut, $montant, $fin, $id_client, $id_tarif]);

    if ($result) {
        $idRes = $bdd->lastInsertId();
        AddLog("Nouvelle réservation #$idRes créée");
        return $idRes;
    }
    return false;
}

function AddParticipant($nom, $prenom, $id_reservation, $id_vehicule) {
    $bdd = dbconnect();
    $req = $bdd->prepare("INSERT INTO Participants (Nom, Prenom, id_reservation, id_vehicule) VALUES (?, ?, ?, ?)");
    return $req->execute([$nom, $prenom, $id_reservation, $id_vehicule]);
}

function GetReservationsClient($id_client) {
    $bdd = dbconnect();
    $req = $bdd->prepare("SELECT r.*, a.nom_Agence, 
            COALESCE(MAX(tv_real.libelle_Type), MAX(tv_tarif.libelle_Type)) as libelle_Type,
            -- AJOUT DE DISTINCT : Corrige le bug '10 véhicules' au lieu de 5
            COUNT(DISTINCT p.id_Participants) as nb_participants,
            -- AJOUT DE DISTINCT : Évite d'afficher les noms de véhicules en double
            GROUP_CONCAT(DISTINCT CONCAT(v.Marque, ' ', v.Modele) SEPARATOR ', ') as vehicules_list
            FROM reservation r
            LEFT JOIN Participants p ON r.id_Reservation = p.id_reservation
            LEFT JOIN vehicule v ON p.id_vehicule = v.id_Vehicule
            LEFT JOIN type_vehicule tv_real ON v.id_type_vehicule = tv_real.id_Type_Vehicule
            LEFT JOIN type_vehicule tv_tarif ON r.id_tarif = tv_tarif.id_Tarif
            LEFT JOIN agence_location a ON v.id_Agence = a.id_Agence
            WHERE r.id_client = ?
            GROUP BY r.id_Reservation
            ORDER BY r.date_debut_location DESC");
    $req->execute([$id_client]);
    return $req->fetchAll(PDO::FETCH_ASSOC);
}

// ============================================================
// STOCK & DISPO
// ============================================================

function GetVehiculesDisponibles($nom_agence, $libelle_type, $date_debut, $date_fin, $quantite_demandee) {
    $bdd = dbconnect();
    $libelle_type = str_replace('_', ' ', $libelle_type);

    $req = $bdd->prepare("SELECT v.id_Vehicule, v.Marque, v.Modele, tv.id_Tarif
            FROM vehicule v
            JOIN agence_location a ON v.id_agence = a.id_Agence
            JOIN type_vehicule tv ON v.id_type_vehicule = tv.id_Type_Vehicule
            WHERE a.nom_Agence = ? AND tv.libelle_Type = ? AND v.statut = 'disponible'");
    $req->execute([$nom_agence, $libelle_type]);
    $tous = $req->fetchAll(PDO::FETCH_ASSOC);

    $dispo = [];
    foreach ($tous as $v) {
        if (IsVehiculeLibre($v['id_Vehicule'], $date_debut, $date_fin)) {
            $dispo[] = $v;
        }
        if (count($dispo) >= $quantite_demandee) break;
    }
    return $dispo;
}

function IsVehiculeLibre($id_vehicule, $date_debut, $date_fin) {
    $bdd = dbconnect();
    $req = $bdd->prepare("SELECT COUNT(*) FROM Participants p
            JOIN reservation r ON p.id_reservation = r.id_Reservation
            WHERE p.id_vehicule = ? 
            AND r.statut_reservation NOT IN ('Annulée', 'Terminée')
            AND (
                (r.date_debut_location < ? AND r.date_fin_location > ?)
            )");
    $req->execute([$id_vehicule, $date_fin, $date_debut]);
    return $req->fetchColumn() == 0;
}

function GetDispoParType() {
    $bdd = dbconnect();
    $req = "SELECT tv.libelle_Type, COUNT(v.id_Vehicule) as total,
            SUM(CASE WHEN v.id_Vehicule NOT IN (
                SELECT p.id_vehicule FROM Participants p 
                JOIN reservation r ON p.id_reservation = r.id_Reservation 
                WHERE r.statut_reservation IN ('En cours', 'Confirmée', 'Réservée')
            ) THEN 1 ELSE 0 END) as dispo
            FROM vehicule v
            JOIN type_vehicule tv ON v.id_type_vehicule = tv.id_Type_Vehicule
            GROUP BY tv.libelle_Type";
    return $bdd->query($req)->fetchAll(PDO::FETCH_ASSOC);
}

// ============================================================
// ADMIN : STATS & DATA
// ============================================================

// 1. Stats globales (Tableau) avec filtre de dates
function GetAdminStatsGlobales($dateDebut = null, $dateFin = null) {
    $bdd = dbconnect();

    $sqlDate = "";
    $params = [];

    // Filtre sur la date de RÉSERVATION (création)
    if ($dateDebut && $dateFin) {
        $sqlDate = " AND r.Date_Reservation BETWEEN ? AND ? ";
        $params = [$dateDebut, $dateFin];
    }

    $sql = "SELECT 
                r.id_Reservation,
                r.montant_totale,
                p.id_Participants,
                COALESCE(tv_real.libelle_Type, tv_tarif.libelle_Type) as type_final
            FROM reservation r
            LEFT JOIN Participants p ON r.id_Reservation = p.id_reservation
            LEFT JOIN vehicule v ON p.id_vehicule = v.id_Vehicule
            LEFT JOIN type_vehicule tv_real ON v.id_type_vehicule = tv_real.id_Type_Vehicule
            LEFT JOIN type_vehicule tv_tarif ON r.id_tarif = tv_tarif.id_Tarif
            WHERE r.statut_reservation != 'Annulée' $sqlDate";

    $stmt = $bdd->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calcul PHP
    $statsByType = [];
    $totalLocs = 0;
    $totalCA = 0;
    $processedReservations = [];
    $processedParticipants = [];

    foreach ($rows as $row) {
        $type = $row['type_final'] ?? 'Autre';
        if (!isset($statsByType[$type])) $statsByType[$type] = ['libelle_Type' => $type, 'nb_locs' => 0, 'ca_type' => 0];

        if ($row['id_Participants'] && !in_array($row['id_Participants'], $processedParticipants)) {
            $statsByType[$type]['nb_locs']++;
            $totalLocs++;
            $processedParticipants[] = $row['id_Participants'];
        }
        if (!in_array($row['id_Reservation'], $processedReservations)) {
            $statsByType[$type]['ca_type'] += $row['montant_totale'];
            $totalCA += $row['montant_totale'];
            $processedReservations[] = $row['id_Reservation'];
        }
    }

    return ['global' => ['total_locs' => $totalLocs, 'ca_total' => $totalCA], 'par_type' => array_values($statsByType)];
}

function GetAdminAgencesStatus() {
    $bdd = dbconnect();
    $req = "SELECT a.nom_Agence, a.Adresse, COUNT(v.id_Vehicule) as total_vehicules,
            (SELECT COUNT(*) FROM Participants p 
             JOIN reservation r ON p.id_reservation = r.id_Reservation 
             JOIN vehicule v2 ON p.id_vehicule = v2.id_Vehicule 
             WHERE v2.id_agence = a.id_Agence 
             AND r.statut_reservation IN ('En cours', 'Confirmée', 'Réservée')) as vehicules_loues
            FROM agence_location a
            LEFT JOIN vehicule v ON a.id_Agence = v.id_agence
            GROUP BY a.id_Agence";
    return $bdd->query($req)->fetchAll(PDO::FETCH_ASSOC);
}
function GetReservationsRecentes() {
    $bdd = dbconnect();
    $req = "SELECT r.id_Reservation, c.Nom, c.Prenom, 
            r.date_debut_location, r.date_fin_location, r.montant_totale,
            COALESCE(MAX(tv_real.libelle_Type), MAX(tv_tarif.libelle_Type)) as libelle_Type, 
            MAX(a.nom_Agence) as nom_Agence, 
            r.statut_reservation
            FROM reservation r
            JOIN client_connecter c ON r.id_client = c.id_Client
            LEFT JOIN Participants p ON r.id_Reservation = p.id_reservation
            LEFT JOIN vehicule v ON p.id_vehicule = v.id_Vehicule
            LEFT JOIN type_vehicule tv_real ON v.id_type_vehicule = tv_real.id_Type_Vehicule
            LEFT JOIN type_vehicule tv_tarif ON r.id_tarif = tv_tarif.id_Tarif
            LEFT JOIN agence_location a ON v.id_agence = a.id_Agence
            GROUP BY r.id_Reservation
            ORDER BY r.id_Reservation DESC LIMIT 10";
    return $bdd->query($req)->fetchAll(PDO::FETCH_ASSOC);
}

function GetParticipantsRecents() {
    $bdd = dbconnect();
    $req = "SELECT p.id_Reservation, p.Nom, p.Prenom, v.id_Vehicule, v.Marque, v.Modele, r.statut_reservation
            FROM Participants p
            JOIN vehicule v ON p.id_vehicule = v.id_Vehicule
            JOIN reservation r ON p.id_reservation = r.id_Reservation
            ORDER BY p.id_Reservation DESC LIMIT 10";
    return $bdd->query($req)->fetchAll(PDO::FETCH_ASSOC);
}

function GetAdminParcStatus() {
    $bdd = dbconnect();
    $req = "SELECT tv.libelle_Type, COUNT(v.id_Vehicule) as total,
            SUM(CASE WHEN v.id_Vehicule NOT IN (
                SELECT p.id_vehicule FROM Participants p 
                JOIN reservation r ON p.id_reservation = r.id_Reservation 
                WHERE r.statut_reservation IN ('En cours', 'Confirmée', 'Réservée')
            ) THEN 1 ELSE 0 END) as dispo
            FROM vehicule v
            JOIN type_vehicule tv ON v.id_type_vehicule = tv.id_Type_Vehicule
            GROUP BY tv.libelle_Type";
    return $bdd->query($req)->fetchAll(PDO::FETCH_ASSOC);
}

// ============================================================
// ADMIN ACTIONS & EXPORTS
// ============================================================

function GetOneReservation($id) {
    $bdd = dbconnect();
    $req = $bdd->prepare("SELECT r.*, c.Nom, c.Prenom, tv.libelle_Type, a.nom_Agence 
        FROM reservation r
        JOIN client_connecter c ON r.id_client = c.id_Client
        LEFT JOIN type_vehicule tv ON r.id_tarif = tv.id_Tarif
        LEFT JOIN Participants p ON r.id_Reservation = p.id_reservation
        LEFT JOIN vehicule v ON p.id_vehicule = v.id_Vehicule
        LEFT JOIN agence_location a ON v.id_agence = a.id_Agence
        WHERE r.id_Reservation = ? GROUP BY r.id_Reservation");
    $req->execute([$id]);
    return $req->fetch(PDO::FETCH_ASSOC);
}

function AdminUpdateStatutReservation($id, $statut) {
    $bdd = dbconnect();
    $req = $bdd->prepare("UPDATE reservation SET statut_reservation = ? WHERE id_Reservation = ?");
    if ($req->execute([$statut, $id])) {
        AddLog("Admin: Statut réserv. #$id changé en $statut");
        return true;
    }
    return false;
}

function AdminDeleteReservation($id) {
    $bdd = dbconnect();
    $info = GetOneReservation($id);
    $client = $info ? ($info['Nom']." ".$info['Prenom']) : "?";

    $bdd->prepare("DELETE FROM Participants WHERE id_reservation = ?")->execute([$id]);
    $res = $bdd->prepare("DELETE FROM reservation WHERE id_Reservation = ?")->execute([$id]);

    if ($res) AddLog("Admin: Suppression réserv. #$id ($client)");
    return $res;
}

// ============================================================
// AUTOMATISATION DES STATUTS
// ============================================================
function UpdateReservationStatusAuto() {
    $bdd = dbconnect();
    $now = date('Y-m-d H:i:s');

    $reqEnCours = "UPDATE reservation 
                   SET statut_reservation = 'En cours' 
                   WHERE date_debut_location <= '$now' 
                   AND date_fin_location > '$now' 
                   AND statut_reservation IN ('Réservée', 'Confirmée')";
    $bdd->query($reqEnCours);

    $reqTerminee = "UPDATE reservation 
                    SET statut_reservation = 'Terminée' 
                    WHERE date_fin_location <= '$now' 
                    AND statut_reservation IN ('En cours', 'Confirmée', 'Réservée')";
    $bdd->query($reqTerminee);
}

function AddLog($msg) {
    $bdd = dbconnect();
    $bdd->prepare("INSERT INTO Logs (message, date_log) VALUES (?, NOW())")->execute([$msg]);
}

function GetLogs() {
    $bdd = dbconnect();
    return $bdd->query("SELECT * FROM Logs ORDER BY date_log DESC LIMIT 7")->fetchAll(PDO::FETCH_ASSOC);
}

function GetAllLogs() {
    $bdd = dbconnect();
    // Même requête que GetLogs mais SANS le LIMIT
    return $bdd->query("SELECT * FROM Logs ORDER BY date_log DESC")->fetchAll(PDO::FETCH_ASSOC);
}

function GetDonneesExport() {
    $bdd = dbconnect();
    $req = "SELECT r.id_Reservation, DATE_FORMAT(r.date_reservation, '%d/%m/%Y') as date_fmt,
            CONCAT(c.Nom, ' ', c.Prenom) as client_complet,
            COALESCE(MAX(tv_real.libelle_Type), MAX(tv_tarif.libelle_Type)) as libelle_Type,
            r.montant_totale
            FROM reservation r
            JOIN client_connecter c ON r.id_client = c.id_Client
            LEFT JOIN Participants p ON r.id_Reservation = p.id_reservation
            LEFT JOIN vehicule v ON p.id_vehicule = v.id_Vehicule
            LEFT JOIN type_vehicule tv_real ON v.id_type_vehicule = tv_real.id_Type_Vehicule
            LEFT JOIN type_vehicule tv_tarif ON r.id_tarif = tv_tarif.id_Tarif
            WHERE r.statut_reservation != 'Annulée'
            GROUP BY r.id_Reservation
            ORDER BY r.date_reservation DESC";
    return $bdd->query($req)->fetchAll(PDO::FETCH_ASSOC);
}

function GetParticipantsByReservation($id_res) {
    $bdd = dbconnect();
    $req = $bdd->prepare("SELECT * FROM Participants WHERE id_reservation = ?");
    $req->execute([$id_res]);
    return $req->fetchAll(PDO::FETCH_ASSOC);
}

function DeleteParticipant($id_part) {
    $bdd = dbconnect();

    // 1. On récupère les infos du participant AVANT de le supprimer
    $stmt = $bdd->prepare("SELECT Nom, Prenom, id_reservation FROM Participants WHERE id_Participants = ?");
    $stmt->execute([$id_part]);
    $info = $stmt->fetch(PDO::FETCH_ASSOC);

    $nomComplet = $info ? ($info['Nom'] . " " . $info['Prenom']) : "Inconnu";
    $refRes = $info ? $info['id_reservation'] : "?";

    // 2. On effectue la suppression
    $req = $bdd->prepare("DELETE FROM Participants WHERE id_Participants = ?");
    $success = $req->execute([$id_part]);

    // 3. Si ça a marché, on ajoute le log
    if ($success) {
        AddLog("Admin: Suppression participant $nomComplet (Réservation #$refRes)");
    }

    return $success;
}

// 2. Stats Graphique (Courbe) avec filtre de dates
function GetStatsGraphique($dateDebut, $dateFin) {
    $bdd = dbconnect();
    $stats = ['labels' => [], 'data' => []];

    $start = new DateTime($dateDebut);
    $end = new DateTime($dateFin);
    // On ajoute 1 jour pour être sûr que la boucle inclue la date de fin
    $end->modify('+1 day');

    $period = new DatePeriod($start, new DateInterval('P1D'), $end);

    foreach ($period as $dt) {
        $dateSql = $dt->format('Y-m-d');
        $label = $dt->format('d/m');

        // On compte les réservations faites CE JOUR LÀ
        $sql = "SELECT COUNT(*) FROM reservation WHERE DATE(Date_Reservation) = '$dateSql' AND statut_reservation != 'Annulée'";
        $count = $bdd->query($sql)->fetchColumn();

        $stats['labels'][] = $label;
        $stats['data'][] = (int)$count;
    }
    return $stats;
}
?>
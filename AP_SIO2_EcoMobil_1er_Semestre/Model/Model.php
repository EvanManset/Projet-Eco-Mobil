<?php

date_default_timezone_set('Europe/Paris');

function dbconnect()
{
    try {
        $bdd = new PDO(
            'mysql:host=localhost;dbname=Ecomobil;charset=utf8',
            'root',
            '',
            array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
        );
        return $bdd;
    } catch (Exception $e) {
        die('Erreur de connexion : ' . $e->getMessage());
    }
}

// ====================
// GESTION UTILISATEURS
// ====================

function EmailExists($Mail)
{
    // Définit une fonction pour vérifier si un email est présent dans la table des clients.
    $bdd = dbconnect();
    // Établit la connexion à la base de données.

    $req = $bdd->prepare("SELECT COUNT(*) FROM client_connecter WHERE Mail = ?");
    // Prépare une requête SQL pour compter le nombre d'entrées correspondant à l'email fourni.
    $req->execute([$Mail]);
    // Exécute la requête en injectant l'email de manière sécurisée.

    return $req->fetchColumn() > 0;
    // Retourne vrai si le compte est supérieur à 0, indiquant que l'email existe.
}

function AddUser($Mail, $MdpHash, $Nom, $Prenom, $Telephone, $Adresse)
{
    // Définit la fonction d'insertion d'un nouvel utilisateur en base de données.
    $bdd = dbconnect();
    // Établit la connexion à la base de données.

    $req = $bdd->prepare("INSERT INTO client_connecter (Mail, Mot_de_Passe_Securiser, Nom, Prenom, Telephone, Adresse, Date_de_Creation) 
            VALUES (?, ?, ?, ?, ?, ?, NOW())");
    // Prépare l'insertion des données personnelles et utilise NOW() pour la date d'inscription.
    $success = $req->execute([$Mail, $MdpHash, $Nom, $Prenom, $Telephone, $Adresse]);
    // Exécute l'insertion avec les valeurs fournies et stocke le résultat (vrai/faux).

    if ($success) {
        // Vérifie si l'enregistrement a été effectué avec succès.
        AddLog("Nouveau client inscrit : $Nom $Prenom");
        // Enregistre un événement dans les logs du système pour l'audit.
    }

    return $success;
    // Retourne l'état de réussite de l'opération.
}

function UpdateUserPassword($Mail, $NewMdpHash)
{
    // Définit la fonction de mise à jour du mot de passe.
    $bdd = dbconnect();
    // Connexion à la base de données.

    $req = $bdd->prepare("UPDATE client_connecter SET Mot_de_Passe_Securiser = ? WHERE Mail = ?");
    // Prépare la mise à jour du hachage du mot de passe pour un email spécifique.

    return $req->execute([$NewMdpHash, $Mail]);
    // Exécute la modification et retourne le résultat.
}

function GetUserByMail($Mail)
{
    // Définit la fonction pour récupérer toutes les données d'un utilisateur via son email.
    $bdd = dbconnect();
    // Connexion à la base de données.

    $req = $bdd->prepare("SELECT * FROM client_connecter WHERE Mail = ?");
    // Prépare la sélection de toutes les colonnes pour l'utilisateur concerné.
    $req->execute([$Mail]);
    // Exécute la recherche.

    return $req->fetch(PDO::FETCH_ASSOC);
    // Retourne les données sous forme de tableau associatif.
}

function getIdClientByMail($mail)
{
    // Définit une fonction spécifique pour obtenir uniquement l'ID numérique d'un client.
    $bdd = dbconnect();
    // Connexion à la base de données.

    $req = $bdd->prepare("SELECT id_Client FROM client_connecter WHERE Mail = ?");
    // Prépare la sélection de la colonne id_Client.
    $req->execute([$mail]);
    // Exécute la recherche.

    $res = $req->fetch(PDO::FETCH_ASSOC);
    // Récupère la ligne de résultat.

    return $res ? $res['id_Client'] : null;
    // Retourne l'ID si trouvé, sinon retourne null.
}

// ====================
// GESTION RÉSERVATIONS
// ====================

function GetPrixTarif($id_tarif)
{
    // Définit la fonction pour récupérer le prix horaire d'un tarif.
    $bdd = dbconnect();
    // Connexion à la base de données.

    $req = $bdd->prepare("SELECT Tarif_Horaire FROM tarif WHERE id_Tarif = ?");
    // Prépare la sélection du montant horaire par ID.
    $req->execute([$id_tarif]);
    // Exécute la requête.

    return $req->fetchColumn() ?: 0.0;
    // Retourne la valeur de la colonne ou 0.0 si rien n'est trouvé.
}

function AddReservation($id_client, $id_tarif, $debut, $fin, $duree, $speciale, $montant)
{
    // Définit la fonction de création d'une nouvelle réservation.
    $bdd = dbconnect();
    // Connexion à la base de données.

    $req = $bdd->prepare("INSERT INTO reservation (Date_Reservation, Duree, Demande_speciale, date_debut_location, montant_totale, date_fin_location, statut_reservation, id_client, id_tarif) 
            VALUES (NOW(), ?, ?, ?, ?, ?, 'Réservée', ?, ?)");
    // Prépare l'insertion avec le statut par défaut 'Réservée'.
    $result = $req->execute([$duree, $speciale, $debut, $montant, $fin, $id_client, $id_tarif]);
    // Exécute l'insertion.

    if ($result) {
        // Si l'insertion a réussi.
        $idRes = $bdd->lastInsertId();
        // Récupère l'identifiant unique de la réservation qui vient d'être créée.
        AddLog("Nouvelle réservation #$idRes créée");
        // Enregistre l'action dans les logs.
        return $idRes;
        // Retourne l'ID de la nouvelle réservation.
    }

    return false;
    // Retourne faux en cas d'échec d'insertion.
}

function AddParticipant($nom, $prenom, $id_reservation, $id_vehicule)
{
    // Définit la fonction pour lier un participant et un véhicule à une réservation.
    $bdd = dbconnect();
    // Connexion à la base de données.

    $req = $bdd->prepare("INSERT INTO Participants (Nom, Prenom, id_reservation, id_vehicule) VALUES (?, ?, ?, ?)");
    // Prépare l'insertion dans la table pivot des participants.

    return $req->execute([$nom, $prenom, $id_reservation, $id_vehicule]);
    // Exécute et retourne le succès ou l'échec.
}

function GetReservationsClient($id_client)
{
    // Définit la fonction pour lister les réservations d'un client avec les détails associés.
    $bdd = dbconnect();
    // Connexion à la base de données.

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
    // Prépare une requête complexe avec jointures pour agréger les participants et véhicules par réservation.
    $req->execute([$id_client]);
    // Exécute la requête pour le client spécifié.

    return $req->fetchAll(PDO::FETCH_ASSOC);
    // Retourne l'historique complet.
}

// =============
// STOCK & DISPO
// =============

function GetVehiculesDisponibles($nom_agence, $libelle_type, $date_debut, $date_fin, $quantite_demandee)
{
    // Définit la fonction pour trouver des véhicules libres selon des critères précis.
    $bdd = dbconnect();
    // Connexion à la base de données.
    $libelle_type = str_replace('_', ' ', $libelle_type);
    // Nettoie le libellé du type en remplaçant les underscores par des espaces.

    $req = $bdd->prepare("SELECT v.id_Vehicule, v.Marque, v.Modele, tv.id_Tarif
            FROM vehicule v
            JOIN agence_location a ON v.id_agence = a.id_Agence
            JOIN type_vehicule tv ON v.id_type_vehicule = tv.id_Type_Vehicule
            WHERE a.nom_Agence = ? AND tv.libelle_Type = ? AND v.statut = 'disponible'");
    // Prépare la sélection des véhicules théoriquement disponibles dans l'agence demandée.
    $req->execute([$nom_agence, $libelle_type]);
    // Exécute la recherche initiale.

    $tous = $req->fetchAll(PDO::FETCH_ASSOC);
    // Récupère la liste potentielle.

    $dispo = [];
    // Initialise la liste des véhicules réellement libres.

    foreach ($tous as $v) {
        // Parcourt chaque véhicule trouvé.
        if (IsVehiculeLibre($v['id_Vehicule'], $date_debut, $date_fin)) {
            // Vérifie si le véhicule n'a pas d'autre réservation aux mêmes dates.
            $dispo[] = $v;
            // Ajoute le véhicule à la liste des disponibles.
        }

        if (count($dispo) >= $quantite_demandee) {
            break;
        }
        // Arrête la recherche dès que le nombre de véhicules demandés est atteint.
    }

    return $dispo;
    // Retourne la liste finale.
}

function IsVehiculeLibre($id_vehicule, $date_debut, $date_fin)
{
    // Définit la fonction de vérification de conflit de calendrier pour un véhicule.
    $bdd = dbconnect();
    // Connexion à la base de données.

    $req = $bdd->prepare("SELECT COUNT(*) FROM Participants p
            JOIN reservation r ON p.id_reservation = r.id_Reservation
            WHERE p.id_vehicule = ? 
            AND r.statut_reservation NOT IN ('Annulée', 'Terminée')
            AND (
                (r.date_debut_location < ? AND r.date_fin_location > ?)
            )");
    // Requête vérifiant s'il existe une réservation active chevauchant la période demandée.
    $req->execute([$id_vehicule, $date_fin, $date_debut]);
    // Exécute la vérification croisée des dates.

    return $req->fetchColumn() == 0;
    // Retourne vrai si aucun conflit n'est trouvé (count == 0).
}

function GetDispoParType()
{
    // Définit la fonction pour obtenir l'état global du stock par type de véhicule.
    $bdd = dbconnect();
    // Connexion à la base de données.

    $req = "SELECT tv.libelle_Type, COUNT(v.id_Vehicule) as total,
            SUM(CASE WHEN v.id_Vehicule NOT IN (
                SELECT p.id_vehicule FROM Participants p 
                JOIN reservation r ON p.id_reservation = r.id_Reservation 
                WHERE r.statut_reservation IN ('En cours', 'Confirmée', 'Réservée')
            ) THEN 1 ELSE 0 END) as dispo
            FROM vehicule v
            JOIN type_vehicule tv ON v.id_type_vehicule = tv.id_Type_Vehicule
            GROUP BY tv.libelle_Type";
    // Requête calculant le total et le nombre d'unités non réservées par catégorie.

    return $bdd->query($req)->fetchAll(PDO::FETCH_ASSOC);
    // Exécute et retourne les statistiques.
}

// ====================
// ADMIN : STATS & DATA
// ====================

// 1. Stats globales (Tableau) avec filtre de dates
function GetAdminStatsGlobales($dateDebut = null, $dateFin = null)
{
    // Définit la fonction de calcul des indicateurs financiers et volumétriques pour l'admin.
    $bdd = dbconnect();
    // Connexion à la base de données.

    $sqlDate = "";
    // Initialise la clause SQL de filtrage temporel.
    $params = [];
    // Initialise le tableau des paramètres de requête.

    // Filtre sur la date de RÉSERVATION
    if ($dateDebut && $dateFin) {
        // Si des dates de début et de fin sont fournies.
        $sqlDate = " AND r.Date_Reservation BETWEEN ? AND ? ";
        // Construit la clause de restriction par intervalle.
        $params = [$dateDebut, $dateFin];
        // Ajoute les dates aux paramètres de sécurité.
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
    // Requête de base pour extraire les montants et les participants actifs.

    $stmt = $bdd->prepare($sql);
    // Prépare la requête SQL.
    $stmt->execute($params);
    // Exécute avec les éventuels filtres de date.

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // Récupère toutes les lignes correspondantes.

    // Calcul PHP
    $statsByType = [];
    // Prépare les stats groupées par catégorie.
    $totalLocs = 0;
    // Compteur global de locations.
    $totalCA = 0;
    // Somme globale du chiffre d'affaires.
    $processedReservations = [];
    // Tableau de suivi pour éviter de compter deux fois le CA d'une même réservation.
    $processedParticipants = [];
    // Tableau de suivi pour éviter de compter deux fois un même participant.

    foreach ($rows as $row) {
        // Parcourt les résultats de la requête.
        $type = $row['type_final'] ?? 'Autre';
        // Détermine le type de véhicule (ou 'Autre' par défaut).

        if (!isset($statsByType[$type])) {
            $statsByType[$type] = ['libelle_Type' => $type, 'nb_locs' => 0, 'ca_type' => 0];
        }
        // Initialise l'entrée du type dans le tableau de stats si nécessaire.

        if ($row['id_Participants'] && !in_array($row['id_Participants'], $processedParticipants)) {
            // Si le participant est valide et non encore traité.
            $statsByType[$type]['nb_locs']++;
            // Incrémente le nombre de locations pour ce type.
            $totalLocs++;
            // Incrémente le total général.
            $processedParticipants[] = $row['id_Participants'];
            // Marque le participant comme traité.
        }

        if (!in_array($row['id_Reservation'], $processedReservations)) {
            // Si la réservation est nouvelle dans la boucle.
            $statsByType[$type]['ca_type'] += $row['montant_totale'];
            // Ajoute le montant au type concerné.
            $totalCA += $row['montant_totale'];
            // Ajoute au total général.
            $processedReservations[] = $row['id_Reservation'];
            // Marque la réservation comme traitée.
        }
    }

    return [
        'global' => ['total_locs' => $totalLocs, 'ca_total' => $totalCA],
        'par_type' => array_values($statsByType)
    ];
    // Retourne un tableau structuré contenant le bilan global et le détail par type.
}

function GetAdminAgencesStatus()
{
    // Définit la fonction de monitoring de l'occupation des agences.
    $bdd = dbconnect();
    // Connexion à la base de données.

    $req = "SELECT a.nom_Agence, a.Adresse, COUNT(v.id_Vehicule) as total_vehicules,
            (SELECT COUNT(*) FROM Participants p 
             JOIN reservation r ON p.id_reservation = r.id_Reservation 
             JOIN vehicule v2 ON p.id_vehicule = v2.id_Vehicule 
             WHERE v2.id_agence = a.id_Agence 
             AND r.statut_reservation IN ('En cours', 'Confirmée', 'Réservée')) as vehicules_loues
            FROM agence_location a
            LEFT JOIN vehicule v ON a.id_Agence = v.id_agence
            GROUP BY a.id_Agence";
    // Requête comptant le parc total et le nombre d'unités actuellement louées par agence.

    return $bdd->query($req)->fetchAll(PDO::FETCH_ASSOC);
    // Exécute et retourne l'état des agences.
}

function GetReservationsRecentes()
{
    // Définit la fonction pour afficher les 10 dernières réservations sur le dashboard.
    $bdd = dbconnect();
    // Connexion à la base de données.

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
    // Requête récupérant l'identité du client et le résumé de la prestation pour les 10 plus récents.

    return $bdd->query($req)->fetchAll(PDO::FETCH_ASSOC);
    // Retourne la liste.
}

function GetParticipantsRecents()
{
    // Définit la fonction pour lister les derniers bénéficiaires de véhicules.
    $bdd = dbconnect();
    // Connexion à la base de données.

    $req = "SELECT p.id_Reservation, p.Nom, p.Prenom, v.id_Vehicule, v.Marque, v.Modele, r.statut_reservation
            FROM Participants p
            JOIN vehicule v ON p.id_vehicule = v.id_Vehicule
            JOIN reservation r ON p.id_reservation = r.id_Reservation
            ORDER BY p.id_Reservation DESC LIMIT 10";
    // Requête liant participants et véhicules pour un aperçu rapide des attributions.

    return $bdd->query($req)->fetchAll(PDO::FETCH_ASSOC);
    // Retourne la liste.
}

function GetAdminParcStatus()
{
    // Définit la fonction de synthèse du parc pour l'interface admin.
    $bdd = dbconnect();
    // Connexion à la base de données.

    $req = "SELECT tv.libelle_Type, COUNT(v.id_Vehicule) as total,
            SUM(CASE WHEN v.id_Vehicule NOT IN (
                SELECT p.id_vehicule FROM Participants p 
                JOIN reservation r ON p.id_reservation = r.id_Reservation 
                WHERE r.statut_reservation IN ('En cours', 'Confirmée', 'Réservée')
            ) THEN 1 ELSE 0 END) as dispo
            FROM vehicule v
            JOIN type_vehicule tv ON v.id_type_vehicule = tv.id_Type_Vehicule
            GROUP BY tv.libelle_Type";
    // Recalcule le stock disponible instantané par catégorie de véhicule.

    return $bdd->query($req)->fetchAll(PDO::FETCH_ASSOC);
    // Retourne les données de disponibilité.
}

// =======================
// ADMIN ACTIONS & EXPORTS
// =======================

function GetOneReservation($id)
{
    // Définit la fonction pour extraire le détail complet d'une seule réservation.
    $bdd = dbconnect();
    // Connexion à la base de données.

    $req = $bdd->prepare("SELECT r.*, c.Nom, c.Prenom, tv.libelle_Type, a.nom_Agence 
        FROM reservation r
        JOIN client_connecter c ON r.id_client = c.id_Client
        LEFT JOIN type_vehicule tv ON r.id_tarif = tv.id_Tarif
        LEFT JOIN Participants p ON r.id_Reservation = p.id_reservation
        LEFT JOIN vehicule v ON p.id_vehicule = v.id_Vehicule
        LEFT JOIN agence_location a ON v.id_agence = a.id_Agence
        WHERE r.id_Reservation = ? GROUP BY r.id_Reservation");
    // Prépare la récupération des infos client, véhicule et agence pour l'ID donné.
    $req->execute([$id]);
    // Exécute la requête.

    return $req->fetch(PDO::FETCH_ASSOC);
    // Retourne les détails.
}

function AdminUpdateStatutReservation($id, $statut)
{
    // Définit la fonction permettant à l'admin de forcer un statut.
    $bdd = dbconnect();
    // Connexion à la base de données.

    $req = $bdd->prepare("UPDATE reservation SET statut_reservation = ? WHERE id_Reservation = ?");
    // Prépare la modification du champ statut_reservation.

    if ($req->execute([$statut, $id])) {
        // Si la mise à jour SQL réussit.
        AddLog("Admin: Statut réserv. #$id changé en $statut");
        // Enregistre l'action administrative dans les logs.
        return true;
        // Succès.
    }

    return false;
    // Échec.
}

function AdminDeleteReservation($id)
{
    // Définit la fonction de suppression d'une réservation.
    $bdd = dbconnect();
    // Connexion à la base de données.

    $info = GetOneReservation($id);
    // Récupère les infos pour identifier le client dans le log avant destruction.
    $client = $info ? ($info['Nom'] . " " . $info['Prenom']) : "?";
    // Formate le nom du client.

    $bdd->prepare("DELETE FROM Participants WHERE id_reservation = ?")->execute([$id]);
    // Supprime d'abord tous les participants liés (intégrité référentielle).
    $res = $bdd->prepare("DELETE FROM reservation WHERE id_Reservation = ?")->execute([$id]);
    // Supprime ensuite la réservation elle-même.

    if ($res) {
        AddLog("Admin: Suppression réserv. #$id ($client)");
    }
    // Enregistre la suppression dans les logs si l'opération a abouti.

    return $res;
    // Retourne le résultat de la suppression.
}

// ==========================
// AUTOMATISATION DES STATUTS
// ==========================

function UpdateReservationStatusAuto()
{
    // Définit la fonction de mise à jour automatique des états selon l'horloge système.
    $bdd = dbconnect();
    // Connexion à la base de données.
    $now = date('Y-m-d H:i:s');
    // Récupère l'instant présent au format SQL.

    $reqEnCours = "UPDATE reservation 
                   SET statut_reservation = 'En cours' 
                   WHERE date_debut_location <= '$now' 
                   AND date_fin_location > '$now' 
                   AND statut_reservation IN ('Réservée', 'Confirmée')";
    // SQL : Passe à 'En cours' les réservations dont l'heure de début est passée mais pas la fin.
    $bdd->query($reqEnCours);
    // Exécute la mise à jour des locations actives.

    $reqTerminee = "UPDATE reservation 
                    SET statut_reservation = 'Terminée' 
                    WHERE date_fin_location <= '$now' 
                    AND statut_reservation IN ('En cours', 'Confirmée', 'Réservée')";
    // SQL : Passe à 'Terminée' les réservations dont l'heure de fin est dépassée.
    $bdd->query($reqTerminee);
    // Exécute la clôture des locations.
}

function AddLog($msg)
{
    // Définit la fonction d'écriture dans le journal système.
    $bdd = dbconnect();
    // Connexion à la base de données.
    $bdd->prepare("INSERT INTO Logs (message, date_log) VALUES (?, NOW())")->execute([$msg]);
    // Insère le message avec l'horodatage actuel.
}

function GetLogs()
{
    // Définit la fonction pour récupérer un extrait court des logs.
    $bdd = dbconnect();
    // Connexion à la base de données.

    return $bdd->query("SELECT * FROM Logs ORDER BY date_log DESC LIMIT 7")->fetchAll(PDO::FETCH_ASSOC);
    // Retourne les 7 entrées les plus récentes pour l'affichage dashboard.
}

function GetAllLogs()
{
    // Définit la fonction pour récupérer l'historique complet des logs.
    $bdd = dbconnect();
    // Connexion à la base de données.
    // Même requête que GetLogs mais SANS le LIMIT

    return $bdd->query("SELECT * FROM Logs ORDER BY date_log DESC")->fetchAll(PDO::FETCH_ASSOC);
    // Retourne l'intégralité des journaux.
}

function GetDonneesExport()
{
    // Définit la fonction d'extraction pour l'export CSV.
    $bdd = dbconnect();
    // Connexion à la base de données.

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
    // Requête formatant les colonnes spécifiquement pour un tableur (date lisible, noms concaténés).

    return $bdd->query($req)->fetchAll(PDO::FETCH_ASSOC);
    // Retourne les données brutes de l'export.
}

function GetParticipantsByReservation($id_res)
{
    // Définit la fonction pour lister les personnes rattachées à une réservation.
    $bdd = dbconnect();
    // Connexion à la base de données.

    $req = $bdd->prepare("SELECT * FROM Participants WHERE id_reservation = ?");
    // Prépare la sélection filtrée par ID de réservation.
    $req->execute([$id_res]);
    // Exécute.

    return $req->fetchAll(PDO::FETCH_ASSOC);
    // Retourne la liste des participants.
}

function DeleteParticipant($id_part)
{
    // Définit la fonction pour retirer un participant individuel.
    $bdd = dbconnect();
    // Connexion à la base de données.

    // 1. On récupère les infos du participant AVANT de le supprimer
    $stmt = $bdd->prepare("SELECT Nom, Prenom, id_reservation FROM Participants WHERE id_Participants = ?");
    // Prépare la récupération du nom pour l'audit.
    $stmt->execute([$id_part]);
    // Exécute la recherche.

    $info = $stmt->fetch(PDO::FETCH_ASSOC);
    // Stocke les informations en mémoire.

    $nomComplet = $info ? ($info['Nom'] . " " . $info['Prenom']) : "Inconnu";
    // Prépare la chaîne du nom complet.
    $refRes = $info ? $info['id_reservation'] : "?";
    // Prépare la référence de la réservation associée.

    // 2. On effectue la suppression
    $req = $bdd->prepare("DELETE FROM Participants WHERE id_Participants = ?");
    // Prépare la suppression physique par ID.
    $success = $req->execute([$id_part]);
    // Exécute et stocke le résultat.

    // 3. Si ça a marché, on ajoute le log
    if ($success) {
        // Si la suppression a été validée.
        AddLog("Admin: Suppression participant $nomComplet (Réservation #$refRes)");
        // Écrit dans le journal des logs.
    }

    return $success;
    // Retourne l'état de réussite.
}

// 2. Stats Graphique (Courbe) avec filtre de dates
function GetStatsGraphique($dateDebut, $dateFin)
{
    // Définit la fonction de préparation des données pour un graphique chronologique.
    $bdd = dbconnect();
    // Connexion à la base de données.
    $stats = ['labels' => [], 'data' => []];
    // Initialise la structure attendue par la plupart des librairies graphiques (JS).

    $start = new DateTime($dateDebut);
    // Crée un objet date pour le début de la période.
    $end = new DateTime($dateFin);
    // Crée un objet date pour la fin de la période.

    // On ajoute 1 jour pour être sûr que la boucle inclue la date de fin
    $end->modify('+1 day');
    // Étend la borne de fin pour couvrir la journée entière de clôture.

    $period = new DatePeriod($start, new DateInterval('P1D'), $end);
    // Génère une itération jour par jour entre les deux dates.

    foreach ($period as $dt) {
        // Boucle sur chaque jour de la période.
        $dateSql = $dt->format('Y-m-d');
        // Formate la date pour la requête SQL.
        $label = $dt->format('d/m');
        // Formate le libellé pour l'axe X du graphique.

        // On compte les réservations faites CE JOUR LÀ
        $sql = "SELECT COUNT(*) FROM reservation WHERE DATE(Date_Reservation) = '$dateSql' AND statut_reservation != 'Annulée'";
        // Requête comptant les nouvelles réservations créées à cette date précise.
        $count = $bdd->query($sql)->fetchColumn();
        // Exécute et récupère le nombre.

        $stats['labels'][] = $label;
        // Ajoute le jour aux étiquettes.
        $stats['data'][] = (int)$count;
        // Ajoute le nombre de réservations aux valeurs de données.
    }

    return $stats;
    // Retourne l'ensemble structuré pour le graphique.
}
?>
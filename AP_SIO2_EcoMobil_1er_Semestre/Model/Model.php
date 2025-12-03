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

// Vérifie l'existence d'un email (optimisé avec COUNT)
function EmailExists($Mail) {
    $bdd = dbconnect();
    $req = "SELECT COUNT(*) AS count FROM client_connecter WHERE Mail = :mail";
    $res = $bdd->prepare($req);
    $res->execute(['mail' => $Mail]);
    $row = $res->fetch(PDO::FETCH_ASSOC);
    return $row['count'] > 0;
}

// Ajoute un utilisateur (Requête préparée pour éviter injection SQL)
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

// Récupère toutes les infos d'un user (SELECT *)
function GetUserByMail($Mail)
{
    $bdd = dbconnect();
    $req = "SELECT * FROM client_connecter WHERE Mail = :mail";
    $res = $bdd->prepare($req);
    $res->execute(['mail' => $Mail]);
    return $res->fetch(PDO::FETCH_ASSOC);
}

// Récupère juste l'ID (plus léger quand on n'a besoin que de ça)
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
    return $row ? floatval($row['Tarif_Horaire']) : 0.0;
}

// ============================================================
// ALGORITHME DE RECHERCHE DE VÉHICULE
// ============================================================
function FindVehiculeDisponible($nom_agence, $libelle_type, $date_debut, $date_fin)
{
    $bdd = dbconnect();
    // Gestion formatage (ex: "Velo_Urbain" -> "Velo Urbain")
    $libelle_type = str_replace('_', ' ', $libelle_type);

    // Étape 1 : Sélectionner TOUS les véhicules qui correspondent à l'agence et au type
    // On utilise INNER JOIN pour lier les tables Vehicule, Agence et Type
    $req = "SELECT v.id_Vehicule, tv.id_Tarif
            FROM vehicule v
            INNER JOIN agence_location a ON v.id_agence = a.id_Agence
            INNER JOIN type_vehicule tv ON v.id_type_vehicule = tv.id_Type_Vehicule
            WHERE a.nom_Agence = :nom_agence 
            AND tv.libelle_Type = :libelle_type
            AND v.statut = 'disponible'"; // Uniquement ceux physiquement dispos

    $res = $bdd->prepare($req);
    $res->execute(['nom_agence' => $nom_agence, 'libelle_type' => $libelle_type]);
    $vehicules = $res->fetchAll(PDO::FETCH_ASSOC);

    if (empty($vehicules)) return null; // Rien trouvé dans cette agence

    // Étape 2 : Pour chaque véhicule trouvé, vérifier s'il est libre sur les dates demandées
    foreach ($vehicules as $vehicule) {
        if (IsVehiculeLibre($vehicule['id_Vehicule'], $date_debut, $date_fin)) {
            return $vehicule; // On retourne le premier libre trouvé
        }
    }
    return null; // Tous occupés
}

// Vérifie si un véhicule précis a des conflits de réservation
function IsVehiculeLibre($id_vehicule, $date_debut, $date_fin)
{
    $bdd = dbconnect();

    // LOGIQUE DE CHEVAUCHEMENT (OVERLAP) :
    // Une réservation bloque le créneau SI :
    // (Début Réservé < Ma Fin) ET (Fin Réservée > Mon Début)
    $req = "SELECT COUNT(*) as nb FROM reservation
            WHERE id_vehicule = :id_vehicule
            AND statut_reservation IN ('Réservée', 'En cours', 'confirmée')
            AND ((date_debut_location < :date_fin AND date_fin_location > :date_debut))";

    $res = $bdd->prepare($req);
    $res->execute(['id_vehicule' => $id_vehicule, 'date_debut' => $date_debut, 'date_fin' => $date_fin]);
    $row = $res->fetch(PDO::FETCH_ASSOC);

    // Si nb == 0, c'est qu'il n'y a aucun conflit. Le véhicule est libre.
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

// Récupère l'historique d'un client (avec les noms des agences/marques via JOIN)
function GetReservationsClient($id_client)
{
    $bdd = dbconnect();
    $req = "SELECT r.*, v.Marque, v.Modele, a.nom_Agence, tv.libelle_Type
            FROM reservation r
            INNER JOIN vehicule v ON r.id_vehicule = v.id_Vehicule
            INNER JOIN agence_location a ON v.id_Agence = a.id_Agence
            INNER JOIN type_vehicule tv ON v.id_type_vehicule = tv.id_Type_Vehicule
            WHERE r.id_client = :id_client
            ORDER BY r.date_debut_location DESC"; // Tri du plus récent au plus ancien

    $res = $bdd->prepare($req);
    $res->execute(['id_client' => $id_client]);

    return $res->fetchAll(PDO::FETCH_ASSOC);
}

// Stats pour les badges de disponibilité
// Compte les véhicules qui n'ont AUCUNE réservation active en ce moment
function GetDispoParType()
{
    $bdd = dbconnect();

    // On sélectionne type et quantité
    // WHERE id_Vehicule n'est PAS dans la liste des véhicules réservés
    $req = "SELECT tv.libelle_Type, COUNT(v.id_Vehicule) as nb
            FROM vehicule v
            INNER JOIN type_vehicule tv ON v.id_type_vehicule = tv.id_Type_Vehicule
            WHERE v.statut = 'disponible' 
            AND v.id_Vehicule NOT IN (
                SELECT id_vehicule FROM reservation 
                WHERE statut_reservation IN ('Réservée', 'En cours', 'confirmée')
            )
            GROUP BY tv.libelle_Type";

    $res = $bdd->prepare($req);
    $res->execute();
    return $res->fetchAll(PDO::FETCH_ASSOC);
}
?>
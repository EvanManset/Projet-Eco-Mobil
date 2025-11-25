<?php

// ---------------------------------------------------
// Fonction de connexion à la base de données Eco-Mobil
// ---------------------------------------------------

function dbconnect()
{
    try
    {
        $bdd = new PDO('mysql:host=localhost;dbname=Ecomobil', 'root', '',
            array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        //echo "CONNECTION a AIRSIO OK " . '</br>';
        return $bdd;
    }
    catch (Exception $e)
    {
        die('Erreur de connection à la base : ' . $e->getMessage());
    }
}


// ---------------------------------------------------
// Vérifie si un email existe déjà dans la base de données
// ---------------------------------------------------

function EmailExists($Mail) {
    $bdd = dbconnect();
    $request = "SELECT COUNT(*) AS count FROM client_connecter WHERE mail = :mail";
    $result = $bdd->prepare($request);
    $result->execute([
        'mail' => $Mail
    ]);

    // Récupère le résultat
    $row = $result->fetch(PDO::FETCH_ASSOC);

    // Retourne true si count > 0, sinon false
    return $row['count'] > 0;
}

// ---------------------------------------------------
// Ajoute un nouvel utilisateur dans la base de données
// ---------------------------------------------------

function AddUser($Mail, $Mot_de_Passe_Securiser, $Nom, $Prenom, $Telephone, $Adresse) {
    $bdd = dbconnect();

    $request = "INSERT INTO client_connecter 
                (Mail, Mot_de_Passe_Securiser, Nom, Prenom, Telephone, Adresse, Date_de_Creation) 
                VALUES (:Mail, :Mot_de_Passe_Securiser, :Nom, :Prenom, :Telephone, :Adresse, NOW())";

    $result = $bdd->prepare($request);
    $result->execute([
        'Mail' => $Mail,
        'Mot_de_Passe_Securiser' => $Mot_de_Passe_Securiser,
        'Nom' => $Nom,
        'Prenom' => $Prenom,
        'Telephone' => $Telephone,
        'Adresse' => $Adresse
    ]);

    return $result;
}

// ---------------------------------------------------
// Vérifie la connexion
// ---------------------------------------------------

function CheckLoginUser($Mail, $Mot_de_Passe_Securiser)
{
    $bdd = dbconnect();

    // Récupère l'utilisateur correspondant à cet email
    $request = "SELECT * FROM client_connecter WHERE Mail = :Mail";
    $result = $bdd->prepare($request);
    $result->execute(['Mail' => $Mail]);
    $user = $result->fetch(PDO::FETCH_ASSOC);

    // Si aucun utilisateur trouvé
    if (!$user) {
        return "email_not_found";
    }

    // Vérifie que le mot de passe correspond
    if ($user['Mot_de_Passe_Securiser'] !== $Mot_de_Passe_Securiser) {
        return "wrong_password";
    }

    return $user;
}

// ---------------------------------------------------
// Vérifie la disponibilité d'un véhicule
// ---------------------------------------------------
function CheckVehiculeDisponible($Type_Vehicule, $Agence, $Date_Debut, $Date_Fin, $Heure_Debut, $Heure_Fin)
{
    $bdd = dbconnect();

    // Vérifie s'il y a des réservations qui se chevauchent pour ce type de véhicule dans cette agence
    $request = "SELECT COUNT(*) AS count FROM reservation 
                WHERE Type_Vehicule = :Type_Vehicule 
                AND Agence = :Agence 
                AND Statut != 'Annulée'
                AND (
                    (Date_Debut <= :Date_Fin AND Date_Fin >= :Date_Debut)
                )";

    $result = $bdd->prepare($request);
    $result->execute([
        'Type_Vehicule' => $Type_Vehicule,
        'Agence' => $Agence,
        'Date_Debut' => $Date_Debut,
        'Date_Fin' => $Date_Fin
    ]);

    $row = $result->fetch(PDO::FETCH_ASSOC);

    // Si count = 0, le véhicule est disponible
    return $row['count'] == 0;
}

// ---------------------------------------------------
// Ajoute une nouvelle réservation
// ---------------------------------------------------
function AddReservation($Mail_Client, $Agence, $Type_Vehicule, $Date_Debut, $Date_Fin, $Heure_Debut, $Heure_Fin, $Options)
{
    $bdd = dbconnect();

    // Génère un numéro de réservation unique
    $Numero_Reservation = 'RES' . date('Ymd') . rand(1000, 9999);

    $request = "INSERT INTO reservation 
                (Numero_Reservation, Mail_Client, Agence, Type_Vehicule, Date_Debut, Date_Fin, Heure_Debut, Heure_Fin, Options, Statut, Date_Creation) 
                VALUES (:Numero_Reservation, :Mail_Client, :Agence, :Type_Vehicule, :Date_Debut, :Date_Fin, :Heure_Debut, :Heure_Fin, :Options, 'Réservé', NOW())";

    $result = $bdd->prepare($request);
    $result->execute([
        'Numero_Reservation' => $Numero_Reservation,
        'Mail_Client' => $Mail_Client,
        'Agence' => $Agence,
        'Type_Vehicule' => $Type_Vehicule,
        'Date_Debut' => $Date_Debut,
        'Date_Fin' => $Date_Fin,
        'Heure_Debut' => $Heure_Debut,
        'Heure_Fin' => $Heure_Fin,
        'Options' => $Options
    ]);

    return $result;
}

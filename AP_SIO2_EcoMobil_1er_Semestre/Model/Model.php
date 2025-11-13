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

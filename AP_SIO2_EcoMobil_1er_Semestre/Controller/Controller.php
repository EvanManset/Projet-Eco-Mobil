<?php
require('model/model.php');

function Signupuser($Nom, $Prenom, $Telephone, $Adresse, $Mail, $Mot_de_Passe_Securiser): bool
{
    // Vérifie que le mail est valide
    if (!filter_var($Mail, FILTER_VALIDATE_EMAIL)) {
        echo "<div class='error-message-standalone'>❌ Email incorrect</div>";
        return false;
    }

    // Vérifie si l’email existe déjà
    if (EmailExists($Mail)) {
        echo "<div class='error-message-standalone'>❌ Cet email est déjà utilisé</div>";
        return false;
    }

    // Vérifie si le mot de passe respecte les règles
    if (!PasswordValide($Mot_de_Passe_Securiser)) {
        echo "<div class='error-message-standalone'>❌ Mot de passe non conforme.<br></div>";
        echo "<div class='error-message-standalone'>⚠️ Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre, et un caractère spécial.<br></div>";
        return false;
    }

    // Crée le compte avec tous les paramètres
    if (AddUser($Mail, $Mot_de_Passe_Securiser, $Nom, $Prenom, $Telephone, $Adresse)) {
        $_SESSION['Mail'] = $Mail;
        $_SESSION['Nom'] = $Nom;
        $_SESSION['Prenom'] = $Prenom;
        return true;
    }

    return false;
}

function Loginuser($Mail, $Mot_de_Passe_Securiser): bool
{
    $user = CheckLoginUser($Mail, $Mot_de_Passe_Securiser);

    if ($user == "email_not_found") {
        return false;
    }

    if ($user == "wrong_password") {
        return false;
    }

    // Si login réussi
    $_SESSION['Mail'] = $user['Mail'];
    $_SESSION['Nom'] = $user['Nom'];
    $_SESSION['Prenom'] = $user['Prenom'];

    return true;
}

// ---------------------------------------------------
// Déconnexion de l'utilisateur
// ---------------------------------------------------
function LogoutUser()
{
    // Détruit toutes les variables de session
    session_unset();
    // Détruit la session
    session_destroy();
}

// ---------------------------------------------------
// Crée une nouvelle réservation
// ---------------------------------------------------
function CreateReservation($Mail_Client, $Agence, $Type_Vehicule, $Date_Debut, $Date_Fin, $Heure_Debut, $Heure_Fin, $Options)
{
    // Vérifie que les dates sont cohérentes
    $debut = strtotime($Date_Debut . ' ' . $Heure_Debut);
    $fin = strtotime($Date_Fin . ' ' . $Heure_Fin);

    if ($fin <= $debut) {
        echo "<div class='error-message-standalone'><br>❌ La date de fin doit être supérieur à la date de début<br></div>";
        return false;
    }

    // Vérifie la disponibilité du véhicule
    if (!CheckVehiculeDisponible($Type_Vehicule, $Agence, $Date_Debut, $Date_Fin, $Heure_Debut, $Heure_Fin)) {
        echo "<div class='error-message-standalone'><br>❌ Ce véhicule n'est pas disponible pour ces dates<br></div>";
        return false;
    }

    // Crée la réservation
    if (AddReservation($Mail_Client, $Agence, $Type_Vehicule, $Date_Debut, $Date_Fin, $Heure_Debut, $Heure_Fin, $Options)) {
        return true;
    }

    return false;
}

// ---------------------------------------------------
// Vérifie que le mot de passe est valide
// ---------------------------------------------------
function PasswordValide($password) {
    // Vérifie la longueur minimale
    if (strlen($password) < 8) {
        return false;
    }

    // Vérifie qu’il contient au moins une majuscule
    if (!preg_match('/[A-Z]/', $password)) {
        return false;
    }

    // Vérifie qu’il contient au moins une minuscule
    if (!preg_match('/[a-z]/', $password)) {
        return false;
    }

    // Vérifie qu’il contient au moins un chiffre
    if (!preg_match('/[0-9]/', $password)) {
        return false;
    }

    // Vérifie qu’il contient au moins un caractère spécial
    if (!preg_match('/[^A-Za-z0-9]/', $password)) {
        return false;
    }

    // Si toutes les conditions sont remplies, le mot de passe est valide
    return true;
}



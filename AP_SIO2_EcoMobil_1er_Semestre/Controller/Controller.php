<?php
require('model/model.php');

function Signupuser($Nom, $Prenom, $Telephone, $Adresse, $Mail, $Mot_de_Passe_Securiser): bool
{
    // Vérifie que le mail est valide
    if (!filter_var($Mail, FILTER_VALIDATE_EMAIL)) {
        echo "<br>❌ Email incorrect<br>";
        return false;
    }

    // Vérifie si l’email existe déjà
    if (EmailExists($Mail)) {
        echo "<br>❌ Cet email est déjà utilisé<br>";
        return false;
    }

    // Vérifie si le mot de passe respecte les règles
    if (!PasswordValide($Mot_de_Passe_Securiser)) {
        echo "<br>❌ Mot de passe non conforme.<br>";
        echo "Il doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre, et un caractère spécial.<br>";
        return false;
    }

    // Crée le compte avec tous les paramètres
    if (AddUser($Mail, $Mot_de_Passe_Securiser, $Nom, $Prenom, $Telephone, $Adresse)) {
        $_SESSION['Mail'] = $Mail;
        $_SESSION['Nom'] = $Nom;
        $_SESSION['Prenom'] = $Prenom;
        echo "<br>✅ Bienvenue " . $Prenom . " " . $Nom . "<br>";
        return true;
    }

    return false;
}

function Loginuser($Mail, $Mot_de_Passe_Securiser): bool
{
    $user = CheckLoginUser($Mail, $Mot_de_Passe_Securiser);

    if ($user == "email_not_found") {
        echo "<br>❌ Cet email n'existe pas<br>";
        return false;
    }

    if ($user == "wrong_password") {
        echo "<br>❌ Mot de passe incorrect<br>";
        return false;
    }

    // Si login réussi
    $_SESSION['Mail'] = $user['Mail'];
    $_SESSION['Nom'] = $user['Nom'];
    $_SESSION['Prenom'] = $user['Prenom'];

    echo "<br>✅ Connexion réussie, bienvenue " . $user['Prenom'] . " " . $user['Nom'] . "<br>";
    return true;
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



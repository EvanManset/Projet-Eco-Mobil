<?php

require('model/model.php');

// ============================================================
// 1. FONCTION D'INSCRIPTION
// ============================================================
function Signupuser($Nom, $Prenom, $Telephone, $Adresse, $Mail, $Mot_de_Passe_Securiser): bool
{
    if (!filter_var($Mail, FILTER_VALIDATE_EMAIL)) {
        echo "<div class='error-message-standalone'>❌ Email incorrect</div>";
        return false;
    }
    if (EmailExists($Mail)) {
        echo "<div class='error-message-standalone'>❌ Email déjà utilisé</div>";
        return false;
    }
    if (!PasswordValide($Mot_de_Passe_Securiser)) {
        echo "<div class='error-message-standalone'>❌ Le mot de passe ne respecte pas les critères de sécurité.</div>";
        return false;
    }

    $MdpHash = password_hash($Mot_de_Passe_Securiser, PASSWORD_DEFAULT);

    if (AddUser($Mail, $MdpHash, $Nom, $Prenom, $Telephone, $Adresse)) {
        $_SESSION['Mail'] = $Mail;
        $_SESSION['Nom'] = $Nom;
        $_SESSION['Prenom'] = $Prenom;
        return true;
    }
    return false;
}

// ============================================================
// 2. FONCTION DE CONNEXION
// ============================================================
function Loginuser($Mail, $Mot_de_Passe_Securiser)
{
    if (isset($_SESSION['blocked_time']) && time() < $_SESSION['blocked_time']) {
        $wait = ceil(($_SESSION['blocked_time'] - time()) / 60);
        echo "<div class='error-message-standalone'>⛔ Trop de tentatives. Réessayez dans environ $wait minute(s).</div>";
        return "locked";
    }

    if (!isset($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts'] = 0;
    }

    $user = GetUserByMail($Mail);

    if ($user && password_verify($Mot_de_Passe_Securiser, $user['Mot_de_Passe_Securiser'])) {
        $_SESSION['login_attempts'] = 0;
        unset($_SESSION['blocked_time']);
        $_SESSION['Mail'] = $user['Mail'];
        $_SESSION['Nom'] = $user['Nom'];
        $_SESSION['Prenom'] = $user['Prenom'];
        return true;
    } else {
        $_SESSION['login_attempts']++;
        if ($_SESSION['login_attempts'] >= 4) {
            $_SESSION['blocked_time'] = time() + 40;
            echo "<div class='error-message-standalone'>⛔ 4 échecs : Compte bloqué temporairement.</div>";
            return "locked";
        }
        echo "<div class='error-message-standalone'>❌ Identifiants incorrects. Tentative " . $_SESSION['login_attempts'] . "/4</div>";
        return false;
    }
}

// ============================================================
// 3. FONCTION DE DÉCONNEXION
// ============================================================
function LogoutUser() {
    session_unset();
    session_destroy();
}

// ============================================================
// 4. VALIDATION ÉTAPE 1
// ============================================================
function ValidateStep1($debut, $fin, $h_debut, $h_fin) {
    $dt_debut = $debut . ' ' . $h_debut . ':00';
    $dt_fin = $fin . ' ' . $h_fin . ':00';

    if (strtotime($dt_fin) <= strtotime($dt_debut)) {
        echo "<div class='error-message-standalone'>❌ La date/heure de fin doit être après la date de début.</div>";
        return false;
    }

    // On pourrait ajouter ici : vérifier si c'est pas dans le passé
    if (strtotime($dt_debut) < time()) {
        echo "<div class='error-message-standalone'>❌ Vous ne pouvez pas réserver dans le passé.</div>";
        return false;
    }

    return true;
}

// ============================================================
// 5. CRÉATION RÉSERVATION FINAL
// ============================================================
function CreateReservation($mail, $agence, $type, $debut, $fin, $h_debut, $h_fin, $speciale)
{
    $dt_debut = $debut . ' ' . $h_debut . ':00';
    $dt_fin = $fin . ' ' . $h_fin . ':00';

    // Double vérification sécurité (au cas où session manipulée)
    if (strtotime($dt_fin) <= strtotime($dt_debut)) {
        echo "<div class='error-message-standalone'>❌ Erreur de dates.</div>";
        return false;
    }

    $duree = round((strtotime($dt_fin) - strtotime($dt_debut)) / 3600, 2);

    $id_client = getIdClientByMail($mail);
    $vehicule = FindVehiculeDisponible($agence, $type, $dt_debut, $dt_fin);

    if (!$vehicule) {
        echo "<div class='error-message-standalone'>❌ Aucun véhicule de ce type n'est disponible pour ces dates dans cette agence.</div>";
        return false;
    }
    if (!$id_client) {
        echo "<div class='error-message-standalone'>❌ Erreur : Client introuvable.</div>";
        return false;
    }

    $id_v = $vehicule['id_Vehicule'];
    $id_t = $vehicule['id_Tarif'];

    $prix = GetPrixTarif($id_t);
    if ($prix <= 0) $prix = 10;

    $total = $duree * $prix;

    if (AddReservation($id_client, $id_v, $id_t, $dt_debut, $dt_fin, $duree, $speciale, $total)) {
        return true;
    }

    return false;
}

// Validateur de mot de passe
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
    // Interdit les mots de passes courants
    if (preg_match('/123456|123456789|12345678|password|qwerty123|qwerty1|111111|12345|secret|123123|1234567890|1234567|000000|abc123|password1|iloveyou|dragon|monkey|letmein|qwerty|admin/i', $password)) {
        return false;
    }
    // Interdit les espaces
    if (preg_match('/\s/', $password)) {
        return false;
    }
    // Vérifie qu’il contient au moins un caractère spécial
    if (!preg_match('/[^A-Za-z0-9]/', $password)) {
        return false;
    }

    return true;
}
?>
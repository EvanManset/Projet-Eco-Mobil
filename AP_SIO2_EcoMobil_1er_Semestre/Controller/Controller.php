<?php

require('model/model.php');

// ============================================================
// FONCTION D'INSCRIPTION
// ============================================================
function Signupuser($Nom, $Prenom, $Telephone, $Adresse, $Mail, $Mot_de_Passe_Securiser): bool
{
    // 1. Validation de l'email via un filtre PHP natif
    if (!filter_var($Mail, FILTER_VALIDATE_EMAIL)) {
        echo "<div class='error-message-standalone'>❌ Email incorrect</div>";
        return false;
    }
    // 2. Vérification métier : L'email existe-t-il déjà ?
    if (EmailExists($Mail)) {
        echo "<div class='error-message-standalone'>❌ Email déjà utilisé</div>";
        return false;
    }
    // 3. Vérification de la complexité du mot de passe
    if (!PasswordValide($Mot_de_Passe_Securiser)) {
        echo "<div class='error-message-standalone'>❌ Le mot de passe ne respecte pas les critères de sécurité</div>";
        return false;
    }

    // 4. Hachage du mot de passe (MODIFIÉ POUR PASSWORD_HASH)
    // PASSWORD_DEFAULT utilise l'algo le plus fort dispo (actuellement Bcrypt) et gère le sel automatiquement.
    $MdpHash = password_hash($Mot_de_Passe_Securiser, PASSWORD_DEFAULT);

    // 5. Envoi au modèle pour insertion en base
    if (AddUser($Mail, $MdpHash, $Nom, $Prenom, $Telephone, $Adresse)) {
        $_SESSION['Mail'] = $Mail;
        $_SESSION['Nom'] = $Nom;
        $_SESSION['Prenom'] = $Prenom;
        return true;
    }
    return false;
}

// ============================================================
// FONCTION DE CONNEXION (Avec sécurité anti-bruteforce)
// ============================================================
function Loginuser($Mail, $Mot_de_Passe_Securiser)
{
    // A. SÉCURITÉ : Vérification du blocage temporaire
    if (isset($_SESSION['blocked_time']) && time() < $_SESSION['blocked_time']) {
        $wait = ceil(($_SESSION['blocked_time'] - time()) / 60);
        echo "<div class='error-message-standalone'>⛔ Trop de tentatives. Réessayez dans $wait minute(s).</div>";
        return "locked";
    }

    // Initialisation du compteur d'essais
    if (!isset($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts'] = 0;
    }

    // B. Récupération des infos de l'utilisateur depuis la BDD
    $user = GetUserByMail($Mail);

    // C. Comparaison (MODIFIÉ POUR PASSWORD_VERIFY)
    // On ne hache plus le mot de passe ici. On donne le mot de passe en clair
    // et le hash stocké en BDD à password_verify qui fait le travail de comparaison.

    if ($user && password_verify($Mot_de_Passe_Securiser, $user['Mot_de_Passe_Securiser'])) {

        // SUCCÈS : On réinitialise la sécurité
        $_SESSION['login_attempts'] = 0;
        unset($_SESSION['blocked_time']);

        $_SESSION['Mail'] = $user['Mail'];
        $_SESSION['Nom'] = $user['Nom'];
        $_SESSION['Prenom'] = $user['Prenom'];
        return true;

    } else {
        // ÉCHEC : On incrémente le compteur de tentatives
        $_SESSION['login_attempts']++;

        if ($_SESSION['login_attempts'] >= 4) {
            // Blocage de 40 secondes (pour l'exemple)
            $_SESSION['blocked_time'] = time() + (40);
            echo "<div class='error-message-standalone'>⛔ 4 échecs : Compte bloqué temporairement.</div>";
            return "locked";
        }

        echo "<div class='error-message-standalone'>❌ Identifiants incorrects. Tentative " . $_SESSION['login_attempts'] . "/4</div>";
        return false;
    }
}

// Déconnexion simple
function LogoutUser() {
    session_unset();
    session_destroy();
}

// ============================================================
// FONCTION DE RÉSERVATION (Inchangée)
// ============================================================
function CreateReservation($mail, $agence, $type, $debut, $fin, $h_debut, $h_fin, $speciale)
{
    $dt_debut = $debut . ' ' . $h_debut . ':00';
    $dt_fin = $fin . ' ' . $h_fin . ':00';

    if (strtotime($dt_fin) <= strtotime($dt_debut)) {
        echo "<div class='error-message-standalone'>❌ La fin doit être après le début</div>";
        return false;
    }

    $duree = round((strtotime($dt_fin) - strtotime($dt_debut)) / 3600, 2);

    $id_client = getIdClientByMail($mail);
    $vehicule = FindVehiculeDisponible($agence, $type, $dt_debut, $dt_fin);

    if (!$vehicule || !$id_client) {
        echo "<div class='error-message-standalone'>❌ Indisponible pour ces dates ou type.</div>";
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
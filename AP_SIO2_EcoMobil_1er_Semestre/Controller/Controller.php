<?php

require('model/model.php'); // Lien vers la base de données

// ============================================================
// 1. FONCTION D'INSCRIPTION
// ============================================================
function Signupuser($Nom, $Prenom, $Telephone, $Adresse, $Mail, $Mot_de_Passe_Securiser): bool
{
    // A. Validation format Email
    if (!filter_var($Mail, FILTER_VALIDATE_EMAIL)) {
        echo "<div class='error-message-standalone'>❌ Email incorrect</div>";
        return false;
    }

    // B. Vérifie si l'email existe déjà en BDD
    if (EmailExists($Mail)) {
        echo "<div class='error-message-standalone'>❌ Email déjà utilisé</div>";
        return false;
    }

    // C. Vérifie la force du mot de passe
    if (!PasswordValide($Mot_de_Passe_Securiser)) {
        echo "<div class='error-message-standalone'>❌ Le mot de passe ne respecte pas les critères de sécurité.</div>";
        return false;
    }

    // D. HACHAGE DU MOT DE PASSE
    $MdpHash = password_hash($Mot_de_Passe_Securiser, PASSWORD_DEFAULT);

    // E. Insertion en base via le modèle
    if (AddUser($Mail, $MdpHash, $Nom, $Prenom, $Telephone, $Adresse)) {
        // Si l'inscription réussit, on connecte l'utilisateur automatiquement
        $_SESSION['Mail'] = $Mail;
        $_SESSION['Nom'] = $Nom;
        $_SESSION['Prenom'] = $Prenom;
        return true;
    }

    return false;
}

// ============================================================
// 2. FONCTION DE CONNEXION (Avec sécurité anti-bruteforce)
// ============================================================
function Loginuser($Mail, $Mot_de_Passe_Securiser)
{
    // A. SÉCURITÉ : Vérification du blocage temporaire
    if (isset($_SESSION['blocked_time']) && time() < $_SESSION['blocked_time']) {
        $wait = ceil(($_SESSION['blocked_time'] - time()) / 60);
        echo "<div class='error-message-standalone'>⛔ Trop de tentatives. Réessayez dans environ $wait minute(s).</div>";
        return "locked"; // Code spécial pour dire "bloqué"
    }

    // B. Initialise le compteur d'essais s'il n'existe pas
    if (!isset($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts'] = 0;
    }

    // C. Récupération des infos de l'utilisateur en BDD
    $user = GetUserByMail($Mail);

    // D. Comparaison du mot de passe
    if ($user && password_verify($Mot_de_Passe_Securiser, $user['Mot_de_Passe_Securiser'])) {

        // SUCCÈS : On remet les compteurs de sécurité à zéro
        $_SESSION['login_attempts'] = 0;
        unset($_SESSION['blocked_time']);

        // On remplit la session avec les infos utilisateur
        $_SESSION['Mail'] = $user['Mail'];
        $_SESSION['Nom'] = $user['Nom'];
        $_SESSION['Prenom'] = $user['Prenom'];

        return true;

    } else {
        // ÉCHEC : On augmente le compteur d'erreurs
        $_SESSION['login_attempts']++;

        // Si 4 échecs ou plus, on déclenche le blocage
        if ($_SESSION['login_attempts'] >= 4) {
            $_SESSION['blocked_time'] = time() + 40; // Bloqué pour 40 secondes
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
    session_unset();   // Vide les variables de session
    session_destroy(); // Détruit la session sur le serveur
}

// ============================================================
// 4. CRÉATION RÉSERVATION (Calculs et Vérifications)
// ============================================================
function CreateReservation($mail, $agence, $type, $debut, $fin, $h_debut, $h_fin, $speciale)
{
    // Création des chaînes DATETIME complètes pour SQL (ex: "2023-10-12 14:00:00")
    $dt_debut = $debut . ' ' . $h_debut . ':00';
    $dt_fin = $fin . ' ' . $h_fin . ':00';

    // A. Validation Temporelle : La fin doit être APRÈS le début
    if (strtotime($dt_fin) <= strtotime($dt_debut)) {
        echo "<div class='error-message-standalone'>❌ La date de fin doit être postérieure à la date de début.</div>";
        return false;
    }

    // Calcul durée en heures (Différence de timestamps / 3600 secondes)
    $duree = round((strtotime($dt_fin) - strtotime($dt_debut)) / 3600, 2);

    // B. Recherche Disponibilité (Algorithme complexe dans le Model)
    $id_client = getIdClientByMail($mail);
    $vehicule = FindVehiculeDisponible($agence, $type, $dt_debut, $dt_fin);

    // Si pas de véhicule trouvé ou pas de client
    if (!$vehicule) {
        echo "<div class='error-message-standalone'>❌ Aucun véhicule de ce type n'est disponible pour ces dates dans cette agence.</div>";
        return false;
    }
    if (!$id_client) {
        echo "<div class='error-message-standalone'>❌ Erreur : Client introuvable.</div>";
        return false;
    }

    // C. Calcul du Prix Total
    $id_v = $vehicule['id_Vehicule'];
    $id_t = $vehicule['id_Tarif'];

    $prix = GetPrixTarif($id_t); // Prix horaire
    if ($prix <= 0) $prix = 10; // Sécurité : prix par défaut si erreur BDD

    $total = $duree * $prix;

    // D. Enregistrement final en BDD
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
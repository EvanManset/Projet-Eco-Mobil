<?php

require('model/model.php');

// ============================================================
// FONCTION D'INSCRIPTION
// ============================================================
function Signupuser($Nom, $Prenom, $Telephone, $Adresse, $Mail, $Mot_de_Passe_Securiser): bool
{
    // Validation de l'email via un filtre PHP natif (très fiable)
    if (!filter_var($Mail, FILTER_VALIDATE_EMAIL)) {
        echo "<div class='error-message-standalone'>❌ Email incorrect</div>";
        return false;
    }
    // Vérification métier : L'email existe-t-il déjà ?
    if (EmailExists($Mail)) {
        echo "<div class='error-message-standalone'>❌ Email déjà utilisé</div>";
        return false;
    }
    // 3. Vérification de la complexité du mot de passe
    if (!PasswordValide($Mot_de_Passe_Securiser)) {
        echo "<div class='error-message-standalone'>❌ Mot de passe incorrect, il doit contenir au moins 8 caractères, une majuscule, une miniscule, un chiffre, un carctère spécial, pas de mot de passes courants et pas d'espaces </div>";
        return false;
    }

    // Hachage du mot de passe (SHA-256).
    $MdpHash = hash('sha256', $Mot_de_Passe_Securiser);

    // Envoi au modèle pour insertion en base
    if (AddUser($Mail, $MdpHash, $Nom, $Prenom, $Telephone, $Adresse)) {
        // Si l'insertion marche, on connecte l'utilisateur immédiatement
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
    // Vérification du blocage temporaire
    // Si un temps de blocage est défini et qu'on est encore avant la fin de ce temps
    if (isset($_SESSION['blocked_time']) && time() < $_SESSION['blocked_time']) {
        $wait = ceil(($_SESSION['blocked_time'] - time()) / 60); // Calcul du temps restant en minutes
        echo "<div class='error-message-standalone'>⛔ Trop de tentatives. Réessayez dans $wait minute(s).</div>";
        return "locked"; // On retourne un état spécifique
    }

    // Initialisation du compteur d'essais s'il n'existe pas encore pour cette session
    if (!isset($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts'] = 0;
    }

    // Récupération des infos de l'utilisateur depuis la BDD via son mail
    $user = GetUserByMail($Mail);

    // Hachage du mot de passe saisi pour le comparer à celui en base
    $MdpHash = hash('sha256', $Mot_de_Passe_Securiser);

    // On vérifie si l'utilisateur a été trouvé ($user n'est pas vide) et on vérifie si le hash calculé correspond au hash stocké
    if ($user && $user['Mot_de_Passe_Securiser'] === $MdpHash) {

        // SUCCÈS : On réinitialise la sécurité (compteur à 0, blocage supprimé)
        $_SESSION['login_attempts'] = 0;
        unset($_SESSION['blocked_time']);

        // On remplit la session avec les infos utiles
        $_SESSION['Mail'] = $user['Mail'];
        $_SESSION['Nom'] = $user['Nom'];
        $_SESSION['Prenom'] = $user['Prenom'];
        return true;

    } else {
        // ÉCHEC : On incrémente le compteur de tentatives
        $_SESSION['login_attempts']++;

        // Si on atteint 4 échecs consécutifs
        if ($_SESSION['login_attempts'] >= 4) {
            // On définit un temps de blocage (40 secondes)
            $_SESSION['blocked_time'] = time() + (40);
            echo "<div class='error-message-standalone'>⛔ 4 échecs : Compte bloqué temporairement.</div>";
            return "locked";
        }

        echo "<div class='error-message-standalone'>❌ Identifiants incorrects. Tentative " . $_SESSION['login_attempts'] . "/4</div>";
        return false;
    }
}

// ============================================================
// FONCTION DE DECONNECTION
// ============================================================
function LogoutUser() {
    session_unset();    // Vide les variables
    session_destroy();  // Détruit l'identifiant de session
}

// ============================================================
// FONCTION DE RÉSERVATION
// ============================================================
function CreateReservation($mail, $agence, $type, $debut, $fin, $h_debut, $h_fin, $speciale)
{
    // Concaténation pour créer des formats DATETIME SQL (YYYY-MM-DD HH:MM:SS)
    $dt_debut = $debut . ' ' . $h_debut . ':00';
    $dt_fin = $fin . ' ' . $h_fin . ':00';

    // Validation temporelle : La fin doit être après le début
    if (strtotime($dt_fin) <= strtotime($dt_debut)) {
        echo "<div class='error-message-standalone'>❌ La fin doit être après le début</div>";
        return false;
    }

    // Calcul de la durée en heures
    $duree = round((strtotime($dt_fin) - strtotime($dt_debut)) / 3600, 2);

    // Récupération de l'ID client
    $id_client = getIdClientByMail($mail);

    // Algorithme de recherche de véhicule
    // Cette fonction vérifie le type, l'agence et surtout les conflits de dates
    $vehicule = FindVehiculeDisponible($agence, $type, $dt_debut, $dt_fin);

    // Si aucun véhicule n'est dispo ou si le client n'est pas trouvé
    if (!$vehicule || !$id_client) {
        echo "<div class='error-message-standalone'>❌ Indisponible pour ces dates ou type.</div>";
        return false;
    }

    $id_v = $vehicule['id_Vehicule'];
    $id_t = $vehicule['id_Tarif'];

    // Récupération du prix unitaire
    $prix = GetPrixTarif($id_t);
    if ($prix <= 0) $prix = 10; // Valeur par défaut de sécurité

    // Calcul du montant total
    $total = $duree * $prix;

    // Enregistrement final en BDD
    if (AddReservation($id_client, $id_v, $id_t, $dt_debut, $dt_fin, $duree, $speciale, $total)) {
        return true;
    }
    return false;
}

// ============================================================
// FONCTION DE VERIFICATION DU MOT DE PASSE
// ============================================================

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
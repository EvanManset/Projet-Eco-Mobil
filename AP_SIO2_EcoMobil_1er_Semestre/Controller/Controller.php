<?php

require('model/model.php');

// ============================================================
// 1. FONCTION D'INSCRIPTION
// ============================================================
function Signupuser($Nom, $Prenom, $Telephone, $Adresse, $Mail, $Mot_de_Passe_Securiser)
{
    if (!filter_var($Mail, FILTER_VALIDATE_EMAIL)) {
        return "❌ Email incorrect";
    }
    if (EmailExists($Mail)) {
        return "❌ Cet email est déjà utilisé";
    }
    if (!PasswordValide($Mot_de_Passe_Securiser)) {
        return "❌ Le mot de passe ne respecte pas les critères de sécurité.";
    }

    $MdpHash = password_hash($Mot_de_Passe_Securiser, PASSWORD_DEFAULT);

    if (AddUser($Mail, $MdpHash, $Nom, $Prenom, $Telephone, $Adresse)) {
        $_SESSION['Mail'] = $Mail;
        $_SESSION['Nom'] = $Nom;
        $_SESSION['Prenom'] = $Prenom;
        return true; // Succès
    }

    return "❌ Erreur technique lors de l'inscription.";
}

// ============================================================
// 2. FONCTION DE CONNEXION
// ============================================================
function Loginuser($Mail, $Mot_de_Passe_Securiser)
{
    if (isset($_SESSION['blocked_time']) && time() < $_SESSION['blocked_time']) {
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
        $_SESSION['Role'] = $user['Role'];

        return true;
    } else {
        $_SESSION['login_attempts']++;
        if ($_SESSION['login_attempts'] >= 4) {
            $_SESSION['blocked_time'] = time() + 60;
            return "locked";
        }
        return false;
    }
}

function LogoutUser() {
    session_unset();
    session_destroy();
}

function ProcessPasswordReset($Mail, $NewPass, $ConfirmPass) {
    if (!EmailExists($Mail)) return false;
    if ($NewPass !== $ConfirmPass) return false;
    if (!PasswordValide($NewPass)) return false;
    $MdpHash = password_hash($NewPass, PASSWORD_DEFAULT);
    return UpdateUserPassword($Mail, $MdpHash);
}

function ValidateStep1($debut, $fin, $h_debut, $h_fin) {
    $dt_debut = $debut . ' ' . $h_debut . ':00';
    $dt_fin = $fin . ' ' . $h_fin . ':00';
    if (strtotime($dt_fin) <= strtotime($dt_debut)) return false;
    if (strtotime($dt_debut) < time()) return false;
    return true;
}

function CreateReservationGroup($mail, $agence, $type, $debut, $fin, $h_debut, $h_fin, $speciale, $participantsList)
{
    $dt_debut = $debut . ' ' . $h_debut . ':00';
    $dt_fin = $fin . ' ' . $h_fin . ':00';
    if (strtotime($dt_fin) <= strtotime($dt_debut)) return "Erreur date";
    $duree = round((strtotime($dt_fin) - strtotime($dt_debut)) / 3600, 2);
    $id_client = getIdClientByMail($mail);
    if (!$id_client) return "Client introuvable";

    $nb_participants = count($participantsList);
    $vehicules = GetVehiculesDisponibles($agence, $type, $dt_debut, $dt_fin, $nb_participants);

    if (count($vehicules) < $nb_participants) {
        return "Stock insuffisant.";
    }

    $id_tarif = $vehicules[0]['id_Tarif'];
    $prixUnitaire = GetPrixTarif($id_tarif);
    if ($prixUnitaire <= 0) $prixUnitaire = 10;
    $montantTotal = $duree * $prixUnitaire * $nb_participants;

    $id_Reservation = AddReservation($id_client, $id_tarif, $dt_debut, $dt_fin, $duree, $speciale, $montantTotal);
    if (!$id_Reservation) return "Erreur technique.";

    foreach ($participantsList as $index => $nomComplet) {
        $vehiculeAttribue = $vehicules[$index];
        $parts = explode(' ', trim($nomComplet), 2);
        $nom = $parts[0];
        $prenom = isset($parts[1]) ? $parts[1] : '';
        AddParticipant($nom, $prenom, $id_Reservation, $vehiculeAttribue['id_Vehicule']);
    }

    return true;
}

function ShowAdminDashboard() {
    // Vérification Admin
    if (!isset($_SESSION['Role']) || $_SESSION['Role'] !== 'admin') {
        header('Location: index.php');
        exit();
    }

    $stats = GetAdminStatsGlobales();
    $agences = GetAdminAgencesStatus();
    $reservations = GetReservationsRecentes();
    $participants = GetParticipantsRecents();
    $parc = GetAdminParcStatus();
    $logs = GetLogs();

    require('view/AdminDashboard.php');
}

function ShowAdminEditPage($id) {
    if (!isset($_SESSION['Role']) || $_SESSION['Role'] !== 'admin') {
        header('Location: index.php'); exit();
    }
    $reservation = GetOneReservation($id);
    if (!$reservation) {
        header('Location: index.php?action=admin'); exit();
    }
    require('view/AdminEditReservation.php');
}

function ProcessAdminUpdateRes($id, $statut) {
    if (!isset($_SESSION['Role']) || $_SESSION['Role'] !== 'admin') return;
    AdminUpdateStatutReservation($id, $statut);
    header('Location: index.php?action=admin');
    exit();
}

function ProcessAdminDeleteRes($id) {
    if (!isset($_SESSION['Role']) || $_SESSION['Role'] !== 'admin') return;
    AdminDeleteReservation($id);
    header('Location: index.php?action=admin');
    exit();
}

// ============================================================
// FONCTION UTILITAIRE : VALIDATION DE MOT DE PASSE
// ============================================================
function PasswordValide($password) {
    // Vérifie la longueur minimale (8 caractères)
    if (strlen($password) < 8) {
        return false;
    }
    // Vérifie qu’il contient au moins une majuscule (Regex [A-Z])
    if (!preg_match('/[A-Z]/', $password)) {
        return false;
    }
    // Vérifie qu’il contient au moins une minuscule (Regex [a-z])
    if (!preg_match('/[a-z]/', $password)) {
        return false;
    }
    // Vérifie qu’il contient au moins un chiffre (Regex [0-9])
    if (!preg_match('/[0-9]/', $password)) {
        return false;
    }
    // Interdit une liste noire de mots de passe courants (123456, password, admin, etc...)
    if (preg_match('/123456|123456789|12345678|password|qwerty123|qwerty1|111111|12345|secret|123123|1234567890|1234567|000000|abc123|password1|iloveyou|dragon|monkey|letmein|qwerty|admin|admin123|admin!123/i', $password)) {
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

    // Si tous les tests passent, le mot de passe est valide
    return true;
}
?>
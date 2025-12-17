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
    if (!isset($_SESSION['Role']) || $_SESSION['Role'] !== 'admin') {
        header('Location: index.php'); exit();
    }

    UpdateReservationStatusAuto();

    $filter = $_GET['filter'] ?? 'semaine';

    $dateFin = date('Y-m-d 23:59:59');

    switch ($filter) {
        case 'mois':
            $dateDebut = date('Y-m-d 00:00:00', strtotime('-30 days'));
            break;
        case 'annee':
            $dateDebut = date('Y-m-d 00:00:00', strtotime('-1 year'));
            break;
        case 'custom':
            if (!empty($_GET['start']) && !empty($_GET['end'])) {
                $dateDebut = $_GET['start'] . ' 00:00:00';
                $dateFin   = $_GET['end'] . ' 23:59:59';
            } else {
                $dateDebut = date('Y-m-d 00:00:00', strtotime('-7 days'));
            }
            break;
        case 'semaine':
        default:
            $dateDebut = date('Y-m-d 00:00:00', strtotime('-7 days'));
            break;
    }

    // Passage des dates corrigées aux modèles
    $stats = GetAdminStatsGlobales($dateDebut, $dateFin);
    $graphData = GetStatsGraphique($dateDebut, $dateFin);

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
    $participants_list = GetParticipantsByReservation($id);

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

function ShowAllLogsPage() {
    // Vérification de sécurité Admin
    if (!isset($_SESSION['Role']) || $_SESSION['Role'] !== 'admin') {
        header('Location: index.php');
        exit();
    }

    $logs = GetAllLogs(); // On récupère TOUT
    require('view/AdminLogs.php'); // On affiche la nouvelle vue
}

// ============================================================
// FONCTION UTILITAIRE : GÉNÉRATION ICS
// ============================================================
function GenerateICS($data) {
    // 1. Formatage des dates pour le format ICS (YYYYMMDDTHHMMSS)
    $start = date('Ymd\THis', strtotime($data['Date_Debut'] . ' ' . $data['Heure_Debut']));
    $end   = date('Ymd\THis', strtotime($data['Date_Fin'] . ' ' . $data['Heure_Fin']));

    // 2. Nettoyage des textes
    $summary = "Location EcoMobil - " . str_replace('_', ' ', $data['Vehicule']);
    $location = $data['Agence'];
    $description = "Votre location de véhicule chez EcoMobil.\nAgence: $location\nVéhicule: " . $data['Vehicule'];

    // 3. Contenu du fichier ICS
    $icsContent = "BEGIN:VCALENDAR\r\n";
    $icsContent .= "VERSION:2.0\r\n";
    $icsContent .= "PRODID:-//EcoMobil//Reservation//FR\r\n";
    $icsContent .= "BEGIN:VEVENT\r\n";
    $icsContent .= "UID:" . md5(uniqid(mt_rand(), true)) . "@ecomobil.com\r\n";
    $icsContent .= "DTSTAMP:" . date('Ymd\THis') . "\r\n";
    $icsContent .= "DTSTART:" . $start . "\r\n";
    $icsContent .= "DTEND:" . $end . "\r\n";
    $icsContent .= "SUMMARY:" . $summary . "\r\n";
    $icsContent .= "DESCRIPTION:" . $description . "\r\n";
    $icsContent .= "LOCATION:" . $location . "\r\n";
    $icsContent .= "END:VEVENT\r\n";
    $icsContent .= "END:VCALENDAR";

    // 4. Forcer le téléchargement
    header('Content-Type: text/calendar; charset=utf-8');
    header('Content-Disposition: attachment; filename="reservation_ecomobil.ics"');

    echo $icsContent;
    exit();
}

// --- EXPORT CSV ---
function ExportCSV() {
    // 1. Récupération des données
    $data = GetDonneesExport();

    // 2. En-têtes pour forcer le téléchargement
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=Rapport_CA_EcoMobil.csv');

    // 3. Création du fichier en mémoire
    $output = fopen('php://output', 'w');

    // BOM pour qu'Excel lise bien les accents
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

    // Entêtes des colonnes
    fputcsv($output, ['ID', 'Date', 'Client', 'Vehicule', 'Montant (EUR)'], ';');

    // Boucle sur les données
    foreach ($data as $row) {
        fputcsv($output, $row, ';');
    }

    fclose($output);
    exit(); // On arrête tout pour ne pas afficher le reste du HTML
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
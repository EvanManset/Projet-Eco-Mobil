<?php

require('model/model.php');

// =========================
// 1. FONCTION D'INSCRIPTION
// =========================

function Signupuser($Nom, $Prenom, $Telephone, $Adresse, $Mail, $Mot_de_Passe_Securiser)
// Définit la fonction de création de compte avec six paramètres utilisateur.
{
    if (!filter_var($Mail, FILTER_VALIDATE_EMAIL)) {
        // Utilise une fonction pour vérifier si le format de l'adresse email est valide.
        return "❌ Email incorrect";
        // Retourne un message d'erreur si l'email ne respecte pas le format standard.
    }

    if (EmailExists($Mail)) {
        // Appelle une fonction du modèle pour vérifier si l'email existe déjà dans la base de données.
        return "❌ Cet email est déjà utilisé";
        // Retourne une erreur pour empêcher les comptes en double.
    }

    if (!PasswordValide($Mot_de_Passe_Securiser)) {
        // Appelle la fonction locale de validation pour vérifier la complexité du mot de passe.
        return "❌ Le mot de passe ne respecte pas les critères de sécurité.";
        // Retourne une erreur si le mot de passe est trop simple ou non conforme.
    }

    $MdpHash = password_hash($Mot_de_Passe_Securiser, PASSWORD_DEFAULT);
    // Crypte le mot de passe en utilisant l'algorithme de hachage sécurisé par défaut de PHP.

    if (AddUser($Mail, $MdpHash, $Nom, $Prenom, $Telephone, $Adresse)) {
        // Tente d'insérer le nouvel utilisateur en base de données via la fonction du modèle.
        $_SESSION['Mail'] = $Mail;
        // Stocke l'email de l'utilisateur dans la session pour l'identifier immédiatement.
        $_SESSION['Nom'] = $Nom;
        // Stocke le nom de l'utilisateur dans la session.
        $_SESSION['Prenom'] = $Prenom;
        // Stocke le prénom de l'utilisateur dans la session.
        return true; // Succès
        // Retourne vrai si l'insertion en base de données a été confirmée.
    }

    return "❌ Erreur technique lors de l'inscription.";
    // Retourne un message générique si l'insertion en base de données a échoué.
}

// ========================
// 2. FONCTION DE CONNEXION
// ========================

function Loginuser($Mail, $Mot_de_Passe_Securiser)
// Définit la fonction d'authentification prenant l'email et le mot de passe en paramètres.
{
    if (isset($_SESSION['blocked_time']) && time() < $_SESSION['blocked_time']) {
        // Vérifie si un temps de blocage est défini en session et si l'heure actuelle est inférieure à ce temps.
        return "locked";
        // Retourne un état "verrouillé" pour empêcher les tentatives de connexion répétées.
    }

    if (!isset($_SESSION['login_attempts'])) {
        // Vérifie si le compteur de tentatives de connexion n'est pas encore initialisé en session.
        $_SESSION['login_attempts'] = 0;
        // Initialise le compteur de tentatives à zéro.
    }

    $user = GetUserByMail($Mail);
    // Récupère les informations de l'utilisateur en base de données via son email.

    if ($user && password_verify($Mot_de_Passe_Securiser, $user['Mot_de_Passe_Securiser'])) {
        // Vérifie si l'utilisateur existe et si le mot de passe saisi correspond au hachage stocké.
        $_SESSION['login_attempts'] = 0;
        // Réinitialise le compteur de tentatives après un succès.
        unset($_SESSION['blocked_time']);
        // Supprime le marqueur de temps de blocage si l'utilisateur était précédemment bloqué.

        $_SESSION['Mail'] = $user['Mail'];
        // Enregistre l'email de l'utilisateur identifié en session.
        $_SESSION['Nom'] = $user['Nom'];
        // Enregistre le nom de l'utilisateur identifié en session.
        $_SESSION['Prenom'] = $user['Prenom'];
        // Enregistre le prénom de l'utilisateur identifié en session.
        $_SESSION['Role'] = $user['Role'];
        // Enregistre le rôle (admin/client) pour gérer les accès aux pages.

        return true;
        // Retourne vrai pour confirmer une connexion réussie.
    } else {
        // S'exécute si l'email n'existe pas ou si le mot de passe est incorrect.
        $_SESSION['login_attempts']++;
        // Incrémente le compteur de tentatives échouées.
        if ($_SESSION['login_attempts'] >= 4) {
            // Vérifie si le seuil de 4 tentatives échouées est atteint.
            $_SESSION['blocked_time'] = time() + 60;
            // Définit un blocage de 60 secondes (temps actuel + 60s).
            return "locked";
            // Retourne l'état verrouillé.
        }
        return false;
        // Retourne faux pour indiquer une erreur d'identifiants sans blocage immédiat.
    }
}

function LogoutUser()
{
    // Définit la fonction de déconnexion.
    session_unset();
    // Efface toutes les variables de session actives.
    session_destroy();
    // Détruit physiquement le fichier ou l'identifiant de session côté serveur.
}

function ProcessPasswordReset($Mail, $NewPass, $ConfirmPass)
{
    // Définit la fonction de réinitialisation du mot de passe.
    if (!EmailExists($Mail)) {
        return false;
    }
    // Vérifie si l'email existe, sinon arrête la procédure.

    if ($NewPass !== $ConfirmPass) {
        return false;
    }
    // Vérifie si les deux saisies du nouveau mot de passe sont identiques.

    if (!PasswordValide($NewPass)) {
        return false;
    }
    // Vérifie si le nouveau mot de passe respecte les critères de sécurité.

    $MdpHash = password_hash($NewPass, PASSWORD_DEFAULT);
    // Génère un nouveau hachage sécurisé pour le mot de passe.

    return UpdateUserPassword($Mail, $MdpHash);
    // Appelle le modèle pour mettre à jour le mot de passe et retourne le succès ou l'échec.
}

function ValidateStep1($debut, $fin, $h_debut, $h_fin)
{
    // Définit une fonction de validation chronologique pour la première étape de réservation.
    $dt_debut = $debut . ' ' . $h_debut . ':00';
    // Concatène la date et l'heure pour créer un horodatage de début.
    $dt_fin = $fin . ' ' . $h_fin . ':00';
    // Concatène la date et l'heure pour créer un horodatage de fin.

    if (strtotime($dt_fin) <= strtotime($dt_debut)) {
        return false;
    }
    // Vérifie que la fin n'est pas avant ou égale au début en comparant les timestamps.

    if (strtotime($dt_debut) < time()) {
        return false;
    }
    // Vérifie que la réservation ne commence pas dans le passé par rapport à l'heure actuelle.

    return true;
    // Retourne vrai si les dates sont logiques et futures.
}

function CreateReservationGroup($mail, $agence, $type, $debut, $fin, $h_debut, $h_fin, $speciale, $participantsList)
// Définit la fonction de création de réservation pour un groupe.
{
    $dt_debut = $debut . ' ' . $h_debut . ':00';
    // Prépare la chaîne de date et heure de début.
    $dt_fin = $fin . ' ' . $h_fin . ':00';
    // Prépare la chaîne de date et heure de fin.

    if (strtotime($dt_fin) <= strtotime($dt_debut)) {
        return "Erreur date";
    }
    // Sécurité supplémentaire sur la chronologie des dates.

    $duree = round((strtotime($dt_fin) - strtotime($dt_debut)) / 3600, 2);
    // Calcule la durée en heures en soustrayant les timestamps et en divisant par 3600 secondes.
    $id_client = getIdClientByMail($mail);
    // Récupère l'ID numérique du client propriétaire de la réservation.

    if (!$id_client) {
        return "Client introuvable";
    }
    // Arrête le processus si le compte client n'existe pas en base.

    $nb_participants = count($participantsList);
    // Compte le nombre de personnes pour lesquelles une réservation est demandée.
    $vehicules = GetVehiculesDisponibles($agence, $type, $dt_debut, $dt_fin, $nb_participants);
    // Recherche des véhicules de ce type libres dans cette agence sur ce créneau horaire.

    if (count($vehicules) < $nb_participants) {
        // Vérifie si le nombre de véhicules trouvés est suffisant pour le nombre de participants.
        return "Stock insuffisant.";
        // Retourne un message d'erreur si l'agence n'a pas assez de véhicules disponibles.
    }

    $id_tarif = $vehicules[0]['id_Tarif'];
    // Récupère l'identifiant du tarif associé au type de véhicule sélectionné.
    $prixUnitaire = GetPrixTarif($id_tarif);
    // Récupère le prix horaire défini pour ce tarif dans la base de données.

    if ($prixUnitaire <= 0) {
        $prixUnitaire = 10;
    }
    // Applique un prix de secours par défaut si la base de données ne renvoie pas de prix valide.

    $montantTotal = $duree * $prixUnitaire * $nb_participants;
    // Calcule le coût total : Durée x Prix x Nombre de véhicules.

    $id_Reservation = AddReservation($id_client, $id_tarif, $dt_debut, $dt_fin, $duree, $speciale, $montantTotal);
    // Crée l'entrée principale de la réservation et récupère l'ID généré.

    if (!$id_Reservation) {
        return "Erreur technique.";
    }
    // Arrête si l'insertion de l'en-tête de réservation a échoué.

    foreach ($participantsList as $index => $nomComplet) {
        // Boucle sur chaque nom de participant pour les enregistrer individuellement.
        $vehiculeAttribue = $vehicules[$index];
        // Assigne un véhicule spécifique parmi la liste des disponibles à ce participant.
        $parts = explode(' ', trim($nomComplet), 2);
        // Découpe le nom complet en deux parties (Nom et Prénom) basées sur l'espace.
        $nom = $parts[0];
        // Prend la première partie comme Nom.
        $prenom = isset($parts[1]) ? $parts[1] : '';
        // Prend la deuxième partie comme Prénom si elle existe, sinon vide.
        AddParticipant($nom, $prenom, $id_Reservation, $vehiculeAttribue['id_Vehicule']);
        // Enregistre le participant et le véhicule lié à la réservation dans la table dédiée.
    }

    return true;
    // Retourne vrai pour indiquer que tout le groupe a été réservé avec succès.
}

function ShowAdminDashboard()
{
    // Définit la fonction d'affichage du tableau de bord administrateur.
    if (!isset($_SESSION['Role']) || $_SESSION['Role'] !== 'admin') {
        // Vérifie si l'utilisateur n'a pas le rôle d'administrateur en session.
        header('Location: index.php');
        exit();
        // Redirige vers l'accueil et arrête le script pour protéger l'accès.
    }

    UpdateReservationStatusAuto();
    // Appelle une routine qui met à jour les statuts (ex: passé/en cours) selon l'heure actuelle.

    $filter = $_GET['filter'] ?? 'semaine';
    // Récupère la période de filtrage demandée dans l'URL, ou utilise 'semaine' par défaut.

    $dateFin = date('Y-m-d 23:59:59');
    // Définit la date de fin des statistiques à aujourd'hui, fin de journée.

    switch ($filter) {
        // Structure conditionnelle pour définir la date de début du filtre statistique.
        case 'mois':
            // Cas où le filtre est sur un mois.
            $dateDebut = date('Y-m-d 00:00:00', strtotime('-30 days'));
            // Calcule la date d'il y a 30 jours.
            break;
        // Sort du switch.

        case 'annee':
            // Cas où le filtre est sur un an.
            $dateDebut = date('Y-m-d 00:00:00', strtotime('-1 year'));
            // Calcule la date d'il y a 1 an.
            break;
        // Sort du switch.

        case 'custom':
            // Cas où l'utilisateur a choisi des dates manuelles.
            if (!empty($_GET['start']) && !empty($_GET['end'])) {
                // Vérifie que les deux dates de début et de fin personnalisées sont présentes.
                $dateDebut = $_GET['start'] . ' 00:00:00';
                // Utilise la date fournie en début de journée.
                $dateFin = $_GET['end'] . ' 23:59:59';
                // Utilise la date fournie en fin de journée.
            } else {
                // En cas de formulaire personnalisé vide, repli sur 7 jours.
                $dateDebut = date('Y-m-d 00:00:00', strtotime('-7 days'));
            }
            break;
        // Sort du switch.

        case 'semaine':
            // Cas par défaut ou explicite sur la semaine.
        default:
            // Gestion de tout autre cas imprévu.
            $dateDebut = date('Y-m-d 00:00:00', strtotime('-7 days'));
            // Définit la date à 7 jours en arrière.
            break;
        // Sort du switch.
    }

    // Passage des dates corrigées aux modèles
    $stats = GetAdminStatsGlobales($dateDebut, $dateFin);
    // Récupère les indicateurs clés (Chiffre d'affaires, total réservations) sur la période.
    $graphData = GetStatsGraphique($dateDebut, $dateFin);
    // Récupère les points de données pour générer un graphique d'activité.

    $agences = GetAdminAgencesStatus();
    // Récupère l'état d'activité par agence physique.
    $reservations = GetReservationsRecentes();
    // Récupère les dernières réservations effectuées dans le système.
    $participants = GetParticipantsRecents();
    // Récupère la liste des derniers participants enregistrés.
    $parc = GetAdminParcStatus();
    // Récupère l'état du parc de véhicules (disponibles, loués, maintenance).
    $logs = GetLogs();
    // Récupère les derniers événements de sécurité ou d'activité système.

    require('view/AdminDashboard.php');
    // Charge la vue de l'interface administrateur avec toutes les données collectées.
}

function ShowAdminEditPage($id)
{
    // Définit la fonction pour afficher l'édition d'une réservation précise.
    if (!isset($_SESSION['Role']) || $_SESSION['Role'] !== 'admin') {
        // Vérification de sécurité pour restreindre l'accès aux admins uniquement.
        header('Location: index.php');
        exit();
        // Redirection vers l'accueil en cas d'accès non autorisé.
    }
    $reservation = GetOneReservation($id);
    // Récupère les détails d'une seule réservation par son identifiant unique.
    $participants_list = GetParticipantsByReservation($id);
    // Récupère tous les participants liés à cette réservation spécifique.

    if (!$reservation) {
        // Vérifie si la réservation existe dans la base de données.
        header('Location: index.php?action=admin');
        exit();
        // Redirige vers le tableau de bord si l'ID ne correspond à rien.
    }
    require('view/AdminEditReservation.php');
    // Affiche le formulaire d'édition avec les données récupérées.
}

function ProcessAdminUpdateRes($id, $statut)
{
    // Définit l'action de mise à jour du statut d'une réservation par l'admin.
    if (!isset($_SESSION['Role']) || $_SESSION['Role'] !== 'admin') {
        return;
    }
    // Vérification de sécurité, arrête la fonction sans rien faire si non-admin.

    AdminUpdateStatutReservation($id, $statut);
    // Appelle le modèle pour changer le statut (Confirmé, Annulé, etc.) en base.
    header('Location: index.php?action=admin');
    // Redirige vers le tableau de bord après la mise à jour.
    exit();
    // Interrompt le script.
}

function ProcessAdminDeleteRes($id)
{
    // Définit l'action de suppression d'une réservation par l'admin.
    if (!isset($_SESSION['Role']) || $_SESSION['Role'] !== 'admin') {
        return;
    }
    // Vérification de sécurité admin.

    AdminDeleteReservation($id);
    // Appelle le modèle pour supprimer la réservation et ses participants associés.
    header('Location: index.php?action=admin');
    // Redirige vers le tableau de bord.
    exit();
    // Interrompt le script.
}

function ShowAllLogsPage()
{
    // Définit l'affichage de la page complète des journaux d'activité.
    // Vérification de sécurité Admin
    if (!isset($_SESSION['Role']) || $_SESSION['Role'] !== 'admin') {
        // Vérifie les droits d'administrateur.
        header('Location: index.php');
        // Redirige vers l'accueil si l'accès est frauduleux.
        exit();
        // Arrête le script.
    }

    $logs = GetAllLogs(); // On récupère TOUT
    // Appelle le modèle pour extraire l'intégralité de l'historique des actions enregistrées.
    require('view/AdminLogs.php'); // On affiche la nouvelle vue
    // Charge la vue dédiée à la consultation exhaustive des logs.
}

// ====================================
// FONCTION UTILITAIRE : GÉNÉRATION ICS
// ====================================

function GenerateICS($data)
{
    // Définit la fonction de création de fichier calendrier au format standard ICS.
    // 1. Formatage des dates pour le format ICS (YYYYMMDDTHHMMSS)
    $start = date('Ymd\THis', strtotime($data['Date_Debut'] . ' ' . $data['Heure_Debut']));
    // Convertit la date et l'heure de début en format compact compatible ICS.
    $end = date('Ymd\THis', strtotime($data['Date_Fin'] . ' ' . $data['Heure_Fin']));
    // Convertit la date et l'heure de fin en format compact compatible ICS.

    // 2. Nettoyage des textes
    $summary = "Location EcoMobil - " . str_replace('_', ' ', $data['Vehicule']);
    // Crée le titre de l'événement en remplaçant les underscores par des espaces.
    $location = $data['Agence'];
    // Définit le lieu de l'événement comme étant l'agence de location.
    $description = "Votre location de véhicule chez EcoMobil.\nAgence: $location\nVéhicule: " . $data['Vehicule'];
    // Construit le corps descriptif de l'événement avec les détails.

    // 3. Contenu du fichier ICS
    $icsContent = "BEGIN:VCALENDAR\r\n";
    // Ligne d'ouverture obligatoire du fichier calendrier.
    $icsContent .= "VERSION:2.0\r\n";
    // Définit la version de la spécification iCalendar utilisée.
    $icsContent .= "PRODID:-//EcoMobil//Reservation//FR\r\n";
    // Identifiant de l'application ayant créé le calendrier.
    $icsContent .= "BEGIN:VEVENT\r\n";
    // Début du bloc décrivant l'événement de réservation.
    $icsContent .= "UID:" . md5(uniqid(mt_rand(), true)) . "@ecomobil.com\r\n";
    // Génère un identifiant unique aléatoire pour éviter les doublons dans les calendriers clients.
    $icsContent .= "DTSTAMP:" . date('Ymd\THis') . "\r\n";
    // Horodatage de création du fichier (requis par la norme).
    $icsContent .= "DTSTART:" . $start . "\r\n";
    // Définit l'horodatage de début de l'événement.
    $icsContent .= "DTEND:" . $end . "\r\n";
    // Définit l'horodatage de fin de l'événement.
    $icsContent .= "SUMMARY:" . $summary . "\r\n";
    // Ajoute le titre de l'événement.
    $icsContent .= "DESCRIPTION:" . $description . "\r\n";
    // Ajoute le texte descriptif de l'événement.
    $icsContent .= "LOCATION:" . $location . "\r\n";
    // Ajoute la localisation géographique (l'agence).
    $icsContent .= "END:VEVENT\r\n";
    // Fermeture du bloc d'événement.
    $icsContent .= "END:VCALENDAR";
    // Fermeture finale du calendrier.

    // 4. Forcer le téléchargement
    header('Content-Type: text/calendar; charset=utf-8');
    // Indique au navigateur que le contenu est un fichier calendrier.
    header('Content-Disposition: attachment; filename="reservation_ecomobil.ics"');
    // Spécifie au navigateur de traiter le flux comme un fichier joint nommé.

    echo $icsContent;
    // Envoie le contenu généré vers le navigateur.
    exit();
    // Arrête le script pour éviter d'ajouter des caractères parasites en fin de fichier.
}

// ====================
// ==== EXPORT CSV ====
// ====================

function ExportCSV()
{
    // Définit la fonction de génération de rapport au format CSV.
    // 1. Récupération des données
    $data = GetDonneesExport();
    // Appelle le modèle pour extraire les données de chiffre d'affaires.

    // 2. En-têtes pour forcer le téléchargement
    header('Content-Type: text/csv; charset=utf-8');
    // Définit le type MIME pour un fichier CSV.
    header('Content-Disposition: attachment; filename=Rapport_CA_EcoMobil.csv');
    // Nomme le fichier téléchargé par l'utilisateur.

    // 3. Création du fichier en mémoire
    $output = fopen('php://output', 'w');
    // Ouvre le flux de sortie standard pour envoyer les données directement au navigateur.

    fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));
    // Écrit la marque d'ordre des octets pour assurer le bon rendu des caractères spéciaux.

    // Commentaire sur la ligne de titre du tableur.
    fputcsv($output, ['ID', 'Date', 'Client', 'Vehicule', 'Montant (EUR)'], ';');
    // Écrit la première ligne du CSV contenant les titres des colonnes, séparés par des points-virgules.

    // Boucle sur les données
    foreach ($data as $row) {
        // Parcourt chaque enregistrement récupéré en base.
        fputcsv($output, $row, ';');
        // Écrit une ligne de données dans le fichier en utilisant le point-virgule comme séparateur.
    }

    fclose($output);
    // Ferme le flux d'écriture.
    exit(); // On arrête tout pour ne pas afficher le reste du HTML
    // Stoppe l'exécution immédiate pour que le fichier CSV ne contienne aucun code HTML de la page.
}

// ================================================
// FONCTION UTILITAIRE : VALIDATION DE MOT DE PASSE
// ================================================
function PasswordValide($password)
{
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
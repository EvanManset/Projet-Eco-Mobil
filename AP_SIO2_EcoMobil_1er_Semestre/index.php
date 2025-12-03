<?php

session_start();

require('controller/controller.php');

// ============================================================
// SCÉNARIO 1 : INSCRIPTION (?action=signupsession)
// ============================================================
if (isset($_GET['action']) && $_GET['action'] == 'signupsession') {

    // Cas A : L'utilisateur a rempli le formulaire et cliqué sur "S'inscrire" (Méthode POST)
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        // Validation : On vérifie qu'aucun champ obligatoire n'est vide
        if (!empty($_POST['Nom']) && !empty($_POST['Prenom']) && !empty($_POST['Telephone']) &&
            !empty($_POST['Adresse']) && !empty($_POST['Mail']) && !empty($_POST['Mot_de_Passe_Securiser'])) {

            // On récupère les données dans des variables simples pour la lisibilité
            $Nom = $_POST['Nom'];
            $Prenom = $_POST['Prenom'];
            $Telephone = $_POST['Telephone'];
            $Adresse = $_POST['Adresse'];
            $Mail = $_POST['Mail'];
            $Mot_de_Passe_Securiser = $_POST['Mot_de_Passe_Securiser'];

            // Appel au Contrôleur : On lance la fonction d'inscription
            // Si elle retourne TRUE, tout est bon.
            if (Signupuser($Nom, $Prenom, $Telephone, $Adresse, $Mail, $Mot_de_Passe_Securiser)) {
                // Succès : Redirection vers l'accueil.
                header('Location: index.php');
                exit();
            } else {
                // Échec (ex: Email déjà pris) : On réaffiche le formulaire pour qu'il corrige
                require('view/Signup.php');
            }
        } else {
            // Cas B : Un champ est vide -> Message d'erreur + Réaffichage formulaire
            echo "<div class='error-message-standalone'>⚠️ Veuillez remplir tous les champs.</div>";
            require('view/Signup.php');
        }
    } else {
        // Cas C : L'utilisateur arrive juste sur la page (Méthode GET) -> On affiche le formulaire vierge
        require('view/Signup.php');
    }

// ============================================================
// SCÉNARIO 2 : CONNEXION (?action=loginpsession)
// ============================================================
} elseif (isset($_GET['action']) && $_GET['action'] == 'loginpsession') {

    // Si formulaire soumis (POST)
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Vérif champs remplis
        if (!empty($_POST['Mail']) && !empty($_POST['Mot_de_Passe_Securiser'])) {
            $Mail = $_POST['Mail'];
            $Mot_de_Passe_Securiser = $_POST['Mot_de_Passe_Securiser'];

            // Appel au Contrôleur.
            $loginResult = Loginuser($Mail, $Mot_de_Passe_Securiser);

            if ($loginResult === true) {
                // Connexion réussie -> Accueil
                header('Location: index.php');
                exit();
            } elseif ($loginResult == "locked") {
                // Compte bloqué temporairement -> On reste sur le login avec le message
                require('view/Login.php');
            } else {
                // Mot de passe faux -> On reste sur le login
                require('view/Login.php');
            }
        } else {
            echo "<div class='error-message-standalone'>⚠️ Veuillez remplir tous les champs.</div>";
            require('view/Login.php');
        }
    } else {
        // Affichage simple du formulaire (GET)
        require('view/Login.php');
    }

// ============================================================
// SCÉNARIO 3 : DÉCONNEXION (?action=logout)
// ============================================================
} elseif (isset($_GET['action']) && $_GET['action'] == 'logout') {
    LogoutUser(); // Vide la session côté serveur
    require('Menuprincipal.php');

// ============================================================
// SCÉNARIO 4 : RÉSERVATION (?action=reservationsession)
// ============================================================
} elseif (isset($_GET['action']) && $_GET['action'] == 'reservationsession') {

    // 1. SÉCURITÉ : Si l'utilisateur n'est pas connecté, on le redirige vers le login
    if (!isset($_SESSION['Mail'])) {
        echo "<div class='error-message-standalone'>❌ Vous devez être connecté pour faire une réservation</div>";
        require('view/Login.php');
        exit();
    }

    // 2. PRÉPARATION : On récupère les stocks de véhicules
    $dispoStats = GetDispoParType();

    // 3. TRAITEMENT : Si le formulaire est soumis
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        // Vérification de tous les champs requis
        if (!empty($_POST['Agence']) && !empty($_POST['Type_Vehicule']) &&
            !empty($_POST['Date_Debut']) && !empty($_POST['Date_Fin']) &&
            !empty($_POST['Heure_Debut']) && !empty($_POST['Heure_Fin'])) {

            // Récupération des données
            $Agence = $_POST['Agence'];
            $Type_Vehicule = $_POST['Type_Vehicule'];
            $Date_Debut = $_POST['Date_Debut'];
            $Date_Fin = $_POST['Date_Fin'];
            $Heure_Debut = $_POST['Heure_Debut'];
            $Heure_Fin = $_POST['Heure_Fin'];
            // Ternaire : Si demande spéciale vide, on met une chaîne vide ''
            $demande_speciale = !empty($_POST['Demande_speciale']) ? $_POST['Demande_speciale'] : '';
            $Mail_Client = $_SESSION['Mail']; // On utilise le mail de la session (plus sûr que le POST)

            // Appel de la logique complexe dans le Contrôleur
            $result = CreateReservation($Mail_Client, $Agence, $Type_Vehicule, $Date_Debut, $Date_Fin, $Heure_Debut, $Heure_Fin, $demande_speciale);

            if ($result == true) {
                // Succès -> Page de confirmation
                require('view/SuccessReservation.php');
                exit();
            } else {
                // Erreur (ex: plus de véhicule, dates incohérentes) -> On recharge le formulaire
                require('view/Reservation.php');
            }

        } else {
            echo "<div class='error-message-standalone'>⚠️ Veuillez remplir tous les champs obligatoires.</div>";
            require('view/Reservation.php');
        }
    } else {
        // Affichage initial du formulaire de réservation (premier chargement)
        require('view/Reservation.php');
    }

// ============================================================
// SCÉNARIO 5 : MES RÉSERVATIONS (?action=mesreservationsession)
// ============================================================
} elseif (isset($_GET['action']) && $_GET['action'] == 'mesreservationsession') {

    // Sécurité : connexion requise
    if (!isset($_SESSION['Mail'])) {
        echo "<div class='error-message-standalone'>❌ Connectez-vous pour voir vos réservations.</div>";
        require('view/Login.php');
        exit();
    }

    // On récupère l'ID du client grâce à son email stocké en session
    $id_client = getIdClientByMail($_SESSION['Mail']);

    // Si on a l'ID, on cherche ses réservations, sinon tableau vide
    if ($id_client) {
        $mesReservations = GetReservationsClient($id_client);
    } else {
        $mesReservations = [];
    }

    // On affiche la vue (le tableau HTML utilisera la variable $mesReservations)
    require('view/MesReservations.php');

// ============================================================
// SCÉNARIO PAR DÉFAUT : ACCUEIL
// ============================================================
} else {
    // Si aucune action n'est demandée, ou action inconnue -> Menu Principal
    require('Menuprincipal.php');
}
?>
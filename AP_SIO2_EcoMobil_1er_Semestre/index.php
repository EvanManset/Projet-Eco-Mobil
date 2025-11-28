<?php

session_start();

require('controller/controller.php');

// ============================================================
// ROUTAGE : INSCRIPTION
// On vérifie si l'URL contient ?action=signupsession
// ============================================================

if (isset($_GET['action']) && $_GET['action'] == 'signupsession') {

    // Si la méthode est POST, cela signifie que l'utilisateur a cliqué sur "S'inscrire" dans le formulaire
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        // Validation : On vérifie que tous les champs obligatoires ne sont pas vides
        if (!empty($_POST['Nom']) && !empty($_POST['Prenom']) && !empty($_POST['Telephone']) &&
            !empty($_POST['Adresse']) && !empty($_POST['Mail']) && !empty($_POST['Mot_de_Passe_Securiser'])) {

            // Nettoyage/Récupération des données du formulaire dans des variables simples
            $Nom = $_POST['Nom'];
            $Prenom = $_POST['Prenom'];
            $Telephone = $_POST['Telephone'];
            $Adresse = $_POST['Adresse'];
            $Mail = $_POST['Mail'];
            $Mot_de_Passe_Securiser = $_POST['Mot_de_Passe_Securiser'];

            // Appel à la fonction du Contrôleur (Signupuser).
            // Si elle renvoie TRUE (succès), on redirige. Sinon, l'erreur est gérée dans le 'else'.
            if (Signupuser($Nom, $Prenom, $Telephone, $Adresse, $Mail, $Mot_de_Passe_Securiser)) {
                // Succès : Redirection vers la page d'accueil pour éviter de renvoyer le formulaire
                header('Location: index.php');
                exit();
            } else {
                // Échec (ex: email déjà pris) : On réaffiche le formulaire d'inscription pour qu'il puisse réessayer
                require('view/Signup.php');
            }
        } else {
            // Cas où un champ est vide
            echo "<div class='error-message-standalone'>⚠️ Veuillez remplir tous les champs.</div>";
            require('view/Signup.php');
        }
    } else {
        // Si on n'est pas en POST (méthode GET), c'est qu'on veut juste VOIR le formulaire
        require('view/Signup.php');
    }

// ============================================================
// ROUTAGE : CONNEXION
// On vérifie si l'URL contient ?action=loginpsession
// ============================================================

} elseif (isset($_GET['action']) && $_GET['action'] == 'loginpsession') {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Vérification basique des champs
        if (!empty($_POST['Mail']) && !empty($_POST['Mot_de_Passe_Securiser'])) {
            $Mail = $_POST['Mail'];
            $Mot_de_Passe_Securiser = $_POST['Mot_de_Passe_Securiser'];

            // Appel au contrôleur. Notez que Loginuser renvoie true, false ou "locked"
            $loginResult = Loginuser($Mail, $Mot_de_Passe_Securiser);

            if ($loginResult == true) {
                // Connexion réussie
                header('Location: index.php');
                exit();
            } elseif ($loginResult == "locked") {
                // Cas spécifique : trop de tentatives, compte bloqué temporairement
                require('view/Login.php');
            } else {
                // Erreur classique (mauvais mot de passe)
                echo "<div class='error-message-standalone'>❌ Compte inexistant ou mot de passe incorrect</div>";
                require('view/Login.php');
            }
        } else {
            echo "<div class='error-message-standalone'>⚠️ Veuillez remplir tous les champs.</div>";
            require('view/Login.php');
        }
    } else {
        // Affichage simple du formulaire de connexion
        require('view/Login.php');
    }

// ============================================================
// ROUTAGE : DÉCONNEXION
// ============================================================


} elseif (isset($_GET['action']) && $_GET['action'] == 'logout') {
    // Appelle la fonction qui détruit la session
    LogoutUser();
    // Redirige ou affiche le menu principal
    require('Menuprincipal.php');


// ============================================================
// ROUTAGE : RÉSERVATION
// ============================================================

} elseif (isset($_GET['action']) && $_GET['action'] == 'reservationsession') {

    // Sécurité CRITIQUE : On empêche l'accès si l'utilisateur n'est pas connecté.
    if (!isset($_SESSION['Mail'])) {
        echo "<div class='error-message-standalone'>❌ Vous devez être connecté pour faire une réservation</div>";
        require('view/Login.php'); // On le renvoie vers la page de login
        exit(); // On arrête le script ici
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Vérification de la présence de toutes les données nécessaires à la réservation
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
            $demande_speciale = !empty($_POST['Demande_speciale']) ? $_POST['Demande_speciale'] : '';
            $Mail_Client = $_SESSION['Mail']; // On utilise l'email stocké en session

            // Appel au contrôleur pour la logique complexe
            $result = CreateReservation($Mail_Client, $Agence, $Type_Vehicule, $Date_Debut, $Date_Fin, $Heure_Debut, $Heure_Fin, $demande_speciale);

            if ($result == true) {
                // Si tout est bon, on affiche la vue de succès
                require('view/SuccessReservation.php');
                exit();
            } else {
                // Sinon (véhicule indisponible, erreur dates...), on recharge le formulaire
                require('view/Reservation.php');
            }

        } else {
            echo "<div class='error-message-standalone'>⚠️ Veuillez remplir tous les champs obligatoires.</div>";
            require('view/Reservation.php');
        }
    } else {
        // Affichage du formulaire de réservation
        require('view/Reservation.php');
    }

} else {
    require('Menuprincipal.php');
}
?>
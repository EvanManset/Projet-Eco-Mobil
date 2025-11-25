<?php
session_start();

require('controller/controller.php');

// On vérifie si une action est passée dans l'URL
if (isset($_GET['action']) && $_GET['action'] == 'signupsession') {

    // Vérifie que le formulaire a été soumis
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        // Si tous les champs sont remplis
        if (!empty($_POST['Nom']) &&
            !empty($_POST['Prenom']) &&
            !empty($_POST['Telephone']) &&
            !empty($_POST['Adresse']) &&
            !empty($_POST['Mail']) &&
            !empty($_POST['Mot_de_Passe_Securiser'])) {

            // On récupère les données du formulaire
            $Nom = $_POST['Nom'];
            $Prenom = $_POST['Prenom'];
            $Telephone = $_POST['Telephone'];
            $Adresse = $_POST['Adresse'];
            $Mail = $_POST['Mail'];
            $Mot_de_Passe_Securiser = $_POST['Mot_de_Passe_Securiser'];

            // On appelle la fonction Signupuser
            if (Signupuser($Nom, $Prenom, $Telephone, $Adresse, $Mail, $Mot_de_Passe_Securiser)) {
                // Si l'inscription réussit - redirige vers le menu principal
                header('Location: index.php');
                exit();
            } else {
                // Sinon, message d’erreur
                echo "<div class='error-message-standalone'>❌ Échec de l'inscription</div>";
                require('view/Signup.php');
            }

        } else {
            // Si l’utilisateur n’a pas rempli tous les champs
            echo "<div class='error-message-standalone'>⚠️ Veuillez remplir tous les champs.</div>";
            require('view/Signup.php');
        }

    } else {
        require('view/Signup.php');
    }

// Vérifie si l’action demandée est bien "loginpsession"
} elseif (isset($_GET['action']) && $_GET['action'] === 'loginpsession') {

    // Vérifie si le formulaire a été envoyé en méthode POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        if (!empty($_POST['Mail']) && !empty($_POST['Mot_de_Passe_Securiser'])) {

            // Récupère les données envoyées par le formulaire
            $Mail = $_POST['Mail'];
            $Mot_de_Passe_Securiser = $_POST['Mot_de_Passe_Securiser'];

            // Appelle la fonction du modèle pour vérifier les identifiants
            $user = CheckLoginUser($Mail, $Mot_de_Passe_Securiser);

            if ($user == "email_not_found" || $user == "wrong_password") {
                echo "<div class='error-message-standalone'>❌ Compte inexistant ou mot de passe incorrect</div>";
                require('view/Login.php'); // Recharge la page de login
            }
            // Sinon, la connexion est réussie
            else {
                // Stocke les infos utilisateur dans la session
                $_SESSION['Mail'] = $user['Mail'];
                $_SESSION['Nom'] = $user['Nom'];
                $_SESSION['Prenom'] = $user['Prenom'];

                // Redirige vers le menu principal
                header('Location: index.php');
                exit();
            }

        } else {
            echo "<div class='error-message-standalone'>⚠️ Veuillez remplir tous les champs.</div>";
            require('view/Login.php');
        }

    } else {
        require('view/Login.php');
    }

} elseif (isset($_GET['action']) && $_GET['action'] == 'logout') {

    // Appel de la fonction de déconnexion du contrôleur
    LogoutUser();

    // Retour au menu principal
    require('Menuprincipal.php');

} elseif (isset($_GET['action']) && $_GET['action'] == 'reservationsession') {

    // Vérifie que l'utilisateur est connecté
    if (!isset($_SESSION['Mail'])) {
        // Changement : Affichage du message DANS le contrôleur avant d'inclure la vue
        echo "<div class='error-message-standalone'>❌ Vous devez être connecté pour faire une réservation</div>";
        require('view/Login.php');
    }
    // Vérifie si le formulaire a été soumis
    elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {

        if (!empty($_POST['Agence']) &&
            !empty($_POST['Type_Vehicule']) &&
            !empty($_POST['Date_Debut']) &&
            !empty($_POST['Date_Fin']) &&
            !empty($_POST['Heure_Debut']) &&
            !empty($_POST['Heure_Fin'])) {

            $Agence = $_POST['Agence'];
            $Type_Vehicule = $_POST['Type_Vehicule'];
            $Date_Debut = $_POST['Date_Debut'];
            $Date_Fin = $_POST['Date_Fin'];
            $Heure_Debut = $_POST['Heure_Debut'];
            $Heure_Fin = $_POST['Heure_Fin'];
            $Options = !empty($_POST['Options']) ? $_POST['Options'] : '';
            $Mail_Client = $_SESSION['Mail'];

            // Appelle la fonction pour créer la réservation
            if (CreateReservation($Mail_Client, $Agence, $Type_Vehicule, $Date_Debut, $Date_Fin, $Heure_Debut, $Heure_Fin, $Options)) {
                echo "<br>✅ Réservation créée avec succès !<br>";
                echo "<p><a href='index.php'>Retourner à l'accueil</a></p>";
            } else {
                echo "<br>❌ Erreur lors de la création de la réservation<br>";
                require('view/Reservation.php');
            }

        } else {
            echo "<br>⚠️ Veuillez remplir tous les champs obligatoires.<br>";
            require('view/Reservation.php');
        }

    } else {
        require('view/Reservation.php');
    }

} else {
    require('Menuprincipal.php');
}
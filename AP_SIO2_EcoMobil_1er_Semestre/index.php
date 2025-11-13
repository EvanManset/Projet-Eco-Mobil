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
                // Si l'inscription réussit
                echo "<br>✅ Inscription réussie<br>";
                require('Menuprincipal.php'); // Redirection vers le menu principal
            } else {
                // Sinon, message d’erreur
                echo "<br>❌ Échec de l'inscription<br>";
                require('view/Signup.php');
            }

        } else {
            // Si l’utilisateur n’a pas rempli tous les champs
            echo "<br>Veuillez remplir tous les champs.<br>";
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
                echo "<br>❌ Compte inexistant ou mot de passe incorrect<br>";
                require('view/Login.php'); // Recharge la page de login
            }
            // Sinon, la connexion est réussie
            else {
                // Stocke les infos utilisateur dans la session
                $_SESSION['Mail'] = $user['Mail'];
                $_SESSION['Nom'] = $user['Nom'];
                $_SESSION['Prenom'] = $user['Prenom'];

                // Message de bienvenue
                echo "<br>✅ Connexion réussie, Bienvenue " . $user['Prenom'] . " " . $user['Nom'] . "<br>";

                require('Menuprincipal.php');
            }

        } else {
            echo "<br>Veuillez remplir tous les champs.<br>";
            require('view/Login.php');
        }

    } else {
        require('view/Login.php');
    }
    
} else {
    require('Menuprincipal.php');
}
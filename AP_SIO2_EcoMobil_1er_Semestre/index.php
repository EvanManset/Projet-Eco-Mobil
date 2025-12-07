<?php

session_start();

require('controller/controller.php');

// ============================================================
// SCÉNARIO 1 : INSCRIPTION (?action=signupsession)
// ============================================================
if (isset($_GET['action']) && $_GET['action'] == 'signupsession') {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (!empty($_POST['Nom']) && !empty($_POST['Prenom']) && !empty($_POST['Telephone']) &&
            !empty($_POST['Adresse']) && !empty($_POST['Mail']) && !empty($_POST['Mot_de_Passe_Securiser'])) {

            $Nom = $_POST['Nom'];
            $Prenom = $_POST['Prenom'];
            $Telephone = $_POST['Telephone'];
            $Adresse = $_POST['Adresse'];
            $Mail = $_POST['Mail'];
            $Mot_de_Passe_Securiser = $_POST['Mot_de_Passe_Securiser'];

            if (Signupuser($Nom, $Prenom, $Telephone, $Adresse, $Mail, $Mot_de_Passe_Securiser)) {
                header('Location: index.php');
                exit();
            } else {
                require('view/Signup.php');
            }
        } else {
            echo "<div class='error-message-standalone'>⚠️ Veuillez remplir tous les champs.</div>";
            require('view/Signup.php');
        }
    } else {
        require('view/Signup.php');
    }

// ============================================================
// SCÉNARIO 2 : CONNEXION (?action=loginpsession)
// ============================================================
} elseif (isset($_GET['action']) && $_GET['action'] == 'loginpsession') {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (!empty($_POST['Mail']) && !empty($_POST['Mot_de_Passe_Securiser'])) {
            $Mail = $_POST['Mail'];
            $Mot_de_Passe_Securiser = $_POST['Mot_de_Passe_Securiser'];

            $loginResult = Loginuser($Mail, $Mot_de_Passe_Securiser);

            if ($loginResult === true) {
                header('Location: index.php');
                exit();
            } elseif ($loginResult == "locked") {
                require('view/Login.php');
            } else {
                require('view/Login.php');
            }
        } else {
            echo "<div class='error-message-standalone'>⚠️ Veuillez remplir tous les champs.</div>";
            require('view/Login.php');
        }
    } else {
        require('view/Login.php');
    }

// ============================================================
// SCÉNARIO 3 : DÉCONNEXION (?action=logout)
// ============================================================
} elseif (isset($_GET['action']) && $_GET['action'] == 'logout') {
    LogoutUser();
    require('Menuprincipal.php');

// ============================================================
// SCÉNARIO 4 - ÉTAPE 1 : CHOIX AGENCE & DATES (?action=reservation_step1)
// ============================================================
} elseif (isset($_GET['action']) && $_GET['action'] == 'reservation_step1') {

    // 1. Sécurité : On vérifie que l'utilisateur est connecté
    if (!isset($_SESSION['Mail'])) {
        echo "<div class='error-message-standalone'>❌ Vous devez être connecté pour faire une réservation</div>";
        require('view/Login.php');
        exit();
    }

    // 2. Traitement du formulaire
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // On vérifie que tous les champs sont remplis
        if (!empty($_POST['Agence']) && !empty($_POST['Date_Debut']) && !empty($_POST['Date_Fin']) &&
            !empty($_POST['Heure_Debut']) && !empty($_POST['Heure_Fin'])) {

            // On valide la cohérence des dates (via le contrôleur)
            if (ValidateStep1($_POST['Date_Debut'], $_POST['Date_Fin'], $_POST['Heure_Debut'], $_POST['Heure_Fin'])) {

                // Tout est bon : on sauvegarde dans la SESSION pour l'étape suivante
                $_SESSION['reservation_step1'] = [
                    'Agence' => $_POST['Agence'],
                    'Date_Debut' => $_POST['Date_Debut'],
                    'Date_Fin' => $_POST['Date_Fin'],
                    'Heure_Debut' => $_POST['Heure_Debut'],
                    'Heure_Fin' => $_POST['Heure_Fin']
                ];

                // On redirige vers l'étape 2
                header('Location: index.php?action=reservation_step2');
                exit();

            } else {
                // Erreur de dates : on réaffiche le formulaire 1
                require('view/ReservationStep1.php');
            }
        } else {
            echo "<div class='error-message-standalone'>⚠️ Veuillez remplir tous les champs de l'étape 1.</div>";
            require('view/ReservationStep1.php');
        }
    } else {
        // Affichage par défaut (arrivée sur la page)
        require('view/ReservationStep1.php');
    }

// ============================================================
// SCÉNARIO 4 - ÉTAPE 2 : VÉHICULE & PARTICIPANTS (?action=reservation_step2)
// ============================================================
} elseif (isset($_GET['action']) && $_GET['action'] == 'reservation_step2') {

    // 1. Sécurité : Connexion requise + Etape 1 déjà faite
    if (!isset($_SESSION['Mail'])) { header('Location: index.php?action=loginpsession'); exit(); }
    if (!isset($_SESSION['reservation_step1'])) { header('Location: index.php?action=reservation_step1'); exit(); }

    // 2. On récupère les stocks pour l'affichage (badges)
    $dispoStats = GetDispoParType();

    // 3. Traitement du formulaire final
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        if (!empty($_POST['Type_Vehicule'])) {

            // On récupère les infos de l'étape 1 (SESSION) et de l'étape 2 (POST)
            $step1 = $_SESSION['reservation_step1'];
            $Mail_Client = $_SESSION['Mail'];
            $Type_Vehicule = $_POST['Type_Vehicule'];
            $demande_base = !empty($_POST['Demande_speciale']) ? $_POST['Demande_speciale'] : '';

            // --- GESTION DES PARTICIPANTS ---

            // On récupère la liste des participants du formulaire
            // On utilise array_filter pour supprimer les cases vides
            $participants = [];
            if (isset($_POST['Participant'])) {
                foreach($_POST['Participant'] as $p) {
                    if (trim($p) != "") {
                        $participants[] = trim($p);
                    }
                }
            }

            // Si la liste est vide, on ajoute une entrée vide pour dire "1 seule réservation pour moi"
            if (empty($participants)) {
                $participants = [''];
            }

            // --- BOUCLE DE RÉSERVATION ---

            $nb_reussites = 0;
            $erreurs = [];

            // Pour chaque personne dans la liste (soit les participants, soit juste moi)
            foreach ($participants as $nom) {

                // Si c'est un participant, on ajoute son nom dans le commentaire
                $info_speciale = $demande_base;
                if (!empty($nom)) {
                    $info_speciale = "Participant : $nom. \n" . $demande_base;
                }

                // On tente de créer la réservation via le Contrôleur
                $resultat = CreateReservation(
                    $Mail_Client,
                    $step1['Agence'],
                    $Type_Vehicule,
                    $step1['Date_Debut'],
                    $step1['Date_Fin'],
                    $step1['Heure_Debut'],
                    $step1['Heure_Fin'],
                    $info_speciale
                );

                if ($resultat == true) {
                    $nb_reussites++;
                } else {
                    // Si ça rate (ex: plus de stock), on note l'erreur et on arrête
                    $erreurs[] = "Erreur de réservation pour " . ($nom ? $nom : "vous");
                    break;
                }
            }

            // --- RÉSULTAT FINAL ---

            if ($nb_reussites > 0 && empty($erreurs)) {
                // Tout s'est bien passé
                unset($_SESSION['reservation_step1']); // On nettoie la session
                $nb_reservations = $nb_reussites; // Pour l'affichage "X véhicules réservés"
                require('view/SuccessReservation.php');
                exit();
            } else {
                // Il y a eu un problème
                $error_msg = "Une erreur est survenue. (Peut-être plus de stock ?)";
                require('view/ReservationStep2.php');
            }

        } else {
            echo "<div class='error-message-standalone'>⚠️ Veuillez choisir un véhicule.</div>";
            require('view/ReservationStep2.php');
        }
    } else {
        // Affichage par défaut du formulaire
        require('view/ReservationStep2.php');
    }

// ============================================================
// SCÉNARIO 5 : MES RÉSERVATIONS (?action=mesreservationsession)
// ============================================================
} elseif (isset($_GET['action']) && $_GET['action'] == 'mesreservationsession') {

    if (!isset($_SESSION['Mail'])) {
        echo "<div class='error-message-standalone'>❌ Connectez-vous pour voir vos réservations.</div>";
        require('view/Login.php');
        exit();
    }

    $id_client = getIdClientByMail($_SESSION['Mail']);

    if ($id_client) {
        $mesReservations = GetReservationsClient($id_client);
    } else {
        $mesReservations = [];
    }

    require('view/MesReservations.php');

// ============================================================
// SCÉNARIO PAR DÉFAUT : ACCUEIL
// ============================================================
} else {
    require('Menuprincipal.php');
}
?>
<?php

session_start();

require('controller/controller.php');

// ============================================================
// 1. ROUTAGE PRINCIPAL
// ============================================================

// --- SCÉNARIO : INSCRIPTION ---
if (isset($_GET['action']) && $_GET['action'] == 'signupsession') {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (!empty($_POST['Nom']) && !empty($_POST['Prenom']) && !empty($_POST['Telephone']) &&
            !empty($_POST['Adresse']) && !empty($_POST['Mail']) && !empty($_POST['Mot_de_Passe_Securiser'])) {

            $signupResult = Signupuser(
                $_POST['Nom'], $_POST['Prenom'], $_POST['Telephone'],
                $_POST['Adresse'], $_POST['Mail'], $_POST['Mot_de_Passe_Securiser']
            );

            if ($signupResult === true) {
                header('Location: index.php');
                exit();
            } else {
                $error_msg = $signupResult;
                require('view/Signup.php');
            }
        } else {
            $error_msg = "⚠️ Veuillez remplir tous les champs.";
            require('view/Signup.php');
        }
    } else {
        require('view/Signup.php');
    }

// --- SCÉNARIO : CONNEXION ---
} elseif (isset($_GET['action']) && $_GET['action'] == 'loginpsession') {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (!empty($_POST['Mail']) && !empty($_POST['Mot_de_Passe_Securiser'])) {

            $loginResult = Loginuser($_POST['Mail'], $_POST['Mot_de_Passe_Securiser']);

            if ($loginResult === true) {
                if (isset($_SESSION['Role']) && $_SESSION['Role'] === 'admin') {
                    header('Location: index.php?action=admin');
                } else {
                    header('Location: index.php');
                }
                exit();
            } else {
                require('view/Login.php');
            }
        } else {
            require('view/Login.php');
        }
    } else {
        require('view/Login.php');
    }

// --- SCÉNARIO : MOT DE PASSE OUBLIÉ ---
} elseif (isset($_GET['action']) && $_GET['action'] == 'forgotpassword') {
    require('view/ForgotPassword.php');

} elseif (isset($_GET['action']) && $_GET['action'] == 'ChangePassword') {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (ProcessPasswordReset($_POST['Mail'], $_POST['NewPassword'], $_POST['ConfirmPassword'])) {
            echo "<script>alert('Mot de passe modifié avec succès !'); window.location.href='index.php?action=loginpsession';</script>";
        } else {
            require('view/ForgotPassword.php');
        }
    } else {
        require('view/ForgotPassword.php');
    }

// --- SCÉNARIO : DÉCONNEXION ---
} elseif (isset($_GET['action']) && $_GET['action'] == 'logout') {
    LogoutUser(); // Assure-toi que cette fonction détruit bien la session
    header('Location: index.php');
    exit();

// --- SCÉNARIO : RÉSERVATION ÉTAPE 1 (CLIENT) ---
} elseif (isset($_GET['action']) && $_GET['action'] == 'reservation_step1') {
    if (!isset($_SESSION['Mail'])) {
        echo "<div class='error-message-standalone'>❌ Vous devez être connecté pour faire une réservation</div>";
        require('view/Login.php');
        exit();
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (!empty($_POST['Agence']) && !empty($_POST['Date_Debut']) && !empty($_POST['Date_Fin'])) {
            if (ValidateStep1($_POST['Date_Debut'], $_POST['Date_Fin'], $_POST['Heure_Debut'], $_POST['Heure_Fin'])) {
                $_SESSION['reservation_step1'] = [
                    'Agence' => $_POST['Agence'],
                    'Date_Debut' => $_POST['Date_Debut'],
                    'Date_Fin' => $_POST['Date_Fin'],
                    'Heure_Debut' => $_POST['Heure_Debut'],
                    'Heure_Fin' => $_POST['Heure_Fin']
                ];
                header('Location: index.php?action=reservation_step2');
                exit();
            } else {
                require('view/ReservationStep1.php');
            }
        } else {
            require('view/ReservationStep1.php');
        }
    } else {
        require('view/ReservationStep1.php');
    }

// --- SCÉNARIO : RÉSERVATION ÉTAPE 2 (CLIENT) ---
} elseif (isset($_GET['action']) && $_GET['action'] == 'reservation_step2') {
    if (!isset($_SESSION['Mail'])) { header('Location: index.php?action=loginpsession'); exit(); }
    if (!isset($_SESSION['reservation_step1'])) { header('Location: index.php?action=reservation_step1'); exit(); }

    $dispoStats = GetDispoParType();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (!empty($_POST['Type_Vehicule'])) {
            $step1 = $_SESSION['reservation_step1'];
            $Mail_Client = $_SESSION['Mail'];
            $Type_Vehicule = $_POST['Type_Vehicule'];
            $demande_speciale = $_POST['Demande_speciale'] ?? '';
            $participants = [];

            if (isset($_POST['Participant']) && is_array($_POST['Participant'])) {
                foreach ($_POST['Participant'] as $p) {
                    if (trim($p) != "") $participants[] = trim($p);
                }
            }
            if (empty($participants)) {
                $participants[] = $_SESSION['Nom'] . ' ' . $_SESSION['Prenom'];
            }

            $res = CreateReservationGroup($Mail_Client, $step1['Agence'], $Type_Vehicule, $step1['Date_Debut'], $step1['Date_Fin'], $step1['Heure_Debut'], $step1['Heure_Fin'], $demande_speciale, $participants);

            if ($res === true) {
                unset($_SESSION['reservation_step1']);
                require('view/SuccessReservation.php');
                exit();
            } else {
                $error_msg = $res;
                require('view/ReservationStep2.php');
            }
        } else {
            $error_msg = "Veuillez choisir un véhicule.";
            require('view/ReservationStep2.php');
        }
    } else {
        require('view/ReservationStep2.php');
    }

// --- SCÉNARIO : MES RÉSERVATIONS (CLIENT) ---
} elseif (isset($_GET['action']) && $_GET['action'] == 'mesreservationsession') {
    if (!isset($_SESSION['Mail'])) { require('view/Login.php'); exit(); }
    $id_client = getIdClientByMail($_SESSION['Mail']);
    $mesReservations = $id_client ? GetReservationsClient($id_client) : [];
    require('view/MesReservations.php');

// ============================================================
// 2. GESTION ADMIN (ROUTES PROTÉGÉES)
// ============================================================

// --- ADMIN : Page de modification (Formulaire) ---
} elseif (isset($_GET['action']) && $_GET['action'] == 'admin_edit_page') {
    if (isset($_GET['id'])) {
        ShowAdminEditPage($_GET['id']);
    } else {
        header('Location: index.php?action=admin');
    }

// --- ADMIN : Action de mise à jour (POST) ---
} elseif (isset($_GET['action']) && $_GET['action'] == 'admin_update_res') {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        ProcessAdminUpdateRes($_POST['id_res'], $_POST['statut']);
    }

// --- ADMIN : Action de suppression (GET) ---
} elseif (isset($_GET['action']) && $_GET['action'] == 'admin_delete_res') {
    if (isset($_GET['id'])) {
        ProcessAdminDeleteRes($_GET['id']);
    }

// --- ADMIN : Dashboard Principal ---
} elseif (isset($_GET['action']) && $_GET['action'] == 'admin') {
    ShowAdminDashboard();

// ============================================================
// SÉCURITÉ ADMIN : ISOLATION & PROTECTION
// ============================================================
// Si l'utilisateur est un ADMIN, on restreint son accès.
    if (isset($_SESSION['Role']) && $_SESSION['Role'] === 'admin') {

        $action = isset($_GET['action']) ? $_GET['action'] : '';

        // Liste blanche : Seules ces actions sont autorisées pour l'administrateur
        $allowed_admin_actions = [
            'admin',
            'logout',
            'admin_edit_page',
            'admin_update_res',
            'admin_delete_res'
        ];

        // Si l'action demandée n'est pas autorisée, on force la redirection vers le Dashboard
        if (!in_array($action, $allowed_admin_actions)) {
            header('Location: index.php?action=admin');
            exit();
        }
    }

// ============================================================
// 3. ROUTE PAR DÉFAUT (ACCUEIL)
// ============================================================
} else {
    require('Menuprincipal.php');
}
?>
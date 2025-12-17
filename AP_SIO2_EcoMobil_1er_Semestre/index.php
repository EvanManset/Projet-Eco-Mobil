<?php

session_start();

require('controller/controller.php');


// ============================================================
// 1. ROUTAGE PRINCIPAL
// ============================================================

if (isset($_GET['action'])) {
    $action = $_GET['action'];

    // --- SCÉNARIO : INSCRIPTION ---
    if ($action == 'signupsession') {

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
    } elseif ($action == 'loginpsession') {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!empty($_POST['Mail']) && !empty($_POST['Mot_de_Passe_Securiser'])) {

                $loginResult = Loginuser($_POST['Mail'], $_POST['Mot_de_Passe_Securiser']);

                if ($loginResult === true) {
                    // Redirection selon le rôle
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
    } elseif ($action == 'forgotpassword') {
        require('view/ForgotPassword.php');

    } elseif ($action == 'ChangePassword') {
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
    } elseif ($action == 'logout') {
        LogoutUser();
        header('Location: index.php');
        exit();

        // --- SCÉNARIO : RÉSERVATION ÉTAPE 1 (CLIENT) ---
    } elseif ($action == 'reservation_step1') {
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
    } elseif ($action == 'reservation_step2') {
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
                    $_SESSION['temp_ics'] = [
                        'Agence' => $step1['Agence'],
                        'Date_Debut' => $step1['Date_Debut'],
                        'Heure_Debut' => $step1['Heure_Debut'],
                        'Date_Fin' => $step1['Date_Fin'],
                        'Heure_Fin' => $step1['Heure_Fin'],
                        'Vehicule' => $Type_Vehicule
                    ];

                    unset($_SESSION['reservation_step1']);

                    // IMPORTANT : Variable nécessaire pour la vue de succès
                    $nb_reservations = count($participants);

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
    } elseif ($action == 'mesreservationsession') {
        if (!isset($_SESSION['Mail'])) { require('view/Login.php'); exit(); }
        $id_client = getIdClientByMail($_SESSION['Mail']);
        $mesReservations = $id_client ? GetReservationsClient($id_client) : [];
        require('view/MesReservations.php');

        // --- TÉLÉCHARGER CALENDRIER (.ICS) ---
    } elseif ($action == 'download_ics') {
        if (isset($_SESSION['temp_ics'])) {
            GenerateICS($_SESSION['temp_ics']);
        } else {
            header('Location: index.php');
        }

        // ============================================================
        // 2. GESTION ADMIN (ROUTES PROTÉGÉES)
        // ============================================================

        // --- ADMIN : Dashboard Principal ---
    } elseif ($action == 'admin') {
        ShowAdminDashboard();

        // --- ADMIN : Page de modification ---
    } elseif ($action == 'admin_edit_page') {
        if (isset($_GET['id'])) {
            ShowAdminEditPage($_GET['id']);
        } else {
            header('Location: index.php?action=admin');
        }

        // --- ADMIN : Action de mise à jour ---
    } elseif ($action == 'admin_update_res') {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            ProcessAdminUpdateRes($_POST['id_res'], $_POST['statut']);
        }

        // --- ADMIN : Action de suppression Réservation ---
    } elseif ($action == 'admin_delete_res') {
        if (isset($_GET['id'])) {
            ProcessAdminDeleteRes($_GET['id']);
        }

        // --- ADMIN : Action de suppression Participant (NOUVEAU) ---
    } elseif ($action == 'admin_delete_participant') {
        if (isset($_SESSION['Role']) && $_SESSION['Role'] === 'admin' && isset($_GET['id_part']) && isset($_GET['id_res'])) {
            DeleteParticipant($_GET['id_part']);
            // Redirection vers la page d'édition pour voir le résultat immédiatement
            header('Location: index.php?action=admin_edit_page&id=' . $_GET['id_res']);
            exit();
        } else {
            header('Location: index.php?action=admin');
            exit();
        }

        // --- ADMIN : Page de tous les LOGS (NOUVEAU) ---
    } elseif ($action == 'admin_all_logs') {
        ShowAllLogsPage();

        // --- ADMIN : EXPORT CSV ---
    } elseif ($action == 'export_csv') {
        ExportCSV();

    } else {
        // Route inconnue -> Accueil
        require('Menuprincipal.php');
    }

} else {
    // Pas d'action -> Accueil
    require('Menuprincipal.php');
}

// ============================================================
// SÉCURITÉ ADMIN : ISOLATION & PROTECTION
// ============================================================

// Vérification globale Admin à la fin pour sécuriser l'accès
// (Empêche l'accès direct aux fonctions admin via URL si pas connecté en admin)
if (isset($_SESSION['Role']) && $_SESSION['Role'] === 'admin') {

    $current_action = isset($_GET['action']) ? $_GET['action'] : '';

    // Liste blanche : Seules ces actions sont autorisées pour l'administrateur
    $allowed_admin_actions = [
        'admin',
        'logout',
        'admin_edit_page',
        'admin_update_res',
        'admin_delete_res',
        'admin_delete_participant',
        'admin_all_logs',
        'export_csv'
    ];

    // Si l'action demandée n'est pas autorisée pour un admin, retour au dashboard
    if (!empty($current_action) && !in_array($current_action, $allowed_admin_actions)) {
        header('Location: index.php?action=admin');
        exit();
    }
}
?>
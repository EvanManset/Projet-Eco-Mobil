<?php

session_start();

require('controller/controller.php');

if (isset($_GET['action'])) {
    // Vérifie si un paramètre "action" est présent dans l'URL via la méthode GET.
    $action = $_GET['action'];
    // Stocke la valeur du paramètre "action" dans une variable locale pour faciliter le traitement.

    // ================================
    // ==== SCÉNARIO : INSCRIPTION ====
    // ================================

    if ($action == 'signupsession') {
        // Vérifie si l'action demandée correspond à l'inscription d'un nouvel utilisateur.

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Vérifie si les données du formulaire ont été envoyées via la méthode POST.
            if (
                !empty($_POST['Nom']) &&
                !empty($_POST['Prenom']) &&
                !empty($_POST['Telephone']) &&
                !empty($_POST['Adresse']) &&
                !empty($_POST['Mail']) &&
                !empty($_POST['Mot_de_Passe_Securiser'])
            ) {
                // Vérifie que tous les champs requis du formulaire d'inscription sont remplis et non vides.

                $signupResult = Signupuser(
                    $_POST['Nom'],
                    $_POST['Prenom'],
                    $_POST['Telephone'],
                    $_POST['Adresse'],
                    $_POST['Mail'],
                    $_POST['Mot_de_Passe_Securiser']
                );
                // Appelle la fonction d'inscription avec les données du formulaire et stocke le résultat.

                if ($signupResult === true) {
                    // Vérifie si l'inscription a réussi.
                    header('Location: index.php');
                    // Redirige l'utilisateur vers la page d'accueil après une inscription réussie.
                    exit();
                    // Interrompt l'exécution du script pour s'assurer que la redirection est effectuée.
                } else {
                    // S'exécute si l'inscription a échoué ou a retourné un message d'erreur.
                    $error_msg = $signupResult;
                    // Stocke le message d'erreur retourné pour l'afficher dans la vue.
                    require('view/Signup.php');
                    // Charge la page d'inscription pour afficher l'erreur à l'utilisateur.
                }
            } else {
                // S'exécute si au moins un des champs obligatoires est vide.
                $error_msg = "⚠️ Veuillez remplir tous les champs.";
                // Définit un message d'avertissement pour l'utilisateur.
                require('view/Signup.php');
                // Recharge le formulaire d'inscription avec le message d'erreur.
            }
        } else {
            // S'exécute si la page est accédée normalement.
            require('view/Signup.php');
            // Affiche le formulaire d'inscription vide.
        }

        // ==============================
        // ==== SCÉNARIO : CONNEXION ====
        // ==============================

    } elseif ($action == 'loginpsession') {
        // Vérifie si l'action demandée correspond à la connexion d'un utilisateur.

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Vérifie si l'utilisateur a soumis le formulaire de connexion via POST.
            if (!empty($_POST['Mail']) && !empty($_POST['Mot_de_Passe_Securiser'])) {
                // Vérifie que les champs email et mot de passe ont bien été saisis.

                $loginResult = Loginuser($_POST['Mail'], $_POST['Mot_de_Passe_Securiser']);
                // Appelle la fonction de vérification des identifiants et récupère le résultat.

                if ($loginResult === true) {
                    // Vérifie si les identifiants sont corrects.
                    // Redirection selon le rôle
                    if (isset($_SESSION['Role']) && $_SESSION['Role'] === 'admin') {
                        // Vérifie si l'utilisateur connecté possède le rôle d'administrateur.
                        header('Location: index.php?action=admin');
                        // Redirige l'administrateur vers son tableau de bord spécifique.
                    } else {
                        // S'exécute pour tous les autres types d'utilisateurs (clients).
                        header('Location: index.php');
                        // Redirige l'utilisateur standard vers la page d'accueil.
                    }
                    exit();
                    // Arrête le script suite à la redirection.
                } else {
                    // S'exécute si l'authentification a échoué.
                    require('view/Login.php');
                    // Recharge la page de connexion (pour afficher une erreur potentielle).
                }
            } else {
                // S'exécute si l'un des champs de connexion est manquant.
                require('view/Login.php');
                // Affiche à nouveau la page de connexion.
            }
        } else {
            // S'exécute si la page de connexion est consultée pour la première fois.
            require('view/Login.php');
            // Affiche le formulaire de connexion.
        }

        // ========================================
        // ==== SCÉNARIO : MOT DE PASSE OUBLIÉ ====
        // ========================================

    } elseif ($action == 'forgotpassword') {
        // Vérifie si l'action demandée est l'accès au formulaire de mot de passe oublié.
        require('view/ForgotPassword.php');
        // Affiche la vue permettant de demander la réinitialisation du mot de passe.

    } elseif ($action == 'ChangePassword') {
        // Vérifie si l'action demandée est le changement effectif du mot de passe.
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Vérifie si les nouveaux mots de passe ont été envoyés par formulaire.
            if (ProcessPasswordReset($_POST['Mail'], $_POST['NewPassword'], $_POST['ConfirmPassword'])) {
                // Appelle la fonction de mise à jour et vérifie si le changement est validé.
                echo "<script>alert('Mot de passe modifié avec succès !'); window.location.href='index.php?action=loginpsession';</script>";
                // Affiche une alerte JavaScript de succès et redirige vers la connexion.
            } else {
                // S'exécute en cas d'échec du changement (ex: mots de passe non identiques).
                require('view/ForgotPassword.php');
                // Renvoie l'utilisateur vers le formulaire de réinitialisation.
            }
        } else {
            // S'exécute si la route est accédée sans soumission de formulaire.
            require('view/ForgotPassword.php');
            // Affiche la vue de réinitialisation.
        }

        // ================================
        // ==== SCÉNARIO : DÉCONNEXION ====
        // ================================

    } elseif ($action == 'logout') {
        // Vérifie si l'utilisateur souhaite se déconnecter.
        LogoutUser();
        // Appelle la fonction qui détruit les variables de session.
        header('Location: index.php');
        // Redirige l'utilisateur vers la page d'accueil.
        exit();

        // =================================================
        // ==== SCÉNARIO : RÉSERVATION ÉTAPE 1 (CLIENT) ====
        // =================================================

    } elseif ($action == 'reservation_step1') {
        // Vérifie si l'utilisateur accède à la première étape de réservation.
        if (!isset($_SESSION['Mail'])) {
            // Vérifie si l'utilisateur n'est pas connecté en vérifiant l'absence d'email en session.
            echo "<div class='error-message-standalone'>❌ Vous devez être connecté pour faire une réservation</div>";
            // Affiche un message d'erreur directement dans la page.
            require('view/Login.php');
            // Affiche la page de connexion pour forcer l'identification.
            exit();
            // Arrête l'exécution pour bloquer l'accès à la réservation.
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Vérifie si les critères de réservation (lieu, dates) ont été soumis.
            if (!empty($_POST['Agence']) && !empty($_POST['Date_Debut']) && !empty($_POST['Date_Fin'])) {
                // Vérifie que l'agence et les dates de début et de fin sont renseignées.
                if (ValidateStep1($_POST['Date_Debut'], $_POST['Date_Fin'], $_POST['Heure_Debut'], $_POST['Heure_Fin'])) {
                    // Appelle une fonction de validation pour vérifier la cohérence chronologique des dates/heures.
                    $_SESSION['reservation_step1'] = [
                        'Agence' => $_POST['Agence'],
                        'Date_Debut' => $_POST['Date_Debut'],
                        'Date_Fin' => $_POST['Date_Fin'],
                        'Heure_Debut' => $_POST['Heure_Debut'],
                        'Heure_Fin' => $_POST['Heure_Fin']
                    ];
                    // Stocke les informations de l'étape 1 en session pour les utiliser plus tard.
                    header('Location: index.php?action=reservation_step2');
                    // Redirige vers la deuxième étape du processus.
                    exit();
                    // Interrompt le script.
                } else {
                    // S'exécute si la validation des dates a échoué.
                    require('view/ReservationStep1.php');
                    // Recharge le formulaire de l'étape 1.
                }
            } else {
                // S'exécute si des champs obligatoires sont manquants.
                require('view/ReservationStep1.php');
                // Affiche à nouveau le formulaire de l'étape 1.
            }
        } else {
            // S'exécute lors du premier accès à la page de réservation.
            require('view/ReservationStep1.php');
            // Affiche le formulaire vierge de l'étape 1.
        }

        // =================================================
        // ==== SCÉNARIO : RÉSERVATION ÉTAPE 2 (CLIENT) ====
        // =================================================

    } elseif ($action == 'reservation_step2') {
        // Vérifie si l'utilisateur accède à la sélection du véhicule.
        if (!isset($_SESSION['Mail'])) {
            header('Location: index.php?action=loginpsession');
            exit();
        }
        // Sécurité : Redirige vers la connexion si l'utilisateur n'est pas identifié.

        if (!isset($_SESSION['reservation_step1'])) {
            header('Location: index.php?action=reservation_step1');
            exit();
        }
        // Sécurité : Redirige vers l'étape 1 si les données préalables sont absentes de la session.

        $dispoStats = GetDispoParType();
        // Récupère les statistiques ou listes de véhicules disponibles pour les afficher.

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Vérifie si l'utilisateur a sélectionné un véhicule et soumis le formulaire.
            if (!empty($_POST['Type_Vehicule'])) {
                // Vérifie que le type de véhicule a bien été choisi.
                $step1 = $_SESSION['reservation_step1'];
                // Récupère les données de la première étape stockées en session.
                $Mail_Client = $_SESSION['Mail'];
                // Récupère l'email de l'utilisateur connecté.
                $Type_Vehicule = $_POST['Type_Vehicule'];
                // Récupère le type de véhicule choisi dans le formulaire actuel.
                $demande_speciale = $_POST['Demande_speciale'] ?? '';
                // Récupère les notes spéciales (utilise une chaîne vide si non défini).
                $participants = [];
                // Initialise un tableau pour stocker les noms des participants à la réservation.

                if (isset($_POST['Participant']) && is_array($_POST['Participant'])) {
                    // Vérifie si une liste de participants a été envoyée.
                    foreach ($_POST['Participant'] as $p) {
                        // Parcourt chaque nom de participant soumis.
                        if (trim($p) != "") {
                            $participants[] = trim($p);
                        }
                        // Ajoute le participant au tableau s'il n'est pas composé uniquement d'espaces.
                    }
                }

                if (empty($participants)) {
                    // Si aucun participant n'a été saisi manuellement.
                    $participants[] = $_SESSION['Nom'] . ' ' . $_SESSION['Prenom'];
                    // Ajoute par défaut le nom et prénom de l'utilisateur connecté.
                }

                $res = CreateReservationGroup(
                    $Mail_Client,
                    $step1['Agence'],
                    $Type_Vehicule,
                    $step1['Date_Debut'],
                    $step1['Date_Fin'],
                    $step1['Heure_Debut'],
                    $step1['Heure_Fin'],
                    $demande_speciale,
                    $participants
                );
                // Appelle la fonction pour enregistrer la réservation et les participants dans la base de données.

                if ($res === true) {
                    // Vérifie si l'enregistrement en base de données a réussi.
                    $_SESSION['temp_ics'] = [
                        'Agence' => $step1['Agence'],
                        'Date_Debut' => $step1['Date_Debut'],
                        'Heure_Debut' => $step1['Heure_Debut'],
                        'Date_Fin' => $step1['Date_Fin'],
                        'Heure_Fin' => $step1['Heure_Fin'],
                        'Vehicule' => $Type_Vehicule
                    ];
                    // Prépare les données pour la génération ultérieure d'un fichier calendrier (.ics).

                    unset($_SESSION['reservation_step1']);
                    // Supprime les données temporaires de l'étape 1 car la réservation est finalisée.

                    $nb_reservations = count($participants);
                    // Compte le nombre de réservations créées pour l'affichage du récapitulatif.

                    require('view/SuccessReservation.php');
                    // Charge la vue confirmant le succès de l'opération.
                    exit();
                    // Interrompt le script.
                } else {
                    // S'exécute si une erreur est survenue lors de la création en base de données.
                    $error_msg = $res;
                    // Stocke l'erreur technique pour affichage.
                    require('view/ReservationStep2.php');
                    // Recharge le formulaire de l'étape 2.
                }
            } else {
                // S'exécute si l'utilisateur n'a pas coché de véhicule.
                $error_msg = "Veuillez choisir un véhicule.";
                // Définit le message d'erreur.
                require('view/ReservationStep2.php');
                // Recharge la vue de sélection.
            }
        } else {
            // S'exécute lors de l'arrivée sur la page (méthode GET).
            require('view/ReservationStep2.php');
            // Affiche la liste des véhicules disponibles pour l'étape 2.
        }

        // ==============================================
        // ==== SCÉNARIO : MES RÉSERVATIONS (CLIENT) ====
        // ==============================================

    } elseif ($action == 'mesreservationsession') {
        // Vérifie si l'utilisateur souhaite voir l'historique de ses propres réservations.
        if (!isset($_SESSION['Mail'])) {
            require('view/Login.php');
            exit();
        }
        // Vérifie la connexion et redirige vers la connexion si nécessaire.

        $id_client = getIdClientByMail($_SESSION['Mail']);
        // Récupère l'identifiant numérique du client à partir de son email de session.
        $mesReservations = $id_client ? GetReservationsClient($id_client) : [];
        // Si l'ID existe, récupère ses réservations, sinon initialise un tableau vide.
        require('view/MesReservations.php');
        // Affiche la vue listant les réservations du client.

        // =======================================
        // ==== TÉLÉCHARGER CALENDRIER (.ICS) ====
        // =======================================

    } elseif ($action == 'download_ics') {
        // Vérifie si l'utilisateur demande le téléchargement du fichier ICS.
        if (isset($_SESSION['temp_ics'])) {
            // Vérifie si les données de la dernière réservation sont présentes en session.
            GenerateICS($_SESSION['temp_ics']);
            // Appelle la fonction qui génère et envoie le fichier ICS au navigateur.
        } else {
            // S'exécute si aucune donnée de réservation récente n'est trouvée.
            header('Location: index.php');
            // Redirige vers l'accueil.
        }

        // =====================================
        // ==== ADMIN : Dashboard Principal ====
        // =====================================

    } elseif ($action == 'admin') {
        // Vérifie si l'action est l'accès au tableau de bord administrateur.
        ShowAdminDashboard();
        // Appelle la fonction du contrôleur qui affiche la vue admin principale.

        // ======================================
        // ==== ADMIN : Page de modification ====
        // ======================================

    } elseif ($action == 'admin_edit_page') {
        // Vérifie si l'admin souhaite éditer une réservation précise.
        if (isset($_GET['id'])) {
            // Vérifie que l'ID de la réservation est bien fourni dans l'URL.
            ShowAdminEditPage($_GET['id']);
            // Appelle la fonction affichant le formulaire de modification pour cette réservation.
        } else {
            // S'exécute si l'ID est manquant.
            header('Location: index.php?action=admin');
            // Redirige vers le tableau de bord admin par défaut.
        }

        // =======================================
        // ==== ADMIN : Action de mise à jour ====
        // =======================================

    } elseif ($action == 'admin_update_res') {
        // Vérifie si l'admin a validé le formulaire de modification.
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Vérifie que les données ont été envoyées via POST.
            ProcessAdminUpdateRes($_POST['id_res'], $_POST['statut']);
            // Appelle la fonction mettant à jour le statut de la réservation dans la base.
        }

        // ===================================================
        // ==== ADMIN : Action de suppression Réservation ====
        // ===================================================

    } elseif ($action == 'admin_delete_res') {
        // Vérifie si l'admin a cliqué sur supprimer pour une réservation.
        if (isset($_GET['id'])) {
            // Vérifie que l'ID à supprimer est spécifié.
            ProcessAdminDeleteRes($_GET['id']);
            // Appelle la fonction de suppression définitive de la réservation.
        }

        // ===================================================
        // ==== ADMIN : Action de suppression Participant ====
        // ===================================================

    } elseif ($action == 'admin_delete_participant') {
        // Vérifie si l'action est la suppression d'un participant spécifique.
        if (
            isset($_SESSION['Role']) &&
            $_SESSION['Role'] === 'admin' &&
            isset($_GET['id_part']) &&
            isset($_GET['id_res'])
        ) {
            // Vérifie les droits admin et la présence des ID du participant et de la réservation associée.
            DeleteParticipant($_GET['id_part']);
            // Appelle la fonction supprimant uniquement le participant concerné.
            // Redirection vers la page d'édition pour voir le résultat immédiatement
            header('Location: index.php?action=admin_edit_page&id=' . $_GET['id_res']);
            // Redirige vers la page d'édition de la réservation pour constater la suppression.
            exit();
            // Stoppe l'exécution.
        } else {
            // S'exécute si les conditions de sécurité ou les ID manquent.
            header('Location: index.php?action=admin');
            // Redirige vers le tableau de bord général.
            exit();
            // Stoppe l'exécution.
        }

        // =======================================
        // ==== ADMIN : Page de tous les LOGS ====
        // =======================================

    } elseif ($action == 'admin_all_logs') {
        // Vérifie si l'admin souhaite consulter l'historique des logs.
        ShowAllLogsPage();
        // Appelle la fonction affichant la vue des journaux système.

        // ============================
        // ==== ADMIN : EXPORT CSV ====
        // ============================

    } elseif ($action == 'export_csv') {
        // Vérifie si l'admin demande l'exportation des réservations en format CSV.
        ExportCSV();
        // Appelle la fonction générant le fichier CSV téléchargeable.

    } else {
        require('Menuprincipal.php');
    }

} else {
    require('Menuprincipal.php');
}

// =======================================
// SÉCURITÉ ADMIN : ISOLATION & PROTECTION
// =======================================

// Vérification globale Admin à la fin pour sécuriser l'accès

if (isset($_SESSION['Role']) && $_SESSION['Role'] === 'admin') {
    // Bloc de sécurité supplémentaire exécuté si l'utilisateur actuel est un administrateur.

    $current_action = isset($_GET['action']) ? $_GET['action'] : '';
    // Récupère l'action en cours ou définit une chaîne vide si aucune.

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
    // Définit un tableau contenant uniquement les actions que l'admin a le droit d'exécuter.

    // Si l'action demandée n'est pas autorisée pour un admin, retour au dashboard
    if (!empty($current_action) && !in_array($current_action, $allowed_admin_actions)) {
        // Vérifie si une action est demandée et si elle ne figure pas dans la liste autorisée.
        header('Location: index.php?action=admin');
        // Redirige de force l'administrateur vers son tableau de bord.
        exit();
        // Interrompt le script pour appliquer la redirection.
    }
}
?>
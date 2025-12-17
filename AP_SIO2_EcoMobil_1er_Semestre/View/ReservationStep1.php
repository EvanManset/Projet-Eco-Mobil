<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation - Étape 1</title>

    <link rel="icon" href="assets/Eco-Mobil.png" type="image/png">
    <link rel="apple-touch-icon" href="assets/Eco-Mobil.png">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>

        :root {
            --bg-color: #e0e0e0;
            --light-shadow: #ffffff;
            --dark-shadow: #a3b1c6;
            --main-text-color: #333;
            --accent-color: #71b852; /* Vert EcoMobil */
            --accent-hover: #5fa73d;
            --input-height: 42px;
        }

        body {
            margin: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, var(--bg-color) 0%, #cacaca 50%, var(--bg-color) 100%);
            color: var(--main-text-color);
        }

        .reservation-container {
            background: var(--bg-color);
            padding: 48px;
            border-radius: 30px;
            box-shadow: 10px 10px 20px var(--dark-shadow), -10px -10px 20px var(--light-shadow);
            width: 850px;
            max-width: 95vw;
        }

        h2 { text-align: center; font-weight: 700; margin-bottom: 10px; }
        .step-indicator { text-align: center; color: var(--accent-color); font-weight: 600; margin-bottom: 30px; }

        fieldset {
            border: none;
            padding: 22px;
            margin-top: 20px;
            background: var(--bg-color);
            border-radius: 20px;
            box-shadow: inset 6px 6px 12px var(--dark-shadow), inset -6px -6px 12px var(--light-shadow);
        }

        label { display: block; margin-bottom: 8px; font-weight: 600; font-size: 0.9em; }

        input[type="date"], input[type="time"], select {
            width: 100%;
            height: var(--input-height);
            padding: 10px;
            border-radius: 12px;
            border: none;
            background: var(--bg-color);
            outline: none;
            font-size: 1em;
            box-shadow:  4px 4px 8px var(--dark-shadow),  -4px -4px 8px var(--light-shadow);
            margin-bottom: 20px;
            box-sizing: border-box;
        }

        .btn-accueil {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-decoration: none;

            /* Fond Gris */
            background-color: var(--bg-color);

            /* Texte Gris foncé par défaut */
            color: #555;

            font-weight: 700;
            font-size: 0.95em;

            padding: 12px 25px;
            border-radius: 50px;
            margin-bottom: 20px;

            /* Ombres pour le relief */
            box-shadow: 6px 6px 12px var(--dark-shadow), -6px -6px 12px var(--light-shadow);
            transition: all 0.3s ease;
        }

        .btn-accueil:hover {
            /* Au survol : Le texte devient VERT, le fond reste gris */
            color: var(--accent-color);

            /* Petit effet de mouvement */
            transform: translateY(-2px);
            box-shadow: 4px 4px 8px var(--dark-shadow), -4px -4px 8px var(--light-shadow);
        }
        /* ------------------------------------------------------------------- */

        .submit {
            width: 100%;
            padding: 18px;
            margin-top: 30px;
            border-radius: 50px;
            font-size: 1.2em;
            font-weight: 700;
            background: var(--accent-color);
            color: white;
            cursor: pointer;
            box-shadow: 6px 6px 12px var(--dark-shadow), -6px -6px 12px var(--light-shadow);
            border: none;
            transition: transform 0.2s;
        }
        .submit:hover {
            background-color: var(--accent-hover);
            transform: translateY(-3px);
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .full-width { grid-column: span 2; }

        .error-message-standalone {
            color: #c0392b;
            background: #ffe6e6;
            padding: 12px;
            border-radius: 15px;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<?php

$val_agence = $_POST['Agence'] ?? $_SESSION['reservation_step1']['Agence'] ?? '';
// Assigne à la variable l'agence issue du formulaire (POST), ou à défaut celle de la session, ou une chaîne vide.

$val_dd = $_POST['Date_Debut'] ?? $_SESSION['reservation_step1']['Date_Debut'] ?? '';
// Récupère la date de début depuis les données envoyées en POST, sinon depuis la session, sinon définit une valeur vide.

$val_df = $_POST['Date_Fin'] ?? $_SESSION['reservation_step1']['Date_Fin'] ?? '';
// Tente de récupérer la date de fin via la méthode POST, puis via la variable de session, ou initialise à vide.

$val_hd = $_POST['Heure_Debut'] ?? $_SESSION['reservation_step1']['Heure_Debut'] ?? '';
// Cherche l'heure de début dans le formulaire soumis, à défaut dans les données de session stockées, ou retourne vide.

$val_hf = $_POST['Heure_Fin'] ?? $_SESSION['reservation_step1']['Heure_Fin'] ?? '';
// Affecte l'heure de fin à partir du POST, sinon de la sauvegarde en session, sinon utilise une chaîne de caractères vide.

?>

<div class="reservation-container">

    <a href="index.php" class="btn-accueil">
        <i class="fa-solid fa-arrow-left"></i> Retour au tableau de bord
    </a>

    <h2>Commencer votre réservation</h2>
    <div class="step-indicator">Étape 1 sur 2 : Où et Quand ?</div>

    <?php if (isset($error_msg)) : ?>
        <div class="error-message-standalone"><?= $error_msg ?></div>
    <?php endif; ?>

    <form action="index.php?action=reservation_step1" method="POST">
        <fieldset>
            <div class="form-grid">
                <div class="full-width">
                    <label>Agence de départ</label>
                    <select name="Agence" required>
                        <option value="" disabled <?= $val_agence == '' ? 'selected' : '' ?>>Sélectionnez une agence...</option>
                        <option value="Annecy" <?= $val_agence == 'Annecy' ? 'selected' : '' ?>>Annecy</option>
                        <option value="Grenoble" <?= $val_agence == 'Grenoble' ? 'selected' : '' ?>>Grenoble</option>
                        <option value="Chambéry" <?= $val_agence == 'Chambéry' ? 'selected' : '' ?>>Chambéry</option>
                        <option value="Valence" <?= $val_agence == 'Valence' ? 'selected' : '' ?>>Valence</option>
                        <option value="Saint-Etienne" <?= $val_agence == 'Saint-Etienne' ? 'selected' : '' ?>>Saint-Etienne</option>
                        <option value="Bourg-en-Bresse" <?= $val_agence == 'Bourg-en-Bresse' ? 'selected' : '' ?>>Bourg-en-Bresse</option>
                    </select>
                </div>

                <div>
                    <label>Date Début</label>
                    <input type="Date" name="Date_Debut" value="<?= ($val_dd) ?>" required />
                </div>
                <div>
                    <label>Date Fin</label>
                    <input type="Date" name="Date_Fin" value="<?= ($val_df) ?>" required />
                </div>

                <div>
                    <label>Heure Début</label>
                    <input type="Time" name="Heure_Debut" value="<?= ($val_hd) ?>" required />
                </div>
                <div>
                    <label>Heure Fin</label>
                    <input type="Time" name="Heure_Fin" value="<?= ($val_hf) ?>" required />
                </div>
            </div>
        </fieldset>

        <button type="submit" class="submit">
            Suivant <i class="fa-solid fa-arrow-right" style="margin-left: 8px;"></i>
        </button>
    </form>
</div>

</body>
</html>
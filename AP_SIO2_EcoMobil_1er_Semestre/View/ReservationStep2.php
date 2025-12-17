<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation - Étape 2</title>

    <script type="text/javascript" src="../AP_SIO2_EcoMobil_1er_Semestre/js/script.js"></script>

    <link rel="icon" href="assets/Eco-Mobil.png" type="image/png">
    <link rel="apple-touch-icon" href="assets/Eco-Mobil.png">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* =========================================
           STYLE SOFT UI / NEUMORPHISM (VOTRE STYLE)
           ========================================= */
        :root {
            --bg-color: #e0e5ec;
            --text-color: #4a4a4a;
            --accent-color: #71b852;
            --accent-hover: #5fa73d;
            --locked-bg: #dbe0e8;

            /* Neumorphism Shadows */
            --shadow-light: #ffffff;
            --shadow-dark: #a3b1c6;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            padding: 40px 0;
        }

        .reservation-container {
            width: 1000px;
            max-width: 95%;
            padding: 50px;
            border-radius: 30px;
            background-color: var(--bg-color);
            box-shadow: 9px 9px 16px var(--shadow-dark),
            -9px -9px 16px var(--shadow-light);
        }

        /* Headers */
        .header-section { text-align: center; margin-bottom: 40px; }
        h2 { font-weight: 700; color: #333; margin: 0 0 10px 0; font-size: 1.8em; }

        .step-indicator {
            color: var(--accent-color);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.9em;
        }

        /* Sections (Fieldsets) */
        fieldset {
            border: none;
            padding: 25px;
            margin-bottom: 30px;
            border-radius: 20px;
            background: var(--bg-color);
            box-shadow: inset 2px 2px 5px var(--shadow-dark),
            inset -2px -2px 5px var(--shadow-light);
        }

        legend {
            font-weight: 600;
            color: var(--accent-color);
            padding: 0 15px;
            font-size: 1.1em;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 15px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            font-size: 0.9em;
            color: #555;
        }

        /* Inputs & Selects génériques */
        input[type="text"], input[type="date"], select, textarea {
            width: 100%;
            padding: 12px 20px;
            border-radius: 12px;
            border: none;
            background-color: var(--bg-color);
            color: #333;
            font-family: inherit;
            font-size: 0.95em;
            outline: none;
            box-shadow: inset 5px 5px 10px var(--shadow-dark),
            inset -5px -5px 10px var(--shadow-light);
            transition: all 0.3s ease;
        }

        input:focus, select:focus, textarea:focus {
            box-shadow: inset 3px 3px 6px var(--shadow-dark),
            inset -3px -3px 6px var(--shadow-light),
            0 0 5px rgba(113, 184, 82, 0.2);
        }

        /* --- STYLES SPÉCIFIQUES --- */

        /* 1. Champs verrouillés (Readonly) */
        input[readonly] {
            background-color: var(--locked-bg);
            color: #666;
            cursor: default;
            font-style: italic;
            border: 1px solid transparent;
            box-shadow: inset 3px 3px 6px rgba(163, 177, 198, 0.6),
            inset -3px -3px 6px rgba(255, 255, 255, 0.5);
        }

        .edit-link {
            font-size: 0.85em;
            color: #d35400;
            text-decoration: none;
            font-weight: 600;
            margin-top: 5px;
            display: inline-block;
            transition: 0.3s;
        }
        .edit-link:hover { text-decoration: underline; color: #e67e22; }

        /* 2. Stock Badges */
        .stock-container {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 20px;
        }

        .stock-badge {
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.9em;
            font-weight: 500;
            background: var(--bg-color);
            color: #555;
            box-shadow: 4px 4px 8px var(--shadow-dark),
            -4px -4px 8px var(--shadow-light);
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .stock-badge span { font-weight: 700; color: #333; font-size: 0.9em; }

        /* 3. Table Participants */
        .participant-controls { margin-bottom: 15px; display: flex; gap: 15px; }

        .btn-mini {
            padding: 8px 16px;
            border-radius: 20px;
            border: none;
            background: var(--bg-color);
            color: #555;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 4px 4px 8px var(--shadow-dark),
            -4px -4px 8px var(--shadow-light);
            transition: 0.2s;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .btn-mini:hover {
            transform: translateY(-2px);
            color: var(--accent-color);
        }
        .btn-mini:active {
            box-shadow: inset 3px 3px 6px var(--shadow-dark), inset -3px -3px 6px var(--shadow-light);
            transform: translateY(0);
        }

        /* CSS DU TABLEAU (CORRIGÉ POUR STABILITÉ) */
        #dataTable {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px;
            table-layout: fixed; /* Empêche la déformation */
        }

        #dataTable td {
            vertical-align: middle;
        }

        /* Force la colonne checkbox à 50px */
        #dataTable td:first-child {
            width: 50px;
            text-align: center;
        }

        #dataTable input[type="checkbox"] {
            width: 20px; height: 20px; cursor: pointer; margin: 0;
            accent-color: var(--accent-color);
            box-shadow: none;
        }

        #dataTable input[type="text"] {
            height: 45px;
            width: 100%;
            margin-bottom: 0;
        }

        /* 4. Bouton Valider */
        .submit {
            width: 100%;
            padding: 18px;
            margin-top: 20px;
            border-radius: 50px;
            font-size: 1.2em;
            font-weight: 700;
            background: var(--accent-color);
            color: white;
            cursor: pointer;
            border: none;
            box-shadow: 6px 6px 12px var(--shadow-dark),
            -6px -6px 12px var(--shadow-light);
            transition: all 0.3s ease;
        }
        .submit:hover {
            background-color: var(--accent-hover);
            transform: translateY(-3px);
            box-shadow: 8px 8px 16px var(--shadow-dark),
            -8px -8px 16px var(--shadow-light);
        }

        /* Checkbox custom container */
        .checkbox-container {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 15px;
            padding: 10px;
            border-radius: 10px;
        }
        .checkbox-container input[type="checkbox"] { width: 18px; height: 18px; accent-color: var(--accent-color); margin: 0; box-shadow: none; }
        .checkbox-container label { margin: 0; cursor: pointer; font-weight: 400; font-size: 0.9em; }

        /* Responsive */
        @media (max-width: 800px) {
            .reservation-container { padding: 30px 20px; }
            .form-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<div class="reservation-container">

    <div class="header-section">
        <h2>Finaliser la réservation</h2>
        <div class="step-indicator">Étape 2 sur 2 : Véhicule & Détails</div>
    </div>

    <?php if (isset($error_msg)) : ?>
        <div style="color: #e74c3c; text-align: center; margin-bottom: 20px; font-weight: 600;">
            <i class="fa-solid fa-triangle-exclamation"></i> <?= $error_msg ?>
        </div>
    <?php endif; ?>

    <form action="index.php?action=reservation_step2" method="POST">

        <fieldset>
            <legend><i class="fa-solid fa-lock"></i> Vos choix précédents</legend>
            <div class="form-grid">
                <div>
                    <label>Agence</label>
                    <input type="text" value="<?php echo ($_SESSION['reservation_step1']['Agence']); ?>" readonly />
                </div>
                <div>
                    <label>Du</label>
                    <input type="text" value="<?php echo ($_SESSION['reservation_step1']['Date_Debut']) . ' à ' . ($_SESSION['reservation_step1']['Heure_Debut']); ?>" readonly />
                </div>
                <div>
                    <label>Au</label>
                    <input type="text" value="<?php echo ($_SESSION['reservation_step1']['Date_Fin']) . ' à ' . ($_SESSION['reservation_step1']['Heure_Fin']); ?>" readonly />
                </div>
                <div style="display:flex; align-items:flex-end;">
                    <a href="index.php?action=reservation_step1" class="edit-link">
                        <i class="fa-solid fa-pen-to-square"></i> Modifier les dates
                    </a>
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend><i class="fa-solid fa-car-battery"></i> Choisir un véhicule</legend>

            <div class="stock-container">
                <?php
                // Configuration Icônes EMOJIS
                $iconsConfig = [
                        'Vélo_électrique_urbain' => '⚡ 🚲',
                        'VTT_électrique'         => '⚡ 🚴',
                        'Hoverboard'             => '  🔹',
                        'Trottinette_électrique' => '⚡ 🛴',
                        'Gyropode'               => '  🔹',
                        'Skateboard_électrique'  => '⚡ 🛹'
                ];

                if (isset($dispoStats) && !empty($dispoStats)) {
                    foreach ($dispoStats as $stat) {
                        $key = str_replace(' ', '_', $stat['libelle_Type']);
                        $icon = isset($iconsConfig[$key]) ? $iconsConfig[$key] : '🚗';
                        echo "<div class='stock-badge'>{$icon} {$stat['libelle_Type']} <span>({$stat['dispo']})</span></div>";
                    }
                } else {
                    echo "<div class='stock-badge'>Aucune info de stock</div>";
                }
                ?>
            </div>

            <label>Type de Véhicule souhaité</label>
            <div style="position:relative;">
                <select name="Type_Vehicule" required>
                    <option value="" disabled selected>Sélectionnez votre véhicule...</option>
                    <option value="Vélo_électrique_urbain">Vélo électrique urbain</option>
                    <option value="VTT_électrique">VTT électrique</option>
                    <option value="Hoverboard">Hoverboard</option>
                    <option value="Trottinette_électrique">Trottinette électrique</option>
                    <option value="Gyropode">Gyropode</option>
                    <option value="Skateboard_électrique">Skateboard électrique</option>
                </select>
            </div>
        </fieldset>

        <fieldset>
            <legend><i class="fa-solid fa-users"></i> Participants</legend>

            <div class="participant-controls">
                <button type="button" class="btn-mini" onclick="addRow('dataTable')">
                    <i class="fa-solid fa-plus"></i> Ajouter Participants
                </button>
                <button type="button" class="btn-mini" onclick="deleteRow('dataTable')">
                    <i class="fa-solid fa-trash"></i> Enlever Participants
                </button>
            </div>

            <div class="participant-scroll-area">
                <table id="dataTable"><tr><td><input type="checkbox" name="chk[]" checked /></td><td><input type="text" name="Participant[]" placeholder="Nom & Prénom du participant" required /></td></tr></table>
            </div>

            <p style="font-size:0.8em; color:#888; margin-top:10px; font-style:italic;">
                * Cochez la case pour supprimer une ligne.
            </p>
        </fieldset>

        <fieldset>
            <legend><i class="fa-solid fa-comment-dots"></i> Options</legend>
            <label>Demandes spéciales</label>
            <textarea name="Demande_speciale" rows="4" placeholder="Ex: Siège bébé, casque taille L, heure d'arrivée précise..."></textarea>
        </fieldset>

        <div class="checkbox-container">
            <input type="checkbox" id="terms" required>
            <label for="terms">J'accepte les conditions générales de location</label>
        </div>

        <button type="submit" class="submit">
            Confirmer la réservation <i class="fa-solid fa-check-circle" style="margin-left:8px;"></i>
        </button>
    </form>
</div>

</body>
</html>
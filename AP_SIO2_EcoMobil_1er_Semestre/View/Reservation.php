<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation - EcoMobil</title>

    <script type="text/javascript" src="../AP_SIO2_EcoMobil_1er_Semestre/js/script.js"></script>

    <link rel="icon" href="assets/Eco-Mobil.png" type="image/png">
    <link rel="apple-touch-icon" href="assets/Eco-Mobil.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>

        .stock-container {
            margin-top: 15px;
            margin-bottom: 30px;
            padding: 15px 20px;
            background-color: #f0f0f0;
            border-radius: 20px;
            width: 90%;
            max-width: 1100px;
            margin-left: auto;
            margin-right: auto;

            /* C'est ici que l'on met en ligne horizontalement */
            display: flex;
            flex-wrap: wrap;         /* Passe à la ligne si nécessaire */
            justify-content: center; /* Centre les badges */
            gap: 15px;               /* Espace entre les badges */
        }

        .stock-title {
            color: #71b852;
            text-transform: uppercase;
            font-weight: 700;
            font-size: 1.1em;
            width: 100%;
            text-align: center;
            margin-bottom: 12px;
            letter-spacing: 0.5px;
        }

        .stock-item {
            display: flex;
            align-items: center;
            background-color: #ffffff;
            padding: 8px 16px;
            border-radius: 12px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            transition: transform 0.2s;
        }

        .stock-item:hover {
            transform: translateY(-2px);
        }

        .stock-icon {
            font-size: 1.3em;
            margin-right: 10px;
        }

        .stock-count {
            font-weight: 800;
            font-size: 1.1em;
            color: #333;
            margin-right: 6px;
        }

        .stock-label {
            font-size: 0.95em;
            color: #555;
            white-space: nowrap;
        }

        :root {
            --bg-color: #e0e0e0;
            --light-shadow: #ffffff;
            --dark-shadow: #a3b1c6;
            --main-text-color: #333;
            --accent-color: #71b852;
            --input-height: 42px;
        }

        input[type="checkbox"] {
            -webkit-appearance: none;
            appearance: none;
            width: 22px;
            height: 22px;
            border: 2px solid var(--accent-color);
            border-radius: 6px;
            background-color: var(--bg-color);
            cursor: pointer;
            position: relative;
            vertical-align: middle;
            outline: none;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        input[type="checkbox"]:checked {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
        }
        input[type="checkbox"]:checked::after {
            content: '✔';
            position: absolute;
            color: white;
            font-size: 14px;
            font-weight: bold;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
        }

        .error-message-standalone {
            color: #c0392b;
            background: #ffe6e6;
            padding: 12px 25px;
            border-radius: 15px;
            margin: 0 auto 20px auto;
            font-weight: 500;
            font-size: 1em;
            text-align: center;
            box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.1), -5px -5px 10px var(--light-shadow);
            max-width: 800px;
            width: 90%;
            display: block;
            border: 1px solid #ffcccc;
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
            background-size: 400% 400%;
            animation: backgroundAnimation 15s ease infinite alternate;
            color: var(--main-text-color);
            overflow-x: hidden;
            padding: 50px 0;
        }

        @keyframes backgroundAnimation {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .reservation-container {
            background: var(--bg-color);
            padding: 48px;
            border-radius: 30px;
            box-shadow: 10px 10px 20px var(--dark-shadow), -10px -10px 20px var(--light-shadow);
            width: 1150px;
            max-width: 97vw;
            margin-top: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .reservation-container:hover {
            transform: translateY(-5px);
            box-shadow: 15px 15px 30px var(--dark-shadow), -15px -15px 30px var(--light-shadow);
        }

        h2 { text-align: center; font-weight: 700; margin-bottom: 30px; }

        fieldset {
            border: none;
            padding: 22px;
            margin-top: 28px;
            background: var(--bg-color);
            border-radius: 20px;
            box-shadow: 6px 6px 12px var(--dark-shadow), -6px -6px 12px var(--light-shadow);
        }

        .legend-container { display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; }

        .form-table { width: 100%; border-spacing: 0 13px; margin-top: 5px; }
        .form-table td { padding: 8px 16px; vertical-align: middle; }

        .form-table input[type="text"],
        .form-table input[type="date"],
        .form-table input[type="time"],
        .form-table select,
        textarea {
            width: 100%;
            height: var(--input-height);
            padding: 10px;
            border-radius: 12px;
            border: none;
            background: var(--bg-color);
            outline: none;
            font-size: 1em;
            box-shadow: inset 4px 4px 8px var(--dark-shadow), inset -4px -4px 8px var(--light-shadow);
        }

        textarea { height: 100px; resize: vertical; }

        input[type="button"] {
            background: var(--accent-color);
            color: white;
            border: none;
            padding: 12px 22px;
            border-radius: 20px;
            cursor: pointer;
            box-shadow: 5px 5px 10px var(--dark-shadow), -5px -5px 10px var(--light-shadow);
            transition: transform 0.2s ease;
        }
        input[type="button"]:hover { transform: translateY(-3px); }

        .submit {
            width: 100%;
            padding: 18px;
            margin-top: 40px;
            border-radius: 50px;
            font-size: 1.2em;
            font-weight: 700;
            background: var(--accent-color);
            color: white;
            cursor: pointer;
            box-shadow: 6px 6px 12px var(--dark-shadow), -6px -6px 12px var(--light-shadow);
            border: none;
        }
        .submit:hover { transform: translateY(-3px); }

        .btn-accueil {
            padding: 10px 18px;
            font-size: 1em;
            background-color: var(--accent-color);
            color: white;
            border-radius: 10px;
            text-decoration: none;
            box-shadow: 3px 3px 7px rgba(0,0,0,0.2);
            transition: 0.2s ease;
        }
        .btn-accueil:hover { transform: translateY(-2px); }

        .agreement { margin-bottom: 15px; display: flex; align-items: center; gap: 10px; }
        .agreement label { font-size: 0.95em; cursor: pointer; }
        .agreement a { color: var(--accent-color); text-decoration: none; font-weight: 600; }

        @media (max-width: 1200px) {
            .reservation-container { width: 98vw; padding: 18px; }
            .form-table td { padding: 6px 4px; }
        }

        /* Ces règles s'appliquent maintenant PARTOUT (y compris sur PC) */
        .form-table tr {
            display: grid;
            grid-template-columns: 40px repeat(6, 1fr);
            grid-gap: 10px;
            align-items: center;
        }

        .form-table td:last-child {
            grid-column: 1 / -1;
            margin-top: 15px;
        }
    </style>
</head>
<body>

<div class="reservation-container">

    <h2>Réservation</h2>

    <form action="" method="POST">

        <fieldset class="row2">
            <div class="legend-container">
                <legend>Détails des passagers</legend>
                <a href="/AP_SIO2_EcoMobil_1er_Semestre/index.php" class="btn-accueil">Retour à l'accueil</a>
            </div>

            <p>
                <input type="button" value="Ajouter Participants" onclick="addRow('dataTable')" />
                <input type="button" value="Enlever Participants" onclick="deleteRow('dataTable')" />
            </p>

            <p style="font-size: 0.85em; color: #555; font-style: italic;">
                (Les actions s’appliquent seulement aux lignes cochées.)
            </p>

            <div class="stock-container">
                <div class="stock-title">Disponibilité en temps réel :</div>

                <?php
                // Configuration des icônes
                $iconsConfig = [
                        'Vélo_électrique_urbain' => '⚡ 🚲',
                        'VTT_électrique'         => '⚡ 🚴',
                        'Hoverboard'             => '🔹',
                        'Trottinette_électrique' => '⚡ 🛴',
                        'Gyropode'               => '🔹',
                        'Skateboard_électrique'  => '⚡ 🛹'
                ];

                if (isset($dispoStats) && !empty($dispoStats)) {
                    foreach ($dispoStats as $stat) {
                        $typeKey = str_replace(' ', '_', $stat['libelle_Type']);
                        $icone = isset($iconsConfig[$typeKey]) ? $iconsConfig[$typeKey] : '🚗';
                        $nomAffiche = str_replace('_', ' ', $stat['libelle_Type']);

                        echo "
                        <div class='stock-item'>
                            <span class='stock-icon'>{$icone}</span>
                            <span class='stock-count'>{$stat['nb']}</span>
                            <span class='stock-label'>{$nomAffiche}</span>
                        </div>";
                    }
                } else {
                    echo "<div style='width:100%; text-align:center;'>🚫 Aucun véhicule disponible.</div>";
                }
                ?>
            </div>
            <table id="dataTable" class="form-table">
                <tbody>
                <tr>
                    <td style="width: 40px; text-align: center;"><input type="checkbox" name="chk[]" checked="checked" /></td>
                    <td>
                        <label>Agence</label>
                        <select name="Agence" required>
                            <option value="">...</option>
                            <option value="Annecy" <?php echo (isset($_POST['Agence']) && $_POST['Agence'] == 'Annecy') ? 'selected' : ''; ?>>Annecy</option>
                            <option value="Grenoble" <?php echo (isset($_POST['Agence']) && $_POST['Agence'] == 'Grenoble') ? 'selected' : ''; ?>>Grenoble</option>
                            <option value="Chambéry" <?php echo (isset($_POST['Agence']) && $_POST['Agence'] == 'Chambéry') ? 'selected' : ''; ?>>Chambéry</option>
                            <option value="Valence" <?php echo (isset($_POST['Agence']) && $_POST['Agence'] == 'Valence') ? 'selected' : ''; ?>>Valence</option>
                            <option value="Saint-Etienne" <?php echo (isset($_POST['Agence']) && $_POST['Agence'] == 'Saint-Etienne') ? 'selected' : ''; ?>>Saint-Etienne</option>
                            <option value="Bourg-en-Bresse" <?php echo (isset($_POST['Agence']) && $_POST['Agence'] == 'Bourg-en-Bresse') ? 'selected' : ''; ?>>Bourg-en-Bresse</option>
                        </select>
                    </td>
                    <td>
                        <label>Type Véhicule</label>
                        <select name="Type_Vehicule" required>
                            <option value="">...</option>
                            <option value="Vélo_électrique_urbain" <?php echo (isset($_POST['Type_Vehicule']) && $_POST['Type_Vehicule'] == 'Vélo_électrique_urbain') ? 'selected' : ''; ?>>Vélo électrique urbain</option>
                            <option value="VTT_électrique" <?php echo (isset($_POST['Type_Vehicule']) && $_POST['Type_Vehicule'] == 'VTT_électrique') ? 'selected' : ''; ?>>VTT électrique</option>
                            <option value="Hoverboard" <?php echo (isset($_POST['Type_Vehicule']) && $_POST['Type_Vehicule'] == 'Hoverboard') ? 'selected' : ''; ?>>Hoverboard</option>
                            <option value="Trottinette_électrique" <?php echo (isset($_POST['Type_Vehicule']) && $_POST['Type_Vehicule'] == 'Trottinette_électrique') ? 'selected' : ''; ?>>Trottinette électrique</option>
                            <option value="Gyropode" <?php echo (isset($_POST['Type_Vehicule']) && $_POST['Type_Vehicule'] == 'Gyropode') ? 'selected' : ''; ?>>Gyropode</option>
                            <option value="Skateboard_électrique" <?php echo (isset($_POST['Type_Vehicule']) && $_POST['Type_Vehicule'] == 'Skateboard_électrique') ? 'selected' : ''; ?>>Skateboard électrique</option>
                        </select>
                    </td>
                    <td>
                        <label>Date Début</label>
                        <input type="Date" name="Date_Debut" value="<?php echo isset($_POST['Date_Debut']) ? htmlspecialchars($_POST['Date_Debut']) : ''; ?>" required />
                    </td>
                    <td>
                        <label>Date Fin</label>
                        <input type="Date" name="Date_Fin" value="<?php echo isset($_POST['Date_Fin']) ? htmlspecialchars($_POST['Date_Fin']) : ''; ?>" required />
                    </td>
                    <td>
                        <label>Heure Début</label>
                        <input type="Time" name="Heure_Debut" value="<?php echo isset($_POST['Heure_Debut']) ? htmlspecialchars($_POST['Heure_Debut']) : ''; ?>" required />
                    </td>
                    <td>
                        <label>Heure Fin</label>
                        <input type="Time" name="Heure_Fin" value="<?php echo isset($_POST['Heure_Fin']) ? htmlspecialchars($_POST['Heure_Fin']) : ''; ?>" required />
                    </td>
                    <td>
                        <label>Demande(s) spécial éventuelle(s)</label>
                        <textarea name="Demande_speciale" rows="10" cols="100"><?php echo isset($_POST['Demande_speciale']) ? htmlspecialchars($_POST['Demande_speciale']) : ''; ?></textarea>
                    </td>
                </tr>
                </tbody>
            </table>
        </fieldset>

        <fieldset class="row4">
            <legend>Conditions et Envoi</legend>

            <div class="agreement">
                <input type="checkbox" id="terms" required/>
                <label for="terms">J'accepte les <a href="#">Termes et Conditions</a></label>
            </div>

            <div class="agreement">
                <input type="checkbox" id="newsletter"/>
                <label for="newsletter">Je souhaite recevoir des offres personnalisées</label>
            </div>

            <div class="clear"></div>
        </fieldset>

        <input class="submit" type="submit" value="Confirmer" />

    </form>

</div>

</body>
</html>
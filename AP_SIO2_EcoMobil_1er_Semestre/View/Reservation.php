<?php
// reservation.php
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation - EcoMobil</title>

    <!-- Script JS externe (Vérifie bien que ce fichier existe à cet endroit) -->
    <script type="text/javascript" src="../AP_SIO2_EcoMobil_1er_Semestre/js/script.js"></script>

    <link rel="icon" href="View/Eco-Mobil.png" type="image/png">
    <link rel="apple-touch-icon" href="View/Eco-Mobil.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">


    <style>
        :root {
            --bg-color: #e0e0e0;
            --light-shadow: #ffffff;
            --dark-shadow: #a3b1c6;
            --main-text-color: #333;
            --accent-color: #71b852;
        }

        body {
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, var(--bg-color) 0%, #cacaca 50%, var(--bg-color) 100%);
            background-size: 400% 400%;
            animation: backgroundAnimation 15s ease infinite alternate;
            color: var(--main-text-color);
            overflow-x: hidden;
            padding: 40px 0;
        }

        @keyframes backgroundAnimation {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .reservation-container {
            background: var(--bg-color);
            padding: 40px;
            border-radius: 30px;
            box-shadow: 10px 10px 20px var(--dark-shadow),
            -10px -10px 20px var(--light-shadow);
            width: 650px;
            max-width: 90%;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .reservation-container:hover {
            transform: translateY(-5px);
            box-shadow: 15px 15px 30px var(--dark-shadow),
            -15px -15px 30px var(--light-shadow);
        }

        .reservation-container h2 {
            font-size: 2.2em;
            text-align: center;
            margin-bottom: 25px;
            font-weight: 700;
        }

        fieldset {
            border: none;
            padding: 20px;
            margin-top: 20px;
            background: var(--bg-color);
            border-radius: 20px;
            box-shadow: 6px 6px 12px var(--dark-shadow),
            -6px -6px 12px var(--light-shadow);
        }

        .legend-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        legend {
            font-weight: 600;
            font-size: 1.1em;
            padding: 0 10px;
        }

        /* Boutons d'action (Add/Remove) */
        input[type="button"] {
            background: var(--accent-color);
            color: white;
            border: none;
            padding: 10px 20px;
            margin-right: 10px;
            border-radius: 20px;
            cursor: pointer;
            font-weight: 500;
            box-shadow: 5px 5px 10px var(--dark-shadow),
            -5px -5px 10px var(--light-shadow);
            transition: transform 0.2s ease;
        }

        input[type="button"]:hover {
            transform: translateY(-3px);
            background-color: #5fa63d;
        }

        /* Lien Retour Accueil */
        .btn-accueil {
            padding: 8px 16px;
            background-color: var(--accent-color);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 0.9em;
            font-weight: bold;
            box-shadow: 3px 3px 7px rgba(0,0,0,0.2);
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn-accueil:hover {
            background-color: #5fa63d;
            transform: translateY(-2px);
        }

        /* Table Styles */
        .form-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px;
            margin-top: 5px;
        }

        .form-table td {
            padding: 5px;
            vertical-align: middle;
        }

        .form-table input[type="text"],
        .form-table select {
            width: 100%;
            padding: 10px;
            border-radius: 10px;
            border: none;
            outline: none;
            background: var(--bg-color);
            box-sizing: border-box;
            box-shadow: inset 4px 4px 8px var(--dark-shadow),
            inset -4px -4px 8px var(--light-shadow);
        }

        input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
            accent-color: var(--accent-color);
            /* Correction CSS pour s'assurer que la checkbox reste alignée */
            margin: 0 auto;
            display: block;
        }

        .agreement {
            display: flex;
            align-items: center;
            margin: 12px 0;
        }

        /* Reset du style checkbox pour la section agreement pour ne pas centrer */
        .agreement input[type="checkbox"] {
            margin: 0;
            display: inline-block;
        }

        .agreement label {
            margin-left: 10px;
            cursor: pointer;
        }

        .agreement a {
            color: var(--accent-color);
            text-decoration: none;
            font-weight: 600;
        }

        .submit {
            width: 100%;
            background: var(--accent-color);
            color: white;
            border: none;
            padding: 15px 20px;
            margin-top: 30px;
            border-radius: 50px;
            font-size: 1.2em;
            font-weight: 700;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 6px 6px 12px var(--dark-shadow),
            -6px -6px 12px var(--light-shadow);
            transition: all 0.3s ease;
        }

        .submit:hover {
            background-color: #5fa63d;
            transform: translateY(-3px);
            box-shadow: 8px 8px 16px var(--dark-shadow),
            -8px -8px 16px var(--light-shadow);
        }

        .submit:active {
            transform: translateY(1px);
            box-shadow: inset 4px 4px 8px var(--dark-shadow),
            inset -4px -4px 8px var(--light-shadow);
        }

        .clear {
            clear: both;
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
                <input type="button" value="Add Passenger" onclick="addRow('dataTable')" />
                <input type="button" value="Remove Passenger" onclick="deleteRow('dataTable')" />
            </p>

            <p style="font-size: 0.85em; color: #555; font-style: italic;">
                (Les actions s’appliquent seulement aux lignes cochées.)
            </p>

            <table id="dataTable" class="form-table">
                <tbody>
                <tr>
                    <!-- IMPORTANT: Tout sur une seule ligne ici pour ne pas casser le JS "childNodes" -->
                    <td style="width: 40px; text-align: center;"><input type="checkbox" name="chk[]" checked="checked" /></td>
                    <td>
                        <label>Name</label>
                        <input type="text" name="BX_NAME[]" required>
                    </td>
                    <td>
                        <label>Age</label>
                        <input type="text" name="BX_age[]" required>
                    </td>
                    <td>
                        <label>Gender</label>
                        <select name="BX_gender[]" required>
                            <option value="">....</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </td>
                    <td>
                        <label>Berth Preference</label>
                        <select name="BX_birth[]" required>
                            <option value="">....</option>
                            <option value="Window">Window</option>
                            <option value="No Choice">No Choice</option>
                        </select>
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
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation - EcoMobil</title>
    <script type="text/javascript" src="js/script.js"></script>

    <link rel="icon" href="View/Eco-Mobil.png" type="image/png">
    <link rel="apple-touch-icon" href="View/Eco-Mobil.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">


    <style>
        /* --- DÉBUT AJOUT CSS --- */
        /* Style pour les messages d'erreur uniformisés */
        .error-message-standalone {
            color: #c0392b;
            background: #ffe6e6;
            padding: 12px 25px;
            border-radius: 15px;
            margin: 20px auto; /* Centré avec marge */
            font-weight: 500;
            font-size: 1em;
            text-align: center;
            box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.1), -5px -5px 10px var(--light-shadow);
            max-width: 600px; /* Un peu plus large pour la réservation */
            width: 80%;
            display: block;
        }

        .success-message {
            color: #27ae60;
            background: #eafaf1;
            padding: 12px 25px;
            border-radius: 15px;
            margin: 20px auto;
            font-weight: 600;
            text-align: center;
            box-shadow: 5px 5px 10px rgba(0,0,0,0.1), -5px -5px 10px #fff;
            max-width: 600px;
        }
        /* --- FIN AJOUT CSS --- */

        :root {
            --bg-color: #e0e0e0;
            --light-shadow: #ffffff;
            --dark-shadow: #a3b1c6;
            --main-text-color: #333;
            --accent-color: #71b852;
            --input-height: 42px;
        }

        /* PAGE */
        body {
            margin: 0;
            display: flex;

            /* --- AJOUT IMPORTANT --- */
            flex-direction: column;  /* Force l'affichage vertical (l'un sous l'autre) */
            align-items: center;     /* Centre les éléments horizontalement */
            /* ----------------------- */

            justify-content: flex-start; /* Aligne tout vers le haut */
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

        /* CONTENEUR PRINCIPAL - MODIF largeur réelle */
        .reservation-container {
            background: var(--bg-color);
            padding: 48px; /* plus modéré que 70px */
            border-radius: 30px;
            box-shadow: 10px 10px 20px var(--dark-shadow), -10px -10px 20px var(--light-shadow);
            width: 1150px;      /* Agrandi, visible sur écran large */
            max-width: 97vw;    /* Toujours fluide sur petits écrans */
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .reservation-container:hover {
            transform: translateY(-5px);
            box-shadow: 15px 15px 30px var(--dark-shadow), -15px -15px 30px var(--light-shadow);
        }

        /* TITRES */
        h2 {
            text-align: center;
            font-weight: 700;
            margin-bottom: 30px;
        }

        /* SECTIONS (AGENCE, VEHICULE) */
        fieldset {
            border: none;
            padding: 22px;       /* Ajusté, pas trop d'espace */
            margin-top: 28px;    /* Espace entre blocs plus sobre */
            background: var(--bg-color);
            border-radius: 20px;
            box-shadow: 6px 6px 12px var(--dark-shadow), -6px -6px 12px var(--light-shadow);
        }

        .legend-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 14px;    /* espace sous le titre réduit */
        }

        /* TABLE DE FORMULAIRE - MODIF espace ajusté */
        .form-table {
            width: 100%;
            border-spacing: 0 13px; /* Espace vertical plus court */
            margin-top: 5px;
        }

        .form-table td {
            padding: 8px 16px;      /* Espace horizontal correct */
            vertical-align: middle;
        }

        /* Checkbox personnalisée verte */
        .form-table input[type="checkbox"] {
            width: 22px;
            height: 22px;
            accent-color: var(--accent-color);
            border-radius: 7px;
            box-shadow: 0 2px 6px rgba(158,184,152,0.15);
            background: var(--bg-color);
            outline: none;
            vertical-align: middle;
        }

        /* CHAMPS UNIFORMES */
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
            box-shadow: inset 4px 4px 8px var(--dark-shadow),
            inset -4px -4px 8px var(--light-shadow);
        }

        textarea {
            height: 100px;
            resize: vertical;
        }

        /* BOUTONS PARTICIPANTS */
        input[type="button"] {
            background: var(--accent-color);
            color: white;
            border: none;
            padding: 12px 22px;
            border-radius: 20px;
            cursor: pointer;
            font-weight: 500;
            box-shadow: 5px 5px 10px var(--dark-shadow), -5px -5px 10px var(--light-shadow);
            transition: transform 0.2s ease;
        }
        input[type="button"]:hover {
            transform: translateY(-3px);
        }

        /* SUBMIT */
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
            /* Supprimer cette ligne si elle existe : border: 2px solid #222; */
            border: none;  /* <-- Garde seulement cette ligne */
        }

        .submit:hover {
            transform: translateY(-3px);
        }

        /* BOUTON ACCUEIL */
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

        /* Responsive : adapte sur petits écrans */
        @media (max-width: 1200px) {
            .reservation-container {
                width: 98vw;
                padding: 18px;
            }
            .form-table td { padding: 6px 4px; }
        }

        .form-table tr {
            display: grid;
            grid-template-columns: 40px
        repeat(6, 1fr);
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

            <table id="dataTable" class="form-table">
                <tbody>
                <tr>
                    <td style="width: 40px; text-align: center;"><input type="checkbox" name="chk[]" checked="checked" /></td>
                    <td>
                        <label>Agence</label>
                        <select name="Agence" required>
                            <option value="">....</option>
                            <option value="Annecy">Annecy</option>
                            <option value="Grenoble">Grenoble</option>
                            <option value="Chambéry">Chambéry</option>
                            <option value="Valence">Valence</option>
                            <option value="Saint-Etienne">Saint-Etienne</option>
                            <option value="Bourg-en-Bresse">Bourg-en-Bresse</option>
                        </select>
                    </td>
                    <td>
                        <label>Type Véhicule</label>
                        <select name="Type_Vehicule" required>
                            <option value="">....</option>
                            <option value="Vélo_électrique_urbain">Vélo électrique urbain</option>
                            <option value="VTT_électrique">VTT électrique</option>
                            <option value="Hoverboard">Hoverboard</option>
                            <option value="Trottinette_électrique">Trottinette électrique</option>
                            <option value="Gyropode">Gyropode</option>
                            <option value="Skateboard_électrique">Skateboard électrique</option>
                        </select>
                    </td>
                    <td>
                        <label>Date Début</label>
                        <input type="Date" name="Date_Debut" value="Date_Debut" required />
                    </td>
                    <td>
                        <label>Date Fin</label>
                        <input type="Date" name="Date_Fin" value="Date_Fin" required />
                    </td>
                    <td>
                        <label>Heure Début</label>
                        <input type="Time" name="Heure_Debut" value="Heure_Debut" required />
                    </td>
                    <td>
                        <label>Heure Fin</label>
                        <input type="Time" name="Heure_Fin" value="Heure_Fin" required />
                    </td>
                    <td>
                        <label>Demande(s) spécial éventuelle(s)</label>
                        <textarea name="Demande_speciale" rows="10" cols="100"></textarea>
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
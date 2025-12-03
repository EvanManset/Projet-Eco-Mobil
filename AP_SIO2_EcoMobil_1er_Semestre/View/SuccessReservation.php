<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Succès - EcoMobil</title>
    <link rel="icon" href="assets/Eco-Mobil.png" type="image/png">
    <link rel="apple-touch-icon" href="assets/Eco-Mobil.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #e0e0e0;
            --light-shadow: #ffffff;
            --dark-shadow: #a3b1c6;
            --accent-color: #71b852;
            --accent-hover: #5fa73d;
            --main-text-color: #333;
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
        }

        @keyframes backgroundAnimation {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .success-card {
            background: var(--bg-color);
            padding: 40px;
            border-radius: 30px;
            box-shadow: 10px 10px 20px var(--dark-shadow), -10px -10px 20px var(--light-shadow);
            text-align: center;
            max-width: 650px; /* Un peu plus large pour le texte détaillé */
            width: 90%;
        }

        .success-message {
            color: #27ae60;
            background: #eafaf1;
            padding: 20px;
            border-radius: 15px;
            font-weight: 500;
            font-size: 1.1em;
            margin-bottom: 30px;
            box-shadow: inset 3px 3px 7px rgba(0,0,0,0.1), inset -3px -3px 7px #fff;
            display: flex;
            flex-direction: column; /* Texte sous l'icône */
            align-items: center;
            justify-content: center;
            gap: 10px;
            line-height: 1.6; /* Pour que le texte soit aéré */
        }

        .success-icon {
            font-size: 2em;
            margin-bottom: 5px;
        }

        .btn-retour {
            display: inline-block;
            padding: 15px 30px;
            border-radius: 25px;
            background: var(--accent-color);
            color: white;
            text-decoration: none;
            font-weight: bold;
            box-shadow: 6px 6px 12px var(--dark-shadow), -6px -6px 12px var(--light-shadow);
            transition: transform 0.2s ease;
        }

        .btn-retour:hover {
            background: var(--accent-hover);
            transform: translateY(-3px);
        }
    </style>
</head>
<body>

<div class="success-card">
    <div class="success-message">
        <div class="success-icon">✅</div>
        <div>
            <?php
            // 1. On vérifie si les variables existent, sinon on met des valeurs par défaut
            $type = isset($Type_Vehicule) ? str_replace('_', ' ', $Type_Vehicule) : 'Véhicule';
            $agence = isset($Agence) ? $Agence : 'Agence';

            // 2. Formatage des dates en Français (JJ/MM/AAAA)
            $dateD = isset($Date_Debut) ? date("d/m/Y", strtotime($Date_Debut)) : 'Date début';
            $dateF = isset($Date_Fin) ? date("d/m/Y", strtotime($Date_Fin)) : 'Date fin';

            $heureD = isset($Heure_Debut) ? $Heure_Debut : '--:--';
            $heureF = isset($Heure_Fin) ? $Heure_Fin : '--:--';

            // 3. Affichage du message dynamique
            echo "Votre réservation d’un <strong>" . ($type) . "</strong> ";
            echo "dans l’agence <strong>" . ($agence) . "</strong><br>";
            echo "du <strong>" . $dateD . "</strong> au <strong>" . $dateF . "</strong> ";
            echo "de <strong>" . ($heureD) . "</strong> à <strong>" . ($heureF) . "</strong><br>";
            echo "est réservée avec succès.";
            ?>
        </div>
    </div>
    <a href="index.php" class="btn-retour">Retour à l'accueil</a>
</div>

</body>
</html>
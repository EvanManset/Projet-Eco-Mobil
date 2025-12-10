<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Succès - EcoMobil</title>


    <link rel="icon" href="assets/Eco-Mobil.png" type="image/png">
    <link rel="apple-touch-icon" href="assets/Eco-Mobil.png">


    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* =========================================
           STYLE SOFT UI / NEUMORPHISM
           ========================================= */
        :root {
            --bg-color: #e0e5ec;
            --light-shadow: #ffffff;
            --dark-shadow: #a3b1c6;
            --main-text-color: #333;
            --accent-color: #71b852;
            --accent-hover: #5fa73d;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-color);
            color: var(--main-text-color);
            padding: 20px;
        }

        .success-card {
            background-color: var(--bg-color);
            padding: 50px;
            border-radius: 30px;
            text-align: center;
            max-width: 700px;
            width: 100%;
            /* Relief sortant (Neumorphism) */
            box-shadow: 9px 9px 16px var(--dark-shadow),
            -9px -9px 16px var(--light-shadow);
        }

        /* En-tête avec l'icône */
        .success-header {
            margin-bottom: 30px;
        }

        .success-icon-wrapper {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background-color: var(--bg-color);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px auto;
            /* Cercle en relief */
            box-shadow: 5px 5px 10px var(--dark-shadow),
            -5px -5px 10px var(--light-shadow);
        }

        .success-icon {
            font-size: 40px;
            color: var(--accent-color);
        }

        h2 {
            margin: 0;
            color: var(--accent-color);
            font-weight: 700;
            font-size: 1.8em;
        }

        /* Bloc Détails (Style creusé/Inset) */
        .details-container {
            background-color: var(--bg-color);
            border-radius: 20px;
            padding: 30px;
            text-align: left;
            margin-bottom: 30px;
            /* Effet creusé */
            box-shadow: inset 5px 5px 10px var(--dark-shadow),
            inset -5px -5px 10px var(--light-shadow);
        }

        .details-list {
            list-style: none;
            padding: 0;
            margin: 0;
            font-size: 1em;
            color: #555;
        }

        .details-list li {
            margin-bottom: 12px;
            display: flex;
            align-items: flex-start;
        }

        .details-list li:last-child { margin-bottom: 0; }

        .details-list i {
            color: var(--accent-color);
            margin-right: 12px;
            margin-top: 4px; /* Alignement icône */
            width: 20px;
            text-align: center;
        }

        .details-list strong {
            color: #333;
            font-weight: 600;
            margin-right: 5px;
        }

        /* Liste participants */
        .participants-block {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid rgba(0,0,0,0.05); /* Séparateur subtil */
        }

        .participants-title {
            display: flex;
            align-items: center;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }

        .participants-list {
            margin: 0;
            padding-left: 32px; /* Alignement sous le texte */
            list-style-type: none;
        }

        .participants-list li {
            position: relative;
            padding-left: 15px;
            font-size: 0.95em;
            margin-bottom: 5px;
        }

        .participants-list li::before {
            content: "•";
            color: var(--accent-color);
            position: absolute;
            left: 0;
            font-weight: bold;
        }

        /* Texte informatif */
        .info-text {
            color: #777;
            font-size: 0.9em;
            margin-bottom: 40px;
            font-style: italic;
        }

        /* Bouton Retour (Style Soft UI identique aux autres pages) */
        .btn-retour {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 15px 35px;
            border-radius: 50px;
            background-color: var(--accent-color);
            color: white;
            text-decoration: none;
            font-weight: 700;
            font-size: 1.1em;
            transition: all 0.3s ease;
            /* Relief sortant */
            box-shadow: 6px 6px 12px var(--dark-shadow),
            -6px -6px 12px var(--light-shadow);
        }

        .btn-retour:hover {
            background-color: var(--accent-hover);
            transform: translateY(-3px);
            box-shadow: 8px 8px 16px var(--dark-shadow),
            -8px -8px 16px var(--light-shadow);
        }

        .btn-retour i { margin-right: 10px; }

        @media (max-width: 600px) {
            .success-card { padding: 30px 20px; }
            .details-container { padding: 20px; }
        }
    </style>
</head>
<body>

<div class="success-card">

    <div class="success-header">
        <div class="success-icon-wrapper">
            <i class="fa-solid fa-check success-icon"></i>
        </div>
        <h2>Réservation Confirmée !</h2>
    </div>

    <?php
    // 1. Récupération robuste des données
    $source_data = [];
    if (isset($s1)) $source_data = $s1;
    elseif (isset($step1)) $source_data = $step1;
    elseif (isset($_SESSION['reservation_step1'])) $source_data = $_SESSION['reservation_step1'];

    // Extraction avec valeurs par défaut
    $typeRaw = $_POST['Type_Vehicule'] ?? $Type_Vehicule ?? 'Véhicule';
    $agence = $source_data['Agence'] ?? $Agence ?? 'Agence inconnue';
    $rawDateD = $source_data['Date_Debut'] ?? $Date_Debut ?? null;
    $rawDateF = $source_data['Date_Fin'] ?? $Date_Fin ?? null;
    $heureD = $source_data['Heure_Debut'] ?? $Heure_Debut ?? '--:--';
    $heureF = $source_data['Heure_Fin'] ?? $Heure_Fin ?? '--:--';

    // 2. Formatage
    $typeLabel = str_replace('_', ' ', $typeRaw);
    $dateD = $rawDateD ? date("d/m/Y", strtotime($rawDateD)) : '--/--/----';
    $dateF = $rawDateF ? date("d/m/Y", strtotime($rawDateF)) : '--/--/----';

    $nb = isset($nb_reservations) ? $nb_reservations : 1;
    ?>

    <div class="details-container">
        <ul class="details-list">
            <li>
                <i class="fa-solid fa-car-side"></i>
                <span><strong>Véhicule :</strong> <?= $nb ?> x <?= ucfirst($typeLabel) ?></span>
            </li>
            <li>
                <i class="fa-solid fa-location-dot"></i>
                <span><strong>Agence :</strong> <?= ($agence) ?></span>
            </li>
            <li>
                <i class="fa-regular fa-calendar-check"></i>
                <span><strong>Début :</strong> Le <?= $dateD ?> à <?= ($heureD) ?></span>
            </li>
            <li>
                <i class="fa-solid fa-flag-checkered"></i>
                <span><strong>Fin :</strong> Le <?= $dateF ?> à <?= ($heureF) ?></span>
            </li>

            <?php
            // Affichage des participants si disponibles
            if (isset($participants) && is_array($participants) && count($participants) > 0 && $participants[0] !== '') {
                echo "<li class='participants-block'>";
                echo "<div style='width:100%'>";
                echo "<div class='participants-title'><i class='fa-solid fa-users'></i> Pour :</div>";
                echo "<ul class='participants-list'>";
                foreach ($participants as $p) {
                    if(trim($p) !== '') {
                        echo "<li>" . ($p) . "</li>";
                    }
                }
                echo "</ul></div></li>";
            }
            ?>
        </ul>
    </div>

    <p class="info-text">
        Un email de confirmation vous sera envoyé prochainement sur votre adresse mail.
    </p>

    <a href="index.php" class="btn-retour">
        <i class="fa-solid fa-house"></i> Retour à l'accueil
    </a>

</div>

</body>
</html>
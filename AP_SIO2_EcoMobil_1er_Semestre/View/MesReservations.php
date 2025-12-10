<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Réservations - EcoMobil</title>

    <link rel="icon" href="assets/Eco-Mobil.png" type="image/png">
    <link rel="apple-touch-icon" href="assets/Eco-Mobil.png">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --bg-color: #e0e0e0;
            --main-text-color: #333;
            --accent-color: #71b852;
            --card-shadow: 8px 8px 16px #a3b1c6, -8px -8px 16px #ffffff;
        }

        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: var(--bg-color);
            color: var(--main-text-color);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px 20px;
        }

        h1 {
            font-size: 2.5em;
            color: var(--accent-color);
            margin-bottom: 40px;
            text-align: center;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }

        /* Conteneur principal */
        .container {
            width: 100%;
            max-width: 1200px; /* Élargi pour que le tableau respire */
        }

        /* Bouton Retour */
        .btn-back {
            display: inline-block;
            margin-bottom: 30px;
            padding: 10px 20px;
            border-radius: 50px;
            background: var(--bg-color);
            color: #555;
            text-decoration: none;
            font-weight: 600;
            box-shadow: 5px 5px 10px #a3b1c6, -5px -5px 10px #ffffff;
            transition: transform 0.2s;
        }
        .btn-back:hover {
            transform: translateY(-2px);
            color: var(--accent-color);
        }

        /* Message vide */
        .empty-state {
            text-align: center;
            padding: 50px;
            background: var(--bg-color);
            border-radius: 30px;
            box-shadow: var(--card-shadow);
        }

        /* TABLEAU (Desktop) */
        .table-wrapper {
            background: var(--bg-color);
            border-radius: 20px;
            padding: 20px;
            box-shadow: inset 5px 5px 10px #a3b1c6, inset -5px -5px 10px #ffffff;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1050px;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            vertical-align: middle;
        }

        th {
            color: var(--accent-color);
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.9em;
        }

        tr:last-child td {
            border-bottom: none;
        }

        /* Badges de statut */
        .badge {
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 0.85em;
            font-weight: 600;
            display: inline-block;
            white-space: nowrap; /* Empêche aussi le statut de se casser */
        }
        .badge-confirmee { background: #d4edda; color: #155724; }
        .badge-en-cours { background: #cce5ff; color: #004085; }
        .badge-terminee { background: #e2e3e5; color: #383d41; }
        .badge-annulee { background: #f8d7da; color: #721c24; }
        .badge-reservee { background: #fff3cd; color: #856404; }

        /* Style Demande Spéciale */
        .demande-speciale-box {
            background: rgba(255, 255, 255, 0.5);
            padding: 8px;
            border-radius: 8px;
            font-style: italic;
            font-size: 0.9em;
            color: #555;
            margin-top: 5px;
        }

        /* VUE MOBILE (Cartes) */
        .mobile-card {
            display: none;
            background: var(--bg-color);
            margin-bottom: 20px;
            padding: 20px;
            border-radius: 20px;
            box-shadow: var(--card-shadow);
        }

        .mobile-card h3 {
            margin: 0 0 5px 0;
            color: var(--accent-color);
        }

        .mobile-card p {
            margin: 8px 0;
            font-size: 0.95em;
            color: #555;
        }

        @media (max-width: 900px) {
            .table-wrapper { display: none; }
            .mobile-card { display: block; }
        }
    </style>
</head>
<body>

<div class="container">
    <a href="index.php" class="btn-back"><i class="fa-solid fa-arrow-left"></i> Retour au tableau de bord</a>

    <h1>Mes Réservations</h1>

    <?php if (empty($mesReservations)): ?>
        <div class="empty-state">
            <h2>Aucune réservation pour le moment 🍃</h2>
            <p>Vous n'avez pas encore effectué de trajet avec nous.</p>
            <br>
            <a href="index.php?action=reservation_step1" class="btn-back" style="background:var(--accent-color); color:white;">Réserver un véhicule</a>
        </div>
    <?php else: ?>

        <div class="table-wrapper">
            <table>
                <thead>
                <tr>
                    <th>Véhicule</th>
                    <th>Agence</th>
                    <th style="width: 25%;">Demande Spéciale</th>
                    <th>Du</th>
                    <th>Au</th>
                    <th>Prix Total</th>
                    <th>Statut</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($mesReservations as $resa): ?>
                    <?php
                    // Préparation des données d'affichage
                    $type = isset($resa['libelle_Type']) ? str_replace('_', ' ', $resa['libelle_Type']) : 'Véhicule';
                    $nb = isset($resa['nb_participants']) ? $resa['nb_participants'] : 1;
                    ?>
                    <tr>
                        <td>
                            <!-- Affichage adapté : Quantité ET Type sur 2 lignes -->
                            <!-- Couleur #333 (Gris très foncé) pour le nombre -->
                            <strong style="white-space: nowrap; font-size: 1.1em; color: #333;">
                                <?php echo $nb; ?> Véhicule(s)
                            </strong>
                            <br>
                            <!-- Couleur #333 (Gris très foncé) pour le type aussi, pour être IDENTIQUE -->
                            <small style="color: #333; font-weight: 600; font-size: 0.9em;">
                                <?php echo ucfirst($type); ?>
                            </small>
                        </td>
                        <td><?php echo ($resa['nom_Agence']); ?></td>

                        <td>
                            <?php if (!empty($resa['Demande_speciale'])): ?>
                                <div class="demande-speciale-box">
                                    "<?php echo ($resa['Demande_speciale']); ?>"
                                </div>
                            <?php else: ?>
                                <span style="color: #aaa;">-</span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <?php echo date("d/m/Y", strtotime($resa['date_debut_location'])); ?><br>
                            <small><?php echo date("H:i", strtotime($resa['date_debut_location'])); ?></small>
                        </td>
                        <td>
                            <?php echo date("d/m/Y", strtotime($resa['date_fin_location'])); ?><br>
                            <small><?php echo date("H:i", strtotime($resa['date_fin_location'])); ?></small>
                        </td>
                        <td style="font-weight:bold; white-space: nowrap;">
                            <?php echo number_format($resa['montant_totale'], 2, ',', ' '); ?> €
                        </td>
                        <td>
                            <?php
                            $statut = strtolower($resa['statut_reservation']);
                            $class = 'badge-terminee';
                            if(strpos($statut, 'confirm') !== false) $class = 'badge-confirmee';
                            if(strpos($statut, 'cours') !== false) $class = 'badge-en-cours';
                            if(strpos($statut, 'annul') !== false) $class = 'badge-annulee';
                            if(strpos($statut, 'réservée') !== false) $class = 'badge-reservee';
                            ?>
                            <span class="badge <?php echo $class; ?>"><?php echo ($resa['statut_reservation']); ?></span>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php foreach ($mesReservations as $resa): ?>
            <?php
            $type = isset($resa['libelle_Type']) ? str_replace('_', ' ', $resa['libelle_Type']) : 'Véhicule';
            $nb = isset($resa['nb_participants']) ? $resa['nb_participants'] : 1;
            ?>
            <div class="mobile-card">
                <!-- Titre Mobile adapté : Couleur forcée à #333 pour uniformité -->
                <h3 style="color: #333;"><?php echo $nb; ?> Véhicule(s)</h3>
                <!-- Type Mobile : Couleur #333 -->
                <div style="color: #333; font-weight: 600; margin-bottom: 10px;">
                    <?php echo ucfirst($type); ?>
                </div>

                <p><strong>Agence :</strong> <?php echo ($resa['nom_Agence']); ?></p>
                <p><strong>Départ :</strong> <?php echo date("d/m/Y H:i", strtotime($resa['date_debut_location'])); ?></p>
                <p><strong>Retour :</strong> <?php echo date("d/m/Y H:i", strtotime($resa['date_fin_location'])); ?></p>

                <?php if (!empty($resa['Demande_speciale'])): ?>
                    <p>
                        <strong>Demande spéciale :</strong>
                        <span class="demande-speciale-box" style="display:block; margin-top:5px;">
                            <?php echo ($resa['Demande_speciale']); ?>
                        </span>
                    </p>
                <?php endif; ?>

                <p style="margin-top:10px;"><strong>Prix :</strong> <?php echo number_format($resa['montant_totale'], 2, ',', ' '); ?> €</p>
                <p><strong>Statut :</strong> <?php echo ($resa['statut_reservation']); ?></p>
            </div>
        <?php endforeach; ?>

    <?php endif; ?>
</div>

</body>
</html>
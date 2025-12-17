<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Réservation</title>

    <link rel="icon" href="assets/Eco-Mobil.png" type="image/png">
    <link rel="apple-touch-icon" href="assets/Eco-Mobil.png">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"/>

    <style>
        :root {
            --bg-color: #e0e5ec;
            --text-main: #4a5568;
            --accent-green: #8fd14f;
            --accent-red: #e53e3e;
            --neu-flat: 9px 9px 16px #a3b1c6, -9px -9px 16px #ffffff;
            --neu-pressed: inset 6px 6px 10px #a3b1c6, inset -6px -6px 10px #ffffff;
        }

        body {
            background: var(--bg-color);
            font-family: "Segoe UI", sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: var(--text-main);
            margin: 0;
            padding: 20px;
        }

        .edit-card {
            background: var(--bg-color);
            padding: 40px;
            border-radius: 30px;
            box-shadow: var(--neu-flat);
            width: 100%;
            max-width: 450px;
            text-align: center;
        }

        h2 { margin-bottom: 25px; color: var(--accent-green); }

        .info-row {
            text-align: left;
            margin-bottom: 15px;
            font-weight: 500;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding-bottom: 8px;
        }

        .info-label {
            font-weight: bold;
            color: #718096;
            display: block;
            font-size: 0.85em;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 4px;
        }

        /* --- STYLE DES PARTICIPANTS --- */
        .participant-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 10px;
            margin-bottom: 8px;
            background: rgba(255,255,255,0.4);
            border-radius: 10px;
        }
        .btn-trash {
            color: var(--accent-red);
            background: transparent;
            border: none;
            cursor: pointer;
            font-size: 1.1em;
            transition: transform 0.2s;
            text-decoration: none;
            padding: 5px;
        }
        .btn-trash:hover { transform: scale(1.2); }

        select {
            width: 100%; padding: 12px; border-radius: 10px; border: none;
            background: var(--bg-color); box-shadow: var(--neu-pressed);
            margin: 20px 0; font-size: 1rem; color: var(--text-main);
            outline: none; cursor: pointer; font-weight: bold;
        }

        .btn-group { display: flex; gap: 20px; justify-content: center; margin-top: 30px; }
        .btn {
            padding: 12px 25px; border: none; border-radius: 50px;
            font-weight: bold; cursor: pointer; box-shadow: var(--neu-flat);
            transition: all 0.2s ease; text-decoration: none; display: inline-block; font-size: 0.95rem;
        }
        .btn-save { background: var(--accent-green); color: white; }
        .btn-cancel { background: var(--bg-color); color: var(--text-main); }
        .btn:hover { transform: translateY(-2px); }
        .btn:active { box-shadow: var(--neu-pressed); transform: translateY(0); }
    </style>
</head>
<body>

<div class="edit-card">
    <h2><i class="fas fa-edit"></i> Modifier Réservation</h2>

    <div class="info-row">
        <span class="info-label">Référence</span>
        <span style="font-size: 1.2em; font-weight: bold;">#<?php echo $reservation['id_Reservation']; ?></span>
    </div>

    <div class="info-row">
        <span class="info-label">Client Principal</span>
        <?php echo ($reservation['Nom'] . ' ' . $reservation['Prenom']); ?>
    </div>

    <div class="info-row">
        <span class="info-label">Liste des Participants</span>
        <div style="margin-top:10px;">
            <?php if (!empty($participants_list)): ?>
                <?php foreach ($participants_list as $p): ?>
                    <div class="participant-item">
                        <span>
                            <i class="fas fa-user" style="color:#a0aec0; margin-right:8px;"></i>
                            <?php echo ($p['Nom'] . ' ' . $p['Prenom']); ?>
                        </span>

                        <a href="index.php?action=admin_delete_participant&id_part=<?php echo $p['id_Participants']; ?>&id_res=<?php echo $reservation['id_Reservation']; ?>"
                           class="btn-trash"
                           title="Supprimer ce participant"
                           onclick="return confirm('Supprimer <?php echo ($p['Nom']); ?> de la liste ?');">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <span style="font-style:italic; color:#aaa; font-size:0.9em;">Aucun participant listé (ou erreur chargement).</span>
            <?php endif; ?>
        </div>
    </div>

    <div class="info-row">
        <span class="info-label">Véhicule</span>
        <?php echo ($reservation['libelle_Type']); ?>
    </div>

    <div class="info-row">
        <span class="info-label">Dates de Location</span>
        <div style="margin-top:5px; font-size:0.95em; display: flex; flex-direction: column; gap: 5px;">
            <div>
                <i class="fas fa-play" style="color: var(--accent-green); width:20px; text-align:center;"></i>
                <?php echo date('d/m/Y H:i', strtotime($reservation['date_debut_location'])); ?>
            </div>
            <div>
                <i class="fas fa-stop" style="color: var(--accent-red); width:20px; text-align:center;"></i>
                <?php echo date('d/m/Y H:i', strtotime($reservation['date_fin_location'])); ?>
            </div>
        </div>
    </div>

    <div class="info-row">
        <span class="info-label">Montant Total</span>
        <span style="font-size: 1.1em; font-weight: 800; color: var(--text-main);">
            <?php echo number_format($reservation['montant_totale'], 2); ?> €
        </span>
    </div>

    <div class="info-row">
        <span class="info-label">Agence de départ</span>
        <?php echo ($reservation['nom_Agence']); ?>
    </div>

    <form action="index.php?action=admin_update_res" method="POST">
        <input type="hidden" name="id_res" value="<?php echo $reservation['id_Reservation']; ?>">

        <label class="info-label" style="text-align:left; margin-top: 25px;">Statut actuel</label>
        <select name="statut">
            <option value="Réservée" <?php if($reservation['statut_reservation'] == 'Réservée') echo 'selected'; ?>>Réservée</option>
            <option value="Confirmée" <?php if(strtolower($reservation['statut_reservation']) == 'confirmée') echo 'selected'; ?>>Confirmée</option>
            <option value="En cours" <?php if(strtolower($reservation['statut_reservation']) == 'en cours') echo 'selected'; ?>>En cours</option>
            <option value="Terminée" <?php if(strtolower($reservation['statut_reservation']) == 'terminée') echo 'selected'; ?>>Terminée</option>
            <option value="Annulée" <?php if(strtolower($reservation['statut_reservation']) == 'annulée') echo 'selected'; ?>>Annulée</option>
        </select>

        <div class="btn-group">
            <button type="submit" class="btn btn-save">Enregistrer</button>
            <a href="index.php?action=admin" class="btn btn-cancel">Annuler</a>
        </div>
    </form>
</div>

</body>
</html>
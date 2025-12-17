<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Eco-Mobil Admin - Tableau de Bord</title>

    <link rel="icon" href="assets/Eco-Mobil.png" type="image/png">
    <link rel="apple-touch-icon" href="assets/Eco-Mobil.png">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* ============================================================
           1. VARIABLES & RESET
           ============================================================ */
        :root {
            --bg-color: #e0e5ec;
            --text-main: #4a5568;
            --text-light: #a0aec0;
            --accent-green: #71B852;
            --accent-dark-green: #2e7d32;
            --accent-red: #e53e3e;
            --accent-orange: #dd6b20;
            --accent-blue: #3182ce;
            --shadow-light: #ffffff;
            --shadow-dark: #a3b1c6;
            --neu-flat: 9px 9px 16px var(--shadow-dark), -9px -9px 16px var(--shadow-light);
            --neu-pressed: inset 6px 6px 10px var(--shadow-dark), inset -6px -6px 10px var(--shadow-light);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: "Segoe UI", sans-serif; }

        body {
            background-color: var(--bg-color);
            color: var(--text-main);
            display: flex;
            min-height: 100vh;
            padding: 20px;
            position: relative;
        }

        /* ============================================================
           2. NAVIGATION & SIDEBAR
           ============================================================ */
        input[type="radio"] { display: none; }

        .sidebar {
            width: 280px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 25px;
            height: calc(100vh - 40px);
            position: sticky;
            top: 20px;
        }

        .brand {
            padding: 15px 25px;
            border-radius: 50px;
            box-shadow: var(--neu-flat);
            text-align: center;
            font-weight: 800;
            font-size: 1.2rem;
            color: var(--text-main);
        }

        .menu-list { display: flex; flex-direction: column; gap: 20px; }

        .menu-label {
            display: flex; align-items: center;
            padding: 15px 25px;
            border-radius: 15px;
            border-top-right-radius: 50px; border-bottom-right-radius: 50px;
            color: var(--text-main);
            box-shadow: var(--neu-flat);
            cursor: pointer;
            transition: all 0.2s ease;
            font-weight: 600;
        }

        .menu-label i { width: 30px; color: var(--text-light); }
        .menu-label:hover { color: var(--accent-green); transform: translateY(-2px); }
        .menu-label:hover i { color: var(--accent-green); }

        /* États Actifs */
        #nav-dash:checked ~ .sidebar .lbl-dash,
        #nav-agences:checked ~ .sidebar .lbl-agences,
        #nav-res:checked ~ .sidebar .lbl-res,
        #nav-users:checked ~ .sidebar .lbl-users,
        #nav-parc:checked ~ .sidebar .lbl-parc,
        #nav-admin:checked ~ .sidebar .lbl-admin {
            background-color: var(--accent-green);
            color: white;
            box-shadow: inset 3px 3px 6px rgba(0, 0, 0, 0.1), inset -3px -3px 6px rgba(255, 255, 255, 0.2);
        }

        #nav-dash:checked ~ .sidebar .lbl-dash i,
        #nav-agences:checked ~ .sidebar .lbl-agences i,
        #nav-res:checked ~ .sidebar .lbl-res i,
        #nav-users:checked ~ .sidebar .lbl-users i,
        #nav-parc:checked ~ .sidebar .lbl-parc i,
        #nav-admin:checked ~ .sidebar .lbl-admin i { color: white; }

        /* ============================================================
           3. CONTENU PRINCIPAL & LAYOUT
           ============================================================ */
        .main-content { flex: 1; padding: 20px 40px; overflow-y: auto; }
        .content-section { display: none; animation: fadeIn 0.5s; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        #nav-dash:checked ~ .main-content #section-dashboard { display: block; }
        #nav-agences:checked ~ .main-content #section-agences { display: block; }
        #nav-res:checked ~ .main-content #section-res { display: block; }
        #nav-users:checked ~ .main-content #section-users { display: block; }
        #nav-parc:checked ~ .main-content #section-parc { display: block; }
        #nav-admin:checked ~ .main-content #section-admin { display: block; }

        .header-title { margin-bottom: 30px; }
        .header-title h1 { font-size: 2.5rem; font-weight: 800; color: #000; }

        .neu-card {
            padding: 30px;
            border-radius: 30px;
            box-shadow: var(--neu-flat);
            background-color: var(--bg-color);
            margin-bottom: 30px;
        }

        /* --- FILTRES DATE (NEUMORPHISM) --- */
        .filter-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 20px;
        }

        .neu-switch {
            background: var(--bg-color);
            border-radius: 50px;
            padding: 5px;
            display: inline-flex;
            box-shadow: inset 4px 4px 8px var(--shadow-dark), inset -4px -4px 8px var(--shadow-light);
        }

        .switch-btn {
            border: none;
            background: transparent;
            padding: 10px 20px;
            border-radius: 50px;
            cursor: pointer;
            font-weight: 600;
            color: var(--text-light);
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }
        .switch-btn:hover { color: var(--text-main); }

        .switch-btn.active {
            background-color: #e2eed8;
            color: #4c8830;
            box-shadow: 4px 4px 8px var(--shadow-dark), -4px -4px 8px var(--shadow-light);
        }

        .neu-input-date {
            padding: 8px 12px;
            border-radius: 15px;
            border: none;
            background: var(--bg-color);
            box-shadow: inset 3px 3px 6px var(--shadow-dark), inset -3px -3px 6px var(--shadow-light);
            color: var(--text-main);
            outline: none;
        }

        .btn-ok {
            padding: 8px 12px;
            border-radius: 50%;
            border: none;
            background: var(--accent-green);
            color: white;
            cursor: pointer;
            box-shadow: 3px 3px 6px var(--shadow-dark), -3px -3px 6px var(--shadow-light);
        }
        .btn-ok:hover { transform: scale(1.1); }

        /* --- BOUTON EXPORT --- */
        .btn-export {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 15px 25px;
            border-radius: 20px;
            text-decoration: none;
            font-weight: 800;
            font-size: 1rem;
            box-shadow: var(--neu-flat);
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
            background-color: var(--bg-color);
            color: var(--accent-green);
        }
        .btn-export:hover { transform: translateY(-2px); background-color: #f0f4f8; }
        .btn-export:active { box-shadow: var(--neu-pressed); transform: translateY(0); }


        /* ============================================================
           4. DASHBOARD GRID & TABLEAUX
           ============================================================ */
        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            align-items: start;
        }
        @media (max-width: 1100px) { .dashboard-grid { grid-template-columns: 1fr; } }

        .data-table { width: 100%; border-collapse: separate; border-spacing: 0 15px; }
        .data-table th { text-align: left; color: #000; font-weight: 800; padding-bottom: 10px; opacity: 0.7; }
        .data-table td {
            color: var(--text-main); font-weight: 600; vertical-align: middle;
            padding: 5px 0; border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .val-number { font-size: 1.1rem; color: #2d3748; }
        .val-money { color: var(--accent-green); font-weight: 800; }

        .res-badge { padding: 6px 15px; border-radius: 20px; font-size: 0.8rem; font-weight: bold; box-shadow: var(--neu-flat); }
        .badge-green { color: var(--accent-green); background: #eef7e6; }
        .badge-orange { color: var(--accent-orange); background: #fffaf0; }
        .badge-blue { color: var(--accent-blue); background: #ebf8ff; }
        .badge-red { color: var(--accent-red); background: #fff5f5; }

        .btn-action { display: inline-block; padding: 8px; font-size: 1.1em; transition: 0.2s; margin-right: 5px; }
        .btn-edit { color: var(--accent-blue); }
        .btn-delete { color: var(--accent-red); }
        .btn-action:hover { transform: scale(1.2); }

        /* BOUTON DÉCONNEXION */
        .logout-btn-top {
            position: absolute; top: 30px; right: 40px; width: 50px; height: 50px;
            border-radius: 50%; background: var(--bg-color); box-shadow: var(--neu-flat);
            display: flex; align-items: center; justify-content: center;
            color: var(--accent-red); font-size: 1.2rem; text-decoration: none; transition: all 0.2s ease; z-index: 1000;
        }
        .logout-btn-top:hover { transform: translateY(-2px); color: #c53030; }
        .logout-btn-top:active { box-shadow: var(--neu-pressed); transform: translateY(0); }

        /* AGENCES GRID */
        .agencies-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 30px; }
        .agency-card {
            padding: 25px; border-radius: 25px; box-shadow: var(--neu-flat);
            display: flex; flex-direction: column; gap: 15px; position: relative; overflow: hidden;
        }
        .agency-status {
            position: absolute; top: 0; right: 0; padding: 5px 15px; font-size: 0.8rem; font-weight: bold;
            border-bottom-left-radius: 15px; color: white; background-color: var(--accent-green);
        }
        .agency-icon {
            width: 50px; height: 50px; border-radius: 50%; background: #e0e5ec; box-shadow: var(--neu-flat);
            display: flex; align-items: center; justify-content: center; color: var(--accent-green); font-size: 1.2rem;
        }
        .stock-indicator {
            margin-top: 10px; display: flex; justify-content: space-between; align-items: center;
            background: #e0e5ec; padding: 10px; border-radius: 10px; box-shadow: var(--neu-pressed);
        }

        /* PARC GRID */
        .parc-grid { display: flex; flex-wrap: wrap; gap: 30px; }
        .parc-card { flex: 1; min-width: 250px; display: flex; align-items: center; gap: 20px; padding: 25px; }
        .parc-icon {
            width: 60px; height: 60px; border-radius: 20px; background: #e0e5ec; box-shadow: var(--neu-flat);
            display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: #4a5568;
        }
        .parc-info { flex: 1; }
        .parc-stats { display: flex; justify-content: space-between; font-size: 0.85rem; color: var(--text-light); margin-bottom: 5px; }
        .progress-bg { height: 8px; width: 100%; background: #d1d9e6; border-radius: 5px; box-shadow: var(--neu-pressed); overflow: hidden; }
        .progress-fill { height: 100%; border-radius: 5px; }

        /* LOGS */
        .logs-container { font-family: "Consolas", monospace; font-size: 0.9rem; display: flex; flex-direction: column; gap: 15px; }
        .log-entry { display: flex; gap: 10px; }
        .log-time { color: var(--accent-red); font-weight: bold; min-width: 60px; }
    </style>
</head>
<body>

<input type="radio" name="nav" id="nav-dash" checked />
<input type="radio" name="nav" id="nav-agences" />
<input type="radio" name="nav" id="nav-res" />
<input type="radio" name="nav" id="nav-users" />
<input type="radio" name="nav" id="nav-parc" />
<input type="radio" name="nav" id="nav-admin" />

<div class="sidebar">
    <div class="brand">Eco-Mobil <span style="color: var(--accent-green)">Admin</span></div>
    <nav class="menu-list">
        <label for="nav-dash" class="menu-label lbl-dash"><i class="fas fa-chart-line"></i> <span>Tableau de Bord</span></label>
        <label for="nav-agences" class="menu-label lbl-agences"><i class="fas fa-building"></i> <span>Réseau & Agences</span></label>
        <label for="nav-res" class="menu-label lbl-res"><i class="fas fa-calendar-check"></i> <span>Réservations</span></label>
        <label for="nav-users" class="menu-label lbl-users"><i class="fas fa-users"></i> <span>Participants</span></label>
        <label for="nav-parc" class="menu-label lbl-parc"><i class="fas fa-bicycle"></i> <span>Gestion Parc</span></label>
        <label for="nav-admin" class="menu-label lbl-admin"><i class="fas fa-shield-alt"></i> <span>Administration</span></label>
    </nav>
</div>

<a href="index.php?action=logout" class="logout-btn-top" title="Se déconnecter">
    <i class="fas fa-arrow-right-from-bracket"></i>
</a>

<div class="main-content">

    <section id="section-dashboard" class="content-section">
        <div class="header-title"><h2>Suivi d'Activité</h2><h1>Vue Intégrée</h1></div>

        <div class="filter-bar">

            <div class="filter-wrapper">
                <form method="GET" action="index.php" style="display:flex; align-items:center; gap:10px;">
                    <input type="hidden" name="action" value="admin">

                    <div class="neu-switch">
                        <?php $f = $_GET['filter'] ?? 'semaine'; ?>

                        <button type="submit" name="filter" value="semaine" class="switch-btn <?php echo $f=='semaine'?'active':''; ?>">Semaine</button>
                        <button type="submit" name="filter" value="mois" class="switch-btn <?php echo $f=='mois'?'active':''; ?>">Mois</button>
                        <button type="submit" name="filter" value="annee" class="switch-btn <?php echo $f=='annee'?'active':''; ?>">Année</button>
                        <button type="button" class="switch-btn <?php echo $f=='custom'?'active':''; ?>" onclick="toggleCustomDate()">Custom</button>
                    </div>

                    <div id="customDateBlock" style="display: <?php echo $f=='custom'?'flex':'none'; ?>; gap:10px; align-items:center;">
                        <input type="date" name="start" value="<?php echo $_GET['start'] ?? ''; ?>" class="neu-input-date">
                        <span style="font-weight:bold;">au</span>
                        <input type="date" name="end" value="<?php echo $_GET['end'] ?? ''; ?>" class="neu-input-date">

                        <button type="submit" name="filter" value="custom" class="btn-ok"><i class="fas fa-check"></i></button>
                    </div>
                </form>
            </div>

            <a href="index.php?action=export_csv" class="btn-export">
                <i class="fas fa-file-csv fa-lg"></i> Export CSV
            </a>
        </div>

        <div class="dashboard-grid">

            <div class="neu-card">
                <h3 style="margin-bottom: 20px">Performance par Véhicule</h3>
                <table class="data-table">
                    <thead><tr><th>Type</th><th>Nb Locs</th><th>CA (€)</th></tr></thead>
                    <tbody>
                    <?php if (!empty($stats['par_type'])): foreach($stats['par_type'] as $type): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($type['libelle_Type']); ?></td>
                            <td><div class="val-number"><?php echo $type['nb_locs']; ?></div></td>
                            <td class="val-money"><?php echo number_format((float)$type['ca_type'], 2); ?> €</td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="3" style="text-align:center; padding:20px;">Aucune donnée sur cette période.</td></tr>
                    <?php endif; ?>
                    <tr style="border-top: 2px solid #ccc">
                        <td style="padding-top: 15px; font-weight: 800">TOTAL</td>
                        <td style="padding-top: 15px; font-weight: 800"><?php echo $stats['global']['total_locs'] ?? 0; ?></td>
                        <td style="padding-top: 15px; color: var(--accent-green); font-size: 1.2rem; font-weight: 800;">
                            <?php echo number_format((float)$stats['global']['ca_total'], 2); ?> €
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div class="neu-card">
                <h3 style="margin-bottom: 20px">Évolution des Réservations</h3>
                <div style="position: relative; height: 300px; width: 100%;">
                    <canvas id="reservationsChart"
                            data-labels="<?php echo htmlspecialchars(json_encode($graphData['labels'] ?? [])); ?>"
                            data-counts="<?php echo htmlspecialchars(json_encode($graphData['data'] ?? [])); ?>">
                    </canvas>
                </div>
            </div>

        </div>
    </section>

    <section id="section-agences" class="content-section">
        <div class="header-title"><h2>Infrastructure</h2><h1>Réseau d'Agences</h1></div>
        <div class="agencies-grid">
            <?php foreach($agences as $agence):
                if (preg_match('/Meylan|Lyon|Bron/i', $agence['nom_Agence'])) continue;
                ?>
                <div class="agency-card">
                    <div class="agency-status status-open">OUVERTE</div>
                    <div class="agency-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <div class="agency-info">
                        <h3><?php echo htmlspecialchars($agence['nom_Agence']); ?></h3>
                        <p><?php echo htmlspecialchars($agence['Adresse']); ?></p>
                    </div>
                    <div class="stock-indicator">
                        <span style="font-weight: bold; color: #4a5568">Véhicules loués</span>
                        <span style="color: var(--accent-green); font-weight: 800">
                            <?php echo $agence['vehicules_loues']; ?> / 90
                        </span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section id="section-res" class="content-section">
        <div class="header-title"><h2>Opérationnel</h2><h1>Gestion des Réservations</h1></div>
        <div class="neu-card">
            <table class="data-table">
                <thead>
                <tr>
                    <th style="padding-left: 10px">Réf.</th>
                    <th>Client</th>
                    <th>Type</th>
                    <th>Départ / Retour</th> <th>Total</th> <th>Agence</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php if(!empty($reservations)): ?>
                    <?php foreach($reservations as $res):
                        $badgeClass = 'badge-orange';
                        if(strtolower($res['statut_reservation']) == 'confirmée') $badgeClass = 'badge-green';
                        if(strtolower($res['statut_reservation']) == 'en cours') $badgeClass = 'badge-blue';
                        if(strtolower($res['statut_reservation']) == 'annulée') $badgeClass = 'badge-red';
                        if(strtolower($res['statut_reservation']) == 'terminée') $badgeClass = 'badge-green';

                        $dateD = date('d/m H:i', strtotime($res['date_debut_location']));
                        $dateF = date('d/m H:i', strtotime($res['date_fin_location']));
                        ?>
                        <tr>
                            <td style="padding-left: 10px; font-weight: normal; color: var(--text-light);">#<?php echo $res['id_Reservation']; ?></td>
                            <td><?php echo htmlspecialchars($res['Nom'] . ' ' . $res['Prenom']); ?></td>
                            <td><?php echo htmlspecialchars($res['libelle_Type']); ?></td>

                            <td style="font-size: 0.9em; line-height: 1.2;">
                                <div><i class="fas fa-play" style="color: var(--accent-green); font-size:0.8em"></i> <?php echo $dateD; ?></div>
                                <div><i class="fas fa-stop" style="color: var(--accent-red); font-size:0.8em"></i> <?php echo $dateF; ?></div>
                            </td>

                            <td style="font-weight: bold;"><?php echo number_format($res['montant_totale'], 2); ?> €</td>

                            <td><?php echo htmlspecialchars($res['nom_Agence']); ?></td>
                            <td><span class="res-badge <?php echo $badgeClass; ?>"><?php echo htmlspecialchars($res['statut_reservation']); ?></span></td>

                            <td>
                                <a href="index.php?action=admin_edit_page&id=<?php echo $res['id_Reservation']; ?>" class="btn-action btn-edit" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="index.php?action=admin_delete_res&id=<?php echo $res['id_Reservation']; ?>"
                                   class="btn-action btn-delete"
                                   title="Supprimer"
                                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette réservation ?');">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="8" style="text-align:center; padding:15px;">Aucune réservation récente.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

    <section id="section-users" class="content-section">
        <div class="header-title"><h2>Données Clients</h2><h1>Liste des Participants</h1></div>
        <div class="neu-card">
            <table class="data-table">
                <thead>
                <tr>
                    <th style="padding-left: 10px">Réf. Res.</th>
                    <th>Participant</th>
                    <th>ID Véh.</th>
                    <th>Véhicule Attribué</th>
                    <th>Statut</th>
                </tr>
                </thead>
                <tbody>
                <?php if(!empty($participants)): ?>
                    <?php foreach($participants as $part): ?>
                        <tr>
                            <td style="padding-left: 10px; font-weight: bold;">#<?php echo $part['id_Reservation']; ?></td>
                            <td><?php echo htmlspecialchars($part['Nom'] . ' ' . $part['Prenom']); ?></td>
                            <td style="font-weight: bold; color: var(--text-light);">#<?php echo $part['id_Vehicule']; ?></td>
                            <td><?php echo htmlspecialchars($part['Marque'] . ' ' . $part['Modele']); ?></td>
                            <td><span class="res-badge badge-green"><?php echo htmlspecialchars($part['statut_reservation']); ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" style="text-align:center; padding:15px;">Aucun participant trouvé.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

    <section id="section-parc" class="content-section">
        <div class="header-title"><h2>Inventaire</h2><h1>État de la Flotte</h1></div>
        <div class="parc-grid">
            <?php foreach($parc as $p):
                $percent = ($p['total'] > 0) ? round(($p['dispo'] / $p['total']) * 100) : 0;

                // COULEURS SPÉCIFIQUES
                // Barre : vert clair (par défaut) ou rouge (si < 20%)
                $barColor = ($percent < 20) ? 'var(--accent-red)' : 'var(--accent-green)';

                // Texte : vert FONCÉ (pour lisibilité) ou rouge
                $textColor = ($percent < 20) ? 'var(--accent-red)' : 'var(--accent-dark-green)';
                ?>
                <div class="neu-card parc-card">
                    <div class="parc-icon"><i class="fas fa-bicycle"></i></div>
                    <div class="parc-info">
                        <h3><?php echo htmlspecialchars($p['libelle_Type']); ?></h3>
                        <div class="parc-stats">
                            <span><?php echo $p['total']; ?> Total</span>
                            <span style="color: <?php echo $textColor; ?>; font-weight: 700;">
                                <?php echo $p['dispo']; ?> Dispo
                            </span>
                        </div>
                        <div class="progress-bg">
                            <div class="progress-fill" style="width: <?php echo $percent; ?>%; background-color: <?php echo $barColor; ?>"></div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section id="section-admin" class="content-section">
        <div class="header-title"><h2>Système & Sécurité</h2><h1>Administration</h1></div>
        <div class="admin-layout">
            <div class="neu-card">
                <h3 style="margin-bottom: 20px; color: var(--accent-red)"><i class="fas fa-shield-virus" style="margin-right: 10px"></i>Activité Récente</h3>
                <div class="logs-container">
                    <?php if(!empty($logs)): foreach($logs as $log): ?>
                        <div class="log-entry">
                            <span class="log-time">[<?php echo date('d/m H:i', strtotime($log['date_log'])); ?>]</span>
                            <span class="log-text"><?php echo htmlspecialchars($log['message']); ?></span>
                        </div>
                    <?php endforeach; else: ?>
                        <div class="log-entry"><span class="log-text">Aucun log récent.</span></div>
                    <?php endif; ?>
                </div>

                <div style="margin-top: 25px; text-align: center; border-top: 1px solid rgba(0,0,0,0.05); padding-top: 15px;">
                    <a href="index.php?action=admin_all_logs" style="
                        display: inline-block;
                        padding: 10px 25px;
                        background: var(--bg-color);
                        color: var(--text-main);
                        text-decoration: none;
                        font-weight: bold;
                        border-radius: 50px;
                        box-shadow: var(--neu-flat);
                        transition: all 0.2s ease;
                        font-size: 0.95em;
                        color: #555;
                    " onmouseover="this.style.transform='translateY(-2px)'; this.style.color='var(--accent-green)'"
                       onmouseout="this.style.transform='translateY(0)'; this.style.color='#555'">
                        <i class="fas fa-list-ul" style="margin-right: 8px;"></i> Voir l'historique complet
                    </a>
                </div>

            </div>
        </div>
    </section>

</div>

<script src="js/admin_dashboard.js"></script>

</body>
</html>
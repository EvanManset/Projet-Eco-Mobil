<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique des Logs</title>

    <link rel="icon" href="assets/Eco-Mobil.png" type="image/png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"/>

    <style>
        :root {
            --bg-color: #e0e5ec;
            --text-main: #4a5568;
            --accent-green: #8fd14f;
            --accent-red: #e53e3e;
            --neu-flat: 9px 9px 16px #a3b1c6, -9px -9px 16px #ffffff;
        }

        body {
            background: var(--bg-color);
            font-family: "Segoe UI", sans-serif;
            color: var(--text-main);
            padding: 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .container {
            width: 100%;
            max-width: 900px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        h1 { margin: 0; color: var(--text-main); }

        .btn-back {
            padding: 10px 20px;
            border-radius: 50px;
            background: var(--bg-color);
            color: var(--text-main);
            text-decoration: none;
            font-weight: bold;
            box-shadow: var(--neu-flat);
            transition: transform 0.2s;
        }
        .btn-back:hover { transform: translateY(-2px); color: var(--accent-green); }

        .logs-card {
            background: var(--bg-color);
            border-radius: 30px;
            padding: 30px;
            box-shadow: var(--neu-flat);
            max-height: 80vh; /* Limite la hauteur */
            overflow-y: auto; /* Ajoute le scroll si trop long */
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th { text-align: left; color: #718096; padding-bottom: 15px; border-bottom: 2px solid #cbd5e0; }
        td { padding: 12px 5px; border-bottom: 1px solid rgba(0,0,0,0.05); vertical-align: top; }

        .date-col {
            width: 180px;
            color: var(--accent-red);
            font-weight: bold;
            font-family: 'Consolas', monospace;
        }

        .msg-col { font-family: 'Consolas', monospace; font-size: 0.95em; }

        /* Scrollbar custom pour faire joli */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e0; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #a0aec0; }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1><i class="fas fa-history"></i> Historique Complet</h1>
        <a href="index.php?action=admin" class="btn-back">
            <i class="fas fa-arrow-left"></i> Retour Dashboard
        </a>
    </div>

    <div class="logs-card">
        <table>
            <thead>
            <tr>
                <th>Date & Heure</th>
                <th>Message</th>
            </tr>
            </thead>
            <tbody>
            <?php if(!empty($logs)): foreach($logs as $log): ?>
                <tr>
                    <td class="date-col">
                        <?php echo date('d/m/Y H:i:s', strtotime($log['date_log'])); ?>
                    </td>
                    <td class="msg-col">
                        <?php echo ($log['message']); ?>
                    </td>
                </tr>
            <?php endforeach; else: ?>
                <tr><td colspan="2" style="text-align:center;">Aucun log enregistré.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Succès - EcoMobil</title>
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
            max-width: 500px;
            width: 90%;
        }

        .success-message {
            color: #27ae60;
            background: #eafaf1;
            padding: 15px 25px;
            border-radius: 15px;
            font-weight: 600;
            font-size: 1.1em;
            margin-bottom: 30px;
            box-shadow: inset 3px 3px 7px rgba(0,0,0,0.1), inset -3px -3px 7px #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
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
        ✅ Réservation créée avec succès !
    </div>
    <a href="/AP_SIO2_EcoMobil_1er_Semestre/index.php" class="btn-retour">Retour à l'accueil</a>
</div>

</body>
</html>
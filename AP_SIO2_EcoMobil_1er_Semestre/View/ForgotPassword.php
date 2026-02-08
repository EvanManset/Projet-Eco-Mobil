<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation - EcoMobil</title>

    <script src="/AP_SIO2_EcoMobil_1er_Semestre/js/showpass.js" defer></script>

    <link rel="icon" href="assets/Eco-Mobil.png" type="image/png">
    <link rel="apple-touch-icon" href="assets/Eco-Mobil.png">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        /* Mêmes variables que Login.php */
        :root {
            --bg-color: #e0e0e0;
            --light-shadow: #ffffff;
            --dark-shadow: #a3b1c6;
            --main-text-color: #333;
            --accent-color: #71b852;
            --accent-hover: #5fa73d;
            --accent-active: #4c8830;
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
            color: var(--main-text-color);
            overflow: auto;
            padding: 20px 0;
        }

        .error-message-standalone {
            color: #c0392b;
            background: #ffe6e6;
            padding: 12px 25px;
            border-radius: 15px;
            margin-bottom: 20px;
            font-weight: 500;
            font-size: 1em;
            text-align: center;
            box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.1),
            -5px -5px 10px var(--light-shadow);
            max-width: 400px;
            width: 80%;
        }

        .login-container {
            background: var(--bg-color);
            padding: 40px;
            border-radius: 30px;
            box-shadow: 10px 10px 20px var(--dark-shadow),
            -10px -10px 20px var(--light-shadow);
            text-align: center;
            width: 400px;
            max-width: 90%;
            transition: transform 0.3s ease;
        }

        .login-container h2 {
            font-size: 2em;
            margin-bottom: 20px;
            color: var(--main-text-color);
            text-shadow: 1px 1px 2px var(--light-shadow);
            font-weight: 700;
        }

        .subtitle {
            margin-bottom: 30px;
            font-size: 0.9em;
            color: #666;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--main-text-color);
        }

        .password-wrapper {
            position: relative;
            width: 100%;
        }

        .form-group input {
            width: calc(100% - 40px);
            padding: 15px 20px;
            border: none;
            outline: none;
            border-radius: 15px;
            background: var(--bg-color);
            font-size: 1em;
            color: var(--main-text-color);
            box-shadow: inset 5px 5px 10px var(--dark-shadow),
            inset -5px -5px 10px var(--light-shadow);
            transition: box-shadow 0.3s ease;
        }

        .password-wrapper input {
            padding-right: 90px;
            width: calc(100% - 110px);
        }

        .form-group input:focus {
            box-shadow: inset 3px 3px 7px var(--dark-shadow),
            inset -3px -3px 7px var(--light-shadow),
            0 0 0 3px var(--accent-color);
        }

        .toggle-btn {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: var(--bg-color);
            border: none;
            border-radius: 12px;
            padding: 8px 12px;
            font-size: 0.85em;
            font-weight: 600;
            color: var(--accent-color);
            cursor: pointer;
            box-shadow: 3px 3px 6px var(--dark-shadow),
            -3px -3px 6px var(--light-shadow);
            transition: all 0.2s ease;
        }

        .toggle-btn:hover {
            color: var(--accent-hover);
            transform: translateY(-50%) scale(1.02);
        }

        .toggle-btn:active {
            box-shadow: inset 2px 2px 5px var(--dark-shadow),
            inset -2px -2px 5px var(--light-shadow);
            transform: translateY(-50%) scale(0.98);
        }

        .login-button {
            width: 100%;
            padding: 18px;
            border: none;
            border-radius: 25px;
            background: var(--accent-color);
            color: white;
            font-size: 1.2em;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 7px 7px 15px var(--dark-shadow),
            -7px -7px 15px var(--light-shadow);
            transition: all 0.3s ease;
            text-transform: uppercase;
            margin-top: 10px;
        }

        .login-button:hover {
            background: var(--accent-hover);
            transform: translateY(-3px);
        }

        .links {
            margin-top: 25px;
            font-size: 0.9em;
        }

        .links a {
            color: var(--accent-color);
            text-decoration: none;
            margin: 0 10px;
        }
        .links a:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Réinitialisation</h2>
    <p class="subtitle">Entrez votre email et définissez votre nouveau mot de passe.</p>

    <form action="index.php?action=ChangePassword" method="POST">

        <div class="form-group">
            <label for="Mail">Votre E-mail</label>
            <input type="email" id="Mail" name="Mail" placeholder="exemple@domaine.com" required>
        </div>

        <div class="form-group">
            <label for="password">Nouveau mot de passe</label>
            <div class="password-wrapper">
                <input type="password" id="password" name="NewPassword" placeholder="Minimum 8 caractères" required>
                <button type="button" id="togglePassword" class="toggle-btn">Afficher</button>
            </div>
        </div>

        <!-- Confirmation -->
        <div class="form-group">
            <label for="ConfirmPassword">Confirmer le mot de passe</label>
            <input type="password" id="ConfirmPassword" name="ConfirmPassword" placeholder="Répétez le mot de passe" required>
        </div>

        <button type="submit" class="login-button">Valider</button>
    </form>

    <div class="links">
        <a href="index.php?action=loginpsession">Annuler et se connecter</a>
    </div>
</div>

</body>
</html>
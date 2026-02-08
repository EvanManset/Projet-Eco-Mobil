<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - EcoMobil</title>

    <script src="/AP_SIO2_EcoMobil_1er_Semestre/js/showpass.js" defer></script>

    <link rel="icon" href="assets/Eco-Mobil.png" type="image/png">
    <link rel="apple-touch-icon" href="assets/Eco-Mobil.png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        /* Base Colors & Variables for Neomorphism */
        :root {
            --bg-color: #e0e0e0;
            --light-shadow: #ffffff;
            --dark-shadow: #a3b1c6;
            --main-text-color: #333;
            --accent-color: #71b852;
            --accent-hover: #5fa73d;
            --accent-active: #4c8830;
        }

        /* Global Styles */
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

        /* Background Animation */
        @keyframes backgroundAnimation {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Signup Container */
        .signup-container {
            background: var(--bg-color);
            padding: 40px;
            border-radius: 30px;
            box-shadow: 10px 10px 20px var(--dark-shadow),
            -10px -10px 20px var(--light-shadow);
            text-align: center;
            width: 450px;
            max-width: 90%;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            z-index: 1;
        }

        .signup-container:hover {
            transform: translateY(-5px);
            box-shadow: 15px 15px 30px var(--dark-shadow),
            -15px -15px 30px var(--light-shadow);
        }

        .signup-container h2 {
            font-family: 'Poppins', sans-serif;
            font-size: 2.4em;
            margin-bottom: 30px;
            color: #222;
            /* CORRECTION ICI : 700 au lieu de 800 pour réduire l'effet "trop gras" */
            font-weight: 700;
            text-shadow: none;
            letter-spacing: -0.5px;
        }

        /* Form Group */
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 700;
            color: #444;
            font-size: 0.95em;
        }

        /* --- Style du Wrapper pour le mot de passe --- */
        .password-wrapper {
            position: relative;
            width: 100%;
        }

        /* Input Fields */
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
            transition: box-shadow 0.3s ease, transform 0.2s ease;
            font-weight: 400;
        }

        /* Ajustement spécifique pour l'input mot de passe */
        .password-wrapper input {
            padding-right: 90px;
            width: calc(100% - 110px);
        }

        .form-group input::placeholder {
            color: rgba(51, 51, 51, 0.6);
        }

        .form-group input:focus {
            box-shadow: inset 3px 3px 7px var(--dark-shadow),
            inset -3px -3px 7px var(--light-shadow),
            0 0 0 3px var(--accent-color);
            transform: scale(1.01);
        }

        /* --- Nouveau bouton Toggle Neumorphique --- */
        .toggle-btn {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: var(--bg-color);
            border: none;
            border-radius: 12px;
            padding: 6px 12px;
            font-size: 0.85em;
            font-weight: 600;
            color: var(--accent-color);
            cursor: pointer;
            box-shadow: 2px 2px 5px var(--dark-shadow),
            -2px -2px 5px var(--light-shadow);
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

        /* Password Requirements */
        .password-requirements {
            text-align: left;
            margin: 25px 0;
            padding: 20px;
            background: var(--bg-color);
            border-radius: 18px;
            box-shadow: 4px 4px 10px var(--dark-shadow),
            -4px -4px 10px var(--light-shadow);
        }

        .password-requirements p {
            margin: 0 0 12px 0;
            font-weight: 700;
            font-size: 0.95em;
            color: var(--accent-active);
        }

        .password-requirements ul {
            margin: 0;
            padding-left: 20px;
            font-size: 0.88em;
            line-height: 1.6;
            list-style-type: '🌿 ';
        }

        .password-requirements li {
            margin-bottom: 5px;
        }

        /* Signup Button */
        .signup-button {
            width: 100%;
            padding: 18px;
            border: none;
            outline: none;
            border-radius: 25px;
            background: var(--accent-color);
            color: white;
            font-size: 1.1em;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 7px 7px 15px var(--dark-shadow),
            -7px -7px 15px var(--light-shadow);
            transition: all 0.3s ease;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .signup-button:hover {
            /* Cela utilisera maintenant la bonne couleur verte au lieu du blanc */
            background: var(--accent-hover);
            box-shadow: 10px 10px 20px var(--dark-shadow),
            -10px -10px 20px var(--light-shadow);
            transform: translateY(-3px);
        }

        .signup-button:active {
            background: var(--accent-active);
            box-shadow: inset 3px 3px 7px var(--dark-shadow),
            inset -3px -3px 7px var(--light-shadow);
            transform: translateY(1px);
        }

        /* Links */
        .links {
            margin-top: 25px;
            font-size: 0.9em;
            font-weight: 400;
        }

        .links a {
            color: var(--accent-color);
            text-decoration: none;
            margin: 0 10px;
            transition: color 0.3s ease, text-decoration 0.3s ease;
        }

        .links a:hover {
            color: var(--accent-active);
            text-decoration: underline;
        }

        /* Two columns for form fields */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        @media (max-width: 600px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<?php if (isset($error_msg)): ?>
    <div class="error-message-standalone">
        <span><?php echo $error_msg; ?></span>
    </div>
<?php endif; ?>

<div class="signup-container">
    <h2>Inscription Eco-Mobil</h2>
    <form action="/AP_SIO2_EcoMobil_1er_Semestre/index.php?action=signupsession" method="POST">

        <div class="form-row">
            <div class="form-group">
                <label for="Prenom">Prénom</label>
                <input type="text" id="Prenom" name="Prenom" placeholder="John"
                       value="<?php echo isset($_POST['Prenom']) ? ($_POST['Prenom']) : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="Nom">Nom</label>
                <input type="text" id="Nom" name="Nom" placeholder="Doe"
                       value="<?php echo isset($_POST['Nom']) ? ($_POST['Nom']) : ''; ?>" required>
            </div>
        </div>

        <div class="form-group">
            <label for="Telephone">Téléphone</label>
            <input type="tel" id="Telephone" name="Telephone" placeholder="07...."
                   value="<?php echo isset($_POST['Telephone']) ? ($_POST['Telephone']) : ''; ?>" required>
        </div>

        <div class="form-group">
            <label for="Adresse">Adresse</label>
            <input type="text" id="Adresse" name="Adresse" placeholder="12 rue des Érables"
                   value="<?php echo isset($_POST['Adresse']) ? ($_POST['Adresse']) : ''; ?>" required>
        </div>

        <div class="form-group">
            <label for="Mail">E-mail</label>
            <input type="email" id="Mail" name="Mail" placeholder="exemple@domaine.com"
                   value="<?php echo isset($_POST['Mail']) ? ($_POST['Mail']) : ''; ?>" required>
        </div>

        <div class="form-group">
            <label for="password">Mot de passe</label>
            <div class="password-wrapper">
                <input type="password" id="password" name="Mot_de_Passe_Securiser" placeholder="Mot de passe sécurisé" required>
                <button type="button" id="togglePassword" class="toggle-btn">Afficher</button>
            </div>
        </div>

        <div class="password-requirements">
            <p>Votre mot de passe doit contenir :</p>
            <ul>
                <li>Au moins 8 caractères</li>
                <li>Au moins une majuscule</li>
                <li>Au moins une minuscule</li>
                <li>Au moins un chiffre</li>
                <li>Au moins un caractère spécial (ex. !@#€%)</li>
                <li>Pas de mots de passes courants</li>
                <li>Pas d'espaces</li>
            </ul>
        </div>

        <button type="submit" class="signup-button">S'inscrire</button>
    </form>
    <div class="links">
        <a href="/AP_SIO2_EcoMobil_1er_Semestre/index.php">Retour à l'accueil</a>
        <span>•</span>
        <a href="/AP_SIO2_EcoMobil_1er_Semestre/index.php?action=loginpsession">Se connecter</a>
    </div>
</div>
</body>
</html>
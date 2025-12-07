<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - EcoMobil</title>

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
            --accent-hover: #ffffff;
            --accent-active: #4c8830;
        }

        /* Global Styles */
        body {
            margin: 0;
            display: flex;
            flex-direction: column; /* Permet d'aligner le conteneur au centre */
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
            color: #c0392b; /* Rouge foncé */
            background: #ffe6e6; /* Fond rose très pâle */
            padding: 12px 25px;
            border-radius: 15px;
            margin-bottom: 20px; /* Espace entre le message et le bloc de login */
            font-weight: 500;
            font-size: 1em;
            text-align: center;
            /* Neumorphisme pour le message */
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
            font-size: 2.5em;
            margin-bottom: 30px;
            color: var(--main-text-color);
            text-shadow: 1px 1px 2px var(--light-shadow);
            font-weight: 700;
        }

        /* Form Group (Label + Input) */
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--main-text-color);
            font-size: 0.95em;
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

        .form-group input::placeholder {
            color: rgba(51, 51, 51, 0.6);
        }

        .form-group input:focus {
            box-shadow: inset 3px 3px 7px var(--dark-shadow),
            inset -3px -3px 7px var(--light-shadow),
            0 0 0 3px var(--accent-color);
            transform: scale(1.01);
        }

        /* Password Requirements */
        .password-requirements {
            text-align: left;
            margin: 25px 0;
            padding: 20px;
            background: var(--bg-color);
            border-radius: 18px;
            /* Ombre plus douce */
            box-shadow: 4px 4px 10px var(--dark-shadow),
            -4px -4px 10px var(--light-shadow);
        }

        .password-requirements p {
            margin: 0 0 12px 0;
            font-weight: 700;
            font-size: 0.95em;
            color: var(--accent-active); /* Couleur accentuée pour le titre */
        }

        .password-requirements ul {
            margin: 0;
            padding-left: 20px;
            font-size: 0.88em;
            line-height: 1.6;
            list-style-type: '🌿 '; /* Utilisation d'un caractère simple */
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
            font-size: 1.2em;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 7px 7px 15px var(--dark-shadow),
            -7px -7px 15px var(--light-shadow);
            transition: all 0.3s ease;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .signup-button:hover {
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
            <label for="Mot_de_Passe_Securiser">Mot de passe</label>
            <input type="password" id="Mot_de_Passe_Securiser" name="Mot_de_Passe_Securiser" placeholder="Mot de passe sécurisé" required>
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
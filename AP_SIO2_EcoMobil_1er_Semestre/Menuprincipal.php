<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Accueil Eco-Mobil</title>
    <!-- Assurez-vous d'avoir la meta viewport pour la responsivité -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" href="assets/Eco-Mobil.png" type="image/png">
    <link rel="apple-touch-icon" href="assets/Eco-Mobil.png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
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
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, var(--bg-color) 0%, #cacaca 50%, var(--bg-color) 100%);
            background-size: 400% 400%;
            animation: backgroundAnimation 15s ease infinite alternate;
            color: var(--main-text-color);
            display: flex;
            /* Changement critique pour le centrage vertical */
            justify-content: center;
            align-items: center; /* Centrage vertical du contenu */
            min-height: 100vh; /* S'assure que le body prend toute la hauteur */
            /* Suppression du padding du body pour laisser le centrage opérer */
        }

        @keyframes backgroundAnimation {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .container {
            background: var(--bg-color);
            padding: 40px;
            border-radius: 30px;
            box-shadow: 10px 10px 20px var(--dark-shadow), -10px -10px 20px var(--light-shadow);
            max-width: 900px;
            width: 90%;
            text-align: center;
            /* Permet de ne pas coller les bords du viewport sur les petits écrans */
            margin: 20px;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .header-container h1 {
            font-size: 2.5em;
            text-shadow: 1px 1px 2px var(--light-shadow);
        }

        .logout-btn {
            padding: 12px 25px;
            border-radius: 25px;
            text-decoration: none;
            color: white;
            font-weight: bold;
            background: var(--accent-color);
            box-shadow: 7px 7px 15px var(--dark-shadow), -7px -7px 15px var(--light-shadow);
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: var(--accent-hover);
            transform: translateY(-3px);
            box-shadow: 10px 10px 20px var(--dark-shadow), -10px -10px 20px var(--light-shadow);
        }

        .content-container {
            display: flex;
            gap: 50px;
            /* CHANGEMENT: Centre verticalement l'image par rapport au texte */
            align-items: center;
            flex-wrap: wrap;
        }

        .links-section {
            flex: 1;
            text-align: left;
        }

        .links-section h2 {
            font-size: 1.8em;
            margin-bottom: 20px;
        }

        .links-section a.button {
            display: block;
            margin: 15px 0;
            padding: 18px;
            text-align: center;
            border-radius: 25px;
            background: var(--accent-color);
            color: white;
            font-weight: bold;
            text-decoration: none;
            font-size: 1.1em;
            box-shadow: 7px 7px 15px var(--dark-shadow), -7px -7px 15px var(--light-shadow);
            transition: all 0.3s ease;
        }

        .links-section a.button:hover {
            background: var(--accent-hover);
            transform: translateY(-3px);
            box-shadow: 10px 10px 20px var(--dark-shadow), -10px -10px 20px var(--light-shadow);
        }

        .image-section {
            flex: 0 0 auto;
        }

        .image-section img {
            width: 400px;
            height: auto;
            border-radius: 20px;
            box-shadow: 7px 7px 15px var(--dark-shadow), -7px -7px 15px var(--light-shadow);
        }

        @media(max-width: 800px) {
            .content-container {
                flex-direction: column;
                align-items: center;
            }
            .image-section img {
                width: 100%; /* S'adapte mieux à la largeur mobile */
                max-width: 300px;
            }
        }
    </style>
</head>

<body>

<div class="container">
    <div class="header-container">
        <h1>Accueil Eco-Mobil</h1>
        <?php if (isset($_SESSION['Mail'])): ?>
            <a href="index.php?action=logout" class="logout-btn">Se déconnecter</a>
        <?php endif; ?>
    </div>

    <div class="content-container">
        <div class="links-section">
            <?php if (isset($_SESSION['Mail'])): ?>
                <h2>Bienvenue <?php echo $_SESSION['Prenom'] . ' ' . $_SESSION['Nom']; ?></h2>
                <p>Vous êtes maintenant connecté à votre espace Eco-Mobil.</p>
                <a href="index.php?action=reservationsession" class="button">Nouvelle Réservation</a>
                <a href="index.php?action=mesreservations" class="button">Mes Réservations</a>
            <?php else: ?>
                <a href="index.php?action=signupsession" class="button">S'inscrire</a>
                <a href="index.php?action=loginpsession" class="button">Se connecter</a>
            <?php endif; ?>
        </div>

        <div class="image-section">
            <img src="assets/Eco-Mobil.png" alt="Logo Eco-Mobil">
        </div>
    </div>
</div>

</body>
</html>
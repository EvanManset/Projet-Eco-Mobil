<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eco-Mobil - La mobilité verte</title>

    <link rel="icon" href="assets/Eco-Mobil.png" type="image/png">
    <link rel="apple-touch-icon" href="assets/Eco-Mobil.png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* =========================================
           1. VARIABLES & STYLE GLOBAL
           ========================================= */
        /* :root permet de stocker des variables CSS utilisables partout */
        :root {
            --bg-color: #e0e0e0;       /* Couleur de fond générale (gris clair) */
            --light-shadow: #ffffff;   /* Couleur de l'ombre claire (lumière) */
            --dark-shadow: #a3b1c6;    /* Couleur de l'ombre foncée (ombre portée) */
            --main-text-color: #333;   /* Couleur du texte principal (gris foncé) */
            --accent-color: #71b852;   /* Vert principal de la marque */
            --accent-hover: #5fa73d;   /* Vert plus foncé pour les survols */
            --accent-active: #4c8830;  /* Vert encore plus foncé pour le clic */
            --nav-height: 80px;        /* Hauteur fixe de la barre de navigation */
        }

        /* Styles appliqués à l'élément html */
        html {
            scroll-behavior: smooth; /* Active le défilement fluide vers les ancres */
            /* Ajoute un décalage au scroll pour que le menu fixe ne cache pas les titres */
            scroll-padding-top: calc(var(--nav-height) + 20px);
        }

        /* Réinitialisation : tous les éléments incluent bordures et padding dans leur taille */
        * {
            box-sizing: border-box;
        }

        /* Styles globaux du corps de page */
        body {
            margin: 0; /* Enlève les marges par défaut */
            font-family: 'Poppins', sans-serif; /* Applique la police Poppins */
            background: var(--bg-color); /* Applique la couleur de fond */
            color: var(--main-text-color); /* Applique la couleur de texte */
            overflow-x: hidden; /* Empêche le défilement horizontal */
            padding-top: var(--nav-height); /* Pousse le contenu sous la barre fixe */
        }

        /* Styles par défaut pour tous les liens */
        a { text-decoration: none; color: inherit; transition: 0.3s; }

        /* =========================================
           NAVBAR
           ========================================= */
        /* Conteneur principal de la barre de navigation */
        .navbar {
            position: fixed; /* Fixe la barre en haut de l'écran */
            top: 0; left: 0; /* Colle la barre en haut à gauche */
            width: 100%; /* Prend toute la largeur */
            height: var(--nav-height); /* Hauteur définie en variable */
            background: var(--bg-color); /* Couleur de fond */
            display: flex; /* Utilise Flexbox pour aligner les enfants */
            justify-content: space-between; /* Ecarte Logo (gauche) et Bouton (droite) */
            align-items: center; /* Centre verticalement */
            padding: 0 40px; /* Marges intérieures gauche/droite */
            z-index: 1000; /* Assure que le menu est au-dessus du reste */
            box-shadow: 0 4px 15px rgba(0,0,0,0.05); /* Légère ombre sous le menu */
        }

        /* Conteneur du logo */
        .logo-container {
            display: flex; /* Aligne image et texte */
            align-items: center;
            gap: 12px; /* Espace entre image et texte */
            z-index: 1002;
        }

        /* Image du logo */
        .logo-container img {
            height: 50px; /* Hauteur fixe */
            width: auto; /* Largeur proportionnelle */
        }

        /* Texte "Eco-Mobil" */
        .logo-text {
            font-size: 1.5em; /* Gros texte */
            font-weight: 700; /* Gras */
            color: var(--accent-color); /* Vert */
            text-transform: none;
        }

        /* Menu central (Liens) */
        .nav-links {
            position: absolute; /* Position absolue pour centrage parfait */
            left: 50%; /* Place le début à 50% de l'écran */
            transform: translateX(-50%); /* Recule de 50% de sa propre largeur pour centrer */
            display: flex; /* Aligne les liens horizontalement */
            gap: 30px; /* Espace entre les liens */
            list-style: none; /* Enlève les puces de liste */
            margin: 0; padding: 0; /* Enlève les marges par défaut */
            align-items: center;
        }

        /* Style des liens individuels */
        .nav-links li a {
            font-weight: 600;
            font-size: 1em;
            color: #555; /* Gris moyen */
            position: relative; /* Pour positionner la ligne de soulignement */
        }

        /* Couleur au survol */
        .nav-links li a:hover {
            color: var(--accent-color);
        }

        /* Création de la ligne de soulignement animée */
        .nav-links li a::after {
            content: '';
            position: absolute;
            width: 0; /* Invisible au début */
            height: 2px;
            bottom: -5px;
            left: 0;
            background-color: var(--accent-color);
            transition: width 0.3s ease; /* Animation de largeur */
        }
        /* Au survol, la ligne prend 100% de la largeur */
        .nav-links li a:hover::after {
            width: 100%;
        }

        /* Bouton "Espace Client" ou "Logout" à droite */
        .nav-btn {
            padding: 10px 25px;
            border-radius: 20px;
            background: var(--bg-color);
            color: #555;
            font-weight: 700;
            /* Effet Neumorphisme (relief) */
            box-shadow: 4px 4px 8px var(--dark-shadow), -4px -4px 8px var(--light-shadow);
            z-index: 1002;
        }
        /* Effet au survol du bouton */
        .nav-btn:hover {
            color: var(--accent-hover);
            transform: translateY(-2px); /* Légère levée */
            box-shadow: 6px 6px 12px var(--dark-shadow), -6px -6px 12px var(--light-shadow);
        }

        /* Responsive : Cache le menu texte sur petit écran (<900px) */
        @media (max-width: 900px) {
            .nav-links { display: none; }
            .navbar { justify-content: space-between; }
        }

        /* =========================================
           AUTRES STYLES (Boutons, Sections, Cartes)
           ========================================= */

        /* Bouton standard style "Neumorphisme" */
        .btn-neu {
            display: inline-block;
            padding: 15px 30px;
            border-radius: 50px;
            background: var(--bg-color);
            color: var(--accent-color);
            font-weight: 700;
            box-shadow: 6px 6px 12px var(--dark-shadow), -6px -6px 12px var(--light-shadow);
            transition: all 0.3s ease;
        }
        /* Survol bouton standard */
        .btn-neu:hover {
            color: var(--accent-hover);
            transform: translateY(-2px);
            box-shadow: 10px 10px 20px var(--dark-shadow), -10px -10px 20px var(--light-shadow);
        }
        /* Variation "primaire" (fond vert) */
        .btn-neu.primary {
            background: var(--accent-color);
            color: #fff;
        }
        .btn-neu.primary:hover {
            background: var(--accent-hover);
            color: #fff;
        }

        /* Gros boutons d'action (S'inscrire / Se connecter) */
        .btn-action {
            display: block;
            width: 100%;
            max-width: 400px;
            margin: 20px auto;
            padding: 20px 0;
            border-radius: 50px;
            background: var(--accent-color);
            color: white;
            font-size: 1.3em;
            font-weight: 700;
            text-align: center;
            box-shadow: 10px 10px 20px var(--dark-shadow), -10px -10px 20px var(--light-shadow);
            transition: all 0.3s ease;
        }
        .btn-action:hover {
            background: var(--accent-hover);
            transform: translateY(-3px);
            box-shadow: 15px 15px 30px var(--dark-shadow), -15px -15px 30px var(--light-shadow);
        }

        /* Bouton de retour en haut de page */
        #back-to-top {
            position: fixed;
            bottom: 30px; right: 30px; /* En bas à droite */
            width: 50px; height: 50px;
            border-radius: 50%; /* Rond */
            background: var(--accent-color);
            color: white;
            display: flex; justify-content: center; align-items: center;
            font-size: 1.8em;
            text-decoration: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            transition: all 0.3s ease;
            z-index: 9999;
        }
        #back-to-top:hover {
            background: var(--accent-hover);
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.4);
        }

        /* Style des sections */
        section {
            padding: 80px 20px;
            max-width: 1200px;
            margin: 0 auto;
            text-align: center;
        }

        /* Style des titres H2 */
        h2 {
            font-size: 2.5em;
            margin-bottom: 40px;
            color: var(--main-text-color);
            text-shadow: 1px 1px 2px var(--light-shadow);
        }

        /* Style des paragraphes */
        p {
            line-height: 1.6;
            color: #555;
            font-size: 1.1em;
            max-width: 800px;
            margin: 0 auto 30px auto;
        }

        /* HERO SECTION (Bannière d'accueil) */
        .hero {
            min-height: 90vh; /* Hauteur quasi pleine page */
            display: flex; flex-direction: column; justify-content: center; align-items: center;
            /* Dégradé animé */
            background: linear-gradient(135deg, var(--bg-color) 0%, #cacaca 50%, var(--bg-color) 100%);
            background-size: 400% 400%;
            animation: backgroundAnimation 15s ease infinite alternate;
        }
        /* Animation du fond */
        @keyframes backgroundAnimation {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Titre du Hero */
        .hero h1 {
            font-size: 4em;
            margin-bottom: 20px;
            color: var(--accent-color);
            text-shadow: 3px 3px 6px var(--dark-shadow), -3px -3px 6px var(--light-shadow);
        }
        .hero p {
            font-size: 1.5em;
            margin-bottom: 50px;
        }

        /* AVANTAGES (Grille) */
        .benefits-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); /* Colonnes auto */
            gap: 30px;
        }
        /* Carte Avantage */
        .card-neu {
            background: var(--bg-color);
            padding: 30px;
            border-radius: 30px;
            box-shadow: 10px 10px 20px var(--dark-shadow), -10px -10px 20px var(--light-shadow);
            transition: transform 0.3s ease;
        }
        .card-neu:hover { transform: translateY(-5px); }
        .card-neu i {
            font-size: 3em;
            color: var(--accent-color);
            margin-bottom: 20px;
        }
        .card-neu h3 { margin-bottom: 15px; font-weight: 600; }

        /* COMMENT CA MARCHE (Étapes) */
        .steps {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 50px;
        }
        .step { flex: 1; min-width: 250px; }
        /* Cercle numéroté */
        .step-number {
            display: inline-block;
            width: 60px; height: 60px;
            line-height: 60px; /* Centrage vertical */
            border-radius: 50%;
            background: var(--bg-color);
            color: var(--accent-color);
            font-size: 1.5em;
            font-weight: bold;
            box-shadow: inset 5px 5px 10px var(--dark-shadow), inset -5px -5px 10px var(--light-shadow); /* Ombre interne */
            margin-bottom: 20px;
        }

        /* LISTES (Badges / Pills) */
        .pill-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
        }
        .pill {
            padding: 10px 25px;
            border-radius: 20px;
            background: var(--bg-color);
            font-weight: 600;
            box-shadow: 5px 5px 10px var(--dark-shadow), -5px -5px 10px var(--light-shadow);
        }

        /* DASHBOARD & BOUTONS ASSOCIÉS */
        .dashboard-container {
            min-height: 80vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px;
            gap: 50px;
            flex-wrap: wrap;
        }

        .dashboard-text { max-width: 500px; }
        .dashboard-text h1 { font-size: 2.5em; margin-bottom: 10px; }
        .dashboard-text p { font-size: 1.1em; margin-bottom: 40px; color: #666; }

        .dashboard-btn {
            display: block;
            width: 100%;
            padding: 20px;
            margin-bottom: 20px;
            text-align: center;
            background: var(--accent-color);
            color: white;
            font-size: 1.2em;
            font-weight: 700;
            border-radius: 50px;
            box-shadow: 8px 8px 16px var(--dark-shadow), -8px -8px 16px var(--light-shadow);
            transition: transform 0.2s ease, background 0.2s ease;
        }
        .dashboard-btn:hover {
            background: var(--accent-hover);
            transform: translateY(-4px);
        }

        .dashboard-card {
            background: var(--bg-color);
            padding: 20px;
            border-radius: 40px;
            box-shadow: 20px 20px 60px var(--dark-shadow), -20px -20px 60px var(--light-shadow);
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .dashboard-card img { max-width: 100%; width: 400px; border-radius: 20px; }

        /* PIED DE PAGE */
        footer {
            padding: 50px 20px;
            background: var(--bg-color);
            text-align: center;
            box-shadow: 0 -10px 20px -10px rgba(0,0,0,0.1);
        }
    </style>
</head>

<body id="top">

<nav class="navbar">

    <div class="logo-container">
        <img src="assets/Eco-Mobil.png" alt="Logo">
        <span class="logo-text">Eco-Mobil</span>
    </div>

    <ul class="nav-links">
        <?php if(isset($_SESSION['Mail'])): ?>
            <li><a href="index.php">Tableau de bord</a></li>
        <?php else: ?>
            <li><a href="#top">Accueil</a></li>
            <li><a href="#presentation">Mission</a></li>
            <li><a href="#avantages">Avantages</a></li>
            <li><a href="#how-it-works">Comment</a></li>
            <li><a href="#vehicules">Véhicules</a></li>
            <li><a href="#agences">Agences</a></li>
        <?php endif; ?>
    </ul>

    <?php if(isset($_SESSION['Mail'])): ?>
        <a href="index.php?action=logout" class="nav-btn">Se déconnecter</a>
    <?php else: ?>
        <a href="#auth-section" class="nav-btn">Espace Client</a>
    <?php endif; ?>
</nav>

<?php if (isset($_SESSION['Mail'])): ?>

    <div class="dashboard-container">
        <div class="dashboard-text">
            <h1>Bienvenue <?php echo ($_SESSION['Prenom']) . ' ' . ($_SESSION['Nom']); ?></h1>
            <p>
                Vous êtes maintenant connecté à votre espace Eco-Mobil.<br>
                Prêt pour votre prochaine aventure écologique ?
            </p>

            <a href="index.php?action=reservation_step1" class="dashboard-btn">
                Nouvelle Réservation
            </a>
            <a href="index.php?action=mesreservationsession" class="dashboard-btn">
                Mes Réservations
            </a>
        </div>

        <div class="dashboard-card">
            <img src="assets/Eco-Mobil.png" alt="Eco-Mobil Dashboard">
        </div>
    </div>

<?php else: ?>

    <header class="hero">
        <h1>Eco-Mobil</h1>
        <p>Louez vert, roulez libre. La mobilité de demain, aujourd'hui.</p>
        <a href="#auth-section" class="btn-neu primary">Commencer maintenant</a>
    </header>

    <section id="presentation">
        <h2>Notre Mission</h2>
        <p>
            Eco-Mobil redéfinit la mobilité urbaine en proposant une large gamme de véhicules électriques en libre-service.
            Simple, rapide et 100% électrique.
        </p>
    </section>

    <section id="avantages">
        <h2>Pourquoi nous choisir ?</h2>
        <div class="benefits-grid">
            <div class="card-neu">
                <i class="fa-solid fa-leaf"></i>
                <h3>100% Écologique</h3>
                <p>Réduisez votre empreinte carbone avec notre flotte entièrement électrique.</p>
            </div>
            <div class="card-neu">
                <i class="fa-solid fa-mobile-screen"></i>
                <h3>Simplicité</h3>
                <p>Réservez en quelques clics via notre plateforme intuitive.</p>
            </div>
            <div class="card-neu">
                <i class="fa-solid fa-bolt"></i>
                <h3>Disponibilité</h3>
                <p>Des véhicules prêts à partir dans 9 villes de la région.</p>
            </div>
            <div class="card-neu">
                <i class="fa-solid fa-wallet"></i>
                <h3>Tarifs Flexibles</h3>
                <p>Payez à l'heure, à la demi-journée ou à la journée.</p>
            </div>
        </div>
    </section>

    <section id="how-it-works">
        <h2>Comment ça marche ?</h2>
        <div class="steps">
            <div class="step">
                <span class="step-number">1</span>
                <h3>Inscrivez-vous</h3>
                <p>Créez votre compte en 2 minutes.</p>
            </div>
            <div class="step">
                <span class="step-number">2</span>
                <h3>Réservez</h3>
                <p>Choisissez votre véhicule et votre agence.</p>
            </div>
            <div class="step">
                <span class="step-number">3</span>
                <h3>Roulez</h3>
                <p>Profitez de votre trajet en toute liberté.</p>
            </div>
        </div>
    </section>

    <section id="vehicules">
        <h2>Nos Véhicules</h2>
        <div class="pill-container">
            <div class="pill"><i class="fa-solid fa-bicycle"></i> Vélo électrique urbain</div>
            <div class="pill"><i class="fa-solid fa-person-biking"></i> VTT électrique</div>
            <div class="pill"><i class="fa-solid fa-scooter"></i> Trottinette électrique</div>
            <div class="pill"><i class="fa-solid fa-biking"></i> Gyropode / Segway</div>
            <div class="pill"><i class="fa-solid fa-snowboarding"></i> Hoverboard</div>
            <div class="pill"><i class="fa-solid fa-bolt"></i> Skateboard électrique</div>
        </div>
    </section>

    <section id="agences">
        <h2>Nos Agences</h2>
        <div class="pill-container">
            <div class="pill">📍 Annecy</div>
            <div class="pill">📍 Grenoble</div>
            <div class="pill">📍 Chambéry</div>
            <div class="pill">📍 Valence</div>
            <div class="pill">📍 Meylan</div>
            <div class="pill">📍 Lyon (Bellecour)</div>
            <div class="pill">📍 Bron</div>
            <div class="pill">📍 Saint-Etienne</div>
            <div class="pill">📍 Bourg-en-Bresse</div>
        </div>
    </section>

    <section id="auth-section" style="background-color: rgba(255,255,255,0.05);">
        <h2>Rejoignez l'aventure</h2>
        <p>Connectez-vous ou créez un compte pour réserver dès maintenant.</p>

        <div style="margin-top: 40px;">
            <a href="index.php?action=signupsession" class="btn-action">S'inscrire</a>
            <a href="index.php?action=loginpsession" class="btn-action">Se connecter</a>
        </div>
    </section>

<?php endif; ?>

<footer>
    <p>&copy; 2025 Eco-Mobil. Tous droits réservés.</p>
    <a href="#" style="color:var(--accent-color);">Mentions légales</a>
</footer>

<a href="#top" id="back-to-top" title="Retour en haut">
    <i class="fa-solid fa-arrow-up"></i>
</a>

</body>
</html>
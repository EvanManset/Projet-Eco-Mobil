<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Login - EcoMobil</title>
</head>
<body>

<p><a href="/AP_SIO2_EcoMobil_1er_Semestre/index.php">Retour à l'index</a></p>
<h2>Login Eco-Mobil</h2>

<div style="display: flex; align-items: flex-start; gap: 5px;">

    <div>
        <form action="/AP_SIO2_EcoMobil_1er_Semestre/index.php?action=loginpsession" method="POST">

            <table>
                <tr>
                    <td><label for="Mail">E-mail :</label></td>
                    <td><input type="Mail" id="Mail" name="Mail" placeholder="exemple@domaine.com" required /></td>
                </tr>
                <tr>
                    <td><label for="Mot_de_Passe_Securiser">Mot de passe :</label></td>
                    <td><input type="password" id="Mot_de_Passe_Securiser" name="Mot_de_Passe_Securiser" required /></td>
                </tr>
            </table>

            <input type="submit" value="Log in">
        </form>
    </div>

    <div style="display:flex; justify-content:flex-end;">
        <img src="View/Eco-Mobil.png" alt="Logo Eco-Mobil" style="width:400px;">
    </div>


</div>

</body>
</html>
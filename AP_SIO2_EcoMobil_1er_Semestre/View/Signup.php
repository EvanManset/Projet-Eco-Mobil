<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Sign up - EcoMobil</title>
</head>
<body>

<p><a href="/AP_SIO2_EcoMobil_1er_Semestre/index.php">Retour à l'index</a></p>
<h2>Sign Up Eco-Mobil</h2>

<div style="display: flex; align-items: flex-start; gap: 5px;">

    <div>
        <form action="/AP_SIO2_EcoMobil_1er_Semestre/index.php?action=signupsession" method="POST">

            <table>
                <tr>
                    <td><label for="Prenom">Prénom :</label></td>
                    <td><input type="text" id="Prenom" name="Prenom" placeholder="John" required/></td>
                </tr>
                <tr>
                    <td><label for="Nom">Nom :</label></td>
                    <td><input type="text" id="Nom" name="Nom" placeholder="Doe" required/></td>
                </tr>
                <tr>
                    <td><label for="Telephone">Téléphone :</label></td>
                    <td><input type="tel" id="Telephone" name="Telephone" placeholder="07...." required/></td>
                </tr>
                <tr>
                    <td><label for="Adresse">Adresse :</label></td>
                    <td><input type="text" id="Adresse" name="Adresse" placeholder="12 rue des Érables" required/></td>
                </tr>
                <tr>
                    <td><label for="Mail">E-mail :</label></td>
                    <td><input type="Mail" id="Mail" name="Mail" placeholder="exemple@domaine.com" required /></td>
                </tr>
                <tr>
                    <td><label for="Mot_de_Passe_Securiser">Mot de passe :</label></td>
                    <td><input type="password" id="Mot_de_Passe_Securiser" name="Mot_de_Passe_Securiser" required /></td>
                </tr>
            </table>


            <p>Votre mot de passe doit contenir :</p>
            <ul>
                <li>Au moins 8 caractères</li>
                <li>Au moins une majuscule</li>
                <li>Au moins une minuscule</li>
                <li>Au moins un chiffre</li>
                <li>Au moins un caractère spécial (ex. !@#€%)</li>
            </ul>

            <input type="submit" value="Sign Up">
        </form>
    </div>

    <div style="display:flex; justify-content:flex-end;">
        <img src="View/Eco-Mobil.png" alt="Logo Eco-Mobil" style="width:400px;">
    </div>


</div>

</body>
</html>
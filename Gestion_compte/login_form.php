<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="min-h-screen bg-black flex items-center justify-center">
    <div class="bg-gray-900 p-8 rounded-lg shadow-md w-full max-w-md">
        <h2 class="text-3xl font-bold text-white text-center mb-6">Formulaire de connexion</h2>
        <form action="login.php" method="post">
            <?php
                session_start();
                if(isset($_SESSION["error"]))
                {
                    echo "<p class='text-gray-400 mb-4'>" . $_SESSION["error"] . "</p>";
                }
            ?>
            <div class="mb-4">
                <label for="email" class="block text-white">E-mail :</label>
                <?php
                    if(isset($_SESSION["email"]))
                    {
                        echo "<input type='email' name='email' value='" . $_SESSION["email"] . "' class='w-full p-2 mt-2 bg-gray-800 text-white rounded-md' required>";
                    }
                    else
                    {
                        echo "<input type='email' name='email' class='w-full p-2 mt-2 bg-gray-800 text-white rounded-md' required>";
                    }
                ?>
            </div>
            <div class="mb-4">
                <label for="mdp" class="block text-white">Mot de passe :</label>
                <?php
                    if(isset($_SESSION["mdp"]))
                    {
                        echo "<input type='password' name='mdp' value='" . $_SESSION["mdp"] . "' class='w-full p-2 mt-2 bg-gray-800 text-white rounded-md' required>";
                    }
                    else
                    {
                        echo "<input type='password' name='mdp' class='w-full p-2 mt-2 bg-gray-800 text-white rounded-md' required>";
                    }
                ?>
            </div>
            <div class="flex justify-between items-center">
                <input type="submit" value="Connexion" class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-md cursor-pointer">
                <a href="recuperation.php" class="text-sm text-gray-400 hover:underline">Mot de passe oublié</a>
            </div>
            <div class="mt-6 text-center">
                <a href="inscription.php" class="bg-gray-700 hover:bg-gray-600 text-white py-2 px-4 rounded-md">Créer un compte</a>
            </div>
        </form>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="min-h-screen bg-black flex items-center justify-center">
    <div class="bg-gray-900 p-8 rounded-lg shadow-md w-full max-w-md">
        <h2 class="text-3xl font-bold text-white text-center mb-6">Formulaire d'inscription</h2>
        <form action="insert.php" method="post">
            <div class="mb-4">
                <label for="nom" class="block text-white">Nom :</label>
                <input type="text" id="nom" name="nom" class="w-full p-2 mt-2 bg-gray-800 text-white rounded-md" required>
            </div>
            <div class="mb-4">
                <label for="prenom" class="block text-white">Prénom :</label>
                <input type="text" id="prenom" name="prenom" class="w-full p-2 mt-2 bg-gray-800 text-white rounded-md" required>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-white">Adresse email :</label>
                <input type="email" name="email" class="w-full p-2 mt-2 bg-gray-800 text-white rounded-md" required>
            </div>

            <?php
                if(isset($_GET['erreur']))
                {
                    echo "<p class='text-red-500'>" . $_GET['erreur'] . "</p>";
                }
            ?>

            <div class="mb-4">
                <label for="mdp" class="block text-white">Mot de passe :</label>
                <input type="password" name="mdp" class="w-full p-2 mt-2 bg-gray-800 text-white rounded-md" required>
            </div>
            <div class="mb-4">
                <label for="confirmer_mdp" class="block text-white">Confirmer le mot de passe :</label>
                <input type="password" name="confirmer_mdp" class="w-full p-2 mt-2 bg-gray-800 text-white rounded-md" required>
            </div>
            <div class="flex justify-center">
                <input type="submit" value="S'inscrire" class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-md cursor-pointer">
            </div>
        </form>
    </div>
</body>
</html>

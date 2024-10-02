<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['code'])) {
    $input_code = $_POST['code'];

    if ($input_code == $_SESSION['verification_code']) {
        header("Location: changer_mot_de_passe.php");
        exit(); 
    } else {
        echo "<p class='text-red-500'>Code incorrect.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification du code</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="min-h-screen bg-black flex items-center justify-center">
    <div class="bg-gray-900 p-8 rounded-lg shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold text-white text-center mb-6">Entrez votre code de vérification</h2>
        <form action="" method="post">
            <div class="mb-4">
                <label for="code" class="block text-white">Code de vérification :</label>
                <input type="text" name="code" class="w-full p-2 mt-2 bg-gray-800 text-white rounded-md" required>
            </div>
            <div class="flex justify-center">
                <input type="submit" value="Vérifier" class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-md cursor-pointer">
            </div>
        </form>

        <div class="mt-6 text-center text-gray-400">
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['code'])) {
                $input_code = $_POST['code'];

                if ($input_code == $_SESSION['verification_code']) {
                    header("Location: changer_mot_de_passe.php");
                    exit(); 
                } else {
                    echo "<p class='text-red-500'>Code incorrect.</p>";
                }
            }
            ?>
        </div>
    </div>
</body>
</html>

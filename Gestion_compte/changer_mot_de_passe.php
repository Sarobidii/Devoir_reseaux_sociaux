<?php
session_start();
include 'db_connect.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['new_password']) && isset($_POST['confirm_password'])) {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password == $confirm_password) {
        $email = $_SESSION['email']; 
        $update_sql = "UPDATE compte SET mdp = '$new_password' WHERE email = '$email'";

        if (mysqli_query($conn, $update_sql)) {
            echo "Mot de passe mis à jour avec succès.";
            session_destroy(); 
            header("Location: login_form.php");
            exit(); 
        } else {
            echo "<p class='text-red-500'>Erreur lors de la mise à jour du mot de passe : " . mysqli_error($conn) . "</p>";
        }
    } else {
        echo "<p class='text-red-500'>Les mots de passe ne correspondent pas.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Changer de mot de passe</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="min-h-screen bg-black flex items-center justify-center">
    <div class="bg-gray-900 p-8 rounded-lg shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold text-white text-center mb-6">Changer votre mot de passe</h2>
        <form action="" method="post">
            <div class="mb-4">
                <label for="new_password" class="block text-white">Nouveau mot de passe :</label>
                <input type="password" name="new_password" class="w-full p-2 mt-2 bg-gray-800 text-white rounded-md" required>
            </div>
            <div class="mb-4">
                <label for="confirm_password" class="block text-white">Confirmer le mot de passe :</label>
                <input type="password" name="confirm_password" class="w-full p-2 mt-2 bg-gray-800 text-white rounded-md" required>
            </div>
            <div class="flex justify-center">
                <input type="submit" value="Mettre à jour le mot de passe" class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-md cursor-pointer">
            </div>
        </form>

        <div class="mt-6 text-center text-gray-400">
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['new_password']) && isset($_POST['confirm_password'])) {
                if ($new_password != $confirm_password) {
                    echo "<p class='text-red-500'>Les mots de passe ne correspondent pas.</p>";
                }
            }
            ?>
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Amis</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100">
<?php
session_start();
include 'db_connect.php';

if (isset($_SESSION['id_user'])) {
    $id_user = $_SESSION['id_user'];

    // Requête pour récupérer uniquement les utilisateurs qui sont déjà amis
    $sql = "
        SELECT c.id, c.nom, c.prenom 
        FROM compte c
        JOIN amis a ON (c.id = a.id_compte_amis OR c.id = a.id_compte)
        WHERE (a.id_compte = $id_user OR a.id_compte_amis = $id_user)
        AND c.id != $id_user";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        echo '<div class="w-[500px] mx-auto">'; // Center the list and set the width
        echo '<ul class="w-full">'; // Set width to 100% inside the container
        while ($row = mysqli_fetch_assoc($result)) {
            $id_ami = $row['id'];
            $prenom = htmlspecialchars($row['prenom']);
            $nom = htmlspecialchars($row['nom']);
            
            // Align user names and button on the same line using flexbox
            echo '<li class="flex items-center justify-between mb-2">'; 
            echo '<span class="text-lg">' . $prenom . ' ' . $nom . '</span>'; // Display user's name

            // Afficher le bouton "Amis"
            echo '<button class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition duration-300">Amis</button>';
            echo '</li>';
        }
        echo '</ul>';
        echo '</div>';
    } else {
        echo '<p class="text-center text-gray-500">Aucun ami trouvé.</p>';
    }

} else {
    header("Location: login_form.php");
    exit();
}
?>
</body>
</html>

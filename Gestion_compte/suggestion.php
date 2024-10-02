<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100"> <!-- Center content vertically and horizontally -->
<?php
session_start();
include 'db_connect.php';

if (isset($_SESSION['id_user'])) {
    $id_user = $_SESSION['id_user'];

    // Requête pour récupérer uniquement les utilisateurs qui ne sont pas encore amis
    $sql = "
        SELECT id, nom, prenom 
        FROM compte 
        WHERE id != $id_user 
        AND id NOT IN (
            SELECT id_compte_amis FROM amis WHERE id_compte = $id_user
            UNION 
            SELECT id_compte FROM amis WHERE id_compte_amis = $id_user
        )";
    
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        echo '<div class="w-[500px] mx-auto">'; // Wrapper to center content horizontally and set width
        echo '<ul class="liste_amis w-full">'; // Set width to 100% of the parent div
        while ($row = mysqli_fetch_assoc($result)) {
            $id_ami = $row['id'];
            $prenom = htmlspecialchars($row['prenom']);
            $nom = htmlspecialchars($row['nom']);
            
            // Utilisation de Tailwind pour aligner les éléments sur la même ligne
            echo '<li class="flex items-center justify-between mb-2">'; // Flex container to align items on the same line
            echo '<span class="text-lg">' . $prenom . ' ' . $nom . '</span>'; // Display user name

            // Afficher le bouton pour ajouter en ami
            echo '<form action="ajout_amis.php" method="post" class="ml-4">';
            echo '<input type="hidden" name="id_ami" value="'. $id_ami .'" >';
            echo '<button type="submit" class="flex items-center bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-900 transition duration-300">';
            echo '<span class="material-symbols-outlined mr-2">person_add</span>';
            echo 'Ajouter';
            echo '</button>';
            echo '</form>';
            echo '</li>';
        }
        echo '</ul>';
        echo '</div>';
    } else {
        echo '<p>Aucun utilisateur trouvé.</p>';
    }

} else {
    header("Location: login_form.php");
    exit();
}
?>
</body>
</html>

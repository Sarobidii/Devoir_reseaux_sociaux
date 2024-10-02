<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publication Reactions</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6 flex items-center justify-center min-h-screen">
<div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: login_form.php");
    exit();
}

if (isset($_GET['id_publication'])) {
    $id_publication = mysqli_real_escape_string($conn, $_GET['id_publication']);
    
    $sql = "
        SELECT rp.id_compte, rp.id_reaction, c.nom, c.prenom, r.type_reaction
        FROM reaction_publication rp
        JOIN compte c ON rp.id_compte = c.id
        JOIN reaction r ON rp.id_reaction = r.id
        WHERE rp.id_publication = $id_publication";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        echo '<h2 class="text-lg font-semibold mb-4">Reactions for Publication</h2>';
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<div class="flex items-center justify-between bg-gray-50 p-3 mb-2 rounded-md">';
            echo '<span class="text-gray-700 font-medium">' . htmlspecialchars($row['prenom']) . ' ' . htmlspecialchars($row['nom']) . '</span>';
            echo '<span class="text-sm text-gray-500"><span class="font-semibold">' . htmlspecialchars($row['type_reaction']) . '</span></span>';
            echo '</div>';
        }
    } else {
        echo '<p class="text-gray-600 text-center">No reactions found for this publication.</p>';
    }
} else {
    echo '<p class="text-red-600 text-center">Invalid request. Publication ID not provided.</p>';
}
?>
</div>
</body>
</html>

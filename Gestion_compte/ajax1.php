<?php
session_start();
include 'db_connect.php';

if (isset($_SESSION['id_user'])) {
    $id_publication = isset($_GET['id_publication']) ? intval($_GET['id_publication']) : 0;
    $type = isset($_GET['type']) && in_array($_GET['type'], ['publication', 'commentaire', 'reponse_commentaire']) ? $_GET['type'] : '';

    if ($type && $id_publication > 0) {
        // Préparer la requête pour compter les réactions
        $sqlCount = "SELECT COUNT(*) AS total_reactions FROM reaction_$type WHERE id_$type = ?";
        $stmtCount = mysqli_prepare($conn, $sqlCount);
        mysqli_stmt_bind_param($stmtCount, "i", $id_publication);
        mysqli_stmt_execute($stmtCount);
        $resultCount = mysqli_stmt_get_result($stmtCount);
        $rowCount = mysqli_fetch_assoc($resultCount);
        $nombre_reactions = $rowCount['total_reactions'];

        // Préparer la requête pour obtenir les types de réactions distincts
        $sqlReactions = "SELECT DISTINCT r.type_reaction FROM reaction_$type rp
            JOIN reaction r ON rp.id_reaction = r.id
            WHERE rp.id_$type = ?";
        $stmtReactions = mysqli_prepare($conn, $sqlReactions);
        mysqli_stmt_bind_param($stmtReactions, "i", $id_publication);
        mysqli_stmt_execute($stmtReactions);
        $resultReactions = mysqli_stmt_get_result($stmtReactions);

        // Afficher les types de réactions distinctes
        while ($row = mysqli_fetch_assoc($resultReactions)) {
            echo htmlspecialchars($row['type_reaction']);
        }


        // Afficher le nombre total de réactions si elles existent
        if ($nombre_reactions > 0) {
            echo $nombre_reactions;
        } 
    } else {
        echo 'Paramètre type ou publication invalide';
    }
} else {
    echo 'Utilisateur non connecté';
}

// Fermer la connexion
mysqli_close($conn);
?>

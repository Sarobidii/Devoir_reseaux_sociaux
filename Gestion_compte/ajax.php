<?php
session_start();
include 'db_connect.php';

if (isset($_SESSION['id_user'])) {
    $id_user = $_SESSION['id_user'];
    $id_publication = isset($_GET['id_publication']) ? intval($_GET['id_publication']) : 0;
    $id_reaction = isset($_GET['id_reaction']) ? intval($_GET['id_reaction']) : 0;
    $type = isset($_GET['type']) && in_array($_GET['type'], ['publication', 'commentaire', 'reponse_commentaire']) ? $_GET['type'] : '';

    if ($type) {
        // Vérifier si l'utilisateur a déjà réagi à la publication/commentaire
        $sqlUserReaction = "SELECT COUNT(*) AS reaction_exists FROM reaction_$type
                            WHERE id_$type = ? AND id_compte = ?";
        
        $stmt = mysqli_prepare($conn, $sqlUserReaction);
        mysqli_stmt_bind_param($stmt, "ii", $id_publication, $id_user);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);

        if ($row['reaction_exists'] > 0) {
            // Si l'utilisateur a déjà réagi, mettre à jour la réaction
            $sqlUpdateReaction = "UPDATE reaction_$type SET id_reaction = ? 
                                  WHERE id_$type = ? AND id_compte = ?";
            $stmt = mysqli_prepare($conn, $sqlUpdateReaction);
            mysqli_stmt_bind_param($stmt, "iii", $id_reaction, $id_publication, $id_user);
            mysqli_stmt_execute($stmt);
        } else {
            // Sinon, insérer une nouvelle réaction
            $sqlInsertReaction = "INSERT INTO reaction_$type (id_$type, id_compte, id_reaction) 
                                  VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sqlInsertReaction);
            mysqli_stmt_bind_param($stmt, "iii", $id_publication, $id_user, $id_reaction);
            mysqli_stmt_execute($stmt);
        }

        // Récupérer le type de réaction pour l'affichage
        $sqlReactionType = "SELECT r.type_reaction, r.nom_reaction, r.couleur_reaction FROM reaction r 
                            JOIN reaction_$type rp ON r.id = rp.id_reaction 
                            WHERE rp.id_$type = ? AND rp.id_compte = ?";
        
        $stmt = mysqli_prepare($conn, $sqlReactionType);
        mysqli_stmt_bind_param($stmt, "ii", $id_publication, $id_user);
        mysqli_stmt_execute($stmt);
        $resultReaction = mysqli_stmt_get_result($stmt);
        $reactionData = mysqli_fetch_assoc($resultReaction);

        if ($reactionData) {
            echo htmlspecialchars($reactionData['type_reaction']);
            echo '<span style="color: ' . htmlspecialchars($reactionData["couleur_reaction"]) . ';"> ';
            echo htmlspecialchars($reactionData['nom_reaction']);
            echo '</span> ';
        } else {
            echo 'Aucune réaction';
        }
    } else {
        echo 'Type de réaction invalide';
    }
} else {
    echo 'Utilisateur non connecté';
}

// Fermer la connexion
mysqli_close($conn);
?>

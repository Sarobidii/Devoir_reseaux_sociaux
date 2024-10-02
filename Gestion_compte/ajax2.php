<?php
session_start();
include 'db_connect.php';

if (isset($_SESSION['id_user'])) {
    $id_user = $_SESSION['id_user'];
    $id_publication = isset($_GET['id_publication']) ? intval($_GET['id_publication']) : 0;
    $type = isset($_GET['type']) && in_array($_GET['type'], ['publication', 'commentaire', 'reponse_commentaire']) ? $_GET['type'] : '';

    if ($type && $id_publication > 0) {
        $sql = "DELETE FROM reaction_$type WHERE id_$type = $id_publication AND id_compte = $id_user";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            if (mysqli_affected_rows($conn) > 0) {
                if($type == "commentaire" || $type == "reponse_commentaire")
                {
                    echo "J'aime";
                } else {
                    echo "👍 J'aime";
                }
            } else {
                $sql_insert = "INSERT INTO reaction_$type (id_$type, id_compte, id_reaction) 
                               VALUES ($id_publication, $id_user, 1)";
                $result_insert = mysqli_query($conn, $sql_insert);
                if ($result_insert) {
                echo '👍';
                echo '<span style="color: #ffdb5e;"> ';
                echo "J'aime'";
                echo '</span> ';
                }
            }
        } else {
            echo '<p>Erreur lors de la suppression de la réaction : ' . mysqli_error($conn) . '</p>';
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

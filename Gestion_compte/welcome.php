<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="index.php">
    <link rel="stylesheet" href="file.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<body>
    <nav class="flex justify-center p-4">
        <div class="flex flex-col sm:flex-row sm:space-x-4 space-y-2 sm:space-y-0 justify-center items-center">
            <button type="button" class="w-full sm:w-auto inline-block rounded bg-neutral-800 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-neutral-50 shadow-md transition duration-150 ease-in-out hover:bg-neutral-700 focus:bg-neutral-700 focus:outline-none active:bg-neutral-900">
                <a href="publication.php">Créer une publication</a>
            </button>
            <button type="button" class="w-full sm:w-auto inline-block rounded bg-neutral-800 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-neutral-50 shadow-md transition duration-150 ease-in-out hover:bg-neutral-700 focus:bg-neutral-700 focus:outline-none active:bg-neutral-900">
                <a href="suggestion.php">Voir les suggestions d'amis</a>
            </button>
            <button type="button" class="w-full sm:w-auto inline-block rounded bg-neutral-800 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-neutral-50 shadow-md transition duration-150 ease-in-out hover:bg-neutral-700 focus:bg-neutral-700 focus:outline-none active:bg-neutral-900">
                <a href="liste_amis.php">Voir la liste d'amis</a>
            </button>
            <button type="button" class="w-full sm:w-auto inline-block rounded bg-neutral-800 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-neutral-50 shadow-md transition duration-150 ease-in-out hover:bg-neutral-700 focus:bg-neutral-700 focus:outline-none active:bg-neutral-900">
                <a class="logout flex items-center justify-center" href="logout.php">
                    <span class="material-symbols-outlined">power_settings_new</span> Se déconnecter
                </a>
            </button>
        </div>
    </nav>
    <div class="ensemble_publications flex flex-col items-center">
        <?php
            session_start();
            include 'db_connect.php';

            if (isset($_SESSION['id_user'])) {
                $id_user = $_SESSION['id_user'];
            
                $sql_amis = "SELECT id_compte_amis FROM amis WHERE id_compte = $id_user";
                $result_amis = mysqli_query($conn, $sql_amis);

                $amis_ids = [$id_user]; 
            
                if (mysqli_num_rows($result_amis) > 0) {
                    while ($row_amis = mysqli_fetch_assoc($result_amis)) {
                        $amis_ids[] = $row_amis['id_compte_amis'];
                    }
                }
            
                $sql_amis1 = "SELECT id_compte FROM amis WHERE id_compte_amis = $id_user";
                $result_amis1 = mysqli_query($conn, $sql_amis1);
            
                if (mysqli_num_rows($result_amis1) > 0) {
                    while ($row_amis1 = mysqli_fetch_assoc($result_amis1)) {
                        $amis_ids[] = $row_amis1['id_compte'];
                    }
                }
            
                $amis_ids_str = implode(',', $amis_ids); 
                $sql = "SELECT p.id, p.contenu, p.date_lance, c.nom, c.prenom 
                        FROM publication p 
                        JOIN compte c ON p.id_compte = c.id 
                        WHERE p.id_compte IN ($amis_ids_str) 
                        ORDER BY p.date_lance DESC"; 

                $result = mysqli_query($conn, $sql);
            
                $sql2 = "SELECT id, type_reaction FROM reaction";
                $result2 = mysqli_query($conn, $sql2);
            
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<div class="publication bg-white rounded-lg py-3 mt-2 mx-2 max-w-md w-full shadow-lg">';
                            echo '<div class="nom_prenom">';
                                echo '<div class="h-10 w-10 overflow-hidden rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            class="h-10 w-10 p-2 text-white bg-gray-500 stroke-current"> <!-- Taille réduite ici -->
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                ';
                                echo '<p>' . htmlspecialchars($row['prenom']) . ' ' . htmlspecialchars($row['nom']);
                                    echo '</br><small>' . htmlspecialchars($row['date_lance']) . '</small>';
                                echo '</p>';
                            echo '</div>';
                            echo '<p class="contenu">' . htmlspecialchars($row['contenu']) . '</p>';
                        
                            $id_publication = $row['id'];
                            $sqlCount = "SELECT COUNT(*) AS total_reactions FROM reaction_publication WHERE id_publication = $id_publication";
                            $resultCount = mysqli_query($conn, $sqlCount);
                            $rowCount = mysqli_fetch_assoc($resultCount);
                            $nombre_reactions = $rowCount['total_reactions'];
                        
                            $sqlCount1 = "SELECT COUNT(*) AS total_commentaires FROM commentaire WHERE id_publication = $id_publication";
                            $resultCount1 = mysqli_query($conn, $sqlCount1);
                            $rowCount1 = mysqli_fetch_assoc($resultCount1);
                            $nombre_commentaires = $rowCount1['total_commentaires'];
                            echo '<div class="nombre_reactions_commentaires">';
                                echo '<div class="nombre_reactions_' . htmlspecialchars($id_publication) . '">';
                                $sqlReactions = "SELECT DISTINCT r.type_reaction FROM reaction_publication rp
                                    JOIN reaction r ON rp.id_reaction = r.id
                                    WHERE rp.id_publication = ?";
                                $stmtReactions = mysqli_prepare($conn, $sqlReactions);
                                mysqli_stmt_bind_param($stmtReactions, "i", $id_publication);
                                mysqli_stmt_execute($stmtReactions);
                                $resultReactions = mysqli_stmt_get_result($stmtReactions);
                        
                                while ($row = mysqli_fetch_assoc($resultReactions)) {
                                    echo htmlspecialchars($row['type_reaction']);
                                }
                                if($nombre_reactions != 0)
                                {
                                    echo $nombre_reactions;
                                } 
                                echo '</div>';
                                if ($nombre_commentaires > 0) {
                                    echo '<form class ="voir_commentaire" action="afficher_commentaires.php" method="get">';
                                    echo '<input type="hidden" name="id_publication" value="' . htmlspecialchars($id_publication) . '">';
                                    echo '<input type="submit" value="' . $nombre_commentaires . ' commentaire(s)' .'">';
                                    echo '</form>';
                                } 
                            echo '</div>';
                            echo '<div class="buttons">';
                                echo '<div class="reaction-container_' . htmlspecialchars($id_publication) . '">';
                                $sqlUserReaction = "SELECT r.type_reaction,r.couleur_reaction, r.nom_reaction FROM reaction_publication rp
                                        JOIN reaction r ON rp.id_reaction = r.id
                                        WHERE rp.id_publication = $id_publication AND rp.id_compte = $id_user";
                                $resultUserReaction = mysqli_query($conn, $sqlUserReaction);
                                if(mysqli_num_rows($resultUserReaction) > 0) {
                                    $userReaction = mysqli_fetch_assoc($resultUserReaction);
                                    echo '<div onclick="changerContenu(' . $id_publication . ', \'publication\'); nombreReaction(' . $id_publication . ', \'publication\');" class="like-button_' . htmlspecialchars($id_publication) . '">' . $userReaction['type_reaction'];
                                    echo '<span style="color: ' . htmlspecialchars($userReaction["couleur_reaction"]) . ';"> ';
                                    echo $userReaction['nom_reaction'];
                                    echo '</span> ';
                                    echo '</div>';   
                                } else {
                                    echo '<div onclick="changerContenu(' . $id_publication . ', \'publication\'); nombreReaction(' . $id_publication . ', \'publication\');" class="like-button_' . htmlspecialchars($id_publication) . '">👍 J\'aime';
                                    echo '</div>';
                                }
                                echo '<div class="reaction-icons_' . htmlspecialchars($id_publication) . '">';
                                include 'db_connect.php';
                                $sql2 = "SELECT id, type_reaction FROM reaction";
                                $result2 = mysqli_query($conn, $sql2);
                                if (mysqli_num_rows($result2) > 0) {
                                    mysqli_data_seek($result2, 0);
                                    while ($row2 = mysqli_fetch_assoc($result2)) {
                                        echo '<div onclick="contenuReaction(this.getAttribute(\'data-reaction\'),' . $id_publication . ', \'publication\'); nombreReaction(' . $id_publication . ', \'publication\');" class="reaction_' . htmlspecialchars($id_publication) . '" data-reaction="' . htmlspecialchars($row2['id']) . '">' . htmlspecialchars($row2['type_reaction']) . '</div>';
                                    }
                                }
                                echo '</div>';
                                echo '</div>'; 
                                echo '<div class="emoji_commenter"><a href="afficher_commentaires.php?id_publication='. $id_publication . '">💬 Commenter</a></div>';
                            echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>Aucune publication trouvée.</p>';
                }
            
            } else {
                header("Location: login_form.php");
            }
        ?>
    </div>
<script src="script.js"></script>
</body>
</html>
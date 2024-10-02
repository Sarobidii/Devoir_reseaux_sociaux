<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="index.php">
    <link rel="stylesheet" href="file.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body flex items-center justify-center>
    <div class="ensemble_publications flex flex-col items-center">
    <?php
        session_start();
        include 'db_connect.php';

        if (!isset($_SESSION['id_user'])) {
            header("Location: login_form.php");
            exit();
        }
        $id_compte = $_SESSION['id_user'];

        if (isset($_POST['id_publication'])) {
            $id_publication = mysqli_real_escape_string($conn, $_POST['id_publication']);
        } elseif (isset($_GET['id_publication'])) {
            $id_publication = mysqli_real_escape_string($conn, $_GET['id_publication']);
        } else {
            echo 'Invalid request. Publication ID not provided.';
            exit();
        }

        $sqlPublication = "
            SELECT p.id, p.contenu, c.nom, c.prenom, p.date_lance
            FROM publication p
            JOIN compte c ON p.id_compte = c.id
            WHERE p.id = $id_publication";

        $resultPublication = mysqli_query($conn, $sqlPublication);
            if (mysqli_num_rows($resultPublication) > 0) {
                $rowPublication = mysqli_fetch_assoc($resultPublication);
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

                        echo '<p>' . htmlspecialchars($rowPublication['prenom']) . ' ' . htmlspecialchars($rowPublication['nom']);
                            echo '</br><small>' . htmlspecialchars($rowPublication['date_lance']) . '</small>';
                        echo '</p>';
                    echo '</div>';
                    echo '<p class="contenu">' . htmlspecialchars($rowPublication['contenu']) . '</p>';

                    $id_publication = $rowPublication['id'];
                    $sqlCount = "SELECT COUNT(*) AS total_reactions FROM reaction_publication WHERE id_publication = $id_publication";
                    $resultCount = mysqli_query($conn, $sqlCount);
                    $rowCount = mysqli_fetch_assoc($resultCount);
                    $nombre_reactions = $rowCount['total_reactions'];

                    echo '<div class="nombre_reactions_commentaires">';
                        if ($nombre_reactions > 0) {
                            echo '<form class="voir_reaction" action="afficher_reactions.php" method="get">';
                            echo '<input type="hidden" name="id_publication" value="' . htmlspecialchars($id_publication) . '">';
                            echo '<input class="nombre_reactions_' . htmlspecialchars($id_publication) . '" type="submit" value="';
                            $sqlReactions = "SELECT DISTINCT r.type_reaction FROM reaction_publication rp
                                JOIN reaction r ON rp.id_reaction = r.id
                                WHERE rp.id_publication = ?";
                            $stmtReactions = mysqli_prepare($conn, $sqlReactions);
                            mysqli_stmt_bind_param($stmtReactions, "i", $id_publication);
                            mysqli_stmt_execute($stmtReactions);
                            $resultReactions = mysqli_stmt_get_result($stmtReactions);

                            // Afficher les types de réactions distinctes
                            while ($row = mysqli_fetch_assoc($resultReactions)) {
                                echo htmlspecialchars($row['type_reaction']);
                            }
                            if($nombre_reactions != 0)
                            {
                                echo $nombre_reactions .'">';
                            } 
                            echo '</form>';
                        } 
                    echo '</div>';
                    echo '<div class="buttons">';
                        echo '<div class="reaction-container_' . htmlspecialchars($id_publication) . '">';
                            $sqlUserReaction = "SELECT r.type_reaction,r.couleur_reaction, r.nom_reaction FROM reaction_publication rp
                                    JOIN reaction r ON rp.id_reaction = r.id
                                    WHERE rp.id_publication = $id_publication AND rp.id_compte = $id_compte";
                            $resultUserReaction = mysqli_query($conn, $sqlUserReaction);
                            if(mysqli_num_rows($resultUserReaction) > 0) {
                                $userReaction = mysqli_fetch_assoc($resultUserReaction);
                                echo '<div onclick="changerContenu(' . $id_publication . ', \'publication\'); nombreReaction(' . $id_publication . ', \'publication\');" class="like-button_' . htmlspecialchars($id_publication) . '">' . $userReaction['type_reaction'];
                                    echo '<span style="color: ' . htmlspecialchars($userReaction["couleur_reaction"]) . ';"> ';
                                    echo $userReaction['nom_reaction'];
                                    echo '</span> ';
                                echo '</div>';     
                            } else {
                                echo '<div onclick="changerContenu(' . $id_publication . ', \'publication\'); nombreReaction(' . $id_publication . ', \'publication\');" class="like-button_' . htmlspecialchars($id_publication) . '">👍 J\'aime</div>';  
                            }
                            echo '<div class="reaction-icons_' . htmlspecialchars($id_publication) . '">';
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

                echo '<div class="ensemble_commentaires">';
                    $sql = "
                        SELECT rp.date_lance, rp.id, rp.id_compte, rp.contenu, c.nom, c.prenom
                        FROM commentaire rp
                        JOIN compte c ON rp.id_compte = c.id
                        WHERE rp.id_publication = $id_publication";

                    $result = mysqli_query($conn, $sql);

                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<div class="commentaire">';
                                echo '<div class="nom_prenom">';
                                    echo '<div class="h-7 w-7 overflow-hidden rounded-full">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                class="h-7 w-7 p-1 text-white bg-gray-500 stroke-current"> <!-- Taille réduite ici -->
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                    ';
                                    echo "<p class='nom_prenom'>" . $row['prenom'] . ' ' . $row['nom'] . '<br>';
                                echo '</div>'; 
                                echo '<div class="contenu_commentaire">' . htmlspecialchars($row['contenu']) . '</div>';
                                $id_commentaire = $row['id'];
                            echo '</div>'; // End of commentaire div
                            echo '<div class="bouton_commentaire">';
                                echo "<div><small class='date_lance_commentaire'> " . htmlspecialchars($row['date_lance']) . "</small></div>";
                                echo '<div class="reaction-container_' . htmlspecialchars($id_commentaire) . '">';
                                    $sqlUserReaction = "SELECT r.type_reaction,r.couleur_reaction, r.nom_reaction FROM reaction_commentaire rp
                                            JOIN reaction r ON rp.id_reaction = r.id
                                            WHERE rp.id_commentaire = $id_commentaire AND rp.id_compte = $id_compte";
                                    $resultUserReaction = mysqli_query($conn, $sqlUserReaction);
                                    if(mysqli_num_rows($resultUserReaction) > 0) {
                                        $userReaction = mysqli_fetch_assoc($resultUserReaction);
                                        echo '<div onclick="changerContenu(' . $id_commentaire . ', \'commentaire\'); nombreReaction(' . $id_commentaire . ', \'commentaire\');" class="like-button_' . htmlspecialchars($id_commentaire) . '">' . $userReaction['type_reaction'];
                                        echo '<span style="color: ' . htmlspecialchars($userReaction["couleur_reaction"]) . ';"> ';
                                        echo $userReaction['nom_reaction'];
                                        echo '</span> ';
                                        echo '</div>';   
                                    } else {
                                        echo '<div onclick="changerContenu(' . $id_commentaire . ', \'commentaire\'); nombreReaction(' . $id_commentaire . ', \'commentaire\');" class="like-button_' . htmlspecialchars($id_commentaire) . '">J\'aime';
                                        echo '</div>';
                                    }
                                    echo '<div class="reaction-icons_' . htmlspecialchars($id_commentaire) . '">';
                                        $sql2 = "SELECT id, type_reaction FROM reaction";
                                        $result2 = mysqli_query($conn, $sql2);
                                        if (mysqli_num_rows($result2) > 0) {
                                            mysqli_data_seek($result2, 0);
                                            while ($row2 = mysqli_fetch_assoc($result2)) {
                                                echo "<div onclick='contenuReaction(this.getAttribute(\"data-reaction\")," . $id_commentaire . ", \"commentaire\"); nombreReaction(" . $id_commentaire . ", \"commentaire\");' class='reaction_" . htmlspecialchars($id_commentaire) . "' data-reaction='" . htmlspecialchars($row2['id']) . "'>" . htmlspecialchars($row2['type_reaction']) . "</div>";
                                            }
                                        }
                                    echo '</div>';
                                echo '</div>'; 

                                echo '<div><a href="voir_reponses.php?id_compte=' . $id_compte . '&id_commentaire=' . $id_commentaire . '&id_publication=' . $id_publication .'">Répondre</a></div>';
                                echo '<div class="nombre_reactions_' . htmlspecialchars($id_commentaire) . '">';
                                // Count reactions for each comment
                                $sqlCount = "SELECT COUNT(*) AS total_reactions FROM reaction_commentaire WHERE id_commentaire = $id_commentaire";
                                $resultCount = mysqli_query($conn, $sqlCount);
                                $rowCount = mysqli_fetch_assoc($resultCount);
                                $nombre_reactions = $rowCount['total_reactions'];
                                $sqlReactions = "SELECT DISTINCT r.type_reaction FROM reaction_commentaire rp
                                        JOIN reaction r ON rp.id_reaction = r.id
                                        WHERE rp.id_commentaire = ?";
                                    $stmtReactions = mysqli_prepare($conn, $sqlReactions);
                                    mysqli_stmt_bind_param($stmtReactions, "i", $id_commentaire);
                                    mysqli_stmt_execute($stmtReactions);
                                    $resultReactions = mysqli_stmt_get_result($stmtReactions);

                                    // Afficher les types de réactions distinctes
                                    while ($row = mysqli_fetch_assoc($resultReactions)) {
                                        echo htmlspecialchars($row['type_reaction']);
                                    }
                                    if($nombre_reactions != 0)
                                    {
                                        echo $nombre_reactions;
                                    } 
                                echo '</div>';
                            echo '</div>';
                    }
                }
            } else {
                echo 'No publication found with this ID.';
            }
            echo "</div>";
        echo "</div>";

        echo '<form class="flex items-center gap-2 rounded-lg bg-white max-w-md mx-auto p-2 shadow-md sticky bottom-0 z-10" action="commentaire.php" method="post">';
            echo '<input type="hidden" name="id_publication" value="' . htmlspecialchars($id_publication) . '">';
            echo '<input class="ecrire_commentaire flex-grow p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" name="contenu" placeholder="Écrivez un commentaire...">';
            echo '<button class="bg-gray-600 text-white p-2 rounded-md hover:bg-gray-500 transition-colors" type="submit">Envoyer</button>';
        echo '</form>';

    ?>
    <script>
        function getXMLHttpRequest() {
    	    var xhr = null;

    	    if (window.XMLHttpRequest || window.ActiveXObject) { 
    	    	if (window.ActiveXObject) {
    	    		try {
    	    			xhr = new ActiveXObject("Msxml2.XMLHTTP");
    	    		} catch(e) {
    	    			xhr = new ActiveXObject("Microsoft.XMLHTTP"); //Internet Explorer
    	    		}
    	    	} else {
    	    		xhr = new XMLHttpRequest(); //Chrome & Firefox
    	    	}
            } else {
            	alert("Votre navigateur ne supporte pas l'objet XMLHTTPRequest...");
            	return null;
            }

            return xhr;
        }
        function contenuReaction(variable, id_publication, type) {
            var xhr = getXMLHttpRequest();

            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4) {
                    if (xhr.status == 200) {
                        let element = document.querySelector('.like-button_' + id_publication);
                        if (element) {
                            element.innerHTML = xhr.responseText;
                        } else {
                            console.error('Élément non trouvé pour id_publication:', id_publication);
                        }
                    } else {
                        console.error('Erreur dans la requête AJAX :', xhr.statusText);
                    }
                }
            };

            xhr.open("GET", "ajax.php?id_reaction=" + variable + "&id_publication=" + id_publication+ "&type=" + type, true);
            xhr.send(null);
        }
        function nombreReaction(id_publication, type) {
            var xhr = getXMLHttpRequest();

            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4) {
                    if (xhr.status == 200) {
                        let element = document.querySelector('.nombre_reactions_' + id_publication);
                        if (element) {
                            if(type == 'publication')
                            {
                                element.value = xhr.responseText.trim();   
                            } else {
                                element.innerHTML = xhr.responseText.trim(); 
                            }
                        } else {
                            console.error('Élément non trouvé pour id_publication:', id_publication);
                        }
                    } else {
                        console.error('Erreur dans la requête AJAX :', xhr.statusText);
                    }
                }
            };

            xhr.open("GET", "ajax1.php?id_publication=" + id_publication + "&type=" + type, true);
            xhr.send(null);
        }

        function changerContenu(id_publication, type){
            var xhr = getXMLHttpRequest();

            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4) {
                    if (xhr.status == 200) {
                        let element = document.querySelector('.like-button_' + id_publication);
                        if (element) {
                            element.innerHTML = xhr.responseText;
                        } else {
                            console.error('Élément non trouvé pour id_publication:', id_publication);
                        }
                    } else {
                        console.error('Erreur dans la requête AJAX :', xhr.statusText);
                    }
                }
            };

            xhr.open("GET", "ajax2.php?id_publication=" + id_publication+ "&type=" + type, true);
            xhr.send(null);
        }
    </script>
</body>
</html>
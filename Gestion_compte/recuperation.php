<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="min-h-screen bg-black flex items-center justify-center">
    <div class="bg-gray-900 p-8 rounded-lg shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold text-white text-center mb-6">Entrez votre adresse email</h2>
        <form action="" method="post">
            <div class="mb-4">
                <label for="email" class="block text-white">E-mail :</label>
                <input type="email" name="email" class="w-full p-2 mt-2 bg-gray-800 text-white rounded-md" required>
            </div>
            <div class="flex justify-center">
                <input type="submit" value="Valider" class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-md cursor-pointer">
            </div>
        </form>
        
        <div class="mt-6 text-center text-gray-400">
            <?php
            session_start();
            
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
            
            include 'db_connect.php'; 
            
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'])) {
                $email = $_POST['email'];
            
                $email = stripslashes($email);
                $email = mysqli_real_escape_string($conn, $email);
            
                $sql = "SELECT id FROM compte WHERE email = '$email'";
                $result = mysqli_query($conn, $sql);
            
                if (mysqli_num_rows($result) == 1) {
                    $verification_code = rand(10000000, 99999999);
                    
                    $subject = "Votre code de vérification";
                    $message = "Votre code de vérification est : $verification_code";
                    $headers = "From: noreply@votre_site.com";
            
                    if (mail($email, $subject, $message, $headers)) {
                        $_SESSION['verification_code'] = $verification_code;
                        $_SESSION['email'] = $email;
            
                        header("Location: verification_code.php");
                        exit();
                    } else {
                        echo "<p class='text-red-500'>Erreur lors de l'envoi de l'e-mail.</p>";
                    }
                } else {
                    echo "<p class='text-red-500'>E-mail non trouvé.</p>";
                }
            }
            ?>
        </div>
    </div>
</body>
</html>

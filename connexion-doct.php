<?php
// Démarrer la session
session_start();

// Connexion à la base de données
$servername = "localhost"; // ou l'adresse de votre serveur
$username = "root";
$password = "";
$dbname = "data";

// Créer la connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// Initialiser les variables
$message = "";

// Gérer la soumission du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];

    // Vérifier les informations d'identification
    $sql = "SELECT * FROM utilisateurs WHERE email = ? AND role = 'docteur'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Vérifier le mot de passe
        if (password_verify($mot_de_passe, $user['mot_de_passe'])) {
            // Authentification réussie
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role']; // Récupérer le rôle de l'utilisateur
            header("Location: dashboard_docteur.php"); // Rediriger vers le tableau de bord du docteur
            exit();
        } else {
            $message = "Mot de passe incorrect.";
        }
    } else {
        $message = "Aucun docteur trouvé avec cet email.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <style>body {
      color: beige;
    font-family: Arial, sans-serif;
    background-image: url(home.jpg);
    padding: 20px;
    }
    
    .container {
        max-width: 400px;
    margin: auto;
    background: #1d1d1d;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 0 10px black;
    }
    
    h2 {
        color:#007bff ;
        text-align: center;
    }
    
    .form-group {
        margin-bottom: 15px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 5px;
    }
    
    .form-group input {
        width: 100%;
        padding: 8px;
        box-sizing: border-box;
    }
    
    button {
        width: 100%;
        padding: 10px;
        background-color: #14476B;
        color: white;
        border: none;
        cursor: pointer;
    }
    
    button:hover {
        background-color: #14476B;
    }
    .but{
        color: #007bff;
    }
    </style>
</head>
<body>
    <div class="container">
        <h2>Connexion</h2>
        <form id="connexion" onsubmit="return validateLogin()">
            <div class="form-group">
                <label for="email">Email :</label>
                <input type="email" id="email" required placeholder="entrer votre email">
            </div>
            <div class="form-group">
                <label for="password">Mot de passe :</label>
                <input type="password" id="password" required placeholder="..........">
            </div>
            <button type="submit">Se connecter</button>
        </form>
       
    </div>

   
</body>
<script>
function validateLogin() {
    // Ajoutez ici la logique de validation si nécessaire
    alert("Connexion réussie !");
    return false; // Empêche l'envoi du formulaire pour la démonstration
}


</script>
</html>

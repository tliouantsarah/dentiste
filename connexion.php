<?php
// Démarrer la session
session_start();

// Initialiser les variables
$message = "";

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

    // Récupérer les données du formulaire
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Préparer et exécuter la requête
    $sql = "SELECT * FROM utilisateurs WHERE email = ? AND mot_de_passe = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    // Vérifier si l'utilisateur existe
    if ($result->num_rows > 0) {
        // L'utilisateur est authentifié
        $_SESSION['email'] = $email;
        header("Location: dashboard.php"); // Rediriger vers le tableau de bord
        exit();
    } else {
        $message = "Identifiants incorrects.";
    }

    // Fermer la connexion
    $stmt->close();
    $conn->close();
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
        <p>Pas encore de compte ? <a href="inscription.html" onclick="redirectToSignup()" class="but">Inscrivez-vous ici</a>.</p>
    </div>

   
</body>
<script>
function validateLogin() {
    // Ajoutez ici la logique de validation si nécessaire
    alert("Connexion réussie !");
    return false; // Empêche l'envoi du formulaire pour la démonstration
}

function redirectToSignup() {
    // Redirige vers la page d'inscription
    window.location.href = 'signup.html'; // Remplacez par l'URL de votre page d'inscription
}
</script>
</html>

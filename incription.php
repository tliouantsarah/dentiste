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
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];

    // Vérifier si l'email existe déjà
    $sql = "SELECT * FROM utilisateurs WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $message = "Cet email est déjà utilisé.";
    } else {
        // Insérer l'utilisateur dans la base de données
        $sql = "INSERT INTO utilisateurs (email, mot_de_passe, nom, prenom) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hachage du mot de passe
        $stmt->bind_param("ssss", $email, $hashed_password, $nom, $prenom);

        if ($stmt->execute()) {
            $message = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
        } else {
            $message = "Erreur lors de l'inscription : " . $stmt->error;
        }
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
    <title>Inscription</title>
   <style>

  body {
    color: beige;
    font-family: Arial, sans-serif;
    background-image:url(home.jpg) ;
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
        <h2>Inscription</h2>
        <form id="inscription" onsubmit="return validateForm()">
            <div class="form-group">
                <label for="nom">Nom :</label>
                <input type="text" id="nom" required placeholder="entrer votre nom">
            </div>
            <div class="form-group">
                <label for="prenom">Prénom :</label>
                <input type="text" id="prenom" required placeholder="entrer votre prénom" >
            </div>
            <div class="form-group">
                <label for="email">Email :</label>
                <input type="email" id="email" required placeholder="entrer votre email">
            </div>
            <div class="form-group">
                <label for="password">Mot de passe :</label>
                <input type="password" id="password" required placeholder="..........">
            </div>
            <button type="submit">S'inscrire</button>
        </form>
        <p>Vous avez déjà un compte ? <a href="connexion.html" onclick="redirectToLogin()"class="but">Connectez-vous ici</a>.</p>
    </div>

    
</body>
<script>
    function validateForm() {
    // Ajoutez ici la logique de validation si nécessaire
    alert("Inscription réussie !");
    return false; // Empêche l'envoi du formulaire pour la démonstration
}

function redirectToLogin() {
    // Redirige vers la page de connexion (à adapter selon votre structure)
    window.location.href = 'login.html'; // Remplacez par l'URL de votre page de connexion
}

</script>
</html>


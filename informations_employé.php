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

// Vérifier si l'ID de l'employé est passé en paramètre
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Récupérer les informations de l'employé
    $sql = "SELECT * FROM employes WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Vérifier si l'employé existe
    if ($result->num_rows > 0) {
        $employe = $result->fetch_assoc();
    } else {
        die("Employé non trouvé.");
    }
} else {
    die("ID de l'employé non spécifié.");
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Patient</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #121212;
            color: white;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 30px auto;
            background: #2c2c2c;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h2 {
            text-align: center;
            color: #007bff;
        }
        .btn {
            padding: 10px;
            background-color: #14476B;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            display: inline-block;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Détails d'employé</h2>
        <p><strong>Nom:</strong> <span id="nom"></span></p>
        <p><strong>Prénom:</strong> <span id="prenom"></span></p>
        <p><strong>Téléphone:</strong> <span id="telephone"></span></p>
        <p><strong>Email:</strong> <span id="email"></span></p>
        <p><strong>Adresse:</strong> <span id="adresse"></span></p>
        <p><strong>spécialité:</strong> <span id="spécialité"></span></p>


        <a href="employé.html" class="btn">Retour à la liste des employés</a>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Récupérer les informations du patient depuis sessionStorage
            const nom = sessionStorage.getItem('employéNom');
            const prenom = sessionStorage.getItem('employéPrenom');
            const telephone = sessionStorage.getItem('employéTelephone');
            const email = sessionStorage.getItem('employéEmail');
            const Adresse = sessionStorage.getItem('employéAdresse');
            const spécialité = sessionStorage.getItem('employéSpécialité');

            // Afficher les informations dans les éléments HTML
            document.getElementById('nom').textContent = nom;
            document.getElementById('prenom').textContent = prenom;
            document.getElementById('telephone').textContent = telephone;
            document.getElementById('email').textContent = email;
            document.getElementById('adresse').textContent = Adresse;
            document.getElementById('spécialité').textContent = spécialité;

            // Supprimer les informations de sessionStorage après affichage
            sessionStorage.removeItem('employéNom');
            sessionStorage.removeItem('employéPrenom');
            sessionStorage.removeItem('employéTelephone');
            sessionStorage.removeItem('employéEmail');
            sessionStorage.removeItem('employéAdresse');
            sessionStorage.removeItem('employéSpécialité');
        });
    </script>
</body>
</html>

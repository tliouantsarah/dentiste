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

// Vérifier si l'ID du patient est passé en paramètre
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Récupérer les informations du patient
    $sql = "SELECT * FROM patients WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Vérifier si le patient existe
    if ($result->num_rows > 0) {
        $patient = $result->fetch_assoc();
    } else {
        die("Patient non trouvé.");
    }
} else {
    die("ID du patient non spécifié.");
}

// Gérer la mise à jour des informations du patient
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];

    $sql = "UPDATE patients SET nom = ?, prenom = ?, email = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $nom, $prenom, $email, $id);

    if ($stmt->execute()) {
        $message = "Informations mises à jour avec succès.";
        header("Location: informations.php?id=" . $id); // Rediriger vers la page d'informations
        exit();
    } else {
        $message = "Erreur lors de la mise à jour des informations : " . $stmt->error;
    }
}
?> 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Patient</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #121212;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            background: #2c2c2c;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .form-label {
            font-weight: normal;
            color: rgba(255, 255, 255, 0.915);
        }
        .form-control {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            background-color: #14476B;
            color: white;
            font-size: 16px;
        }
        h2{
            text-align: center;
            color: #007bff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Modifier les Informations du Patient</h2>
        <form id="edit-patient-form">
            <label for="edit-nom" class="form-label">Nom</label>
            <input type="text" class="form-control" id="edit-nom" required>

            <label for="edit-prenom" class="form-label">Prénom</label>
            <input type="text" class="form-control" id="edit-prenom" required>

            <label for="edit-telephone" class="form-label">Téléphone</label>
            <input type="text" class="form-control" id="edit-telephone" required>

            <label for="edit-email" class="form-label">Email</label>
            <input type="email" class="form-control" id="edit-email" required>

            <button type="submit" class="btn">Enregistrer</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Charger les informations du patient
            document.getElementById('edit-nom').value = sessionStorage.getItem('editNom');
            document.getElementById('edit-prenom').value = sessionStorage.getItem('editPrenom');
            document.getElementById('edit-telephone').value = sessionStorage.getItem('editTelephone');
            document.getElementById('edit-email').value = sessionStorage.getItem('editEmail');
        });

        document.getElementById('edit-patient-form').addEventListener('submit', function (e) {
            e.preventDefault();

            // Sauvegarder les données modifiées
            const updatedPatient = {
                nom: document.getElementById('edit-nom').value,
                prenom: document.getElementById('edit-prenom').value,
                telephone: document.getElementById('edit-telephone').value,
                email: document.getElementById('edit-email').value
            };
            sessionStorage.setItem('updatedPatient', JSON.stringify(updatedPatient));

            // Retour à la page principale
            window.location.href = 'employé.html';
        });
    </script>
</body>
</html>

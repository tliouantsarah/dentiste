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

// Vérifier si l'ID de l'article est passé en paramètre
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Récupérer les informations de l'article
    $sql = "SELECT * FROM stock WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Vérifier si l'article existe
    if ($result->num_rows > 0) {
        $article = $result->fetch_assoc();
    } else {
        die("Article non trouvé.");
    }
} else {
    die("ID de l'article non spécifié.");
}

// Gérer la mise à jour des informations de l'article
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom_article = $_POST['nom_article'];
    $quantite = $_POST['quantite'];
    $prix = $_POST['prix'];

    $sql = "UPDATE stock SET nom_article = ?, quantite = ?, prix = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sidi", $nom_article, $quantite, $prix, $id);

    if ($stmt->execute()) {
        $message = "Informations mises à jour avec succès.";
        header("Location: stock.php"); // Rediriger vers la page de gestion des stocks
        exit();
    } else {
        $message = "Erreur lors de la mise à jour des informations : " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Modifier un Article</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #121212;
      color: #e0e0e0;
    }

    .container {
      width: 90%;
      margin: auto;
      max-width: 600px;
      padding: 20px;
      background-color: #1e1e1e;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
    }

    h1 {
      text-align: center;
      color: #007bff;
    }

    label {
      display: block;
      margin: 10px 0 5px;
    }

    input, select {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border-radius: 5px;
      border: none;
      background-color: rgba(250, 235, 215, 0.524);
    }

    .btn {
      display: block;
      width: 100%;
      padding: 10px;
      background-color:#0056b3;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      text-align: center;
    }

    .btn:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Modifier un Article</h1>
    <form id="edit-form">
      <label for="name">Nom de l'article</label>
      <input type="text" id="name" name="name" required>

      <label for="category">Catégorie</label>
      <select id="category" name="category">
        <option value="Médicaments">Médicaments</option>
        <option value="Hygiène">Hygiène</option>
        <option value="Produits">Produits</option>
      </select>

      <label for="quantity">Quantité</label>
      <input type="number" id="quantity" name="quantity" required>

      <label for="supplier">Fournisseur</label>
      <input type="text" id="supplier" name="supplier" required>

      <label for="expiry">Date de Péremption</label>
      <input type="date" id="expiry" name="expiry" required>

      <button type="submit" class="btn">Enregistrer les Modifications</button>
    </form>
  </div>

  <script>
    // Pré-remplir le formulaire avec les données envoyées via l'URL
    document.addEventListener('DOMContentLoaded', () => {
      const urlParams = new URLSearchParams(window.location.search);
      document.getElementById('name').value = urlParams.get('name');
      document.getElementById('category').value = urlParams.get('category');
      document.getElementById('quantity').value = urlParams.get('quantity');
      document.getElementById('supplier').value = urlParams.get('supplier');
      document.getElementById('expiry').value = urlParams.get('expiry');
    });
  </script>
</body>
</html>

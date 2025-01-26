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

// Gérer l'ajout d'un article
if (isset($_POST['ajouter'])) {
    $nom_article = $_POST['nom_article'];
    $quantite = $_POST['quantite'];
    $prix = $_POST['prix'];

    $sql = "INSERT INTO stock (nom_article, quantite, prix) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sid", $nom_article, $quantite, $prix);

    if ($stmt->execute()) {
        $message = "Article ajouté avec succès.";
    } else {
        $message = "Erreur lors de l'ajout de l'article : " . $stmt->error;
    }
}

// Gérer la suppression d'un article
if (isset($_GET['supprimer'])) {
    $id = $_GET['supprimer'];
    $sql = "DELETE FROM stock WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $message = "Article supprimé avec succès.";
    } else {
        $message = "Erreur lors de la suppression de l'article : " . $stmt->error;
    }
}

// Récupérer la liste des articles en stock
$sql = "SELECT * FROM stock";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestion de Stock - Cabinet Dentaire</title>
  
  <style>
    /* Global Styles */
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #121212; /* Fond sombre */
      color: #e0e0e0; /* Texte clair */
    }
    
    .container {
      width: 90%;
      margin: auto;
      max-width: 1200px;
    }
    
    /* Header */
    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 20px 0;
    }
    
    .header h1 {
      font-size: 2rem;
      color: #007bff; /* Couleur du titre */
    }
    
    .btn {
      background-color: #14476B; /* Couleur du bouton */
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 5px;
      cursor: pointer;
    }
    
    .btn:hover {
      background-color: #14476B;; /* Couleur au survol */
    }
    
    /* Stats Section */
    .stats {
      display: flex;
      justify-content: space-between;
      margin: 20px 0;
    }
    
    .stat-card {
      background-color: #1e1e1e; /* Fond des cartes statistiques */
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
      text-align: center;
      width: 30%;
    }
    
    .stat-card h2 {
      font-size: 1.2rem;
      margin-bottom: 10px;
    }
    
    .stat-card p {
      font-size: 1.5rem;
      color: #14476B;; /* Couleur des chiffres */
    }
    
    /* Table Section */
    .table-section {
      background-color: #1e1e1e; /* Fond de la section table */
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
    }
    
    table {
      width: 100%;
      border-collapse: collapse;
    }
    
    thead {
      background-color: #14476B;; /* Couleur du header de la table */
      color: white;
    }
    
    thead th {
      padding: 10px;
      text-align: left;
    }
    
    tbody tr:nth-child(even) {
      background-color: #2a2a2a; /* Lignes paires de la table */
    }
    
    tbody td {
      padding: 10px;
    }
    
    .status {
      padding: 5px 10px;
      border-radius: 5px;
      font-size: 0.9rem;
      font-weight: bold;
    }
    
    .in-stock {
      background-color: #0d98162c; /* Couleur pour "En Stock" */
      color: white; /* Texte pour "En Stock" */
    }
    
    .low-stock {
      background-color: #98730d85; /* Couleur pour "Faible" */
      color: white; /* Texte pour "Faible" */
    }
    
    .out-of-stock {
      background-color: #721c258b; /* Couleur pour "Rupture" */
      color: white; /* Texte pour "Rupture" */
    }
    
    /* Buttons in Table */
    .btn-edit {
        background-color: #007bff; /* Couleur du bouton Modifier */
        border: none;
        padding: 5px 10px;
        color: white;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease; 
     }

     .btn-edit:hover {
        background-color:#007bff; /* Couleur au survol pour Modifier*/
     }

     .btn-delete {
        background-color:#dc3546b6; /* Couleur du bouton Supprimer */
        border:none; 
        padding :5px 10px ;
        color:white ;
        border-radius :5px ;
        cursor:pointer ;
        transition :background-color 0.3s ease ; 
     }

     .btn-delete:hover{
         background-color:#c8233378 ;/*Couleur au survol pour Supprimer*/
     }
 
   </style>
</head>
<body>
<div class="container">
<!-- Header -->
<header class="header">
<h1>Gestion de Stock</h1>
 <button class="btn" onclick="window.location.href='ajout.html'">+ Ajouter un Article</button>

</header>

<!-- Dashboard Stats -->
<section class="stats">
<div class="stat-card">
<h2>Total Articles</h2>
<p>320</p>
</div>
<div class="stat-card">
<h2>Proches de Rupture</h2>
<p>12</p>
</div>
<div class="stat-card">
<h2>Articles Expirés</h2>
<p>4</p>
</div>
</section>

<!-- Table Section -->
<section class="table-section">
<table>
<thead>
<tr>
<th>Nom</th>
<th>Catégorie</th>
<th>Quantité</th>
<th>Fournisseur</th>
<th>Date de Péremption</th>
<th>État</th>
<th>Actions</th>
</tr>
</thead>
<tbody>
<tr>
<td>Anesthésique A</td>
<td>Médicaments</td>
<td>50</td>
<td>Fournisseur X</td>
<td>2024-12-31</td>
<td><span class="status in-stock">En Stock</span></td>
<td>
<button class="btn-edit">Modifier</button>
<button class="btn-delete">Supprimer</button>
</td>
</tr>
<tr>
<td>Masques Stériles</td>
<td>Hygiène</td>
<td>10</td>
<td>Fournisseur Y</td>
<td>2025-01-15</td>
<td><span class="status low-stock">Faible</span></td>
<td>
<button class="btn-edit">Modifier</button>
<button class="btn-delete">Supprimer</button>
</td>
</tr>
<tr>
<td>Solution Désinfectante</td>
<td>Produits</td>
<td>0</td>
<td>Fournisseur Z</td>
<td>2023-11-01</td>
<td><span class="status out-of-stock">Rupture</span></td>
<td>
<button class="btn-edit">Modifier</button>
<button class="btn-delete">Supprimer</button>
</td>
</tr>

 </tbody >
 </table >
 </section >
 </div >
 </body >
 <script>
  // Fonction pour rediriger vers la page de modification
function editItem(button) {
  const row = button.closest('tr'); // Trouver la ligne correspondante
  const name = row.querySelector('td:nth-child(1)').innerText; // Nom de l'article
  const category = row.querySelector('td:nth-child(2)').innerText; // Catégorie
  const quantity = row.querySelector('td:nth-child(3)').innerText; // Quantité
  const supplier = row.querySelector('td:nth-child(4)').innerText; // Fournisseur
  const expiry = row.querySelector('td:nth-child(5)').innerText; // Date de péremption

  // Rediriger vers la page de modification avec les détails dans l'URL
  window.location.href = `modifier-stock.html?name=${encodeURIComponent(name)}&category=${encodeURIComponent(category)}&quantity=${encodeURIComponent(quantity)}&supplier=${encodeURIComponent(supplier)}&expiry=${encodeURIComponent(expiry)}`;
}

// Attacher l'événement "Modifier" aux boutons correspondants
document.addEventListener('DOMContentLoaded', () => {
  const editButtons = document.querySelectorAll('.btn-edit');
  editButtons.forEach(button => {
    button.addEventListener('click', () => editItem(button));
  });
});
// Fonction pour supprimer un article
function deleteItem(button) {
  const row = button.closest('tr'); // Trouver la ligne correspondante
  row.remove(); // Supprimer la ligne du tableau
}

// Attacher l'événement "Supprimer" aux boutons correspondants
document.addEventListener('DOMContentLoaded', () => {
  const deleteButtons = document.querySelectorAll('.btn-delete');
  deleteButtons.forEach(button => {
    button.addEventListener('click', () => deleteItem(button));
  });
});


 </script>
 </html >

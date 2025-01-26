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

// Gérer l'ajout d'un employé
if (isset($_POST['ajouter'])) {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $poste = $_POST['poste'];

    $sql = "INSERT INTO employes (nom, prenom, email, poste) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $nom, $prenom, $email, $poste);

    if ($stmt->execute()) {
        $message = "Employé ajouté avec succès.";
    } else {
        $message = "Erreur lors de l'ajout de l'employé : " . $stmt->error;
    }
}

// Gérer la suppression d'un employé
if (isset($_GET['supprimer'])) {
    $id = $_GET['supprimer'];
    $sql = "DELETE FROM employes WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $message = "Employé supprimé avec succès.";
    } else {
        $message = "Erreur lors de la suppression de l'employé : " . $stmt->error;
    }
}

// Récupérer la liste des employés
$sql = "SELECT * FROM employes";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des employé</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #121212;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 100%;
            margin: 30px auto;
            background: #2c2c2c;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f4f4f4;
        }
        tr{
            color: white;
        }
        .btn {
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-primary {
            background-color: #00ff1132;
            color: white;
        }
        .btn-warning {
            background-color: #98730d85;
            color: white;
        }
        .btn-danger {
            background-color: #721c258b;
            color: white;
        }
        .btn-success {
            background-color: #14476B;
            color: white;
        }
        .form-label {
            font-weight:normal;
            color: wheat;

        }
        .form-control {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        h3{
            color: white;
        }
        h2{
            text-align: center;
            color: #007bff;
        }
        .one{
            color: #14476B;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center mb-4">Gestion des employé</h2>

      
        <div class="card mb-4">
            <h3>Employé </h3>
            <table>
                <thead>
                    <tr class="one">
                        <th>#</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Téléphone</th>
                        <th>Email</th>
                        <th>Adresse</th>
                        <th>spécialité</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="patient-table-body">
                    <tr>
                        <td>1</td>
                        <td> Belkacem</td>
                        <td>Amine</td>
                        <td>055 2489670 </td>
                        <td>Amine-bk@gmail.com</td>
                        <td>alger</td>
                        <td>orthodontie</td>

                        <td>
                            <button class="btn btn-primary btn-sm" onclick="voirEmployé(this)">Voir</button>

                            <button class="btn btn-warning btn-sm" onclick="editEmployé(this)" >Modifier</button>
                            <button class="btn btn-danger btn-sm" onclick="deleteEmployé(this)">Supprimer</button>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td> Mekki </td>
                        <td>Manel</td>
                        <td>076 1302909 </td>
                        <td>Manel-mek@gmail.com</td>
                        <td>blida</td>
                        <td>Parodontologue</td>
                        <td>
                            <button class="btn btn-primary btn-sm" onclick="voirEmployé(this)">Voir</button>
                            <button class="btn btn-warning btn-sm" onclick="editEmployé(this)">Modifier</button>
                            <button class="btn btn-danger btn-sm" onclick="deleteEmployé(this)">Supprimer</button>
                        </td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td> Boudoucha Bacem </td>
                        <td>Bacem</td>
                        <td>076 5678909 </td>
                        <td>Bacem-bou@gmail.com</td>
                        <td>Alger</td>
                        <td>Chirurgien-dentiste</td>
                        <td>
                            <button class="btn btn-primary btn-sm" onclick="voirEmployé(this)">Voir</button>
                            <button class="btn btn-warning btn-sm" onclick="editEmployé(this)">Modifier</button>
                            <button class="btn btn-danger btn-sm" onclick="deleteEmployé(this)">Supprimer</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="card">
            <h3>Ajouter un stagière</h3>
            <form id="patient-form">
                <label for="nom" class="form-label">Nom</label>
                <input type="text" class="form-control" id="nom" placeholder="Entrer le nom du stagière" required>

                <label for="prenom" class="form-label">Prénom</label>
                <input type="text" class="form-control" id="prenom" placeholder="Entrer le prénom du stagière" required>

                <label for="telephone" class="form-label">Téléphone</label>
                <input type="text" class="form-control" id="telephone" placeholder="Entrer le numéro de téléphone" required>

                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" placeholder="Entrer l'email du stagière" required>

                <label for="adresse" class="form-label">Adresse</label>
                <input type="text" class="form-control" id="adresse" placeholder="Entrer l'adresse du stagière" required>
                
               

                <button type="submit" class="btn btn-success">Enregistrer</button>
            </form>
        </div>
    </div>

      
    <script>
       
              
        function deleteEmployé(button) {
            const row = button.parentElement.parentElement;
            row.remove();

            // Réorganiser les indices
            const tableBody = document.getElementById('employé-table-body');
            Array.from(tableBody.children).forEach((row, index) => {
                row.children[0].textContent = index + 1;
            });
        }

      
       
    function editEmployé(button) {
        const row = button.parentElement.parentElement;
        const nom = row.children[1].textContent;
        const prenom = row.children[2].textContent;
        const telephone = row.children[3].textContent;
        const email = row.children[4].textContent;
        const adresse = row.children[4].textContent;
        const spécialité = row.children[4].textContent;

        // Stocker les informations dans sessionStorage
        sessionStorage.setItem('editNom', nom);
        sessionStorage.setItem('editPrenom', prenom);
        sessionStorage.setItem('editTelephone', telephone);
        sessionStorage.setItem('editEmail', email);
        sessionStorage.setItem('editAdresse', adresse);
        sessionStorage.setItem('editSpécialité', spécialité);

        // Rediriger vers la page de modification
        window.location.href = 'edit_employé.html';
    }

    // Gérer les données mises à jour lors du retour à la page principale
    document.addEventListener('DOMContentLoaded', function () {
        const updatedEmployé = sessionStorage.getItem('updatedEmployé');
        if (updatedEmployé) {
            const employé = JSON.parse(updatedEmployé);

            const tableBody = document.getElementById('Employé-table-body');
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${tableBody.children.length + 1}</td>
                <td>${employé.nom}</td>
                <td>${employé.Prénom}</td>
                <td>${employé.telephone}</td>
                <td>${employé.email}</td>
                <td>${employé.adresse}</td>
                <td>${employé.spécialité}</td>
                <td>
                    <button class="btn btn-primary btn-sm" onclick="voirEmployé(this)">Voir</button>
                    <button class="btn btn-warning btn-sm" onclick="editEmployé(this)">Modifier</button>
                    <button class="btn btn-danger btn-sm" onclick="deleteEmployé(this)">Supprimer</button>
                </td>
            `;
            tableBody.appendChild(row);

            // Supprimer les données de sessionStorage pour éviter de les réutiliser
            sessionStorage.removeItem('updatedEmployé');
        }
    });
    function voirEmployé(button) {
    const row = button.parentElement.parentElement;
    const nom = row.children[1].textContent;
    const prenom = row.children[2].textContent;
    const telephone = row.children[3].textContent;
    const email = row.children[4].textContent;
    const adresse = row.children[5].textContent; // Adaptez si nécessaire
    const spécialité = row.children[6].textContent; // Adaptez si nécessaire

    // Stocker les informations dans sessionStorage
    sessionStorage.setItem('employéNom', nom);
    sessionStorage.setItem('employéPrenom', prenom);
    sessionStorage.setItem('employéTelephone', telephone);
    sessionStorage.setItem('employéEmail', email);
    sessionStorage.setItem('employéAdresse', adresse);
    sessionStorage.setItem('employéSpécialité', spécialité);

   

    // Rediriger vers la page de détails
    window.location.href = 'informations_employé.html'; // Assurez-vous que cette page existe
}


    </script>
    
</body>
</html>

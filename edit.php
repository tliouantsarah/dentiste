<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le Patient</title>
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
        .btn-2{
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            background-color: #6b141496;
            color: white;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Modifier le Patient</h2>
        <form id="edit-patient-form">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" class="form-control" id="nom" required>

            <label for="prenom" class="form-label">Prénom</label>
            <input type="text" class="form-control" id="prenom" required>

            <label for="telephone" class="form-label">Téléphone</label>
            <input type="text" class="form-control" id="telephone" required>

            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" required>

            <button type="submit" class="btn">Sauvegarder les modifications</button>
            <button type="submit" class="btn-2">Annuler</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Récupérer les informations du patient depuis sessionStorage
            const nom = sessionStorage.getItem('editPatientNom');
            const prenom = sessionStorage.getItem('editPatientPrenom');
            const telephone = sessionStorage.getItem('editPatientTelephone');
            const email = sessionStorage.getItem('editPatientEmail');

            // Pré-remplir le formulaire avec les informations existantes
            document.getElementById('nom').value = nom;
            document.getElementById('prenom').value = prenom;
            document.getElementById('telephone').value = telephone;
            document.getElementById('email').value = email;

            // Supprimer les informations de sessionStorage après utilisation
            sessionStorage.removeItem('editPatientNom');
            sessionStorage.removeItem('editPatientPrenom');
            sessionStorage.removeItem('editPatientTelephone');
            sessionStorage.removeItem('editPatientEmail');
        });

        // Gérer la soumission du formulaire
        document.getElementById('edit-patient-form').addEventListener('submit', function(e) {
            e.preventDefault();

            // Récupérer les valeurs mises à jour
            const nom = document.getElementById('nom').value;
            const prenom = document.getElementById('prenom').value;
            const telephone = document.getElementById('telephone').value;
            const email = document.getElementById('email').value;

            // Mettre à jour le tableau des patients dans la page principale (par exemple, en utilisant sessionStorage)
            // ou envoyer les données au serveur pour enregistrer les modifications

            alert('Les modifications ont été sauvegardées !');
        });
    </script>
</body>
</html>

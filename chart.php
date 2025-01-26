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

// Récupérer le nombre total de patients
$sql = "SELECT COUNT(*) AS total_patients FROM patients";
$result = $conn->query($sql);
$total_patients = $result->fetch_assoc()['total_patients'];

// Récupérer le nombre total d'employés
$sql = "SELECT COUNT(*) AS total_employes FROM employes";
$result = $conn->query($sql);
$total_employes = $result->fetch_assoc()['total_employes'];

// Récupérer le nombre total de rendez-vous
$sql = "SELECT COUNT(*) AS total_rendezvous FROM rendezvous";
$result = $conn->query($sql);
$total_rendezvous = $result->fetch_assoc()['total_rendezvous'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Cabinet Dentaire</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #2c2c2c;
        }
        
        
        .card {
            border: none;
            border-radius: 10px;
        }
        .card-header {
            background: #4e73df;
            color:#2c2c2c ;
        }
        .card-body{
            background-color: #2c2c2c45;
            color: #0078FF;
        }
    </style>
</head>
<body>
   

            <!-- Main content -->
            <main class="col-md-10">
                <div class="row mt-3">
                    <!-- Statistiques principales -->
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">Rendez-vous</h5>
                                <p class="card-text">42 aujourd'hui</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">Patients</h5>
                                <p class="card-text">215 actifs</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">Stock</h5>
                                <p class="card-text">12 articles à réapprovisionner</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">Revenus</h5>
                                <p class="card-text">15 000 DA ce mois</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Graphiques -->
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">Rendez-vous (semaine)</div>
                            <div class="card-body">
                                <canvas id="appointmentsChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">Stocks Critiques</div>
                            <div class="card-body">
                                <canvas id="stockChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Graphique des rendez-vous
        const appointmentsCtx = document.getElementById('appointmentsChart').getContext('2d');
        new Chart(appointmentsCtx, {
            type: 'line',
            data: {
                labels: ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi'],
                datasets: [{
                    label: 'Rendez-vous',
                    data: [10, 12, 15, 8, 20],
                    borderColor: '#4e73df',
                    backgroundColor: 'rgba(78, 115, 223, 0.1)',
                }]
            },
        });

        // Graphique du stock
        const stockCtx = document.getElementById('stockChart').getContext('2d');
        new Chart(stockCtx, {
            type: 'bar',
            data: {
                labels: ['Gants', 'Masques', 'Anesthésiques', 'Fil dentaire'],
                datasets: [{
                    label: 'Stock Restant',
                    data: [15, 10, 5, 2],
                    backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e'],
                }]
            },
        });
    </script>
     

    
</body>
</html>

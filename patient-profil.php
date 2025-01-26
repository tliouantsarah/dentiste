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
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Client</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url(home.jpg);
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            width: 80%;
            max-width: 1200px;
            margin: 30px auto;
            background-color: rgba(255, 255, 255, 0.667);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h1, h2 {
            text-align: center;
            color: #0078FF;
        }

        .section {
            margin-bottom: 30px;
        }

        .section h3 {
           text-align: center;
            color: #14476B;
            padding: 10px;
            border-radius: 5px;
        }

        .info-table, .payment-table, .appointment-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td, .payment-table td, .appointment-table td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        .info-table th, .payment-table th, .appointment-table th {
            background-color: #33333381;
            text-align: left;
            color: rgba(255, 255, 255, 0.845);
        }

        .actions {
            display: flex;
            justify-content: space-between;
        }

        .actions button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #14476B;
            color: white;
            cursor: pointer;
            font-size: 16px;
        }

        .actions button:hover {
            background-color: #14476B;
        }

        .notification {
            background-color: #14476b6e;
            border-left: 5px solid #14476B;
            padding: 10px;
            margin-bottom: 20px;
            color: white;
        }

    </style>
</head>
<body>

    <div class="container">
        <h1>Profil Client</h1>

        <!-- Informations Personnelles -->
        <div class="section">
            <h3>Informations Personnelles</h3>
            <table class="info-table" id="infoTable">
                <tr><th>Nom :</th><td id="name">Sarah B.</td></tr>
                <tr><th>Date de naissance :</th><td id="dob">12/05/1985</td></tr>
                <tr><th>Numéro de téléphone :</th><td id="phone">+213 000 000 000</td></tr>
                <tr><th>Email :</th><td id="email">sarah@example.com</td></tr>
                <tr><th>Adresse :</th><td id="address">123 Rue des Orangers, Alger, Algérie</td></tr>
            </table>
            <button onclick="editPersonalInfo()">Modifier mes informations</button>
        </div>

        <!-- Historique des Rendez-vous -->
        <div class="section">
            <h3>Historique des Rendez-vous</h3>
            <h4>Prochain Rendez-vous :</h4>
            <table class="appointment-table" id="upcomingAppointment">
                <tr><th>Date :</th><td>05/01/2024</td></tr>
                <tr><th>Heure :</th><td>10h00</td></tr>
                <tr><th>Médecin :</th><td>Dr. Ahmed</td></tr>
                <tr><th>Traitement prévu :</th><td>Nettoyage dentaire</td></tr>
            </table>

            <h4>Rendez-vous Passés :</h4>
            <table class="appointment-table" id="pastAppointments">
                <!-- Rendez-vous passés seront ajoutés dynamiquement -->
            </table>
            <button onclick="addAppointment()">Ajouter un Rendez-vous</button>
        </div>

        <!-- Historique des Paiements -->
        <div class="section">
            <h3>Historique des Paiements</h3>
            <table class="payment-table" id="paymentHistory">
                <!-- Paiements seront ajoutés dynamiquement -->
            </table>
            <button onclick="addPayment()">Ajouter un Paiement</button>
        </div>

        <!-- Notifications -->
        <div class="section">
            <h3>Notifications</h3>
            <div class="notification" id="notification">
                <!-- Notifications dynamiques -->
            </div>
        </div>

        <!-- Actions -->
        <div class="actions">
            <button onclick="modifyInfo()">Modifier mes informations</button>
            <button onclick="cancelAppointment()">Annuler le rendez-vous</button>
            <button onclick="bookNewAppointment()">Prendre un nouveau rendez-vous</button>
            <button onclick="contactSupport()">Contacter le support</button>
        </div>

    </div>

    <script>
        // Données initiales
        const appointments = [
            { date: '12/12/2023', treatment: 'Extraction de dent', doctor: 'Dr. Samira' }
        ];

        const payments = [
            { date: '12/12/2023', amount: '5000 DZD', for: 'Extraction dentaire' },
            { date: '10/11/2023', amount: '3000 DZD', for: 'Consultation' },
            { date: '03/10/2023', amount: '2000 DZD', for: 'Nettoyage dentaire' }
        ];

        // Fonction pour modifier les informations personnelles
        function editPersonalInfo() {
            const newName = prompt("Entrez le nouveau nom", document.getElementById('name').innerText);
            if (newName) {
                document.getElementById('name').innerText = newName;
            }
            const newDob = prompt("Entrez la nouvelle date de naissance", document.getElementById('dob').innerText);
            if (newDob) {
                document.getElementById('dob').innerText = newDob;
            }
            const newPhone = prompt("Entrez le nouveau numéro de téléphone", document.getElementById('phone').innerText);
            if (newPhone) {
                document.getElementById('phone').innerText = newPhone;
            }
            const newEmail = prompt("Entrez le nouvel email", document.getElementById('email').innerText);
            if (newEmail) {
                document.getElementById('email').innerText = newEmail;
            }
            const newAddress = prompt("Entrez la nouvelle adresse", document.getElementById('address').innerText);
            if (newAddress) {
                document.getElementById('address').innerText = newAddress;
            }
        }

        // Ajouter un rendez-vous
        function addAppointment() {
            const date = prompt("Entrez la date du rendez-vous");
            const treatment = prompt("Entrez le traitement prévu");
            const doctor = prompt("Entrez le nom du médecin");
            if (date && treatment && doctor) {
                appointments.push({ date, treatment, doctor });
                displayAppointments();
            }
        }

        // Ajouter un paiement
        function addPayment() {
            const date = prompt("Entrez la date du paiement");
            const amount = prompt("Entrez le montant");
            const forWhat = prompt("Entrez la raison du paiement");
            if (date && amount && forWhat) {
                payments.push({ date, amount, for: forWhat });
                displayPayments();
            }
        }

        // Afficher les rendez-vous
        function displayAppointments() {
            const pastAppointmentsTable = document.getElementById('pastAppointments');
            pastAppointmentsTable.innerHTML = '';
            appointments.forEach(appointment => {
                const row = `<tr><td>${appointment.date}</td><td>${appointment.treatment}</td><td>${appointment.doctor}</td></tr>`;
                pastAppointmentsTable.innerHTML += row;
            });
        }

        // Afficher les paiements
        function displayPayments() {
            const paymentTable = document.getElementById('paymentHistory');
            paymentTable.innerHTML = '';
            payments.forEach(payment => {
                const row = `<tr><td>${payment.date}</td><td>${payment.amount}</td><td>${payment.for}</td></tr>`;
                paymentTable.innerHTML += row;
            });
        }

        // Notifications dynamiques
        function showNotification(message) {
            const notificationDiv = document.getElementById('notification');
            notificationDiv.innerHTML = `<p><strong>${message}</strong></p>`;
        }

        // Initialisation
        displayAppointments();
        displayPayments();
        showNotification("Votre prochain rendez-vous est dans 5 jours.");

        // Actions des boutons
        function modifyInfo() {
            editPersonalInfo();
        }

        function cancelAppointment() {
            alert("Le rendez-vous a été annulé.");
        }

        function bookNewAppointment() {
            alert("Prenez un nouveau rendez-vous!");
        }

        function contactSupport() {
            alert("Contacter le support");
        }
    </script>

</body>
</html>

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

// Gérer l'ajout d'un rendez-vous
if (isset($_POST['ajouter'])) {
    $patient_id = $_POST['patient_id'];
    $date_rendezvous = $_POST['date_rendezvous'];
    $heure_rendezvous = $_POST['heure_rendezvous'];

    $sql = "INSERT INTO rendezvous (patient_id, date_rendezvous, heure_rendezvous) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $patient_id, $date_rendezvous, $heure_rendezvous);

    if ($stmt->execute()) {
        $message = "Rendez-vous ajouté avec succès.";
    } else {
        $message = "Erreur lors de l'ajout du rendez-vous : " . $stmt->error;
    }
}

// Gérer la suppression d'un rendez-vous
if (isset($_GET['supprimer'])) {
    $id = $_GET['supprimer'];
    $sql = "DELETE FROM rendezvous WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $message = "Rendez-vous supprimé avec succès.";
    } else {
        $message = "Erreur lors de la suppression du rendez-vous : " . $stmt->error;
    }
}

// Récupérer la liste des rendez-vous
$sql = "SELECT r.*, p.nom AS nom_patient, p.prenom AS prenom_patient FROM rendezvous r JOIN patients p ON r.patient_id = p.id";
$result = $conn->query($sql);
?><!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demande de rendez-vous</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image :url(home.jpg);
            color: #ffffff75;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #262626;
            padding: 2rem;
            border-radius: 8px;
            max-width: 900px;
            width: 100%;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .form-container .form {
            background-color: #ffffff;
            color: #000;
            padding: 1.5rem;
            border-radius: 8px;
            flex: 1;
            margin-right: 2rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .form h1 {
            font-size: 20px;
            margin-bottom: 1rem;
            color: #007bff;
        }

        .form .tabs {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
        }

        .form .tabs .tab {
            flex: 1;
            text-align: center;
            padding: 0.5rem 0;
            cursor: pointer;
            background-color: #f0f0f0;
            border-bottom: 3px solid transparent;
            font-size: 14px;
            color: #14476B;
        }

        .form .tabs .tab.active {
            font-weight: bold;
            border-bottom: 3px solid #007bff;
        }

        .form .form-section {
            display: none;
        }

        .form .form-section.active {
            display: block;
        }

        .form .form-group {
            margin-bottom: 1rem;
        }

        .form input, .form select, .form textarea {
            width: 100%;
            padding: 0.5rem;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 1rem;
        }

        .form textarea {
            resize: vertical;
        }
            .form button {
    width: 48%;
    padding: 0.7rem;
    font-size: 16px;
    background-color: #14476B;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    margin: 0.5rem 0; /* Espacer les boutons */
}

        .form button:hover {
            background-color: #0056b3;
        }

        .cta {
            flex: 1;
            text-align: center;
            color: #ffffff;
        }

        .cta h2 {
            font-size: 2.5rem;
            margin-bottom: 20px;
        }

        .cta span {
            color: #007bff;
            font-size: 2.5rem;
            margin-bottom: 20px;
           text-decoration: underline;
        }

        .cta hr {
            margin: 1rem auto;
            width: 50%;
            border: 0.5px solid #444;
        }

        /* Styles du calendrier */
        .calendar {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 5px; /* Réduire l'espace entre les jours */
    margin-top: 1rem;
    width: 80%; /* Réduire la largeur du calendrier */
    max-width: 400px; /* Limiter la largeur max */
    margin: 0 auto; /* Centrer le calendrier */
}

        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            color:#14476B(67, 67, 172, 0.477);
        }

        .calendar-header button {
            background-color: #1e8fff56;
            color: rgba(67, 67, 172, 0.477);
            border: none;
            padding: 5px;
            border-radius: 5px;
            cursor: pointer;
        }

        .calendar-days, .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            color:rgba(34, 34, 114, 0.517);
        }

        .day, .date {
            padding: 10px;
            margin: 2px;
            text-align: center;
        }
        .calendar div {
    padding: 0.5rem;
    text-align: center;
    background-color: #f0f0f0;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px; /* Réduire la taille de la police */
}
.calendar div.full {
    background-color: #ff4d4d;
    color: #fff;
    cursor: not-allowed;
}

.calendar div:hover:not(.full) {
    background-color: #d1e7ff;
}
        .day {
            font-weight: bold;
            color: #0056b3;
        }

        .date {
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .date:hover {
            background-color: #55555531;
        }

        .date.selected {
            background-color: #1e90ff;
        }

        .date.occupied {
            color: #888;
            cursor: not-allowed;
        }

        .date.weekend {
            color: #888;
            cursor: not-allowed;
        }

        #confirmationMessage {
            margin-top: 15px;
            font-size: 14px;
            color: #14476B;
        }
        #monthDisplay{
            color: #14476B;
        }
       
.calendar-header button {
    background-color: #14476B; /* Couleur de fond */
    color: white; /* Couleur de texte */
    border-radius: 5px;
    padding: 5px;
    cursor: pointer;
    font-size: 18px; /* Taille de la police */
    width: 30px; /* Largeur du bouton */
    height: 30px; /* Hauteur du bouton */
}

.calendar-header button:hover {
    background-color: #0056b3; /* Couleur au survol */
}
body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            color: #333;
        }

       

    </style>
</head>
<body>
    <div class="form-container">
        <div class="form">
            <h1>Demande de rendez-vous</h1>
            <div class="tabs">
                <div class="tab active" data-tab="contact">Informations de contact</div>
                <div class="tab" data-tab="service">Choix du service</div>
                <div class="tab" data-tab="date">Choix du date</div>
            </div>
            <form>
                <div class="form-section active" id="contact">
                    <div class="form-group">
                        <input type="text" placeholder="Entrez votre nom et prénom">
                    </div>
                    <div class="form-group">
                        <input type="email" placeholder="Entrez votre email">
                    </div>
                    <div class="form-group">
                        <input type="text" placeholder="Entrez votre numéro de portable">
                    </div>
                    <button type="button" class="next">Étape suivante</button>
                </div>
                <div class="form-section" id="service">
                    <div class="form-group">
                        <select>
                            <option value="">Choisissez un service</option>
                            <option value="implant">Implant</option>
                            <option value="facette">Facette</option>
                            <option value="blanchiment">Blanchiment</option>
                            <option value="orthodontie">Orthodontie</option>
                            <option value="soin">Soin</option>
                            <option value="prothese">Prothèse fixe</option>
                            <option value="radiologie">Radiologie</option>
                            <option value="autre">Autre</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <textarea placeholder="Commentaire ou note additionnelle"></textarea>
                    </div>
                    <button type="button" class="prev">Étape précédente</button>
                    <button type="button" class="next">Étape suivante</button>
                </div>
                <div class="form-section" id="date">
                    <p>Sélectionnez une date disponible dans le calendrier.</p>
                    <div class="calendar-container">
                        <div class="calendar-header">
                            <button id="prevMonth"> < </button>
                            <h2 id="monthDisplay">Décembre</h2>
                            <button id="nextMonth"> > </button>
                        </div>
                        <div class="calendar-days">
                            <div class="day">Lun</div><div class="day">Mar</div><div class="day">Mer</div><div class="day">Jeu</div>
                            <div class="day">Ven</div><div class="day">Sam</div><div class="day">Dim</div>
                        </div>
                       
                        <div class="calendar-grid" id="calendar"></div>
                        <p id="confirmationMessage"></p>
                    </div>
                    <button type="button" class="prev">Étape précédente</button>
                    <button type="button" id="confirmButton">Confirmer</button>
                </div>
            </form>
        </div>
        <div class="cta">
            <h2>Prenez votre <span>rendez-vous</span> en ligne</h2>
            <hr>
        </div>
    </div>
    <div id="confirmationMessage" class="form-section"></div>

    <script>
     const monthNames = ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"];
const calendar = document.getElementById("calendar");
const monthDisplay = document.getElementById("monthDisplay");
const confirmationMessage = document.getElementById("confirmationMessage");
const confirmButton = document.getElementById('confirmButton');

let currentSection = 0; 
let selectedDate = null;
let currentMonth = new Date().getMonth();
let currentYear = new Date().getFullYear();
const reservations = {};

function renderCalendar() {
    calendar.innerHTML = "";
    const firstDayOfMonth = new Date(currentYear, currentMonth, 1).getDay();
    const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();

    monthDisplay.textContent = `${monthNames[currentMonth]} ${currentYear}`;

    // Créer les cases vides avant le premier jour du mois
    for (let i = 0; i < firstDayOfMonth; i++) {
        const emptyCell = document.createElement("div");
        calendar.appendChild(emptyCell);
    }

    // Créer les jours du mois
    for (let day = 1; day <= daysInMonth; day++) {
        const dateCell = document.createElement("div");
        dateCell.textContent = day;
        dateCell.classList.add("date");

        const dayOfWeek = new Date(currentYear, currentMonth, day).getDay();
        const dateKey = `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

        // Gérer les week-ends et les dates complètes
        if (dayOfWeek === 5 || dayOfWeek === 6) {
            dateCell.classList.add("weekend", "occupied");
            dateCell.title = "Date occupée (week-end)";
        } else if (reservations[dateKey] && reservations[dateKey] >= 10) {
            dateCell.classList.add("occupied");
            dateCell.title = "Date complète (10 réservations atteintes)";
        } else {
            dateCell.addEventListener("click", () => selectDate(day, dateKey));
        }

        calendar.appendChild(dateCell);
    }
}

function selectDate(day, dateKey) {
    if (!reservations[dateKey]) reservations[dateKey] = 0;
    if (reservations[dateKey] >= 10) return;

    reservations[dateKey] += 1;
    selectedDate = `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
    
    document.querySelectorAll(".date").forEach(date => date.classList.remove("selected"));
    event.target.classList.add("selected");

    confirmationMessage.textContent = `Merci ! Vous avez demandé un rendez-vous pour le ${selectedDate}. Nous vous contacterons pour confirmer.`;
    
    renderCalendar();
}

// Gérer le mois précédent
document.getElementById("prevMonth").addEventListener("click", () => {
    currentMonth = currentMonth === 0 ? 11 : currentMonth - 1;
    currentYear = currentMonth === 11 ? currentYear - 1 : currentYear;
    renderCalendar(); // Rafraîchir le calendrier
});

// Gérer le mois suivant
document.getElementById("nextMonth").addEventListener("click", () => {
    currentMonth = currentMonth === 11 ? 0 : currentMonth + 1;
    currentYear = currentMonth === 0 ? currentYear + 1 : currentYear;
    renderCalendar(); // Rafraîchir le calendrier
});

renderCalendar();

// Gestion des tabs (sections du formulaire)
const tabs = document.querySelectorAll('.tab');
const sections = document.querySelectorAll('.form-section');
const nextButtons = document.querySelectorAll('.next');
const prevButtons = document.querySelectorAll('.prev');

tabs.forEach((tab, index) => {
    tab.addEventListener('click', () => {
        activateSection(index);
    });
});

nextButtons.forEach(button => {
    button.addEventListener('click', () => {
        const currentIndex = getActiveSectionIndex();
        if (currentIndex < sections.length - 1) {
            activateSection(currentIndex + 1);
        }
    });
});

prevButtons.forEach(button => {
    button.addEventListener('click', () => {
        const currentIndex = getActiveSectionIndex();
        activateSection(currentIndex - 1);
    });
});

// Fonction pour activer la section
function activateSection(index) {
    sections.forEach((section, i) => {
        section.classList.toggle('active', i === index);
        tabs[i].classList.toggle('active', i === index);
    });
}

function getActiveSectionIndex() {
    return [...sections].findIndex(section => section.classList.contains('active'));
}

// Lors de la confirmation
confirmButton.addEventListener('click', () => {
    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const phone = document.getElementById('phone').value;
    const service = document.getElementById('serviceSelect').value;
    const date = document.getElementById('dateSelect').value;

    // Vérifier si tous les champs sont remplis
    if (!name || !email || !phone || !service || !date) {
        alert("Veuillez remplir tous les champs.");
    } else {
        confirmationMessage.textContent = `Merci, ${name} ! Votre rendez-vous pour ${service} a été confirmé pour le ${date}.`;
        confirmationMessage.style.color = 'green';
        currentSection = 0; // Retourner à la première section après la confirmation
        showSection(currentSection);  // Afficher la première section
    }
});

// Initialiser l'affichage de la première section
showSection(currentSection);
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

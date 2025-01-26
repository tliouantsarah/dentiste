document.addEventListener("DOMContentLoaded", () => {
  const links = document.querySelectorAll(".load-page");  // Select all elements with class "load-page"
  const contentArea = document.getElementById("content-area");  // Select the content area to update with new page content

  // Load the "dashboard" page content when the page is first opened
  const defaultPage = "dash.html";  // Set the default page (dashboard)
  loadPage(defaultPage);  // Use the loadPage function to load the first page

  // Add event listeners for clicking on the links
  links.forEach(link => {
      link.addEventListener("click", (e) => {
          e.preventDefault();  // Prevent the default action of reloading the page

          const url = link.getAttribute("href");  // Get the URL of the page to load
          loadPage(url);  // Load the new page content
      });
  });

  // Function to load a page and update the content area
  function loadPage(url) {
      fetch(url)  // Fetch the page content from the URL
          .then(response => {
              if (!response.ok) {
                  console.error("Error loading page:", response.status, response.statusText);
                  throw new Error(`Error loading page: ${response.status} ${response.statusText}`);
              }
              return response.text();  // Return the page content as text
          })
          .then(html => {
              contentArea.innerHTML = html;  // Insert the fetched content into the content area
              initCharts();  // Initialize charts after loading the content
          })
          .catch(error => {
              console.error("Error occurred:", error);
              contentArea.innerHTML = `<p>Error loading the page. Please try again. Details: ${error.message}</p>`;  // Display an error message if something goes wrong
          });
  }

  // Function to initialize charts (e.g., charts in the "dash.html" page)
  function initCharts() {
      // Ensure the chart elements exist in the page
      const appointmentsChart = document.getElementById('appointmentsChart');
      const stockChart = document.getElementById('stockChart');

      if (appointmentsChart && stockChart) {
          // Appointments Chart (Line chart)
          const appointmentsCtx = appointmentsChart.getContext('2d');
          new Chart(appointmentsCtx, {
              type: 'line',
              data: {
                  labels: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
                  datasets: [{
                      label: 'Appointments',
                      data: [10, 12, 15, 8, 20],  // Example data
                      borderColor: '#4e73df',
                      backgroundColor: 'rgba(78, 115, 223, 0.1)',
                  }]
              },
          });

          // Stock Chart (Bar chart)
          const stockCtx = stockChart.getContext('2d');
          new Chart(stockCtx, {
              type: 'bar',
              data: {
                  labels: ['Gloves', 'Masks', 'Anesthetics', 'Dental Floss'],
                  datasets: [{
                      label: 'Remaining Stock',
                      data: [15, 10, 5, 2],  // Example data
                      backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e'],
                  }]
              },
          });
      }
  }
});

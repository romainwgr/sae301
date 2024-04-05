// JS du tableau de bord

function fetchData() {
  fetch("index.php?controller=dashboard&action=chartCategory")
    .then((response) => response.json())
    .then((data) => {
      console.log("Données reçues du serveur :", data);

      // Appeler une fonction pour construire le diagramme avec les données reçues
      buildTeachersChart(data);
    })
    .catch((error) =>
      console.error("Erreur lors de la récupération des données:", error)
    );
}
Chart.register(ChartDataLabels);

function buildTeachersChart(data) {
  const labels = data.map((entry) => entry.categorie);
  const values = data.map((entry) => entry.nombre_enseignants);

  // Dynamically generate colors for each category
  const backgroundColors = generateColors(labels.length);
  const hoverBackgroundColors = generateColors(labels.length);

  const total = values.reduce((acc, value) => acc + value, 0);

  const chartData = {
    labels: labels,
    datasets: [
      {
        data: values,
        backgroundColor: backgroundColors,
        hoverBackgroundColor: hoverBackgroundColors,
      },
    ],
  };

  const options = {
    responsive: false,
    maintainAspectRatio: false,
    plugins: {
      datalabels: {
        formatter: function (value, context) {
          const percentage = ((value / total) * 100).toFixed(2);
          return percentage + "%";
        },
        color: "white",
        display: "auto",
      },
    },
  };

  const ctx = document.getElementById("myChart2").getContext("2d");
  const myChart2 = new Chart(ctx, {
    type: "pie",
    data: chartData,
    options: options,
  });
}

// Function to generate random colors
function generateColors(numColors) {
  const colors = [];
  for (let i = 0; i < numColors; i++) {
    colors.push(getRandomColor());
  }
  return colors;
}

// Function to generate a random color
function getRandomColor() {
  const letters = "0123456789ABCDEF";
  let color = "#";
  for (let i = 0; i < 6; i++) {
    color += letters[Math.floor(Math.random() * 16)];
  }
  return color;
}

fetchData();
//3eme

// Dans votre fichier script.js

function fetchTeachersData() {
  fetch("index.php?controller=dashboard&action=chartCategoryByDepartement")
    .then((response) => response.json())
    .then((data) => {
      console.log("Données reçues du serveur :", data);

      // Appeler une fonction pour construire le diagramme avec les données reçues
      buildChart(data);
    })
    .catch((error) =>
      console.error("Erreur lors de la récupération des données :", error)
    );
}

function buildChart(data) {
  const labels = data.map((entry) => entry.categorie);
  const values = data.map((entry) => entry.nombre_enseignants);

  const chartData = {
    labels: labels,
    datasets: [
      {
        data: values,
        backgroundColor: ["#FF6384", "#36A2EB", "#FFCE56"],
        hoverBackgroundColor: ["#FF6384", "#36A2EB", "#FFCE56"],
      },
    ],
  };

  const options = {
    responsive: false,
    maintainAspectRatio: false,
    plugins: {
      datalabels: {
        formatter: (value, context) => {
          const percentage = (
            (value / values.reduce((a, b) => a + b, 0)) *
            100
          ).toFixed(2);
          return percentage + "%";
        },
        color: "white",
        display: "auto",
      },
    },
  };

  var graphique = Chart.getChart("myChart3");
  if (graphique) {
    graphique.destroy();
  }

  const ctx = document.getElementById("myChart3").getContext("2d");
  const myChart3 = new Chart(ctx, {
    type: "pie",
    data: chartData,
    options: options,
  });
}

fetchTeachersData();

//4eme

function fetchHoursData() {
  fetch("index.php?controller=dashboard&action=chartHoursbyDiscipline")
    .then((response) => response.json())
    .then((data) => {
      console.log("Données reçues du serveur :", data);
      buildBarChart(data);
    })
    .catch((error) =>
      console.error("Erreur lors de la récupération des données :", error)
    );
}

function buildBarChart(data) {
  const labels = data.map((entry) => entry.discipline);
  const values = data.map((entry) => entry.total_heures);

  const chartData = {
    labels: labels,
    datasets: [
      {
        label: "Total des heures par discipline",
        data: values,
        backgroundColor: "rgba(255, 206, 86, 0.7)",
        borderColor: "rgba(255, 206, 86, 1)",
        borderWidth: 1,
      },
    ],
  };

  const options = {
    responsive: false,
    maintainAspectRatio: false,
    scales: {
      y: {
        beginAtZero: true,
      },
    },
  };

  const ctx = document.getElementById("myBarChart").getContext("2d");
  const myBarChart = new Chart(ctx, {
    type: "bar",
    data: chartData,
    options: options,
  });
}

fetchHoursData();

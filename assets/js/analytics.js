const analyticsData = window.analyticsData;

function createProgressChart(canvasId, labels, completed, missed, labelText) {
  const canvas = document.getElementById(canvasId);

  if (!canvas) {
    return;
  }

  new Chart(canvas, {
    type: "bar",

    data: {
      labels: labels,

      datasets: [
        {
          label: "Completed",
          data: completed,
          backgroundColor: "#2563eb",
        },
        {
          label: "Missed",
          data: missed,
          backgroundColor: "#ef4444",
        },
      ],
    },

    options: {
      responsive: true,
      maintainAspectRatio: false,

      plugins: {
        legend: {
          position: "top",
        },

        title: {
          display: false,
          text: labelText,
        },
      },

      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            precision: 0,
          },
        },
      },
    },
  });
}

createProgressChart(
  "weekly-chart",
  analyticsData.weekly.labels,
  analyticsData.weekly.completed,
  analyticsData.weekly.missed,
  "Weekly Progress",
);

createProgressChart(
  "monthly-chart",
  analyticsData.monthly.labels,
  analyticsData.monthly.completed,
  analyticsData.monthly.missed,
  "Monthly Progress",
);

createProgressChart(
  "yearly-chart",
  analyticsData.yearly.labels,
  analyticsData.yearly.completed,
  analyticsData.yearly.missed,
  "Yearly Progress",
);

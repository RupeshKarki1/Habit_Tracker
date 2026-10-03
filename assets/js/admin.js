const dashboardNav = document.getElementById("admin-nav-dashboard");
const usersNav = document.getElementById("admin-nav-users");

function updateAdminNavigation() {
  dashboardNav.classList.remove("active");
  usersNav.classList.remove("active");

  if (window.location.hash === "#users") {
    usersNav.classList.add("active");
  } else {
    dashboardNav.classList.add("active");
  }
}

updateAdminNavigation();

window.addEventListener("hashchange", updateAdminNavigation);

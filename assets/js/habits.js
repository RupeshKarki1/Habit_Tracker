// Add Habit Modal

const addModal = document.getElementById("habit-modal");
const addButton = document.getElementById("open-habit-modal");
const addCloseButtons = document.querySelectorAll("[data-close-modal]");

addButton.addEventListener("click", () => {
  addModal.classList.add("is-open");
  addModal.setAttribute("aria-hidden", "false");
});

addCloseButtons.forEach((button) => {
  button.addEventListener("click", () => {
    addModal.classList.remove("is-open");
    addModal.setAttribute("aria-hidden", "true");
  });
});

// Edit Habit Modal
const editModal = document.getElementById("edit-habit-modal");
const editButtons = document.querySelectorAll(".edit-habit-button");
const editCloseButtons = document.querySelectorAll("[data-close-edit-modal]");
const editHabitId = document.getElementById("edit-habit-id");
const editForm = document.getElementById("edit-habit-form");
const editHabitName = document.getElementById("edit-habit-name");
const editHabitDescription = document.getElementById("edit-habit-description");
const editHabitCategory = document.getElementById("edit-habit-category");
const editHabitFrequency = document.getElementById("edit-habit-frequency");

editButtons.forEach((button) => {
  button.addEventListener("click", () => {
    editHabitId.value = button.dataset.habitId;
    editHabitName.value = button.dataset.name;
    editHabitDescription.value = button.dataset.description;
    editHabitCategory.value = button.dataset.category;
    editHabitFrequency.value = button.dataset.frequency;

    editModal.classList.add("is-open");
    editModal.setAttribute("aria-hidden", "false");
  });
});

editCloseButtons.forEach((button) => {
  button.addEventListener("click", () => {
    editModal.classList.remove("is-open");
    editModal.setAttribute("aria-hidden", "true");
  });
});

// Delete Habit Modal
const deleteModal = document.getElementById("delete-habit-modal");
const deleteButtons = document.querySelectorAll(".delete-habit-button");
const deleteCloseButtons = document.querySelectorAll(
  "[data-close-delete-modal]",
);
const deleteHabitId = document.getElementById("delete-habit-id");
const deleteForm = document.getElementById("delete-habit-form");

deleteButtons.forEach((button) => {
  button.addEventListener("click", () => {
    deleteHabitId.value = button.dataset.habitId;

    deleteModal.classList.add("is-open");
    deleteModal.setAttribute("aria-hidden", "false");
  });
});

deleteCloseButtons.forEach((button) => {
  button.addEventListener("click", () => {
    deleteModal.classList.remove("is-open");
    deleteModal.setAttribute("aria-hidden", "true");
  });
});

// Close open modal with Escape key
document.addEventListener("keydown", (event) => {
  if (event.key === "Escape") {
    addModal.classList.remove("is-open");
    editModal.classList.remove("is-open");
    deleteModal.classList.remove("is-open");
  }
});

// Sidebar active navigation

const dashboardNav = document.getElementById("nav-dashboard");
const habitsNav = document.getElementById("nav-habits");

function updateActiveNavigation() {
  dashboardNav.classList.remove("active");
  habitsNav.classList.remove("active");

  if (window.location.hash === "#habits") {
    habitsNav.classList.add("active");
  } else {
    dashboardNav.classList.add("active");
  }
}

updateActiveNavigation();

window.addEventListener("hashchange", updateActiveNavigation);
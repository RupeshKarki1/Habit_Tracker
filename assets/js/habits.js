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

editButtons.forEach((button) => {
  button.addEventListener("click", () => {
    editHabitId.value = button.dataset.habitId;

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

// Temporary until update backend is ready
editForm.addEventListener("submit", (event) => {
  event.preventDefault();
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

// Temporary until delete backend is ready
deleteForm.addEventListener("submit", (event) => {
  event.preventDefault();
});

// Close open modal with Escape key

document.addEventListener("keydown", (event) => {
  if (event.key === "Escape") {
    addModal.classList.remove("is-open");
    editModal.classList.remove("is-open");
    deleteModal.classList.remove("is-open");
  }
});

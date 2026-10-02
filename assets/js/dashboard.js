const habitModal = document.getElementById("habit-modal");
const openHabitModalButton = document.getElementById("open-habit-modal");
const closeHabitModalButtons = document.querySelectorAll("[data-close-modal]");
const habitForm = document.getElementById("habit-form");

function openHabitModal() {
  habitModal.classList.add("is-open");
  habitModal.setAttribute("aria-hidden", "false");
}

function closeHabitModal() {
  habitModal.classList.remove("is-open");
  habitModal.setAttribute("aria-hidden", "true");
}

openHabitModalButton.addEventListener("click", openHabitModal);

closeHabitModalButtons.forEach((button) => {
  button.addEventListener("click", closeHabitModal);
});

document.addEventListener("keydown", (event) => {
  if (event.key === "Escape" && habitModal.classList.contains("is-open")) {
    closeHabitModal();
  }
});


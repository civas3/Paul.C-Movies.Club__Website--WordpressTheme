document.addEventListener("DOMContentLoaded", () => {
  // Function to open the modal
  const openModal = (modalId) => {
    const modal = document.getElementById(modalId);
    if (modal) {
      modal.style.display = "block";
    } else {
      console.error(`Modal with ID ${modalId} not found`);
    }
  };

  // Function to close the modal
  const closeModal = (modal) => {
    modal.style.display = "none";
  };

  // Add click event listeners to all buttons with the class 'myBtn'
  const buttons = document.querySelectorAll(".myBtn");
  buttons.forEach(button => {
    const modalId = button.getAttribute("data-modal");
    button.addEventListener("click", () => openModal(modalId));
  });

  // Add click event listeners to all close buttons inside modals
  const closeButtons = document.querySelectorAll(".modal .close");
  closeButtons.forEach(closeButton => {
    closeButton.addEventListener("click", () => {
      const modal = closeButton.closest(".modal");
      closeModal(modal);
    });
  });

  // Close modal when clicking outside of it
  window.addEventListener("click", (event) => {
    const modals = document.querySelectorAll(".modal");
    modals.forEach(modal => {
      if (event.target === modal) {
        closeModal(modal);
      }
    });
  });
});
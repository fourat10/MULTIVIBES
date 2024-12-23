// Reset the form when the modal is closed
document
  .getElementById("staticBackdrop")
  .addEventListener("hidden.bs.modal", function () {
    document.getElementById("addStudentForm").reset();
  });
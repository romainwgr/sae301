document.addEventListener("DOMContentLoaded", function () {
  var formulaire = document.getElementById("formulaireTrier");
  formulaire.style.display = "none";
  var boutonAfficher = document.getElementById("afficherFormulaire");

  boutonAfficher.addEventListener("click", function (event) {
    event.stopPropagation();
    formulaire.style.display = "block";
  });

  formulaire.addEventListener("submit", function (event) {
    formulaire.style.display = "none";
  });

  document.addEventListener("click", function (event) {
    if (!formulaire.contains(event.target) && event.target !== boutonAfficher) {
      formulaire.style.display = "none";
    }
  });

  const modalContainer = document.querySelector(".modal-container");
  const ajouterForm = document.getElementById("ajouterForm");
  const modifierForm = document.getElementById("modifierForm");
  const modalTriggers = document.querySelectorAll(".modal-trigger");
  const modifierIcons = document.querySelectorAll(".fa-pen-to-square");

  modalTriggers.forEach((trigger) =>
    trigger.addEventListener("click", toggleModal)
  );

  modifierIcons.forEach((modifierIcon) =>
    modifierIcon.addEventListener("click", toggleModifierForm)
  );

  function toggleModal() {
    modalContainer.classList.toggle("active");
    // Affiche le formulaire principal (ajouterForm)
    ajouterForm.style.display = "block";
    // Cache le formulaire de modification (modifierForm)
    modifierForm.style.display = "none";
  }
});

function toggleDivs() {
  console.log("Function toggleDivs() called");

  var roleSelect = document.getElementById("role");
  var l4Div = document.querySelector(".l4");
  var l5Div = document.querySelector(".l5");

  if (roleSelect.value === "secretaire") {
    l4Div.style.display = "none";
    l5Div.style.display = "none";
  } else {
    l4Div.style.display = "block";
    l5Div.style.display = "block";
  }
}

// Assurez-vous que la fonction est appelée une fois au chargement initial de la page
toggleDivs();

function getURLParameter(name) {
  name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
  var regex = new RegExp("[\\?&]" + name + "=([^&#]*)");
  var results = regex.exec(location.search);
  return results === null
    ? ""
    : decodeURIComponent(results[1].replace(/\+/g, " "));
}

function toggleFormVisibility() {
  var nomParam = getURLParameter("nom");
  const modalContainer = document.querySelector(".modal-container");
  const ajouterForm = document.getElementById("ajouterForm");
  const modifierForm = document.getElementById("modifierForm");

  if (nomParam !== "") {
    modalContainer.classList.toggle("active");
    // Cache le formulaire principal (ajouterForm)
    ajouterForm.style.display = "none";
    // Affiche le formulaire de modification (modifierForm)
    modifierForm.style.display = "block";
  } else {
    ajouterForm.style.display = "block";
    // Affiche le formulaire de modification (modifierForm)
    modifierForm.style.display = "none";
  }
}

document.addEventListener("DOMContentLoaded", toggleFormVisibility);

const modal = document.getElementById("myModal");

window.openModal = function() {
modal.classList.remove("hidden");
}

window.closeModal = function() {
modal.classList.add("hidden");
}

window.onclick = function (event) {
if (event.target === modal) {
closeModal();
}
};

window.closeButton.onclick = function () {
closeModal();
};
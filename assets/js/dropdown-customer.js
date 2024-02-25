document.addEventListener('DOMContentLoaded', function () {
    const customerForm = document.querySelector('#customerForm'); // ID du formulaire
    const customerDropdown = "#" + customerForm.dataset.type + "_create_customer"; // ID du menu déroulant

    customerForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(customerForm);

        fetch('/quotes/customer/new', {
            method: 'POST',
            body: formData,
        })
            .then(response => response.json())
            .then(data => {
                closeModal();
                updateCustomerDropdown(data.newClientId, data.newCustomerName, customerDropdown); // Assurez-vous d'envoyer le nom du nouveau client dans la réponse JSON
            })
            .catch(error => console.error('Errorrr:', error));
    });
});

function updateCustomerDropdown(newCustomerId, newCustomerName, mycustomerDropdown) {
    const customerDropdown = document.querySelector(mycustomerDropdown); // Assurez-vous que cet ID correspond à votre menu déroulant
    const newOption = new Option(newCustomerName, newCustomerId, true, true);
    customerDropdown.appendChild(newOption);
    customerDropdown.value = newCustomerId; // Sélectionnez le nouveau client
}
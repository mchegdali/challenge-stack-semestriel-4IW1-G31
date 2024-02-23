let collection, boutonAjout, span;

window.onload = () => {
    collection = document.querySelector("#invoiceitem");
    span = collection.querySelector("#lignesItems");

    boutonAjout = document.createElement("button");
    boutonAjout.classList.add("ajout-invoiceitem", "bg-blue-800", "text-white", "font-bold", "py-2", "px-4", "rounded", "w-auto", "items-center", "flex", "justify-center", "gap-2", "text-sm");
    boutonAjout.type = "button";
    boutonAjout.innerText = "Ajouter un élément à votre devis";

    let nouveauBouton = span.append(boutonAjout);

    collection.dataset.index = collection.querySelectorAll("div").length;

    boutonAjout.addEventListener("click", function () {
        addButton(collection, nouveauBouton);
    })
}

function addButton(collection, nouveauButton) {
    let prototype = collection.dataset.prototype;

    let index = collection.dataset.index;

    prototype = prototype.replace(/__name__/g, index);

    let content = document.createElement("html");
    content.innerHTML = prototype;
    let newForm = content.querySelector("div");

    let boutonSuppr = document.createElement("button");
    boutonSuppr.type = "button";
    boutonSuppr.classList.add("bg-blue-800", "text-white", "font-bold", "py-2", "px-4", "rounded", "w-auto", "items-center", "flex", "justify-center", "gap-2", "text-sm");

    boutonSuppr.id = "delete-invoiceitem-" + index;
    boutonSuppr.innerText = "Supprimer cet élément";

    newForm.append(boutonSuppr);

    collection.dataset.index++;

    let boutonAjout = collection.querySelector(".ajout-invoiceitem");

    span.insertBefore(newForm, boutonAjout);

    boutonSuppr.addEventListener("click", function() {
        this.parentNode.remove(); 
    })
}


document.addEventListener('DOMContentLoaded', function() {
    document.body.addEventListener('change', function(event) {
        const serviceSelectRegex = /invoice_create_invoiceitems_(\d+)_service/;
        const match = serviceSelectRegex.exec(event.target.id);
        
        if (match) {
            // On récupère l'index du champ pour identifier le champ priceExcludingTax correspondant
            const index = match[1];
            const priceFieldId = `invoice_create_invoiceitems_${index}_priceExcludingTax`;
            const priceField = document.getElementById(priceFieldId);
            
            if (priceField) {
                // On récupère le prix depuis data-price de l'option sélectionnée
                const price = event.target.options[event.target.selectedIndex].dataset.price;
                priceField.value = price; // On met à jour la valeur du champ Prix HT
            }
        }
    });
});
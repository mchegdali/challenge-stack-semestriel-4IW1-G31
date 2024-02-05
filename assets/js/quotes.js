console.log("salut")
let collection, boutonAjout, span;

window.onload = () => {
    collection = document.querySelector("#quoteitem");
    span = collection.querySelector("span");

    boutonAjout = document.createElement("button");
    boutonAjout.classList.add("ajout-quoteitem", "bg-blue-800", "text-white", "font-bold", "py-2", "px-4", "rounded", "w-auto", "items-center", "flex", "justify-center", "gap-2", "text-sm");
    boutonAjout.type = "button";
    boutonAjout.innerText = "Ajouter un élément à votre devis";

    let nouveauBouton = span.append(boutonAjout);

    collection.dataset.index = collection.querySelectorAll("div").length;

    boutonAjout.addEventListener("click", function () {
        addButton(collection, nouveauBouton);
    })
}

function addButton(collection, nouveauButton) {
    console.log("salut toi");
    let prototype = collection.dataset.prototype;

    let index = collection.dataset.index;

    prototype = prototype.replace(/__name__/g, index);

    let content = document.createElement("html");
    content.innerHTML = prototype;
    let newForm = content.querySelector("div");

    let boutonSuppr = document.createElement("button");
    boutonSuppr.type = "button";
    boutonSuppr.classList.add("bg-blue-800", "text-white", "font-bold", "py-2", "px-4", "rounded", "w-auto", "items-center", "flex", "justify-center", "gap-2", "text-sm");

    boutonSuppr.id = "delete-quoteitem-" + index;
    boutonSuppr.innerText = "Supprimer cet élément";

    newForm.append(boutonSuppr);

    collection.dataset.index++;

    let boutonAjout = collection.querySelector(".ajout-quoteitem");

    span.insertBefore(newForm, boutonAjout);

    boutonSuppr.addEventListener("click", function() {
        this.parentNode.remove(); 
    })
}



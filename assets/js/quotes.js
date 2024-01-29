alert("dfefre");
console.log("gtrgtr");
let collection,
boutonAjout,
span;
window.onload = () => {
    collection = document.querySelector("#quoteitem")
    span = collection.querySelector("span")

    boutonAjout = document.createElement("button");
    boutonAjout.className = "ajout-quoteitem"
    boutonAjout.innerText = "Ajouter un service à votre devis"

    let nouveauBouton = span.append(boutonAjout)

    collection.dataset.index = collection.querySelector("input").length

    boutonAjout.addEventListener("click", function () {
        addButton(collection, nouveauBouton)
    })
}

function addButton(collection, nouveauButton) {
    let prototype = collection.dataset.prototype

    let index = collection.dataset.index

    prototype = prototype.replace(/__name__/g, index)

    let content = document.createElement("html")
    content.innerHTML = prototype
    let newForm = content.querySelector("div")

    let boutonSuppr = document.createElement("button")
    boutonSuppr.type = "button"
    boutonSuppr.id = "delete-quoteitem" + index
    boutonSuppr.innerText = "Supprimer cette ligne"

    newForm.append(boutonSuppr)

    collection.dataset.index ++;

    let boutonAjout = collection.querySelector(".ajout-quoteitem")

    span.insertBefore(newForm, boutonAjout);

    boutonSuppr.addEventListener("click", function() {
        this.previousElementSibling.parent.remove()
    })
}
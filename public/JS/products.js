document.addEventListener('DOMContentLoaded', () => {
    fetch("/products.json")
        .then((response) => response.json())
        .then((data) => {
            const productList = document.getElementById("product-list");
            let row = document.createElement("div");
            row.className = "row";
            productList.appendChild(row);
            let count = 0;
            data.forEach((product) => {
                if (count % 4 === 0 && count !== 0) {
                    row = document.createElement("div");
                    row.className = "row";
                    productList.appendChild(row);
                }
                const productDiv = document.createElement("div");
                productDiv.className = "col-md-3";
                const card = document.createElement("div");
                card.className = "card m-3";
                card.style.width = "18rem";
                productDiv.appendChild(card);
                const img = document.createElement("img");
                img.className = "card-img-top";
                img.src = product.image;
                img.alt = product.title;
                card.appendChild(img);
                const cardBody = document.createElement("div");
                cardBody.className = "card-body";
                card.appendChild(cardBody);
                const title = document.createElement("h3");
                title.className = "card-title";
                title.textContent = product.title;
                cardBody.appendChild(title);
                const description = document.createElement("p");
                description.className = "card-text";
                description.textContent = product.description;
                cardBody.appendChild(description);
                const price = document.createElement("p");
                price.textContent = product.price;
                cardBody.appendChild(price);
                const button = document.createElement("button");
                button.className = "btn btn-primary";
                button.textContent = "Agregar al carrito";
                button.onclick = () => {
                    alert(`Agregado ${product.title} al carrito`);
                };
                cardBody.appendChild(button);
                row.appendChild(productDiv);
                count++;
            });
        })
        .catch((error) => console.error("Error al cargar el JSON:", error));
});

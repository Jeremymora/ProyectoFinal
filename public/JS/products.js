document.addEventListener('DOMContentLoaded', () => {
    fetch("/api/platos")
        .then(response => response.json())
        .then(data => {
            const productList = document.getElementById("product-list");
            let row = document.createElement("div");
            row.className = "row";
            productList.appendChild(row);
            let count = 0;

            data.forEach(product => {
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
                img.alt = product.nombreDelPlato;
                card.appendChild(img);

                const cardBody = document.createElement("div");
                cardBody.className = "card-body";
                card.appendChild(cardBody);

                const title = document.createElement("h4");
                title.className = "card-title";
                title.textContent = product.nombreDelPlato;
                cardBody.appendChild(title);

                const peso = document.createElement("p");
                peso.className = "card-text";
                peso.textContent = `Peso: (${product.peso} g)`;
                cardBody.appendChild(peso);

                const price = document.createElement("p");
                price.textContent = `Precio: ${product.precio} €`;
                cardBody.appendChild(price);

                const kcal = document.createElement("p");
                kcal.textContent = `Kcal: ${product.kcal}`;
                cardBody.appendChild(kcal);

                const disponibilidad = document.createElement("p");
                disponibilidad.textContent = `Disponibilidad: ${product.disponibilidad ? 'Sí' : 'No'}`;
                cardBody.appendChild(disponibilidad);

                const button = document.createElement("button");
                button.className = "btn btn-primary";
                button.textContent = "Agregar al carrito";
                button.onclick = () => addToCart(product.id, product.nombreDelPlato);
                cardBody.appendChild(button);

                row.appendChild(productDiv);
                count++;
            });
        })
        .catch(error => console.error("Error al cargar los platos:", error));
});

function addToCart(platoId) {
    fetch('/add-to-cart', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `platoId=${platoId}&cantidad=1`
    })
        .then(response => {
            if (response.status === 401) {
                window.location.href = '/login';
            } else {
                return response.json();
            }
        })
        .then(data => {
            if (data.success) {
                showCartNotification(nombreDelPlato);
            }
        })
        .catch(error => console.error('Error al agregar al carrito:', error));
}
function showCartNotification(nombreDelPlato) {
    const notification = document.createElement('div');
    notification.className = 'cart-notification';
    notification.innerHTML = `<p>${nombreDelPlato} ha sido agregado al carrito.</p>`;
    document.body.appendChild(notification);

    setTimeout(() => {
        notification.remove();
    }, 5000);
}
function confirmarPedido() {
    fetch('/confirmar-pedido', {
        method: 'POST'
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Pedido confirmado. Revisa tu correo electrónico para más detalles.');
                window.location.href = '/carrito';
            } else {
                alert('Hubo un problema al confirmar tu pedido.');
            }
        })
        .catch(error => console.error('Error al confirmar el pedido:', error));
}
document.addEventListener("DOMContentLoaded", function () {
    const cartButtons = document.querySelectorAll(".add-to-cart");

    cartButtons.forEach(button => {
        button.addEventListener("click", function (event) {
            event.preventDefault();
            let productId = this.dataset.id;

            fetch(`cart.php?action=add&id=${productId}`, {
                method: "GET"
            }).then(response => response.text())
                .then(data => {
                    alert("Товар добавлен в корзину!");
                });
        });
    });
});

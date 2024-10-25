import "./bootstrap";
import "flowbite";

document.addEventListener("DOMContentLoaded", () => {
    const orderContainer = document.getElementById("order-container");
    const overallTotalDisplay = document.getElementById("overall-total");
    let emptyMessage = document.getElementById("empty-message");
    const modal = document.getElementById("popup-modal");
    const cancelOrderButton = document.getElementById("cancel-order");
    const cancelModal = document.getElementById("cancel-modal");
    const confirmCancelButton = document.getElementById("confirm-cancel");
    const denyCancelButton = document.getElementById("deny-cancel");
    const datetimeElement = document.getElementById("datetime");

    const confirmDeleteButton = document.querySelector(".delete-btn");
    let itemToDelete = null;

    orderContainer.addEventListener("click", (e) => {
        if (e.target.closest(".delete")) {
            itemToDelete = e.target.closest(".order-item");
            modal.classList.remove("hidden");
        }
    });


    //cancel otders
    cancelOrderButton.addEventListener("click", () => {
        cancelModal.classList.remove("hidden");
    });

    confirmCancelButton.addEventListener("click", () => {
        orderContainer.innerHTML = "";

        updateEmptyMessage();
        calculateOverallTotal();

        cancelModal.classList.add("hidden");
    });

    denyCancelButton.addEventListener("click", () => {
        cancelModal.classList.add("hidden");
    });

    confirmDeleteButton.addEventListener("click", () => {
        if (itemToDelete) {
            itemToDelete.classList.add("slide-out");

            itemToDelete.addEventListener("animationend", () => {
                itemToDelete.remove();
                itemToDelete = null;
                modal.classList.add("hidden");
                calculateOverallTotal();
                updateEmptyMessage();
            }, { once: true });
        }
    });

    //forda hide ng modal
    modal.addEventListener("click", (e) => {
        if (e.target.classList.contains("cancel") || e.target === modal) {
            modal.classList.add("hidden");
            itemToDelete = null;
        }
    });

    const updateEmptyMessage = () => {
        const hasItems = orderContainer.querySelectorAll(".order-item").length > 0;
        
        if (!hasItems) {
            if (!emptyMessage) {
                emptyMessage = document.createElement("div");
                emptyMessage.id = "empty-message";
                emptyMessage.classList.add("text-center", "text-gray-300", "h-full", "flex", "items-center", "justify-center", "flex-col", "text-xl");
                
                emptyMessage.innerHTML = `
                    <svg class="w-20 h-20 text-gray-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="m6 6 12 12m3-6a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                    <div>No orders yet</div>
                `;
                cancelOrderButton.disabled = true;
                orderContainer.appendChild(emptyMessage);
            }
        } else if (emptyMessage) {
            // Remove the empty message if there are items
            emptyMessage.remove();
            emptyMessage = null;
            cancelOrderButton.disabled = false;
        }
    };
    

    const calculateOverallTotal = () => {
        let overallTotal = 0;
        document
            .querySelectorAll(".order-item .total-price")
            .forEach((priceElem) => {
                const itemTotal = parseFloat(
                    priceElem.textContent.replace("Php ", "")
                );
                overallTotal += itemTotal;
            });
        overallTotalDisplay.textContent = overallTotal.toFixed(2);
    };

    const updateItemTotal = (orderItem, pricePerItem, quantity) => {
        const totalPriceElem = orderItem.querySelector(".total-price");
        const itemTotalPrice = pricePerItem * quantity;
        totalPriceElem.textContent = `Php ${itemTotalPrice.toFixed(2)}`;
        calculateOverallTotal();
    };

    document.querySelectorAll(".product-item").forEach((item) => {
        item.addEventListener("click", function () {
            const productId = this.getAttribute("data-product-id");
            const productName = this.getAttribute("data-product-name");
            const productImage = this.getAttribute("data-product-image");
            const productPrice = parseFloat(
                this.getAttribute("data-product-price")
            );

            updateEmptyMessage();

            // Check if the product is already in the order summary
            const existingOrderItem = orderContainer.querySelector(
                `.order-item[data-product-id="${productId}"]`
            );

            if (existingOrderItem) {
                // If the product already exists, update its quantity and total price
                const quantityDisplay =
                    existingOrderItem.querySelector(".quantity-display");
                let quantity = parseInt(quantityDisplay.textContent, 10);
                quantity += 1;
                quantityDisplay.textContent = quantity;
                updateItemTotal(existingOrderItem, productPrice, quantity);
            } else {
                // Create a new order item element
                const orderItem = document.createElement("div");
                orderItem.classList.add("order-item", "slide-in");
                orderItem.setAttribute("data-product-id", productId);
                orderItem.innerHTML = `
          <div class="order-content">
            <div class="item-image w-[60px] h-[60px] flex justify-center items-center mr-4">
                <img src="${productImage}" class="h-full">
            </div>
            <div class="item-info">
              <span class="item-name w-[12ch] overflow-hidden">${productName}</span>
              <span class="item-price"><span class="total-price">Php ${productPrice.toFixed(2)}</span></span>
            </div>
            <div class="quantity-controls">
              <button class="quantity-btn minus-btn">-</button>
              <span class="quantity-display">1</span>
              <button class="quantity-btn plus-btn">+</button>
            </div>
            <button class="delete" data-index="${productId}" data-modal-target="popup-modal"  data-modal-toggle="popup-modal">
            <svg class="w-6 h-6 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z"/>
            </svg>
          
            </button>
          </div>
          <div class="order-actions">
            <span class="item-price">Php ${productPrice.toFixed(2)}</span>
           
          </div>
           
        `;

                orderItem
                    .querySelector(".plus-btn")
                    .addEventListener("click", () => {
                        const quantityDisplay =
                            orderItem.querySelector(".quantity-display");
                        let quantity = parseInt(quantityDisplay.textContent);
                        quantity++;
                        quantityDisplay.textContent = quantity;
                        updateItemTotal(orderItem, productPrice, quantity);
                    });

                orderItem
                    .querySelector(".minus-btn")
                    .addEventListener("click", () => {
                        const quantityDisplay =
                            orderItem.querySelector(".quantity-display");
                        let quantity = parseInt(quantityDisplay.textContent);
                        if (quantity > 1) {
                            quantity--;
                            quantityDisplay.textContent = quantity;
                            updateItemTotal(orderItem, productPrice, quantity);
                        }
                    });

                orderItem
                    .querySelector(".delete")
                    .addEventListener("click", () => {
                        updateItemTotal(orderItem, productPrice, quantity);
                        updateEmptyMessage();
                    });

                // Append the item to the order container and calculate total
                orderContainer.appendChild(orderItem);
                updateEmptyMessage();

                calculateOverallTotal();
            }
        });
    });

    updateEmptyMessage();
    calculateOverallTotal();

    function updateDateTime() {
        const now = new Date();
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        const date = now.toLocaleDateString('en-US', options);
        const time = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit' });

        datetimeElement.textContent = `${date}, ${time}`;
    }

    updateDateTime();
    setInterval(updateDateTime, 1000);
});

document.querySelectorAll('[role="tab"]').forEach((tab) => {
    tab.addEventListener("click", (event) => {
        const selectedTab = event.currentTarget;
        const selectedCategory = selectedTab.getAttribute("data-tab-category");

        document.querySelectorAll('[role="tab"]').forEach((btn) => {
            btn.classList.remove(
                "text-[var(--primary)]",
                "border-[var(--primary)]"
            );
        });
        selectedTab.classList.add(
            "text-[var(--primary)]",
            "border-[var(--primary)]"
        );

        document.querySelectorAll(".product-container").forEach((container) => {
            if (
                selectedCategory === "all" ||
                container.getAttribute("data-category") === selectedCategory
            ) {
                container.style.display = "block";
            } else {
                container.style.display = "none";
            }
        });
    });
});

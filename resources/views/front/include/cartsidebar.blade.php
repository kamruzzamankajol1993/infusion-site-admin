<div class="sidebar right" id="cart-sidebar">
        <div class="sidebar-header">
            <h5>Your Cart</h5>
            <button class="close-btn" id="close-cart-sidebar" aria-label="Close cart">
                <img src="https://api.iconify.design/mdi:close.svg?color=%23ffffff" alt="Close" class="header-icon-svg-close">
            </button>
        </div>
        <div class="sidebar-body cart-sidebar-body">
            <!-- Cart Item 1 -->
            <div class="cart-item">
                <img src="https://placehold.co/100x100/3498db/ffffff?text=P1" alt="Product 1" class="cart-item-img">
                <div class="cart-item-info">
                    <h6>Generic Product Name</h6>
                    <span class="cart-item-price">$99.99</span>
                    <div class="quantity-controls">
                        <button class="quantity-btn">-</button>
                        <input type="text" class="quantity-input" value="1" readonly>
                        <button class="quantity-btn">+</button>
                    </div>
                </div>
                <button class="remove-item-btn">
                    <img src="https://api.iconify.design/mdi:delete-outline.svg?color=%23dc3545" alt="Remove" class="header-icon-svg-trash">
                </button>
            </div>
            <!-- Cart Item 2 -->
            <div class="cart-item">
                <img src="https://placehold.co/100x100/e74c3c/ffffff?text=P2" alt="Product 2" class="cart-item-img">
                <div class="cart-item-info">
                    <h6>Another Cool Product</h6>
                    <span class="cart-item-price">$75.50</span>
                    <div class="quantity-controls">
                        <button class="quantity-btn">-</button>
                        <input type="text" class="quantity-input" value="2" readonly>
                        <button class="quantity-btn">+</button>
                    </div>
                </div>
                <button class="remove-item-btn">
                    <img src="https://api.iconify.design/mdi:delete-outline.svg?color=%23dc3545" alt="Remove" class="header-icon-svg-trash">
                </button>
            </div>
        </div>
        <div class="cart-sidebar-footer">
            <div class="cart-total">
                <span>Subtotal:</span>
                <span>$250.99</span>
            </div>
            <div class="d-grid gap-2">
                <a href="cart.php" class="btn btn-primary btn-clear-cart">Cart Page</a>
                <a href="checkout.php" class="btn btn-primary btn-checkout" id="checkout-btn">Proceed to Checkout</a>
            </div>
        </div>
    </div>
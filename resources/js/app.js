// Import required dependencies
import 'bootstrap'; // Bootstrap JS
import '@popperjs/core'; // Required for Bootstrap tooltips and popovers
import axios from 'axios';

// Make axios globally available
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

document.addEventListener('DOMContentLoaded', () => {
    console.log('DOM fully loaded and parsed');

    const productsDiv = document.getElementById('products');
    const cartDiv = document.getElementById('cart');
    const ordersDiv = document.getElementById('orders');
    const placeOrderButton = document.getElementById('place-order');
    const createProductForm = document.getElementById('create-product-form');
    const productNameInput = document.getElementById('product-name');
    const productPriceInput = document.getElementById('product-price');
    const productDescriptionInput = document.getElementById('product-description');

    // Fetch and display products
    const fetchProducts = () => {
        fetch('http://127.0.0.1:8000/api/products')
            .then(response => response.json())
            .then(products => {
                console.log('Products:', products); // Debugging
                if (productsDiv) {
                    productsDiv.innerHTML = '<h2>Products</h2>';
                    products.forEach(product => {
                        const productDiv = document.createElement('div');
                        productDiv.classList.add('card', 'mb-3');
                        productDiv.innerHTML = `
                            <div class="card-body">
                                <h5 class="card-title">${product.name}</h5>
                                <p class="card-text">$${product.price}</p>
                                <p class="card-text">${product.description}</p>
                                <button class="btn btn-primary" onclick="addToCart(${product.id})">Add to Cart</button>
                            </div>
                        `;
                        productsDiv.appendChild(productDiv);
                    });
                }
            });
    };

    // Fetch and display cart
    const fetchCart = () => {
        fetch('http://127.0.0.1:8000/api/cart')
            .then(response => response.json())
            .then(cart => {
                console.log('Cart:', cart); // Debugging
                if (cartDiv) {
                    cartDiv.innerHTML = '<h2>Cart</h2>';
                    for (const [productId, quantity] of Object.entries(cart)) {
                        const cartItemDiv = document.createElement('div');
                        cartItemDiv.classList.add('card', 'mb-3');
                        cartItemDiv.innerHTML = `
                            <div class="card-body">
                                <p class="card-text">Product ID: ${productId} - Quantity: ${quantity}</p>
                                <button class="btn btn-danger" onclick="removeFromCart(${productId})">Remove from Cart</button>
                            </div>
                        `;
                        cartDiv.appendChild(cartItemDiv);
                    }
                }
            });
    };

    // Fetch and display orders
    const fetchOrders = () => {
        fetch('http://127.0.0.1:8000/api/orders')
            .then(response => response.json())
            .then(orders => {
                console.log('Orders:', orders); // Debugging
                if (ordersDiv) {
                    ordersDiv.innerHTML = '<h2>Orders</h2>';
                    orders.forEach(order => {
                        const items = Object.entries(order.items)
                            .map(([name, quantity]) => `${name}: ${quantity}`)
                            .join(', ');
                        const orderDiv = document.createElement('div');
                        orderDiv.classList.add('card', 'mb-3');
                        orderDiv.innerHTML = `
                            <div class="card-body">
                                <p class="card-text">Order ID: ${order.id} - Items: ${items}</p>
                            </div>
                        `;
                        ordersDiv.appendChild(orderDiv);
                    });
                }
            });
    };

    // Add to cart
    window.addToCart = (productId) => {
        console.log('Adding to cart:', productId); // Debugging
        fetch('http://127.0.0.1:8000/api/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ product_id: productId }),
        }).then(fetchCart);
    };

    // Remove from cart
    window.removeFromCart = (productId) => {
        console.log('Removing from cart:', productId); // Debugging
        fetch('http://127.0.0.1:8000/api/cart/remove', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ product_id: productId }),
        }).then(fetchCart);
    };

    // Place order
    if (placeOrderButton) {
        placeOrderButton.addEventListener('click', () => {
            console.log('Placing order'); // Debugging
            fetch('http://127.0.0.1:8000/api/orders', {
                method: 'POST',
            }).then(fetchOrders);
        });
    }

    // Create product
    if (createProductForm) {
        createProductForm.addEventListener('submit', (event) => {
            event.preventDefault();
            const name = productNameInput.value;
            const price = productPriceInput.value;
            const description = productDescriptionInput.value;
            console.log('Creating product:', { name, price, description }); // Debugging
            fetch('http://127.0.0.1:8000/api/products', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ name, price, description }),
            })
                .then((response) => {
                    if (!response.ok) {
                        console.error('Failed to create product:', response.statusText);
                        return;
                    }
                    return response.json();
                })
                .then((product) => {
                    if (product) {
                        console.log('Product created:', product); // Debugging
                        productNameInput.value = '';
                        productPriceInput.value = '';
                        productDescriptionInput.value = '';
                        fetchProducts();
                    }
                })
                .catch((error) => {
                    console.error('Error creating product:', error);
                });
        });
    }

    // Initial fetch of products, cart, and orders
    fetchProducts();
    fetchCart();
    fetchOrders();
});

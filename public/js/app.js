/**
 * ==========================================================================
 * Product Management System - Frontend Logic
 * ==========================================================================
 *
 * Handles all client-side functionality for the Product Management System:
 *  - AJAX form submissions
 *  - Real-time product listing
 *  - Edit / Delete product actions
 *  - UI feedback and validation
 *
 * Dependencies:
 *  - Bootstrap 5
 *  - Laravel CSRF protection (via <meta> tag)
 *  - Blade route variables defined in index.blade.php
 *
 * Author: Tanner Abraham
 * Version: 1.0.0
 * ==========================================================================
 */

/* --------------------------------------------------------------------------
 * GLOBAL VARIABLES
 * -------------------------------------------------------------------------- */

// Fetch Laravel's CSRF token for secure AJAX requests
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

/**
 * Global route endpoints are passed from Blade:
 * 
 * Example (in index.blade.php):
 * <script>
 *   const routes = {
 *     store: "{{ route('products.store') }}",
 *     list: "{{ route('products.list') }}"
 *   };
 * </script>
 */


/* --------------------------------------------------------------------------
 * INITIALIZATION
 * -------------------------------------------------------------------------- */

/**
 * Initialize the application once the DOM is fully loaded.
 * Loads existing products and sets up event listeners.
 */
document.addEventListener('DOMContentLoaded', function () {
    loadProducts();
});


/* --------------------------------------------------------------------------
 * PRODUCT FORM HANDLING (CREATE)
 * -------------------------------------------------------------------------- */

/**
 * Handles new product form submissions using AJAX (Fetch API).
 * Prevents default form behavior and dynamically updates the product list.
 */
document.getElementById('productForm').addEventListener('submit', function (e) {
    e.preventDefault();

    // Collect form input values
    const formData = {
        product_name: document.getElementById('product_name').value.trim(),
        quantity: document.getElementById('quantity').value,
        price: document.getElementById('price').value
    };

    // Send data to server via POST request
    fetch(routes.store, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify(formData)
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showMessage('Product added successfully!', 'success');
                document.getElementById('productForm').reset();
                loadProducts();
            } else {
                showMessage(data.message || 'Error adding product.', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('A network error occurred. Please try again.', 'danger');
        });
});


/* --------------------------------------------------------------------------
 * PRODUCT LIST (READ)
 * -------------------------------------------------------------------------- */

/**
 * Fetch and render all products from the backend.
 * Displays a loading spinner during data retrieval.
 */
function loadProducts() {
    const spinner = document.getElementById('loadingSpinner');
    spinner.style.display = 'block';

    fetch(routes.list, { headers: { 'Accept': 'application/json' } })
        .then(response => response.json())
        .then(products => {
            spinner.style.display = 'none';
            displayProducts(products);
        })
        .catch(error => {
            console.error('Error loading products:', error);
            spinner.style.display = 'none';
            showMessage('Failed to load products. Please refresh.', 'danger');
        });
}


/* --------------------------------------------------------------------------
 * DISPLAY PRODUCTS
 * -------------------------------------------------------------------------- */

/**
 * Renders the product table based on data retrieved from the server.
 *
 * @param {Array} products - Array of product objects
 */
function displayProducts(products) {
    const tbody = document.getElementById('productsTableBody');

    // Handle empty state
    if (!products || products.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6">
                    <div class="empty-state">
                        <i class="bi bi-inbox"></i>
                        <p>No products yet. Add your first product above!</p>
                    </div>
                </td>
            </tr>`;
        return;
    }

    // Build table rows dynamically
    let totalSum = 0;
    let html = '';

    products.forEach(product => {
        totalSum += parseFloat(product.total_value);
        const datetime = new Date(product.datetime).toLocaleString();

        html += `
            <tr>
                <td><strong>${escapeHtml(product.product_name)}</strong></td>
                <td>${parseInt(product.quantity)}</td>
                <td><span class="badge price-badge">$${parseFloat(product.price).toFixed(2)}</span></td>
                <td><small>${datetime}</small></td>
                <td><strong>$${parseFloat(product.total_value).toFixed(2)}</strong></td>
                <td>
                    <button class="btn btn-sm btn-warning btn-edit" onclick="editProduct('${product.id}')">
                        <i class="bi bi-pencil"></i> Edit
                    </button>
                    <button class="btn btn-sm btn-danger btn-delete" onclick="deleteProduct('${product.id}')">
                        <i class="bi bi-trash"></i> Delete
                    </button>
                </td>
            </tr>`;
    });

    // Append grand total row
    html += `
        <tr class="total-row">
            <td colspan="4" class="text-end"><i class="bi bi-calculator"></i> GRAND TOTAL:</td>
            <td><strong>$${totalSum.toFixed(2)}</strong></td>
            <td></td>
        </tr>`;

    tbody.innerHTML = html;
}


/* --------------------------------------------------------------------------
 * EDIT PRODUCT (UPDATE)
 * -------------------------------------------------------------------------- */

/**
 * Opens the Edit Product modal and populates fields with the selected product.
 *
 * @param {string} id - Unique product ID
 */
function editProduct(id) {
    // Fetch current list to get latest product data
    fetch(routes.list)
        .then(response => response.json())
        .then(products => {
            const product = products.find(p => p.id === id);
            if (!product) return;

            // Populate modal fields
            document.getElementById('edit_id').value = product.id;
            document.getElementById('edit_product_name').value = product.product_name;
            document.getElementById('edit_quantity').value = product.quantity;
            document.getElementById('edit_price').value = product.price;

            // Display modal
            const modal = new bootstrap.Modal(document.getElementById('editModal'));
            modal.show();
        })
        .catch(error => console.error('Error loading product for edit:', error));
}

/**
 * Saves the edited product data back to the server.
 * Sends a PUT request to update the product.
 */
function saveEdit() {
    const id = document.getElementById('edit_id').value;

    const formData = {
        product_name: document.getElementById('edit_product_name').value.trim(),
        quantity: document.getElementById('edit_quantity').value,
        price: document.getElementById('edit_price').value
    };

    fetch(`/products/${id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify(formData)
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const modal = bootstrap.Modal.getInstance(document.getElementById('editModal'));
                modal.hide();
                showMessage('Product updated successfully!', 'success');
                loadProducts();
            } else {
                showMessage(data.message || 'Error updating product.', 'danger');
            }
        })
        .catch(error => {
            console.error('Error updating product:', error);
            showMessage('Error updating product.', 'danger');
        });
}


/* --------------------------------------------------------------------------
 * DELETE PRODUCT
 * -------------------------------------------------------------------------- */

/**
 * Deletes a product after user confirmation.
 *
 * @param {string} id - Unique product ID
 */
function deleteProduct(id) {
    if (!confirm('Are you sure you want to delete this product?')) return;

    fetch(`/products/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showMessage('Product deleted successfully!', 'success');
                loadProducts();
            } else {
                showMessage(data.message || 'Error deleting product.', 'danger');
            }
        })
        .catch(error => {
            console.error('Error deleting product:', error);
            showMessage('Error deleting product.', 'danger');
        });
}


/* --------------------------------------------------------------------------
 * HELPER FUNCTIONS
 * -------------------------------------------------------------------------- */

/**
 * Displays a temporary success or error message to the user.
 *
 * @param {string} message - Message text
 * @param {string} type - Alert type (success | danger)
 */
function showMessage(message, type) {
    const messageDiv = document.getElementById('formMessage');

    messageDiv.innerHTML = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            <i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>`;

    // Auto-hide message after 3 seconds
    setTimeout(() => (messageDiv.innerHTML = ''), 3000);
}

/**
 * Escapes HTML special characters to prevent XSS.
 *
 * @param {string} text - Input text
 * @returns {string} Safe, escaped text
 */
function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, (m) => map[m]);
}

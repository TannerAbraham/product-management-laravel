<!DOCTYPE html>
<html lang="en">
<head>
    <!-- ================================
         META & PAGE CONFIGURATION
         ================================ -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Laravel CSRF Token for Secure AJAX Requests -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Product Management System</title>

    <!-- ================================
         STYLES & ICONS
         ================================ -->
    <!-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- ================================
         ROUTE VARIABLES (Used by app.js)
         ================================ -->
    <script>
        const routes = {
            store: "{{ route('products.store') }}",
            list: "{{ route('products.list') }}"
        };
    </script>
</head>

<body>
    <div class="container">
        <!-- ========================================
             HEADER / TITLE
             ======================================== -->
        <h1 class="main-title">
            <i class="bi bi-box-seam"></i> Product Management
        </h1>

        <!-- ========================================
             ADD PRODUCT FORM
             ======================================== -->
        <div class="card mb-4">
            <div class="card-header">
                <h5><i class="bi bi-plus-circle"></i> Add New Product</h5>
            </div>
            <div class="card-body">
                <form id="productForm">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="product_name" class="form-label">
                                <i class="bi bi-tag"></i> Product Name
                            </label>
                            <input type="text" class="form-control" id="product_name" placeholder="Enter product name" required>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="quantity" class="form-label">
                                <i class="bi bi-box"></i> Quantity in Stock
                            </label>
                            <input type="number" class="form-control" id="quantity" step="1" min="0" placeholder="0" required>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="price" class="form-label">
                                <i class="bi bi-currency-dollar"></i> Price per Item
                            </label>
                            <input type="number" class="form-control" id="price" step="0.01" min="0" placeholder="0.00" required>
                        </div>

                        <div class="col-md-2 mb-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-plus-lg"></i> Add
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Inline Feedback / Success Messages -->
                <div id="formMessage" class="mt-2"></div>
            </div>
        </div>

        <!-- ========================================
             PRODUCT LIST TABLE
             ======================================== -->
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-list-ul"></i> Products Inventory</h5>
            </div>
            <div class="card-body">
                <!-- Loading Spinner -->
                <div id="loadingSpinner" class="text-center py-5" style="display:none;">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-3 text-muted">Loading products...</p>
                </div>

                <!-- Products Table -->
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th><i class="bi bi-tag-fill"></i> Product Name</th>
                                <th><i class="bi bi-box-fill"></i> Quantity</th>
                                <th><i class="bi bi-currency-dollar"></i> Price</th>
                                <th><i class="bi bi-clock-fill"></i> Date Added</th>
                                <th><i class="bi bi-calculator-fill"></i> Total Value</th>
                                <th><i class="bi bi-gear-fill"></i> Actions</th>
                            </tr>
                        </thead>
                        <tbody id="productsTableBody">
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <i class="bi bi-inbox"></i>
                                        <p>No products yet. Add your first product above!</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================
         EDIT PRODUCT MODAL
         ======================================== -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-pencil-square"></i> Edit Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        <input type="hidden" id="edit_id">

                        <div class="mb-3">
                            <label for="edit_product_name" class="form-label">
                                <i class="bi bi-tag"></i> Product Name
                            </label>
                            <input type="text" class="form-control" id="edit_product_name" required>
                        </div>

                        <div class="mb-3">
                            <label for="edit_quantity" class="form-label">
                                <i class="bi bi-box"></i> Quantity
                            </label>
                            <input type="number" class="form-control" id="edit_quantity" step="1" min="0" required>
                        </div>

                        <div class="mb-3">
                            <label for="edit_price" class="form-label">
                                <i class="bi bi-currency-dollar"></i> Price
                            </label>
                            <input type="number" class="form-control" id="edit_price" step="0.01" min="0" required>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i> Cancel
                    </button>
                    <button type="button" class="btn btn-primary" onclick="saveEdit()">
                        <i class="bi bi-check-lg"></i> Save Changes
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ================================
         SCRIPTS
         ================================ -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>

# Laravel Product Management System

A modern, AJAX-powered product inventory management application built with Laravel, demonstrating RESTful API design and real-time user interactions.

---

## 🌐 Live Demo

**Try it now**: [https://products.tannerabraham.com/public/](https://products.tannerabraham.com/public/)

Experience the full functionality of the application without any installation. The live demo includes all features:
- Add new products
- Edit existing products
- Delete products
- Real-time AJAX updates
- Automatic calculations

---

## 📋 Overview

This product management system provides a complete solution for managing product inventory with a clean, modern interface and seamless user experience.

### Key Features

- ✅ **Add Products** - Create new products with name, quantity, and price
- ✅ **View Products** - Display all products in a sortable table
- ✅ **Edit Products** - Update existing product information
- ✅ **Delete Products** - Remove products from inventory
- ✅ **Real-time Updates** - All operations via AJAX (no page reloads)
- ✅ **Automatic Calculations** - Total value per product and grand total
- ✅ **Data Persistence** - JSON file storage with valid syntax
- ✅ **Modern UI** - Responsive Bootstrap 5 design with animations

---

## 🛠️ Technologies Used

### Backend
- **PHP 8.3** - Server-side programming
- **Laravel 11.x** - PHP framework
- **JSON** - Data storage format

### Frontend
- **HTML5** - Semantic markup
- **CSS3** - Modern styling with gradients and animations
- **JavaScript (ES6+)** - AJAX and DOM manipulation
- **Bootstrap 5.3** - Responsive CSS framework
- **Bootstrap Icons** - Icon library

### Development Tools
- **Composer** - PHP dependency management
- **Git** - Version control

---

## 📦 Installation

### Prerequisites

Ensure you have the following installed:
- PHP 8.1 or higher
- Composer 2.0 or higher
- Git (optional)

### Quick Start

```bash
# 1. Navigate to project directory
cd product-management

# 2. Install dependencies
composer install

# 3. Set up environment
cp .env.example .env
php artisan key:generate

# 4. Set permissions (Mac/Linux)
chmod -R 775 storage bootstrap/cache

# 5. Start development server
php artisan serve

# 6. Open browser
# Navigate to http://localhost:8000
```

### Windows Users

For step 4, instead of `chmod`, manually set folder permissions:
- Right-click `storage` and `bootstrap/cache` folders
- Select **Properties** → **Security** → **Edit**
- Grant **Full Control** permissions

---

## 📁 Project Structure

```
product-management/
├── app/
│   └── Http/
│       └── Controllers/
│           └── ProductController.php    # Handles CRUD operations
├── public/
│   ├── css/
│   │   └── style.css                    # Custom styles
│   └── js/
│       └── app.js                       # AJAX functionality
├── resources/
│   └── views/
│       └── products/
│           └── index.blade.php          # Main view
├── routes/
│   └── web.php                          # Application routes
├── storage/
│   └── app/
│       └── products.json                # Data storage (auto-created)
├── .env.example                         # Environment template
├── composer.json                        # Dependencies
└── README.md                            # Documentation
```

---

## 💡 Usage Guide

### Adding a Product

1. Fill in the form at the top of the page:
   - **Product Name**: Enter product name (e.g., "Laptop")
   - **Quantity in Stock**: Enter whole number (e.g., 10)
   - **Price per Item**: Enter price (e.g., 999.99)
2. Click the **"Add"** button
3. Product appears in the table immediately
4. Form resets automatically

### Editing a Product

1. Click the **"Edit"** button (orange) on any product row
2. Modal dialog opens with current values
3. Modify the fields as needed
4. Click **"Save Changes"**
5. Table updates instantly

### Deleting a Product

1. Click the **"Delete"** button (red) on any product row
2. Confirm the deletion in the alert dialog
3. Product is removed immediately
4. Grand total recalculates automatically

### Understanding the Table

- **Product Name**: Name of the product
- **Quantity**: Number of units in stock (integer)
- **Price**: Price per individual item
- **Date Added**: Timestamp when product was created
- **Total Value**: Automatically calculated (Quantity × Price)
- **Actions**: Edit and Delete buttons
- **Grand Total**: Sum of all product total values (bottom row)

---

## 🔧 Technical Details

### Data Storage

Products are stored in `storage/app/products.json` in the following format:

```json
[
  {
    "id": "67123abc456def",
    "product_name": "Laptop",
    "quantity": 10,
    "price": 999.99,
    "datetime": "2025-10-05T14:30:00+00:00",
    "total_value": 9999.90
  }
]
```

### API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/` | Display main page |
| GET | `/products` | Retrieve all products (JSON) |
| POST | `/products` | Create new product |
| PUT | `/products/{id}` | Update existing product |
| DELETE | `/products/{id}` | Delete product |

### Validation Rules

**Product Name:**
- Required field
- String type
- Maximum 255 characters

**Quantity:**
- Required field
- Integer (whole numbers only)
- Minimum value: 0

**Price:**
- Required field
- Numeric (decimals allowed)
- Minimum value: 0

### Security Features

- **CSRF Protection**: All form submissions include CSRF tokens
- **XSS Prevention**: User input is properly escaped before display
- **Input Validation**: Server-side validation for all data
- **Error Handling**: Graceful error messages for users

---

## 🎨 Design Features

### User Interface

- **Modern Gradient Background**: Purple gradient theme
- **Glassmorphism**: Semi-transparent cards with blur effect
- **Smooth Animations**: Fade-in effects and hover transitions
- **Responsive Design**: Works on desktop, tablet, and mobile
- **Loading States**: Spinner shown during data fetch
- **Success/Error Messages**: User feedback for all actions

### Color Scheme

- **Primary**: Purple gradient (#667eea to #764ba2)
- **Success**: Green gradient (#48bb78 to #38a169)
- **Warning**: Orange gradient (#f6ad55 to #ed8936)
- **Danger**: Red gradient (#fc8181 to #e53e3e)

---

## 🐛 Troubleshooting

### Common Issues

**"No application encryption key has been specified"**
```bash
php artisan key:generate
```

**"Class 'ProductController' not found"**
```bash
composer dump-autoload
```

**CSS or JavaScript not loading**
```bash
php artisan cache:clear
```

**Port 8000 already in use**
```bash
php artisan serve --port=8080
```

**Storage permission errors (Mac/Linux)**
```bash
chmod -R 775 storage bootstrap/cache
```

**Products not saving**
- Check that `storage/app` directory exists and is writable
- Verify `products.json` file can be created

### Debugging Tips

1. **Check Browser Console** (F12 → Console tab)
   - Look for JavaScript errors
   - Verify AJAX requests are successful

2. **Check Laravel Logs**
   ```bash
   tail -f storage/logs/laravel.log
   ```

3. **Verify Routes**
   ```bash
   php artisan route:list
   ```

4. **Clear All Cache**
   ```bash
   php artisan cache:clear
   php artisan route:clear
   php artisan config:clear
   php artisan view:clear
   ```

---

## 📊 Testing Checklist

Use this checklist to verify all functionality works correctly:

- [ ] Page loads without errors
- [ ] Form fields are visible and accept input
- [ ] Adding product works and shows success message
- [ ] Product appears in table immediately
- [ ] Quantity field only accepts whole numbers
- [ ] Price field accepts decimals
- [ ] Total value calculates correctly (Quantity × Price)
- [ ] Grand total updates correctly
- [ ] Products are sorted by date (newest first)
- [ ] Edit button opens modal with correct data
- [ ] Saving edits updates the table
- [ ] Delete button shows confirmation
- [ ] Deleting removes product from table
- [ ] Data persists after page refresh
- [ ] Responsive design works on mobile
- [ ] All AJAX operations work without page reload

---

## 📝 Requirements Met

This project fulfills all customer requirements:

### Core Requirements
- ✅ Form with Product name, Quantity in stock, Price per item
- ✅ Data saved in JSON file with valid syntax
- ✅ Data displayed in table below form
- ✅ Ordered by datetime submitted
- ✅ Columns: Product name, Quantity, Price, Datetime, Total value
- ✅ Total value calculated as (Quantity × Price)
- ✅ Last row shows sum of all total values

### Technical Requirements
- ✅ Uses PHP/Laravel
- ✅ Uses HTML
- ✅ Uses JavaScript
- ✅ Uses CSS
- ✅ Uses Twitter Bootstrap
- ✅ AJAX form submission
- ✅ AJAX data updates
- ✅ Works after extraction (with `composer install`)

### Extra Credit
- ✅ Full edit functionality with modal interface
- ✅ Delete functionality
- ✅ Modern, professional UI design

---

## 🚀 Deployment Notes

### For Production

1. Set environment to production:
   ```env
   APP_ENV=production
   APP_DEBUG=false
   ```

2. Optimize Laravel:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

3. Set proper permissions:
   ```bash
   chmod -R 755 storage bootstrap/cache
   ```

4. Use a proper web server (Apache/Nginx) instead of `php artisan serve`

### For Submission

1. Ensure all files are included
2. Verify `composer.json` and `composer.lock` are present
3. Include `.env.example` but NOT `.env`
4. Create ZIP file excluding:
   - `vendor/` folder
   - `node_modules/` folder
   - `.git/` folder
   - `.env` file

---

## 👤 Author

**Tanner Abraham**  
Trust Built, Inc.  
Date: October 2025  
Framework: Laravel 11.x  
PHP Version: 8.3

---

## 📄 License

Public

---

**Thank you for reviewing this submission!** 🎉

If you have any questions about the implementation or would like to see any specific features demonstrated, please don't hesitate to ask.

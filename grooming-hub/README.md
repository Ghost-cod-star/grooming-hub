
## 📁 PROJECT STRUCTURE

```
grooming-hub-fixed/
├── admin/                      # Admin panel (requires login)
│   ├── add_product.php        # Add new products (SECURE)
│   ├── auth_check.php         # Authentication guard
│   ├── delete_product.php     # Delete products (SECURE)
│   ├── index.php              # Admin dashboard
│   ├── login.php              # Admin login
│   ├── logout.php             # Admin logout
│   ├── orders.php             # View all orders
│   └── products.php           # Manage products
│
├── assets/
│   ├── css/
│   │   └── style.css          # Main stylesheet
│   └── images/                # Product images folder
│       └── [Place images here]
│
├── includes/
│   ├── db.php                 # Database connection
│   ├── footer.php             # Footer template
│   └── header.php             # Header template (with CSRF)
│
├── public/                     # Customer-facing pages
│   ├── about.php              # About page
│   ├── cart.php               # Shopping cart (FIXED)
│   ├── checkout.php           # Checkout page (FIXED)
│   ├── contact.php            # Contact form (working)
│   ├── index.php              # Homepage
│   ├── login.php              # User login
│   ├── logout.php             # User logout
│   ├── my_orders.php          # User order history
│   ├── place_order.php        # Order processing (FIXED)
│   ├── product.php            # Single product page
│   ├── products.php           # Product listing
│   ├── profile.php            # User profile
│   ├── register.php           # User registration
│   ├── search.php             # Product search
│   └── success.php            # Order confirmation
│
├── database.sql               # Database schema (FIXED)
└── README.md                  # This file
```

---

## 🚀 INSTALLATION INSTRUCTIONS

### 1. **Setup Requirements**
- XAMPP (or WAMP/LAMP)
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Modern web browser

### 2. **Install Files**
1. Extract this folder to `C:\xampp\htdocs\` (or your web server root)
2. Rename folder to `grooming-hub` if needed

### 3. **Setup Database**
1. Start XAMPP (Apache + MySQL)
2. Open phpMyAdmin: `http://localhost/phpmyadmin`
3. Click "Import" tab
4. Choose `database.sql` file
5. Click "Go"

The database will be created with:
- All required tables (users, products, orders, etc.)
- Sample products
- Default admin user (username: `admin`, password: `password`)
- Test user (email: `test@example.com`, password: `password123`)

### 4. **Add Product Images**
Copy the following images to `/assets/images/` folder:

**REQUIRED IMAGES (name them exactly as listed):**
- `Alpha_Beard_Oil.jpg`
- `Precision_Metal_Razor.jpg`
- `Royal_Beard_Balm.jpg`
- `Midnight_Shave_Cream.jpg`
- `Ironclad_Aftershave.jpg`
- `Razor_Kit.jpg`
- `beard_oil.jpg`
- `shaving_cream.jpg`
- `hero_dark.jpg` (for homepage hero section)

**Note:** Images should be:
- Format: JPG, PNG, or WEBP
- Size: Recommended 800x800px minimum
- File size: Under 2MB each

### 5. **Access the Website**
- **Homepage**: `http://localhost/grooming-hub/public/index.php`
- **Admin Panel**: `http://localhost/grooming-hub/admin/login.php`

---

## 🔐 DEFAULT LOGIN CREDENTIALS

### Admin Panel:
- URL: `http://localhost/grooming-hub/admin/login.php`
- Username: `admin`
- Password: `password`

### Test User Account:
- Email: `test@example.com`
- Password: `password123`

**⚠️ IMPORTANT:** Change these passwords immediately in production!

---

## 🎨 IMAGE REQUIREMENTS

### Product Images Needed:

1. **Alpha_Beard_Oil.jpg** - Beard oil bottle
2. **Precision_Metal_Razor.jpg** - Metal safety razor
3. **Royal_Beard_Balm.jpg** - Beard balm container
4. **Midnight_Shave_Cream.jpg** - Shaving cream tube/jar
5. **Ironclad_Aftershave.jpg** - Aftershave bottle
6. **Razor_Kit.jpg** - Complete razor kit set
7. **beard_oil.jpg** - Another beard oil variant
8. **shaving_cream.jpg** - Another shaving cream variant
9. **hero_dark.jpg** - Homepage hero image (grooming theme, dark background)

### Image Guidelines:
- **Aspect Ratio**: Square (1:1) or landscape (4:3)
- **Resolution**: Minimum 800x800px
- **Format**: JPG (preferred), PNG, or WEBP
- **File Size**: Under 2MB each
- **Background**: Clean, professional backgrounds
- **Style**: Dark/masculine aesthetic to match website theme

### Where to Get Images:
- Stock photos from Unsplash, Pexels (search "men's grooming", "beard oil", "shaving")
- Product photos from actual grooming product websites
- Your own product photography

---

## 📝 CONFIGURATION

### Database Settings (`includes/db.php`):
```php
$host = "localhost";
$user = "root";
$pass = "";  // Set a password in production!
$dbname = "grooming_hub";
```

### For Production:
1. Set MySQL password
2. Update `$pass` variable
3. Enable HTTPS
4. Change admin password
5. Remove test users

---

## 🛠️ COMMON ISSUES & SOLUTIONS

### Issue: "Database connection error"
**Solution**: 
- Make sure MySQL is running in XAMPP
- Check database name is `grooming_hub`
- Verify credentials in `includes/db.php`

### Issue: Images not showing
**Solution**:
- Check images are in `/assets/images/` folder
- Verify exact filenames match database entries
- Check file permissions (should be readable)

### Issue: Admin login not working
**Solution**:
- Default credentials are: `admin` / `password`
- Check if admin_users table exists in database
- Clear browser cache and cookies

### Issue: Can't add products (500 error)
**Solution**:
- Check `/assets/images/` folder exists
- Verify folder has write permissions
- Check PHP upload limits in php.ini

### Issue: Order totals showing 0
**Solution**:
- This was the original bug - it's fixed in this version!
- Make sure you're using this FIXED version, not the original

---

## 🔒 SECURITY CHECKLIST

### Before Going Live:
- [ ] Change admin password
- [ ] Set MySQL root password
- [ ] Remove test user accounts
- [ ] Enable HTTPS
- [ ] Update session cookie settings for HTTPS
- [ ] Set proper file permissions (755 for folders, 644 for files)
- [ ] Disable error display in production
- [ ] Enable error logging
- [ ] Test all forms for SQL injection
- [ ] Test file uploads with various file types
- [ ] Review all user inputs for XSS

---

## 📚 KEY FEATURES

### Customer Features:
- ✅ User registration and login
- ✅ Browse products by category
- ✅ Search products
- ✅ Add to cart with quantity selection
- ✅ Update cart quantities
- ✅ Remove items from cart
- ✅ Secure checkout
- ✅ View order history
- ✅ Update profile
- ✅ Contact form

### Admin Features:
- ✅ Secure admin login
- ✅ Dashboard with statistics
- ✅ Add products with image upload
- ✅ Manage products (edit/delete)
- ✅ View all orders
- ✅ Session timeout protection

### Technical Features:
- ✅ PDO with prepared statements
- ✅ Password hashing (bcrypt)
- ✅ CSRF protection
- ✅ Session security
- ✅ Input validation
- ✅ File upload security
- ✅ XSS protection
- ✅ Responsive design
- ✅ Clean URL structure

---

## 🎓 FOR SCHOOL PROJECT

This project demonstrates:
- **Database Design**: Normalized schema with foreign keys
- **Security**: CSRF, SQL injection prevention, secure auth
- **PHP Best Practices**: PDO, prepared statements, sessions
- **User Experience**: Cart management, order tracking
- **Admin Panel**: CRUD operations, dashboard
- **Modern Design**: Responsive CSS, clean UI

### Grading Points:
- ✅ Database connectivity
- ✅ User authentication
- ✅ CRUD operations
- ✅ Security features
- ✅ Professional design
- ✅ Working e-commerce flow
- ✅ Admin functionality

---

## 📧 SUPPORT

If you encounter any issues:
1. Check the "Common Issues" section above
2. Verify all installation steps were followed
3. Check XAMPP error logs
4. Review browser console for JavaScript errors

---

## 📄 LICENSE

This is a school project. Free to use for educational purposes.

---

## 🙏 CREDITS

- Original concept by your friend
- Security fixes and improvements by AI assistant
- CSS framework: Custom dark theme
- Icons: Unicode emoji characters

---

**Built for educational purposes. Please update security settings before any production use!**

Last Updated: January 2026

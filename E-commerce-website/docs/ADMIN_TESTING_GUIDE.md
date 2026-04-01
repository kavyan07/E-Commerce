# 🧪 Admin System Testing Guide

Follow these steps to test the complete admin functionality.

---

## 🚀 Step 1: Setup Admin User

Run this command in your terminal:
```bash
cd c:\xampp\htdocs\E-commerce-website
c:\xampp\php\php.exe setup_admin_user.php
```

**Expected Output:**
```
✅ Admin user created successfully!
📧 Email: admin@gmail.com
🔐 Password: Admin@123
```

---

## 🔑 Step 2: Login to Admin Panel

1. Open your browser and go to:
   ```
   http://localhost/E-commerce-website/admin/login
   ```

2. Enter credentials:
   - **Email**: `admin@gmail.com`
   - **Password**: `Admin@123`

3. Click **Login**

**Expected Result:** You should be redirected to the Admin Dashboard.

---

## 📊 Step 3: Verify Dashboard Stats

You should see three cards displaying:

1. **Total Orders** - Number of orders placed
2. **Total Revenue** - Sum of all order amounts (in Rs.)
3. **Total Products** - Number of products in catalog

---

## 📥 Step 4: Test CSV Import

1. On the dashboard, locate the **"Import Products (CSV)"** section
2. Click **"Download Sample CSV"** to get a template
3. Upload the sample CSV file
4. Click **"Import CSV"**

**Expected Result:** 
- Confirmation message: "Imported: X products. Failed: 0"
- Total Products count should increase

---

## 📤 Step 5: Test CSV Export

1. Click the **"Export All Products"** button
2. A CSV file should download automatically
3. Open it in Excel/Notepad to verify the data

**Expected Filename:** `products_export_2026-02-11_13-30-45.csv`

---

## 🔐 Step 6: Test Security Features

### Test 1: Deactivated Admin
```sql
UPDATE admin_users SET is_active = FALSE WHERE email = 'admin@gmail.com';
```
Try logging in → Should see "Your account has been deactivated"

### Test 2: Wrong Password
Try logging in with wrong password → Should see "Invalid email or password"

### Test 3: Session Persistence
Log in successfully, then navigate to `/admin/logout` → Should redirect to login page

---

## ✅ MVC Compliance Verification

### Check 1: No SQL in Controllers
Open `libs/Controller/Admin/Dashboard.php` and verify:
- ❌ No direct SQL queries in execute() method (All queries delegated to models/resources)

### Check 2: No HTML in Controllers
- ❌ No `echo "<html>"` statements
- ✅ All output through View layer

### Check 3: Autoloading
Test by creating a new controller:
```php
// libs/Controller/Admin/Test.php
class Controller_Admin_Test extends Controller_Abstract {
    public function execute() {
        echo "Autoload works!";
    }
}
```
Navigate to `/admin/test` → Should display "Autoload works!"

### Check 4: View toHtml() Method
Open `libs/View/Abstract.php` → Verify `toHtml()` method exists ✅

---

## 🎯 Success Criteria

✅ Admin can login with email/password  
✅ Dashboard shows accurate stats  
✅ CSV Import adds products to database  
✅ CSV Export downloads all products  
✅ Deactivated admins cannot login  
✅ MVC separation is maintained (no SQL in controllers, no HTML in models)  
✅ Autoloader works for all classes  
✅ All views have toHtml() method  

---

## 🐛 Troubleshooting

### Issue: "Admin user not found"
**Solution:** Run `setup_admin_user.php` again

### Issue: "Permission denied" for CSV upload
**Solution:** Check folder permissions:
```bash
chmod 777 c:\xampp\htdocs\E-commerce-website\public\uploads
```

### Issue: Dashboard shows 0 orders
**Solution:** Place a test order from the customer side first

### Issue: Autoloader not working
**Solution:** Check `libs/Core/Autoload.php` is being loaded in `index.php`

---

## 📞 Support

If you encounter any issues, check:
1. Database connection (`includes/db.php`)
2. .env file exists with correct credentials
3. Apache/XAMPP is running
4. PostgreSQL service is active

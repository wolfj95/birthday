# cPanel Deployment Guide

## Pre-Deployment Checklist

### 1. cPanel Setup Requirements
- [ ] cPanel hosting account with PHP 7.4+ support
- [ ] MySQL database access
- [ ] File Manager or FTP access
- [ ] SSL certificate (recommended)

### 2. Database Setup in cPanel

#### Create MySQL Database
1. **Log into cPanel** → **MySQL Databases**
2. **Create New Database**: `birthday_website` (or similar)
3. **Create MySQL User**: with secure password
4. **Add User to Database**: with ALL PRIVILEGES
5. **Note the database details**:
   - Database name: `youraccount_birthday`
   - Username: `youraccount_dbuser`
   - Password: `your_secure_password`
   - Host: `localhost`

#### Import Database Schema
1. **cPanel** → **phpMyAdmin**
2. Select your database
3. **Import** → **Choose File** → Upload `database/schema.sql`
4. Click **Go** to execute

### 3. File Upload to cPanel

#### Upload Methods
**Option A: File Manager (Recommended)**
1. **cPanel** → **File Manager**
2. Navigate to `public_html/`
3. Create folder for your site (e.g., `birthday/`)
4. Upload all files except:
   - `DATABASE_SETUP.md`
   - `CPANEL_DEPLOYMENT.md`
   - `PROJECT_OVERVIEW.md`
   - `package.json`
   - `database/` folder (after importing)

**Option B: FTP Client**
1. Use FileZilla, WinSCP, or similar
2. Connect with cPanel FTP credentials
3. Upload to `/public_html/birthday/`

#### Required File Structure on Server
```
public_html/birthday/
├── index.html
├── party.html
├── gallery.html
├── links.html
├── forum.php
├── .htaccess
├── css/
│   └── style.css
├── js/
│   ├── countdown.js
│   ├── gallery.js
│   └── forum.js
├── php/
│   ├── .env
│   ├── config.php
│   ├── rsvp.php
│   ├── forum.php
│   ├── gallery.php
│   └── links.php
├── images/
│   └── uploads/
└── data/
```

### 4. Environment Configuration

#### Create .env File
1. Copy `php/.env.example` to `php/.env`
2. Edit with your actual database credentials:
```bash
DB_HOST=localhost
DB_NAME=youraccount_birthday
DB_USER=youraccount_dbuser
DB_PASS=your_actual_password
SITE_EMAIL=your@email.com
SITE_URL=https://yourdomain.com/birthday
```

#### Update .htaccess
1. Edit `.htaccess` file
2. Update paths for your account:
   - Change `youraccount` to your actual cPanel username
   - Adjust error log path

### 5. Set File Permissions

#### Via cPanel File Manager
- **Folders**: 755 (public_html, css, js, php, images, data)
- **PHP files**: 644 (all .php files)
- **HTML/CSS/JS**: 644
- **uploads/ folder**: 755 (writable)
- **data/ folder**: 755 (writable)

#### Via SSH (if available)
```bash
find . -type d -exec chmod 755 {} \;
find . -type f -exec chmod 644 {} \;
chmod 755 images/uploads/
chmod 755 data/
```

### 6. Test Deployment

#### Basic Functionality Tests
1. **Visit homepage**: `https://yourdomain.com/birthday/`
2. **Test RSVP form**: Submit a test RSVP
3. **Test forum**: Post a test message
4. **Check database**: Verify data is saved
5. **Test all navigation links**

#### Database Connection Test
Create `test_db.php` (remove after testing):
```php
<?php
require_once 'php/config.php';
try {
    $pdo = getDatabaseConnection();
    echo "Database connection successful!";
} catch (Exception $e) {
    echo "Database connection failed: " . $e->getMessage();
}
?>
```

### 7. Security Hardening

#### Immediate Steps
1. **Remove test files** after deployment
2. **Secure .env file** (should be protected by .htaccess)
3. **Enable SSL** if not already active
4. **Update contact emails** to real addresses

#### Optional Security Enhancements
1. **Enable HTTPS redirect** in .htaccess
2. **Add CSRF protection** to forms
3. **Implement rate limiting** for form submissions
4. **Regular backups** of database and files

### 8. Domain/Subdomain Setup

#### For Subdomain (e.g., birthday.yourdomain.com)
1. **cPanel** → **Subdomains**
2. Create subdomain: `birthday`
3. Document root: `/public_html/birthday`
4. Upload files to this directory

#### For Main Domain
1. Upload files directly to `/public_html/`
2. Update paths in .htaccess accordingly

### 9. Common cPanel Issues & Solutions

#### Database Connection Issues
- **Problem**: Can't connect to database
- **Solution**: Verify database name format (`account_dbname`)
- **Check**: User has ALL PRIVILEGES on database

#### File Permission Errors
- **Problem**: "Permission denied" errors
- **Solution**: Set correct permissions (755 for folders, 644 for files)
- **Special**: uploads/ and data/ need write permissions

#### PHP Version Issues
- **Problem**: PHP errors or features not working
- **Solution**: cPanel → PHP Version → Select PHP 7.4+
- **Enable**: Required extensions (PDO, MySQL)

#### .htaccess Not Working
- **Problem**: Rules not applied
- **Solution**: Check if mod_rewrite is enabled
- **Alternative**: Contact hosting provider

### 10. Maintenance Tasks

#### Regular Backups
1. **Database**: Export via phpMyAdmin monthly
2. **Files**: Download via File Manager/FTP
3. **Automate**: Use cPanel backup features if available

#### Updates
1. **Monitor**: Check error logs regularly
2. **Update**: Database credentials if changed
3. **Clean**: Remove old uploaded files periodically

#### Performance Monitoring
1. **Check**: Error logs in cPanel
2. **Monitor**: Database size and performance
3. **Optimize**: Images and file sizes as needed

### 11. Troubleshooting Commands

#### Check PHP Version
```php
<?php echo phpversion(); ?>
```

#### Check PHP Extensions
```php
<?php phpinfo(); ?>
```

#### Database Error Logging
Check: cPanel → Error Logs → Domain Error Logs

### 12. Final Checklist

- [ ] Database created and schema imported
- [ ] All files uploaded with correct permissions
- [ ] .env file configured with real credentials
- [ ] .htaccess properly configured
- [ ] SSL certificate active
- [ ] All forms tested and working
- [ ] Error logging enabled
- [ ] Test files removed
- [ ] Real contact information updated
- [ ] Backup plan established

## Support Resources

- **cPanel Documentation**: Your hosting provider's knowledge base
- **PHP/MySQL Errors**: Check error logs in cPanel
- **File Permissions**: Use File Manager or SSH
- **Database Issues**: phpMyAdmin for direct database access

Your birthday website should now be live and fully functional on cPanel hosting!
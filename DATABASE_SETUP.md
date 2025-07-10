# Database Setup Instructions

## Prerequisites
- MySQL server (5.7+ or 8.0+)
- PHP with PDO MySQL extension
- Web server (Apache, Nginx, or PHP built-in server)

## Setup Steps

### 1. Create MySQL Database
```sql
-- Connect to MySQL as root or admin user
mysql -u root -p

-- Run the schema file
source database/schema.sql
```

Or manually:
```sql
CREATE DATABASE birthday_website;
USE birthday_website;
-- Then copy and paste the contents of database/schema.sql
```

### 2. Configure Database Connection
Edit `php/config.php` and update the database credentials:

```php
define('DB_HOST', 'localhost');     // Your MySQL host
define('DB_NAME', 'birthday_website'); // Database name
define('DB_USER', 'your_username'); // Your MySQL username
define('DB_PASS', 'your_password'); // Your MySQL password
```

### 3. Set Permissions
Make sure the web server can read the files:
```bash
chmod -R 644 *
chmod -R 755 php/ data/ images/
```

### 4. Test the Setup
1. Start your web server
2. Navigate to the website
3. Try submitting an RSVP form
4. Try posting a forum message
5. Check the forum page to see posts from the database

## Database Tables Created

### `rsvps` Table
Stores RSVP responses with:
- Name, email, phone
- Attending status (yes/no)
- Number of guests
- Dietary restrictions (JSON)
- Special message
- Timestamp and IP address

### `forum_posts` Table
Stores forum messages with:
- Author name, email, website
- Subject and message content
- Approval status
- Timestamp and IP address

### Sample Data
The schema includes sample forum posts for testing. You can:
- View them on the forum page
- Delete them after testing: `DELETE FROM forum_posts WHERE author LIKE '%@example.com'`

## Configuration Options

### Forum Approval
In `php/config.php`, set:
```php
define('REQUIRE_FORUM_APPROVAL', true);  // Require admin approval
define('REQUIRE_FORUM_APPROVAL', false); // Auto-approve posts
```

### Posts Per Page
```php
define('FORUM_POSTS_PER_PAGE', 10); // Number of posts to show
```

## Security Notes
- All user input is sanitized and validated
- Database queries use prepared statements
- IP addresses are logged for moderation
- Email validation is enforced
- CSRF protection should be added for production use

## Troubleshooting

### Connection Issues
- Check MySQL service is running
- Verify credentials in config.php
- Check PHP has PDO MySQL extension: `php -m | grep pdo_mysql`

### Permission Issues
- Ensure web server can read PHP files
- Check file permissions on data/ and images/ directories

### Missing Posts
- Check if posts need approval (REQUIRE_FORUM_APPROVAL setting)
- Verify database connection
- Check error logs for PHP/MySQL errors

## Production Considerations
- Use environment variables for database credentials
- Implement proper error logging
- Add CSRF protection
- Consider rate limiting for form submissions
- Regular database backups
- Use HTTPS in production
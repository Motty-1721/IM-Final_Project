# IM Project Setup Guide

This guide will help you run this project on your computer.

## What You Need

1. **XAMPP** (or any web server with PHP and MySQL)
   - Download from: https://www.apachefriends.org/

2. **VSCode** (recommended)
   - Download from: https://code.visualstudio.com/

## Setup Steps

### Step 1: Install XAMPP
- Download and install XAMPP
- Make sure Apache and MySQL are installed

### Step 2: Get the Project Files
- Clone or download this repository
- Put it in your `htdocs` folder (usually `C:\xampp\htdocs\`)

### Step 3: Set Up the Database
1. Start XAMPP Control Panel
2. Start **Apache** and **MySQL**
3. Open your browser and go to: `http://localhost/phpmyadmin`
4. Click on "Import" tab at the top
5. Click "Choose File" and select `database.sql` from this project
6. Click "Go" button at the bottom
7. Done! The database is now ready

### Step 4: Open the Project
Open your browser and go to:
```
http://localhost/IM/
```

That's it! The website should now work.

## If Something Goes Wrong

### Problem: "Connection failed" error
**Solution:** Check if MySQL is running in XAMPP Control Panel

### Problem: Page not loading
**Solution:** Check if Apache is running in XAMPP Control Panel

### Problem: Database not found
**Solution:** Make sure you imported the `database.sql` file in Step 3

## For Developers Using VSCode

If you want to use the PHP Server extension:

1. Install "PHP Server" extension in VSCode
2. Create a file: `.vscode/settings.json`
3. Add this inside:
```json
{
  "phpserver.phpPath": "C:\\xampp\\php\\php.exe",
  "phpserver.port": 8000
}
```
4. Right-click on `index.php` and select "PHP Server: Serve project"
5. Your site will open at `http://localhost:8000`

**Note:** Change the path if your XAMPP is installed somewhere else.

## Database Configuration

The database settings are in `config/config.php`:
- **Host:** localhost
- **Username:** root
- **Password:** (empty)
- **Database:** grilliance_db

If your XAMPP has a different password, edit the `config/config.php` file.

=======
# IM-FinalProject
>>>>>>> 947391a30dd13585edfa4139eddf7340cca2007f

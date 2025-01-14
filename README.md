## Sabon Gari Scholarship System
this is a freelancing job build for Nigerian Institute of Leather and Schience Technology (NILEST) HND final year Student 

To run your PHP code on your computer, you need to ensure that the new computer has the necessary environment to execute PHP scripts. Here’s a step-by-step guide:

### **1. Install PHP and a Web Server**
#### **Option 1: Use an All-in-One Solution**
Use tools like:
- **XAMPP**: Includes Apache, PHP, and MySQL in one package.
- **WAMP** (Windows only): Similar to XAMPP.
- **MAMP**: Designed for macOS and Windows.
- **Laragon**: Lightweight and flexible for Windows.

#### **Option 2: Install Manually**
- **Install PHP**:
  - Download PHP from the [official PHP website](https://www.php.net/).
  - Add PHP to your system's `PATH` environment variable for command-line usage.
- **Install a Web Server** (if needed):
  - Install **Apache** or **Nginx**.
  - Configure the server to work with PHP.

---

### **2. Copy Your PHP Code**
- You can Clone the entire repo or download it and unzip it to your computer.
- Place it in a directory accessible by your web server:
  - For XAMPP: Place it in the `htdocs` folder (e.g., `C:\xampp\htdocs\`).
  - For WAMP: Place it in the `www` folder.
  - For other setups: Configure the web server's document root to point to your project folder.

---

### **3. Set Up Configuration Files**
- If your project uses a `.env` file (e.g., with Laravel or other frameworks), update the configurations for the new environment, such as:
  - Database credentials.
  - Base URL or API keys.
- If you use `php.ini`, ensure that PHP extensions required by your project are enabled.

---

### **4. Set Up the Database (if applicable)**
You can find the database structure used in this project at the root of the project, download it and then
1. Export the database from the original computer:
   - Use a tool like **phpMyAdmin** or a database CLI tool to export the database as a `.sql` file.
   - Example CLI command for MySQL:
     ```bash
     mysqldump -u username -p database_name > backup.sql
     ```
2. Import the database to the new computer:
   - Use phpMyAdmin or a CLI tool to import the `.sql` file.
   - Example CLI command for MySQL:
     ```bash
     mysql -u username -p database_name < backup.sql
     ```

---

### **5. Start the Web Server**
- If using XAMPP, WAMP, or MAMP:
  - Start the server from the respective control panel.
- For manual setups:
  - Start Apache or Nginx, ensuring it's configured to serve your PHP project.

---

### **6. Access Your Application**
- Open your browser and navigate to:
  ```text
  http://localhost/your-project-folder/
  ```
- If your project is in a subfolder like `htdocs/myproject`, navigate to:
  ```text
  http://localhost/myproject/
  ```

---

### **7. Install Additional Dependencies**
If your project uses dependencies (e.g., with Composer), install them:
1. Ensure Composer is installed on the new computer.
   - Download it from [getcomposer.org](https://getcomposer.org/).
2. Navigate to your project directory in the terminal:
   ```bash
   cd path/to/your/project
   ```
3. Install dependencies:
   ```bash
   composer install
   ```

---

### **8. Test Your Application**
- Test your application to ensure it works as expected.
- Check for:
  - Missing PHP extensions.
  - Misconfigured paths or environment variables.
  - Database connection issues.

---

### **9. Troubleshoot Common Issues**
- **Missing PHP Extensions**: Install any missing extensions via `php.ini` or using the package manager.
- **File Permissions**: Ensure the project files have the correct read/write permissions.
- **Incorrect URLs**: Update the base URL in `.env` or configuration files if your project is hardcoded to use a specific domain.

---

### **10. Deploy on a Production Server (Optional)**
If you're deploying to a production server, you can use tools like:
- **Docker**: To containerize your PHP application.
- **Shared Hosting**: Upload your project to a hosting provider via FTP.
- **Cloud Platforms**: Use AWS, Azure, or similar services.

---

If evrything work smoothly on your computer, you will see something like this photos below
![Screenshot 2025-01-12 090905](https://github.com/user-attachments/assets/908c67cf-ab60-4a3b-8aa7-88a9f110ea9c)
![Screenshot 2025-01-12 091133](https://github.com/user-attachments/assets/8e7e6f88-1e76-4c7c-a130-c4fd41536442)

![Screenshot 2025-01-12 091418](https://github.com/user-attachments/assets/f22c5a40-8b8e-47fd-ac11-1a1f38d88f7e)
![Screenshot 2025-01-12 091513](https://github.com/user-attachments/assets/fda683b2-16c8-4484-a8ef-c7596ee0942c)
![Screenshot 2025-01-12 091633](https://github.com/user-attachments/assets/237c969b-d76a-4367-aba1-7489b218b37d)

![Screenshot 2025-01-12 091956](https://github.com/user-attachments/assets/d50ba4b3-88ad-4e0f-8a57-1a36975ebfed)
![Screenshot 2025-01-12 093030](https://github.com/user-attachments/assets/9b7166a2-4202-49f6-9ba3-69b75249fa47)


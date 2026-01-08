# Student Contact Management System (PHP + MySQL)

## Features
- **Student self-registration**: Students can create their own accounts.
- **Role-based Dashboards**: Separate interfaces for Students and Admins.
- **Profile Management**: Students can update their phone, email, and address.
- **Admin CRUD**: Admins can manage students and departments.
- **Export**: Admins can export student contact data to CSV.
- **Security**: Using PDO prepared statements and `password_hash`.

## Setup Instructions (XAMPP / WAMP)

1. **Copy Files**:
   Place the project folder in your local server's root directory (e.g., `C:\xampp\htdocs\Student-Contact-Management-System`).

2. **Database Setup**:
   - The system features **Automatic Setup**. Upon first visit, the database and tables will be created automatically if you have MySQL running.
   - Alternatively, you can manually import `database.sql` into **phpMyAdmin** if preferred.

3. **Configuration**:
   - The database connection settings are in `includes/config.php`.
   - Default settings are:
     - **Host**: localhost
     - **DB Name**: student_contact_db
     - **Username**: root
     - **Password**: (empty)

4. **Access the App**:
   - Open your browser and navigate to `http://localhost/Student-Contact-Management-System`.

## Default Credentials

### Admin
- **Username**: `admin`
- **Password**: `admin123`

### Student
- You can register a new student account using the **Register** link on the landing page or student login page.

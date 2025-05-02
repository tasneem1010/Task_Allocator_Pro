# TaskAllocatorPro

TaskAllocatorPro is a web-based project management system designed to streamline task allocation and project tracking. The system allows managers to create projects, assign team leaders, and track task progress efficiently.

## Features

- **User Management**
  - Multiple user roles (Manager, Project Leader, Team Member)
  - Secure login system
  - User profile management

- **Project Management**
  - Create and manage projects
  - Set project timelines and budgets
  - Assign project leaders
  - Track project progress

- **Task Management**
  - Create and assign tasks
  - Set task priorities and deadlines
  - Track task status (Pending, In Progress, Completed)
  - Assign team members to tasks
  - Monitor task progress

- **File Management**
  - Upload project-related files
  - Support for multiple file types (PDF, PNG, DOCX, JPG)
  - File size limit: 2MB per file

## System Requirements

- PHP 7.4 or higher
- MySQL/MariaDB database
- Web server (Apache recommended)
- Modern web browser

## Installation

1. **Database Setup**
   - Import the database schema from `dbschema_1220446.sql`
   - Configure database connection in `php/db.inc.php`

2. **Web Server Configuration**
   - Place the project files in your web server directory
   - Ensure the `uploads` directory is writable
   - Configure your web server to point to the project directory

3. **Initial Setup**
   - Access the system through your web browser
   - Use the default login credentials:
     - Manager: tasneem / Password1
     - Project Leader: ahmad / ahmad123
     - Team Member: leen / leen12345

## Directory Structure

```
TaskAllocatorPro/
├── php/              # PHP backend files
├── pages/            # Frontend pages
├── uploads/          # File upload directory
├── images/           # System images
├── fonts/            # Font files
├── styles.css        # Main stylesheet
├── index.php         # Entry point
└── dbschema_1220446.sql  # Database schema
```

## Usage

1. **Login**
   - Access the system through your web browser
   - Enter your credentials based on your role

2. **Project Management**
   - Managers can create new projects
   - Assign project leaders
   - Monitor project progress

3. **Task Management**
   - Create tasks within projects
   - Assign team members
   - Update task status
   - Track progress

4. **File Management**
   - Upload project-related files
   - View uploaded files
   - Manage file access

## Security Features

- Password-protected access
- Role-based permissions
- Secure file upload handling
- Input validation

## Contributing

This project is maintained by Tasneem Abu Sara (ID: 1220446). For any issues or suggestions, please contact the maintainer.

## License

This project is proprietary software. All rights reserved. 

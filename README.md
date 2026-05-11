# Mediqueue - Hospital Queue & Ticketing System

Mediqueue is a modern, web-based queue management and ticketing system designed for hospitals, clinics, and healthcare facilities. It streamlines the patient flow by providing a digital kiosk for ticket generation, a real-time display board for queue monitoring, and a comprehensive management panel for staff and administrators.

## Key Features

### 1. Patient Kiosk (Self-Service)
- **Ticket Generation**: Patients can generate tickets by selecting a department and service.
- **Priority Support**: Supports normal and priority (e.g., Senior Citizens, PWD, Pregnant) ticketing.
- **Estimated Wait Time**: Provides real-time feedback on how many people are ahead in the queue.
- **Patient Information**: Optional name entry for personalized service.

### 2. Live Display Board
- **Real-Time Updates**: Automatically refreshes to show the current ticket being served.
- **Counter Information**: Displays which counter is serving which ticket number.
- **Waiting List**: Shows a list of upcoming tickets to keep patients informed.
- **Recent Activity**: Displays recently served tickets for reference.

### 3. Staff Queue Management
- **Call Next**: Efficiently call the next patient in the queue based on priority.
- **Recall**: Re-call a patient if they didn't show up initially.
- **Skip**: Skip a ticket if the patient is unavailable.
- **Complete**: Mark a patient as served once the consultation/service is finished.
- **Department Filtering**: Staff can focus on their assigned department.

### 4. Admin Dashboard & Management
- **Real-Time Stats**: View daily statistics including total tickets, served, skipped, and waiting.
- **Analytics Charts**: Visual representation of queue volume and service trends.
- **Department Management**: CRUD operations for hospital departments.
- **Service Management**: Define specific services offered by each department.
- **Counter Management**: Configure physical counters/stations.
- **User Management**: Role-based access control (Admin vs. Staff).
- **Reports**: Generate and export queue performance reports.

##  Technology Stack
- **Backend**: PHP 8.1+ (CodeIgniter 4.7.2)
- **Database**: MariaDB / MySQL
- **Frontend**: Bootstrap 5, jQuery, CSS3
- **Icons**: FontAwesome 6

##  System Functions by Role

### Administrator
- Full access to the Dashboard and Analytics.
- Manage all system entities (Users, Departments, Services, Counters).
- Override and manage any department's queue.
- Generate system-wide reports.

### Staff
- Access to the Queue Management panel for their assigned department.
- Call, skip, recall, and complete tickets.
- View real-time department stats.

### Patient (Public)
- Access the Kiosk to get a ticket.
- View the Display Board for queue status.

## Installation & Setup

1. **Clone the repository**:
   ```bash
   git clone <repository-url>
   cd Mediqueue
   ```

2. **Install Dependencies**:
   ```bash
   composer install
   ```

3. **Environment Setup**:
   - Copy `env` to `.env`.
   - Update `database.default.hostname`, `database.default.database`, `database.default.username`, and `database.default.password`.
   - Set `app.baseURL` to your local development URL.

4. **Database Migration & Seeding**:
   ```bash
   php spark migrate
   php spark db:seed MainSeeder
   ```

5. **Run the Application**:
   ```bash
   php spark serve
   ```

## Security
- CSRF Protection enabled.
- Role-based access control (RBAC).
- Password hashing using PHP `password_hash()`.
- Filter-based authentication for protected routes.




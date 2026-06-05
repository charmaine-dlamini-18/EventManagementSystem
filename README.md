# Event Management System (EMS)

A role-based web application for creating, managing, and registering for events. Built with Laravel 12, this platform supports three user roles — **Admin**, **Organizer**, and **Attendee** — each with distinct permissions.

---

## 📖 Project Overview

The Event Management System (EMS) is designed to streamline the process of organizing and attending events. Organizers can create and manage events, attendees can browse and register for events, and administrators can oversee users, events, and registrations across the system.

---

## ✨ Features

### 👨‍💼 Admin
- Manage users (Create, Read, Update, Delete)
- View all events in the system
- Access system statistics dashboard
- Create administrator accounts

### 🎤 Organizer
- Create, edit, and delete events
- Publish or save events as drafts
- View registrations for owned events
- Approve or decline registrations
- Access event statistics dashboard
- Receive notifications when attendees register

### 👥 Attendee
- Browse published events
- Register for events
- View registration status
- Cancel registrations
- Receive email notifications
- Access personal dashboard
- Update profile

---

## 🛠️ Technologies Used

| Technology | Version | Purpose |
|------------|----------|---------|
| PHP | ^8.3 | Server-side programming language |
| Laravel | ^12 | Backend framework |
| Laravel Breeze | Latest | Authentication and user management |
| Blade Template Engine | Included with Laravel 12 | Server-side rendering |
| Tailwind CSS | ^3.4 | Frontend styling |
| Alpine.js | ^3.15 | Frontend interactivity |
| MySQL | 8.x or later | Database management system |
| Node.js | Latest LTS | JavaScript runtime environment |
| npm | Latest | Package management |
| Vite | ^8.0 | Asset bundling and development server |
| SMTP / Laravel Mail Services | Included with Laravel | Email notifications and mail delivery |

---

## 🚀 Installation & Setup

### Prerequisites

Make sure the following are installed:

- PHP ^8.3
- Composer
- Node.js and npm
- MySQL Server
- XAMPP

### Step 1: Start XAMPP

Before running the project, start:

- Apache
- MySQL

from the XAMPP Control Panel.

### Step 2: Clone the Repository

Open VS Code and clone the repository:

```bash
git clone <repository-url>
cd EMS
```

### Step 3: Install PHP Dependencies

```bash
composer install
```

### Step 4: Create the Environment File

```bash
copy .env.example .env
```
### Step 6: Configure the Database

Open the `.env` file and update the database settings:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=event_management_system
DB_USERNAME=root
DB_PASSWORD=
```

Create the database in phpMyAdmin and then run:

```bash
php artisan migrate --seed
```

### Step 7: Install Frontend Dependencies

```bash
npm install
```

### Step 8: Start Vite

```bash
npm run dev
```

### Step 9: Start the Laravel Server

Open a **new terminal** and run:

```bash
php artisan serve
```

You should see something similar to:

```text
http://127.0.0.1:8000
```

Open the URL in your browser.

> ⚠️ Make sure Apache and MySQL are running in XAMPP before opening the application.

---

## 🔑 Demo Accounts

You can use these demo accounts to login

| Role | Email | Password |
|--------|---------|----------|
| Admin | admin@example.com | password |
| Organizer | lwandle@example.com | password |
| Attendee | lisa@example.com | password |


---
## Usage Guide

### User Registration

1. Navigate to `/register`.
2. Fill in your **name**, **email**, **password**, and select a **role** (`attendee` or `organizer`).
3. Submit the form to create your account.
4. Log in at `/login` with your credentials.

> Admin accounts can only be created by an existing admin via the **Admin Panel** (`/users/create`).


### Posting & Managing Events (Organizer)

1. Log in as an **organizer**.
2. Go to **Events** → **Create Event** (`/events/create`).
3. Fill in the event details:
   - **Title**, **Description**, **Location**
   - **Start Date / End Date**
   - **Capacity** (leave blank for unlimited)
   - **Status** (`draft` or `published`)
4. Submit to create the event.
5. From the event list (`/events`), you can **edit**, **delete**, or **view** registrations for your events.
6. Approve or decline pending registrations from the event details page.

### Registering for Events (Attendee)

1. Log in as an **attendee**.
2. Browse published events on the **Events** page (`/events`).
3. Click **Register** on any event with available capacity.
4. Your registration status will show as **Pending** until the organizer approves it.
5. Once approved, the status changes to **Confirmed** — you'll receive an email notification.
6. You can cancel your own registration at any time.

### Administrative Functions (Admin)

1. Log in as **admin** (`admin@example.com` / `password`).
2. **Dashboard** (`/dashboard`) — view total events and users.
3. **Users** (`/users`) — view, create, edit, and delete user accounts.
4. **Events** (`/events`) — view all events across all organizers.
5. **Registrations** (`/registrations`) — view all registrations system-wide.

---

## Project Structure

```
app/
├── Http/Controllers/     # Controllers (CMS suffix)
│   ├── Auth/             # Authentication controllers
│   ├── DashboardControllerCMS.php
│   ├── EventControllerCMS.php
│   ├── ProfileControllerCMS.php
│   ├── RegistrationControllerCMS.php
│   └── UserControllerCMS.php
├── Http/Middleware/       # RoleMiddleware, LogRequestsMiddleware
├── Mail/                  # Queued email notifications
├── Models/                # EventCMS, RegistrationCMS, UserCMS
├── Observers/             # EventObserver, RegistrationObserver
├── Policies/              # EventPolicyCMS, RegistrationPolicyCMS
├── Rules/                 # FutureDate, ValidEventStatus, ValidRole
└── View/Components/       # Blade components
database/
├── migrations/            # Table definitions
└── seeders/               # Demo data
resources/views/           # Blade templates
routes/web.php             # Web routes
routes/auth.php            # Auth routes
tests/                     # Pest PHP tests
```

---

## 🔒 Security & Authorization

The application implements:

- Laravel Authentication
- Role-Based Access Control (RBAC)
- Policies for authorization
- Custom validation rules
- CSRF protection
- Secure password hashing

---

## 👨‍💻 Development Team

Developed as part of an academic software development project.

### Team Members

- Ceza Sabelo
- Dlamini Charmaine
- Kabala Marc

---

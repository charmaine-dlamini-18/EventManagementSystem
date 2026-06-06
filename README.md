# Event Management System (EMS)

A role-based web application for creating, managing, and registering for events. Built with Laravel 12, this platform supports three user roles — **Admin**, **Organizer**, and **Attendee** — each with distinct permissions.

---

## 📖 Project Overview

The Event Management System (EMS) is designed to streamline the process of organizing and attending events. Organizers can create and manage events, attendees can browse and register for events, and administrators can oversee users, events across the system.

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
- Laravel Herd
- Git
- PHP ^8.3
- Composer
- Node.js and npm
- MySQL Server
- XAMPP

## Option 1: Open the Project Using VS Code

### Step 1: Create a Project Folder

Create a folder anywhere on your computer where you would like to store the project.

Example:

```text
C:\Projects
```

### Step 2: Clone the Repository

1. Open **VS Code**.
2. Select **Clone Git Repository** from the welcome screen or open the Command Palette (`Ctrl + Shift + P`) and search for **Git: Clone**.
3. Paste the repository URL.
4. Select the folder you created in Step 1 as the destination.
5. Once cloning is complete, click **Open** when prompted.

### Step 3: Open the Integrated Terminal

In VS Code, open a terminal:

```text
Terminal → New Terminal
```

### Step 4: Install PHP Dependencies

```bash
composer install
```

### Step 5: Create the Environment File

```bash
copy .env.example .env
```

### Step 6: Generate the Application Key

```bash
php artisan key:generate
```

### Step 7: Configure the Database

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

### Step 8: Install Frontend Dependencies

```bash
npm install
```

### Step 9: Start Vite

```bash
npm run dev
```

### Step 10: Start the Laravel Development Server

Open a new terminal and run:

```bash
php artisan serve
```

Open the generated URL in your browser.

Example:

```text
http://127.0.0.1:8000
```
> ⚠️ Make sure Apache and MySQL are running in XAMPP before opening the application.

---

## 🚀 Installation & Setup

### Prerequisites

Make sure the following are installed:

- Laravel Herd
- Git
- PHP ^8.3
- Composer
- Node.js and npm
- MySQL Server

---

## Option 1: Open the Project Using VS Code

### Step 1: Create a Project Folder

Create a folder anywhere on your computer where you would like to store the project.

Example:

```text
C:\Projects
```

### Step 2: Clone the Repository

1. Open **VS Code**.
2. Select **Clone Git Repository** from the welcome screen or open the Command Palette (`Ctrl + Shift + P`) and search for **Git: Clone**.
3. Paste the repository URL.
4. Select the folder you created in Step 1 as the destination.
5. Once cloning is complete, click **Open** when prompted.

### Step 3: Open the Integrated Terminal

In VS Code, open a terminal:

```text
Terminal → New Terminal
```

### Step 4: Install PHP Dependencies

```bash
composer install
```

### Step 5: Create the Environment File

```bash
copy .env.example .env
```

### Step 6: Generate the Application Key

```bash
php artisan key:generate
```

### Step 7: Configure the Database

Open the `.env` file and update the database settings:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=event_management_system
DB_USERNAME=root
DB_PASSWORD=
```

Create the database in MySQL and run:

```bash
php artisan migrate --seed
```

### Step 8: Install Frontend Dependencies

```bash
npm install
```

### Step 9: Start Vite

```bash
npm run dev
```

### Step 10: Start the Laravel Development Server

Open a new terminal and run:

```bash
php artisan serve
```

Open the generated URL in your browser.

Example:

```text
http://127.0.0.1:8000
```

---

## Option 2: Open the Project Using Laravel Herd

### Step 1: Create a Project Folder

Create a folder anywhere on your computer where you would like to store the project.

### Step 2: Clone the Repository

Open Command Prompt inside the folder and run:

```bash
git clone <repository-url>
```

### Step 3: Open Laravel Herd

Launch Laravel Herd and ensure all services are running.

### Step 4: Add the Project to Herd

1. Click **Sites** in the left sidebar.
2. Click **Add Site**.
3. Select **Link Existing Project**.
4. Click **Next**.
5. Browse to the cloned **EMS** project folder.
6. Click **Next** and complete the setup.

### Step 5: Open the Project Terminal

In Herd, locate the EMS site and click the **Terminal** button on the right-hand side.

### Step 6: Install PHP Dependencies

```bash
composer install
```

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
2. Fill in your **name**, **email**, **password**, and select a **role** as an (`attendee` or `organizer`).
3. Submit the form to create your account.
4. It will direct you to the login page.
5. Log in at `/login` with your credentials.

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

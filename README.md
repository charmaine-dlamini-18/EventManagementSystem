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
- View all registrations
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

---

## Technologies Used

| Technology | Version |
|---|---|
| **PHP** | ^8.3 |
| **Laravel** | ^12 |
| **MySQL** | — |
| **Node.js / npm** | — |
| **Vite** | ^8.0 |
| **Tailwind CSS** | ^3.4 |
| **Alpine.js** | ^3.15 |
| **Laravel Breeze** (Blade + Alpine) | — |
| **Pest PHP** (testing) | — |

---

## 🚀 Installation & Setup

### Prerequisites

Make sure the following are installed:

- PHP ^8.4
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
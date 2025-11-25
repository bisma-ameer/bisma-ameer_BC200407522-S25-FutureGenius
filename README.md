# KIDICODE Learning Management System

---

## Project Overview
KIDICODE is a purpose-built **Learning Management System (LMS)** and web application for delivering STEM and AI coding education to children in Pakistan. The platform supports four primary roles — **Admin, Instructor, Student, Parent** — each with dedicated dashboards and role-specific capabilities. The prototype demonstrates a responsive frontend, secure backend services, role-based access control, course and assignment workflows, and foundations for payments, analytics, and content publishing. The system is designed to be scalable, secure, and extensible for future production deployment.

---

## Setup Instructions
Follow these steps to run the project locally. These instructions assume a PHP/Laravel environment but include notes for the prototype PHP components.

### Prerequisites
- **PHP 8.x**, **Composer**, **MySQL**, **Node.js + npm**  
- Optional: **Redis**, **Memcached**, **Mailhog** for local testing

### Install and Configure
1. **Clone repository**
```bash
git clone <repo-url>
cd <project-folder>
```

2. **Install dependencies**
```bash
composer install
npm install
npm run dev
```

3. **Environment**
- Copy `.env.example` to `.env` and update values. Use the provided environment block below for local development.

4. **Database**
- Create database named **`kidicode_lms`** or import the provided SQL file `kidicode_lms.sql`.
```bash
# If using Laravel migrations
php artisan migrate
php artisan db:seed
```

5. **Application keys and storage**
```bash
php artisan key:generate
php artisan storage:link
```

6. **Run application**
```bash
php artisan serve
# or use Valet / Homestead / Docker as preferred
```

---

## Implemented Features
The prototype implements core LMS functionality and demonstrates the architecture for further extension.

- **User Management**
  - Roles: **admin**, **instructor**, **student**, **parent**
  - Secure authentication and session handling
  - Role-based access control for dashboards and routes

- **Dashboards**
  - **Admin**: user and content management
  - **Instructor**: course management, assignments, analytics
  - **Student**: course access, assignments, submissions
  - **Parent**: view child progress, add child accounts

- **Course and Assignment Workflows**
  - Course creation and listing
  - Assignment creation, submission tracking, and downloads
  - Enrollment counts and per-course student metrics

- **Analytics**
  - Instructor analytics: courses count, total students, new students (last 30 days), assignments due, average completion
  - Data pulled from `users`, `courses`, `enrollments`, `tasks`, and `assignment_submissions` tables

- **Frontend**
  - Responsive UI built with **Bootstrap**
  - Clean, role-specific interfaces and metric cards

- **Security**
  - Password hashing and prepared statements (PDO) or Laravel bcrypt/Eloquent
  - Session management configured via environment

---

## Database and Environment
**Database name:** `kidicode_lms`  
**SQL file:** `kidicode_lms.sql`

### Core tables
- **users** — all users with `role` enum (`admin`, `instructor`, `student`, `parent`) and `created_at` timestamp  
- **courses** — course metadata with `instructor_id`, `level`, `is_paid`, timestamps  
- **enrollments** — mapping of `student_id` to `course_id`  
- **tasks** — assignments/quizzes linked to courses with `type`, `status`, `due_date`  
- **assignment_submissions** — student submissions for tasks  
- **parents** / **parent_students** — optional mapping for parent-child relations (prototype may store parent-child relation in `users` only)

### Recommended environment variables
Use the following `.env` values for local development. Replace secrets for production.

```env
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:Ix2ykSPuPS8fXx/uAWqiF/dqNzRoSr6IvNQpY7jEJ2c=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kidicode_lms
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=database
SESSION_LIFETIME=120

CACHE_STORE=database
QUEUE_CONNECTION=database

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

STRIPE_KEY=pk_test_your_stripe_public_key_here
STRIPE_SECRET=sk_test_your_stripe_secret_key_here
```

---

## Reflection and Planned Future Work
**Reflection**  
This prototype consolidates full‑stack skills across backend, frontend, and database design. Key learnings include translating LMS requirements into modular services, implementing secure role-based authentication, and designing data models that support analytics and reporting. The project balances rapid prototyping with maintainable code and security best practices.

**Planned Future Work**
1. Complete course management with rich editors and media uploads.  
2. Interactive assessments including auto-grading and coding sandboxes.  
3. Gamification features: points, badges, leaderboards.  
4. Payment and subscription management with Stripe integration.  
5. SCORM/xAPI support and advanced learning analytics.  
6. Containerized deployment, CI/CD pipelines, and cloud hosting.  
7. RESTful API and mobile clients for broader access.  
8. Accessibility improvements and localization.

---

## Quick Notes and Best Practices
- **Back up** `kidicode_lms.sql` before schema changes.  
- Use **migrations and seeders** for reproducible environments.  
- Keep **sensitive keys** out of version control; use environment variables and secret managers in production.  
- Add **automated tests** before major refactors.  
- Set `APP_DEBUG=false` in production and configure proper logging and monitoring.


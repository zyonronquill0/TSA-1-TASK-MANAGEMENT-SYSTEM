# Tasks for Today Management System

An IT0049 Technical Summative Assessment 1 project built with CodeIgniter 4, PHP, and MySQL. Developed by **Zyon Ronquillo**.

The Welcome page shows only tasks scheduled for the current date in **Asia/Manila**. The Task List shows all tasks in ascending date order. Profile displays one demo user; About identifies the developer.

## Requirements covered

| Requirement | Implementation |
| --- | --- |
| Welcome `/` | `Home::index()` queries `TaskModel::forDate()` using today's Philippine date |
| Task List `/tasks` | `Tasks::index()` queries `TaskModel::allByDate()`; date ASC, ID ASC |
| Profile `/profile` | `Profile::index()` reads the demo record through `UserModel` |
| About `/about` | Static developer information; no database query |
| Required database schema | `app/Database/Migrations/2026-10-10-000001_CreateTasksAndUsers.php` |
| At least 8 tasks over 3 dates | `DemoSeeder`: 2 yesterday, 4 today, 2 tomorrow |
| Exactly 1 demo user | `DemoSeeder`; username `zyon_dev` |
| Database submission | Dated `database/tasks_db.sql`, fresh-import `database/hosting_seed.sql`, migration, and seeder |

The project implements the activity's four read-only pages. Task creation/editing, login, and registration are outside the requested activity.


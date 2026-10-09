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

## What to do next on this computer

The local `.env` is configured for your existing `tasks_db` database. The database has eight everyday sample tasks (assignments, jogging, movies, and chores) and one demo user.

1. In **XAMPP Control Panel**, keep **MySQL** running. Apache is only needed if you want to use local phpMyAdmin.
2. Open this folder in **VS Code** and choose **Terminal → New Terminal**.
3. Run:

   ```powershell
   php spark serve
   ```

4. Open **http://localhost:8080/**. Keep the terminal running. Stop the server with **Ctrl+C** when finished. If a server is already running on port 8080, use that server instead of starting a second one.
5. Check Welcome, Task List, Profile, and About using the sidebar.

Use `C:\php\php.exe` if `php` is not recognized:

```powershell
& 'C:\php\php.exe' spark serve
```

Your XAMPP installation has an older PHP version, so run the app using the newer PHP CLI above. Opening the project with VS Code **Live Server** will not execute PHP.

## Fresh setup after cloning

Prerequisites: PHP **8.2 or later**, Composer, MySQL/MariaDB, and PHP extensions `intl`, `mbstring`, and `mysqli` (with `mysqlnd`). The exact dependency versions are recorded in `composer.lock`.

```powershell
composer install
Copy-Item .env.example .env
```

Edit `.env` to match your database credentials. The sample values are for local XAMPP only:

```dotenv
CI_ENVIRONMENT = development
app.baseURL = 'http://localhost:8080/'
app.indexPage = ''
app.appTimezone = 'Asia/Manila'
database.default.hostname = 127.0.0.1
database.default.database = tasks_db
database.default.username = root
database.default.password = ''
database.default.DBDriver = MySQLi
database.default.port = 3306
```

Create an empty `tasks_db` database in phpMyAdmin, or run:

```sql
CREATE DATABASE tasks_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
```

Then run:

```powershell
php spark migrate
php spark db:seed DemoSeeder
php spark serve
```

The migration keeps existing tables if they are present; it assumes they have the supplied schema. The seeder populates each table only when it is empty, so repeated setup does not overwrite records or create duplicates. Do not run migration rollback/refresh commands on data you want to keep: those commands drop the application tables.

Alternatively, import **one** of the SQL files into an empty database. `hosting_seed.sql` uses dates relative to the day of import. `tasks_db.sql` preserves the original October 9–11, 2026 records. You do not need to import SQL when using migrations and the seeder.

## How the MVC code works

```text
Browser request → Routes.php → Controller → Model → MySQL
                                    ↓
                             View + shared layout
                                    ↓
                               HTML response
```

- **Models** contain queries. `TaskModel::forDate()` uses `where('task_date', $date)`. `allByDate()` has no date filter and orders the result by `task_date`, then `id`.
- **Controllers** choose data and pass it to a view. `Home` calculates today's date with the app's timezone. It does not use a hard-coded date.
- **Views** render records and escape text with `esc()`. Both task pages use the same table partial. The pages use simple red styling with local CSS and no CDN dependencies.
- **Developer details** are in `app/Config/Developer.php`. The developer's name is separate from the demo user's full name stored in MySQL. Optional section and professor fields are hidden until filled in.

```text
app/
  Config/Developer.php
  Config/Routes.php
  Controllers/{Home,Tasks,Profile,About}.php
  Models/{TaskModel,UserModel}.php
  Database/Migrations/2026-10-10-000001_CreateTasksAndUsers.php
  Database/Seeds/DemoSeeder.php
  Helpers/tasks_helper.php
  Views/layouts/main.php
  Views/pages/{welcome,tasks,profile,about}.php
  Views/partials/task_table.php
public/assets/css/app.css
database/{tasks_db,hosting_seed}.sql
scripts/prepare-hosting.ps1
tests/feature/TaskPagesTest.php
```

## Verify before submission

On the October 10, 2026 dataset, Welcome displays **4** tasks and Task List displays **8** tasks spanning October 9–11. On a fresh seed/import, the dates are relative to the day you run it.

1. Verify Welcome excludes yesterday's and tomorrow's tasks, including when their status is pending.
2. Verify Task List includes all eight records, with the earliest dates first.
3. Verify Profile displays the demo username, full name, email, and creation date.
4. Verify About displays **Zyon Ronquillo**.
5. Check navigation at desktop and phone widths.

Automated tests use a separate **in-memory SQLite** database, never your MySQL records:

```powershell
# Works with this computer's PHP without changing php.ini:
php -d extension=sqlite3 vendor/bin/phpunit --no-coverage

# If sqlite3 is already enabled:
composer test -- --no-coverage
```

The suite covers the sample-data requirements, repeated seeding, the Manila/UTC date boundary, date filtering regardless of status, full-list ordering, profile/about content, empty states, and HTML escaping.

### When today's list becomes empty

Task dates remain the dates stored in the database. An empty Welcome page on a later day is correct; it must not silently display older tasks. Rerunning the seeder preserves existing records and will not move their dates.

For a later classroom demonstration, you can deliberately move the **sample dataset** to yesterday/today/tomorrow. In phpMyAdmin, select the project database, open **SQL**, and run the following only if you want to reschedule those demo records. It preserves the number of records and spacing between their dates:

```sql
SET @today = DATE(DATE_ADD(UTC_TIMESTAMP(), INTERVAL 8 HOUR));
SET @first_date = (SELECT MIN(task_date) FROM tasks);
SET @shift_days = DATEDIFF(DATE_SUB(@today, INTERVAL 1 DAY), @first_date);
UPDATE tasks SET task_date = DATE_ADD(task_date, INTERVAL @shift_days DAY);
```

Refresh the page after running it. For a brand-new hosting database, importing `hosting_seed.sql` already gives you current dates.

## Upload the raw project to GitHub

1. Sign in to GitHub and create a new repository named `tasks-management-system`. Choose **Public**, unless your professor requests otherwise. Leave the automatic README, license, and `.gitignore` options unchecked because this folder already contains them.
2. In VS Code's terminal, run:

   ```powershell
   git init
   git add .
   git status
   git commit -m "Complete Tasks for Today CodeIgniter activity"
   git branch -M main
   git remote add origin https://github.com/YOUR-USERNAME/tasks-management-system.git
   git push -u origin main
   ```

   Replace `YOUR-USERNAME` with your actual GitHub username. Complete the browser sign-in if Git asks. If Git asks for an author identity, configure your own name and GitHub email before committing.
3. Check the repository online. It should contain `app`, `public`, `database`, `tests`, `scripts`, `.env.example`, `composer.json`, `composer.lock`, `spark`, and this README.

`.gitignore` excludes your real `.env`, `vendor`, generated hosting folders in `dist`, and test/preview output in `build`. Keep those excluded. The repository contains raw source, not just an uploaded ZIP. Someone cloning it restores dependencies with `composer install`.

## Put the app online

You need a PHP/MySQL host for the live link. GitHub hosts the source repository; [GitHub Pages serves static sites](https://docs.github.com/en/pages/getting-started-with-github-pages/what-is-github-pages) and cannot run this PHP application.

One free option for this activity is [InfinityFree](https://www.infinityfree.com/), which currently advertises PHP, MySQL, free subdomains, SSL, and `.htaccess` support. The steps below prepare an Apache shared-hosting upload; actual deployment still requires your own hosting account and credentials.

### 1. Create the hosting account and database

- Register on InfinityFree and create a hosting account with a free subdomain.
- Open its control panel and create a MySQL database.
- Save the **MySQL hostname**, **full database name**, **database username**, and **database password** shown by the host. The hosted database hostname will not be `localhost`.
- Open the host's phpMyAdmin, select that empty database, choose **Import**, and select this project's `database/hosting_seed.sql`.
- Confirm that `tasks` contains 8 rows and `users` contains 1 row.

### 2. Prepare the upload folder

A prepared copy may already be present in `dist`. To rebuild after changing code, run from the project folder:

```powershell
powershell -NoProfile -ExecutionPolicy Bypass -File scripts\prepare-hosting.ps1
```

The script creates a new `dist/hosting-YYYYMMDD-HHMMSS/htdocs` folder. It installs runtime dependencies in that copy and leaves the local project intact. Composer needs internet access or its cached packages. Use the most recent folder from a **successful** run.

The upload folder looks like this:

```text
htdocs/
  .htaccess
  index.php
  assets/
  private/
    .htaccess       ← denies browser access to everything in private
    .env           ← hosting settings only
    app/
    vendor/
    writable/
```

In the **generated** `htdocs/private/.env`, replace every `YOUR_...` / `YOUR-SITE...` placeholder with your hosting details. Keep `CI_ENVIRONMENT = production`, `database.default.DBDebug = false`, and the `Asia/Manila` timezone. Set `app.baseURL` to your exact site URL with a trailing `/`. Enable the host's SSL certificate and use `https://` when it is available.

Keep the root project's `.env` configured for localhost so local testing continues to work.

### 3. Upload and check the hosted app

- Using the host's File Manager or an FTP client, upload the **contents** of the generated `htdocs` folder into your domain's `htdocs`. Do not add a second nested `htdocs` folder.
- Include hidden files, especially **both `.htaccess` files** and `private/.env`. Include the entire generated `private/vendor` folder.
- Remove or rename any host-provided default landing page if it takes precedence over `index.php`.
- Visit your site at `/`, `/tasks`, `/profile`, and `/about`. Refresh directly on each URL to check rewriting.
- Verify `/private/.env` and `/private/composer.json` return **403 or 404**, never file contents. The shared-hosting package relies on Apache honoring `.htaccess`; do not use this layout on a server that ignores it.
- If you see a writable-directory error, use the host's file permissions tool to grant the PHP process write access to `private/writable` and its subfolders.

For a host that lets you set a document root, use the normal repository structure instead: install dependencies with `composer install --no-dev --optimize-autoloader`, set the document root to **`public/`**, configure the root `.env`, and create the database using migrations/seeding or the SQL import. Keep `app`, `vendor`, `writable`, and `.env` outside that public document root, as recommended by the [CodeIgniter deployment guide](https://codeigniter4.github.io/userguide/installation/running.html).

## Troubleshooting

| Problem | What to check |
| --- | --- |
| Database connection refused | Start XAMPP MySQL; check host/port in `.env`. |
| Access denied for database user | Use the correct local or hosting database credentials. |
| Unknown database/table | Create the database, then migrate and seed OR import a SQL file. |
| Port 8080 already in use | Open the running site. To use a different port, change `app.baseURL` and run `php spark serve --port 8081`. |
| PHP version error | Use `C:\php\php.exe` locally and PHP 8.2+ on the host. |
| `intl` / `mbstring` extension missing | Enable the extension in the PHP configuration used by the server. Confirm with `php --ini` / `php -m` locally. |
| Hosted navigation gives 404 | Check `.htaccess` was uploaded and Apache rewriting is supported. |
| CSS missing or links return to localhost | Correct the hosted `app.baseURL`; include the final slash and correct HTTPS scheme. |
| Generic production error | Inspect the newest file in `private/writable/logs` on hosting. Keep public error details disabled. |
| Welcome is empty after a few days | Stored tasks are no longer scheduled for today; see the sample-date instructions above. |

## Submit these two links

1. Your **GitHub repository URL** containing the raw project files and database files.
2. Your **live hosted website URL** where all four pages work.

Before submitting, add the actual hosted URL to this README and your repository's About/Website field. Open both links in a private browser window to check your professor can access them. Keep the hosted site available while it is being graded.

The repository and hosting links must be created in your own accounts; no live link is claimed by this local project.

## References

- [CodeIgniter Query Builder](https://codeigniter4.github.io/userguide/database/query_builder.html)
- [CodeIgniter database seeding](https://codeigniter4.github.io/userguide/dbmgmt/seeds.html)
- [CodeIgniter server requirements](https://codeigniter4.github.io/userguide/intro/requirements.html)

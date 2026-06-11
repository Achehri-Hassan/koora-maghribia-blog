<div align="center">

# ⚽ كورة مغربية — Koora Maghribia Blog

**موقع إخباري رياضي متكامل لمتابعة أخبار البطولة الاحترافية المغربية**  
*A full-stack Arabic sports news blog for Moroccan football*

[![PHP](https://img.shields.io/badge/PHP-8%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-5.7%2B-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![Apache](https://img.shields.io/badge/Apache-XAMPP-D22128?style=for-the-badge&logo=apache&logoColor=white)](https://apachefriends.org)
[![License: MIT](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)](LICENSE)

</div>

---

## 📖 Overview

**Koora Maghribia** is a PHP-based sports news web application dedicated to the Moroccan Botola Pro league. It allows an admin to publish football news articles, manage matches with live status tracking, and embed YouTube streams — while visitors can browse news, filter by category, follow match schedules, and comment on articles.

The project is built with a clean separation between public and private code — the web root exposes only `/public`, keeping database logic, models, and config safely outside.

---

## ✨ Features

### 👤 Visitor Side
- Browse latest football news articles on a responsive homepage
- Filter articles by category: **أخبار (News)** / **انتقالات (Transfers)**
- Read full article details with a comments section
- Post anonymous comments on any article
- View today's, yesterday's, and tomorrow's matches in a dedicated schedule page
- Watch live match streams via **YouTube iframe embed**
- Real-time match status indicator: `لم تبدأ` / `مباشر 🔴` / `انتهت`

### 🔐 Admin Side
- Secure login with `password_hash()` / `password_verify()` and PHP sessions
- Dashboard showing article and comment totals at a glance
- **Create, edit, and delete articles** with image upload and MIME type validation
- **Schedule matches** by selecting teams from the DB, choosing a stadium, commentator, date/time, and YouTube URL
- All admin routes protected by `$_SESSION['is_admin']` auth guards

---

## 🗂️ Project Structure

```
koora-maghribia-blog/
│
├── public/                          # ✅ Web root — only this is publicly accessible
│   ├── index.php                    # Homepage — article listing + category filter
│   ├── login.php                    # Admin login form
│   ├── logout.php                   # Session destroy & redirect
│   ├── adetails.php                 # Article detail page + comments
│   ├── dashboard.php                # Admin control panel
│   ├── view_matches.php             # Match listing with live status
│   ├── watch_match_live.php         # Live stream embed page
│   ├── create_Article.php           # Add new article (admin)
│   ├── create_match.php             # Schedule new match (admin)
│   ├── update.php                   # Edit article (admin)
│   ├── delete.php                   # Delete article (auth protected)
│
│
├── src/                             # 🔒 App logic — outside web root
│   ├── config/
│   │   └── connection.php           # PDO connection factory
│   ├── models/
│   │   ├── article.php              # Article CRUD (read, create, update, delete)
│   │   ├── comments.php             # Comment CRUD
│   │   └── matches.php              # Match CRUD + team/stadium/commentator queries
│   └── auth/
│       └── admin.php                # login() authentication function
│
├── assest/                          # Static assets
│   ├── css/
│   │   ├── style.css                # Global styles
│   │   ├── dashboard.css            # Admin panel styles
│   │   ├── create.css               # Form styles
│   │   ├── details.css              # Article detail styles
│   │   ├── view_match.css           # Matches page styles
│   │   ├── watch_match_live.css     # Live stream page styles
│   │   └── components/
│   │       ├── variable.css         # CSS custom properties
│   │       ├── stracture.css        # Layout structure
│   │       ├── header.css           # Header / navbar
│   │       ├── footer.css           # Footer
│   │       └── login.css            # Login form
│   ├── js/
│   │   └── view_match.js            # Live score timer & status logic
│   ├── articles/                    # Uploaded article cover images
│   └── mathes/                      # Team logo images (13 Botola Pro clubs)
│
├── includes/
│   ├── header.php                   # Shared navigation header
│   └── footer.php                   # Shared footer
│
├── database/
│   └── script.sql                   # Full DB schema + seed data
│
├── .env                             # Environment variables (never commit)
└── .gitignore
```

---

## 🛠️ Tech Stack

| Layer | Technology |
|---|---|
| **Backend** | PHP 8+ (Procedural) |
| **Database** | MySQL 5.7+ via PDO |
| **Frontend** | HTML5, CSS3, Vanilla JavaScript |
| **Icons** | Font Awesome 6 |
| **Fonts** | Google Fonts — Cairo (Arabic RTL) |
| **Streaming** | YouTube iframe embed API |
| **Server** | Apache / XAMPP or Laragon (local) |

---

## 🗄️ Database Schema

Database name: `botola_maghribiya` (UTF8MB4, Arabic-safe collation)

```
botola_maghribiya
├── admin           — id, username, email, password (hashed), created_at
├── teams           — id (slug key), team_name, team_image
├── stadiums        — id, stadium_name, city
├── commentators    — id, commentator_name
├── articles        — id, title, content, image, category, user_id → admin(id), created_at
├── comments        — id, article_id → articles(id), username, comment, created_at
└── matches_table   — id, team_one_id → teams(id), team_two_id → teams(id),
                       stadium_id → stadiums(id), commentator_id → commentators(id),
                       youtube_url, match_date, match_time, created_at
```

The SQL seed file pre-loads all **13 Botola Pro teams**, 6 stadiums, 5 commentators, and sample articles.

---

## ⚙️ Installation

### Requirements
- PHP >= 8.0
- MySQL >= 5.7
- Apache with `mod_rewrite` enabled
- XAMPP / Laragon / any local PHP server

---

### Steps

**1. Clone the repository**
```bash
git clone https://github.com/Achehri-Hassan/koora-maghribia-blog.git
cd koora-maghribia-blog
```

**2. Import the database**

Via MySQL CLI:
```bash
mysql -u root -p < database/script.sql
```
Or open **phpMyAdmin** → Import → select `database/script.sql`.

**3. Configure the database connection**

Open `src/config/connection.php` and update the credentials if needed:
```php
$server   = "localhost";
$dbname   = "botola_maghribiya";
$username = "root";
$password = "";          // set your MySQL password here
```

> 💡 **Recommended:** Move credentials to `.env` using [`vlucas/phpdotenv`](https://github.com/vlucas/phpdotenv) to avoid committing secrets.

**4. Set up the web server**

Point your Apache virtual host (or XAMPP document root) to the `/public` folder:
```
DocumentRoot "/path/to/koora-maghribia-blog/public"
```
Then visit:
```
http://localhost/
```

---

## 🔐 Admin Access

The SQL seed inserts a default admin account. To set your own password:

**Step 1 — Generate a bcrypt hash:**
```php
// Run this once in the PHP CLI or a throwaway script
echo password_hash('your_secure_password', PASSWORD_DEFAULT);
```

**Step 2 — Insert the admin into the DB:**
```sql
INSERT INTO admin (username, email, password)
VALUES ('admin', 'admin@example.com', 'PASTE_YOUR_HASH_HERE');
```

**Step 3 — Log in at `/login.php`**

> ⚠️ Never store plain-text passwords. The app uses `password_hash()` / `password_verify()` throughout.

---

## ✅ Security Implemented

| Feature | Implementation |
|---|---|
| Password storage | `password_hash()` (bcrypt) + `password_verify()` |
| Session auth | `$_SESSION['is_admin']` guard on all admin pages |
| SQL injection | PDO prepared statements throughout all models |
| XSS prevention | `htmlspecialchars()` on all output |
| File upload | MIME type validation before saving images |
| Sensitive files | `src/`, `database/`, `.env` — all outside web root |

---

## 🚀 Roadmap

- [ ] Move DB credentials to `.env` via `vlucas/phpdotenv`
- [ ] Add CSRF token protection to all forms
- [ ] Add auth guard to `update.php`
- [ ] Fix `$_SESSION` key consistency across all files
- [ ] Implement real article pagination (currently static UI)
- [ ] Integrate a live score API (replace static `0 - 0`)
- [ ] Add article search functionality
- [ ] Rate-limit comment submissions to prevent spam
- [ ] Add commentator display on the match schedule page

---

## 🎯 Learning Outcomes

This project was built to practice:

- PHP CRUD operations with PDO and prepared statements
- Authentication & Session Management
- File upload handling with MIME validation
- MySQL relational database design (foreign keys, joins)
- Frontend development in Arabic (RTL layout) with HTML, CSS, and Vanilla JS
- Secure coding practices

---

## 👨‍💻 Author

**Hassan Achehri**  
GitHub: [@Achehri-Hassan](https://github.com/Achehri-Hassan)

---

## 📄 License

This project is open source and available under the [MIT License](LICENSE).

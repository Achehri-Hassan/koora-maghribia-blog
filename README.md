# ⚽ كورة مغربية — Koora Maghribia Blog

> موقع إخباري رياضي متكامل لمتابعة أخبار البطولة الاحترافية المغربية مع نظام بث مباريات مباشر
>
> A full-stack Arabic sports news blog for Moroccan football — featuring live match tracking, article management, and an admin dashboard.

---

## 📸 Overview

Koora Maghribia is a PHP-based web application that allows an admin to publish football news articles, schedule matches, and stream live games via YouTube embeds — while visitors can read articles, filter by category, and leave comments.

---

## ✨ Features

### 👤 Visitor Side
- Browse latest football news articles
- Filter articles by category (أخبار / انتقالات)
- Read full article details with comments
- Post anonymous comments on articles
- View today's, yesterday's, and tomorrow's matches
- Watch live match streams (YouTube embed)
- Real-time live match status timer (لم تبدأ / مباشر 🔴 / انتهت)

### 🔐 Admin Side
- Secure login with `password_verify()` and session management
- Admin dashboard with article and comment totals
- Create, edit, and delete articles with image upload and MIME validation
- Schedule new matches with team logos and YouTube stream link
- View and delete comments per article
- All admin actions protected by session auth guards

---

## 🗂️ Project Structure

```
koora-maghribia-blog/
├── public/                          # Web root (only this is publicly accessible)
│   ├── index.php                    # Homepage — article listing
│   ├── login.php                    # Admin login
│   ├── logout.php                   # Session destroy
│   ├── adetails.php                 # Article detail + comments
│   ├── dashboard.php                # Admin control panel
│   ├── view_matches.php             # Match listing with live status
│   ├── watch_match_live.php         # Live stream embed page
│   ├── create_Article.php           # Add new article
│   ├── create_match.php             # Schedule new match
│   ├── update.php                   # Edit article
│   ├── delete.php                   # Delete article (auth protected)
│   ├── delete_comment.php           # Delete comment (auth protected)
│   └── assets/
│       ├── css/
│       │   ├── style.css            # Global styles
│       │   ├── dashboard.css        # Admin panel styles
│       │   ├── create.css           # Forms styles
│       │   ├── details.css          # Article detail styles
│       │   ├── view_match.css       # Matches page styles
│       │   ├── watch_match_live.css # Live stream styles
│       │   └── components/
│       │       ├── variable.css     # CSS custom properties
│       │       ├── stracture.css    # Layout structure
│       │       ├── header.css       # Header navbar
│       │       ├── footer.css       # Footer
│       │       └── form.css         # Login form
│       ├── js/
│       │   └── view_match.js        # Live score & timer logic
│       └── images/
│           ├── mathes/              # Team logo uploads
│           └── articles/            # Article cover uploads
│
├── src/                             # App logic — outside web root
│   ├── config/
│   │   └── connection.php           # PDO connection
│   ├── models/
│   │   ├── article.php              # Article CRUD functions
│   │   ├── comments.php             # Comment CRUD functions
│   │   └── matches.php              # Match CRUD functions
│   └── auth/
│       └── admin.php                # login() auth function
│
├── database/
│   └── script.sql                   # DB schema + seed data
│
├── .env                             # Environment variables (never commit)
└── .gitignore                       # Ignores .env, vendor/
```

---

## 🛠️ Tech Stack

| Layer | Technology |
|---|---|
| Backend | PHP 8+ (Procedural) |
| Database | MySQL via PDO |
| Frontend | HTML5, CSS3, Vanilla JS |
| Icons | Font Awesome 6 |
| Fonts | Google Fonts — Cairo (Arabic) |
| Streaming | YouTube iframe embed API |
| Server | Apache / XAMPP (local) |

---

## ⚙️ Installation

### Requirements
- PHP >= 8.0
- MySQL >= 5.7
- Apache with `mod_rewrite` enabled
- XAMPP / Laragon / any local server

### Steps

**1. Clone the repository**
```bash
git clone https://github.com/Achehri-Hassan/koora-maghribia-blog.git
cd koora-maghribia-blog
```

**2. Import the database**

Open phpMyAdmin (or MySQL CLI) and import:
```bash
mysql -u root -p < database/script.sql
```


**5. Run locally**

Point your Apache virtual host or XAMPP to the `/public` folder as web root, then visit:
```
http://localhost/
```

---

## 🔐 Admin Access

After importing `script.sql`, generate a hashed password first:
```php
// Run this once in PHP to get your hash
echo password_hash('your_password', PASSWORD_DEFAULT);
```

Then insert the admin manually into the DB:
```sql
INSERT INTO admin (username, email, password)
VALUES ('admin', 'admin@gmail.com', 'YOUR_HASH');
```

Then log in at `/login.php`.

> ⚠️ **Never store plain text passwords.** The project uses `password_hash()` and `password_verify()` — make sure your DB row matches.

---

## 🗄️ Database Schema

```
botola_maghribiya
├── admin           — id, username, email, password, created_at
├── articles        — id, title, content, image, category, user_id, created_at
├── comments        — id, article_id, username, comment, created_at
└── matches_table   — id, team_one_name, team_one_image, team_two_name,
                       team_two_image, stadium, youtube_url, match_date,
                       match_time, created_at
```

---

## ✅ Security Implemented

- Passwords hashed with `password_hash()` and verified with `password_verify()`
- All admin pages protected with `$_SESSION['is_admin']` guard
- `delete.php` and `delete_comment.php` require active session
- Image uploads validated by MIME type before saving
- All user output escaped with `htmlspecialchars()`
- SQL injection prevented via PDO prepared statements throughout
- Database files stored outside web root (`src/`, `database/`)

---

## 🚀 Roadmap

- [ ] Move DB credentials to `.env` via `vlucas/phpdotenv`
- [ ] Add CSRF token protection to all forms
- [ ] Fix `$_SESSION` key consistency across all files (`is_admin`)
- [ ] Add auth guard to `update.php`
- [ ] Implement real article pagination (currently static buttons)
- [ ] Add live score API integration (instead of static `0 - 0`)
- [ ] Add article search functionality
- [ ] Rate-limit comment submissions to prevent spam

---


## 🎯 Learning Outcomes

This project helped me practice:

- PHP CRUD operations
- Authentication & Session Management
- PDO & Prepared Statements
- File Upload Handling
- MySQL Database Design
- Frontend Development with HTML, CSS and JavaScript
- Secure Coding Practices



## 👨‍💻 Author

**Hassan Achehri**
GitHub: [@Achehri-Hassan](https://github.com/Achehri-Hassan)

---

## 📄 License

This project is open source and available under the [MIT License](LICENSE).

# Custom Blog Content Management System (CMS)

A functional, secure, and lightweight Content Management System (CMS) designed for blog sites. This application allows multiple authors to register, authenticate, and comprehensively manage (Create, Read, Update, Delete) their individual articles through a secure dashboard, while providing a structured, public-facing article feed for visitors.

## 🚀 Core Features
*   **Structured Public Feed:** Automatically queries and aggregates published articles, displaying them in a chronological presentation feed with proper author mapping and publication timestamps.
*   **Secure Session-Based Authentication:** Employs a robust access gate using PHP native sessions to validate author states and protect administrative portals from unauthorized directory navigation.
*   **Self-Registration Framework:** Includes a registration layout allowing new authors to enroll dynamically into the shared database architecture.
*   **Full CRUD Lifecycle Panel:** Grants authenticated authors exclusive capabilities to publish new titles, live-edit text strings, and permanently remove their own posts via transactional verification prompts.

## 🛠️ Development Infrastructure Stack
*   **Backend Interface:** PHP (Object-Oriented MySQLi Extension)
*   **Database Management:** MySQL (Relational Architecture)
*   **Server Stack Container:** Apache Server environment (via XAMPP)
*   **Frontend Blueprint:** Semantic HTML5 and responsive modern CSS3 structural grids

## 📂 Project Architecture Tree
```text
cms_blog/
│
├── db.php             # Relational MySQL handshake channel database connection
├── index.php          # Public structural feed view landing panel
├── login.php          # Author validation gate with standard Session handlers
├── signup.php         # User self-registration engine form
├── dashboard.php      # Authenticated operations dashboard grid array
├── create_post.php    # Article creation data validation insertion engine
├── edit_post.php      # Existing row column updating correction logic
├── delete_post.php    # Relational row entry permanent dropping script
├── logout.php         # Clean token cache destruction and session reset
└── README.md          # Project documentation guide
```

## 💻 Local Setup Instructions

1.  **Clone the Project Root:**
    Download or place the complete project folder directory directly into your web server's root layout folder:
    `C:\xampp\htdocs\cms_blog\`

2.  **Launch Local Services:**
    Open the **XAMPP Control Panel** on your system and boot both the **Apache** and **MySQL** server layers until their status marks turn bright green.

3.  **Construct Database Schemas:**
    * Access your data manager browser layout via `http://localhost/phpmyadmin/`.
    * Provision a brand-new blank database environment named exactly: `blog_db`.
    * Click the **SQL** console tab, paste the core database structure table setup commands (defining the `users` and `posts` tables with operational foreign key cascades), and click **Go**.

4.  **Execute Runtime Testing:**
    * Open your web browser and view the live website at: `http://localhost/cms_blog/index.php`.
    * Click **Author Login** and sign in using the seed administrator data parameters:
        * **Username:** `admin`
        * **Password:** `password123`

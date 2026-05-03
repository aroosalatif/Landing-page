A lightweight, file-free PHP office management portal with authentication, session-based user storage, and a professional dark-gold dashboard UI.
** Preview**
SignupLoginDashboardNew user registration with role selectionEmail & password authenticationStats, announcements, team & quick links

✨ Features

🔐 Signup & Login — Full user registration with validation and session-based authentication
🚪 Logout — Clears login state and redirects to signup page
📊 Dashboard — Office-style panel with stats, announcements, quick access links, and registered user list
💾 Zero Dependencies — No database, no JSON files, no external libraries — pure PHP sessions only
🎨 Professional UI — Dark luxury theme with gold accents, sidebar navigation, and smooth animations
📱 Responsive — Adapts gracefully to smaller screen sizes
✅ Input Validation — Server-side validation on all forms (email format, password length, match check, duplicate detection)


📁 Project Structure
nexcorp-portal/
│
├── index.php        # Entry point — auto-redirects based on session state
├── signup.php       # User registration + session user storage
├── login.php        # Authentication against session user list
├── dashboard.php    # Protected office dashboard (requires login)
└── logout.php       # Clears login session → redirects to signup

No extra files are created. Everything lives in $_SESSION — no users.json, no database, no config files.


⚙️ How It Works
[index.php]
    │
    ├─ Logged in?  ──────────────► [dashboard.php]
    │
    └─ Not logged in? ───────────► [signup.php]
                                        │
                                   Register Account
                                        │
                                   [login.php]
                                        │
                                   Credentials OK? ──► [dashboard.php]
                                        │
                                   [logout.php] ──────► [signup.php]
Session Variables
VariableTypeDescription$_SESSION['users']arrayAll registered users for the current session$_SESSION['logged_in']boolWhether a user is currently authenticated$_SESSION['current_user']arrayActive user's name, email, role, and join date

Sessions reset when the browser is closed. This is by design — no persistent storage is used.


🚀 Getting Started
Requirements

PHP 8.0 or higher
Apache / Nginx with PHP support (e.g. XAMPP, WAMP, Laragon)

Installation

Clone the repository

bash   git clone https://github.com/your-username/nexcorp-portal.git

Move to your server's root directory

bash   # XAMPP example
   mv nexcorp-portal/ C:/xampp/htdocs/nexcorp/

   # WAMP example
   mv nexcorp-portal/ C:/wamp64/www/nexcorp/

Start your local server (Apache must be running)
Open in browser

   http://localhost/nexcorp/

Register a new account on the signup page and you're ready to go!


🔒 Security Notes

Passwords are stored in plain text within the PHP session — this project is intended for learning and demo purposes only
For production use, passwords should be hashed using password_hash() and stored in a persistent database
Sessions expire when the browser is closed or the server restarts — no data is persisted between sessions


🛠️ Tech Stack
TechnologyUsagePHP 8.0+Server-side logic, session management, form handlingHTML5 / CSS3Markup and stylingGoogle FontsCormorant Garamond (headings) + DM Sans (body)CSS VariablesTheming and dark mode design tokensPHP SessionsUser storage and authentication state

🎨 UI Design
The interface follows a dark luxury office aesthetic:

Color Palette: Deep charcoal #0d0d0f background with gold #c9a84c accents
Typography: Cormorant Garamond for elegant headings, DM Sans for clean body text
Layout: Fixed sidebar navigation with main content area
Animations: Subtle fade-up entrance animations on cards and panels
Grid Background: Fine gold grid overlay for depth


📋 User Roles
During signup, users can select one of the following roles:
RoleDescriptionStaffGeneral office employeeManagerTeam or department managerHRHuman resources personnelAdminPortal administrator

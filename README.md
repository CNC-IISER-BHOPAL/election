# Student Activity Council (SAC) Voting System

## Description

This is a comprehensive web-based voting system designed for student council elections. The system provides a secure and user-friendly interface for students to cast their votes for various council positions. The application features:

1. **Multi-Page Workflow**:
   - Welcome page with voting instructions
   - Voting interface with candidate selection
   - Thank you page after successful vote submission

2. **Key Features**:
   - Session-based authentication
   - Responsive design for all device sizes
   - Animated UI elements for better user experience
   - Form validation and error handling
   - AJAX-based form submission

3. **Technical Stack**:
   - Frontend: HTML5, CSS3, JavaScript (jQuery)
   - Backend: PHP
   - Styling: Bootstrap 5 with custom themes
   - Icons: Font Awesome

The system maintains a consistent design language across all pages with a warm color scheme (oranges and yellows) that creates an inviting voting experience.

## Repository Structure

```
sac-voting-system/
│
├── thank_you.php         # Thank you page shown after successful vote submission
├── home.php              # Main voting interface with candidate selection
├── welcome.php           # Welcome page with voting instructions
├── ajax/                 # AJAX handlers directory (referenced in code)
│   ├── db.php            # Database connection (referenced)
│   ├── getdata.php       # Gets candidate data (referenced)
│   ├── submit_vote.php   # Handles vote submission (referenced)
│   └── reset_vote_session.php # Resets voting session (referenced)
└── system_login.php      # Login page (referenced but not included in files)
```

## Installation & Setup

1. **Requirements**:
   - PHP 7.4 or higher
   - MySQL database
   - Web server (Apache, Nginx, etc.)

2. **Setup**:
   - Clone this repository to your web server
   - Create a MySQL database and configure the connection in `ajax/db.php`
   - Set up the necessary database tables (schema not provided in these files)
   - Configure session settings in PHP if needed

3. **Configuration**:
   - Modify the logo URLs in all pages if needed
   - Adjust color schemes by editing the CSS variables in each file
   - Update the footer information as needed

## Usage

1. Users start at `system_login.php` (not included) which redirects to `welcome.php`
2. From the welcome page, users proceed to `home.php` to cast their votes
3. After submission, users are directed to `thank_you.php`

## Security Notes

- The system uses PHP sessions for authentication
- All sensitive operations should be performed server-side
- Ensure proper file permissions are set on the server
- Consider implementing CSRF protection for form submissions

## Customization

You can easily customize:
- Colors by modifying the CSS variables in each file
- Logos by updating the image URLs
- Layout by adjusting the Bootstrap grid classes
- Animations by editing the CSS keyframes

## License

This code is provided as-is without an explicit license. For use in production, you should:

1. Add your own license file
2. Conduct a thorough security review
3. Customize the system to your specific requirements

## Screenshots

(Would typically include screenshots of each page here in a real README)

## Contributing

Contributions are welcome! Please fork the repository and submit pull requests for any improvements.

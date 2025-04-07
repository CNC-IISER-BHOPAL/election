<?php
session_start();
if (!isset($_SESSION['system_key'])) {
    header('Location: system_login.php');
    exit;
}
$system = $_SESSION['system_key'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You | Voting Complete</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #CB7139;
            --secondary-color: #D9A412;
            --light-bg-primary: #FAE9BD;
            --light-bg-secondary: #FBF0D0;
            --gray-light: #EEEEEE;
            --gray-medium: #E5E7EB;
            --gray-dark: #434343;
            --text-dark: #080808;
            --success-color: #4CAF50;
        }
        
        body {
            background-color: var(--light-bg-secondary);
            font-family: 'Montserrat', sans-serif;
            color: var(--text-dark);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Add patterned background */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: linear-gradient(rgba(251, 240, 208, 0.97), rgba(251, 240, 208, 0.97)),
                url('data:image/svg+xml;utf8,<svg width="20" height="20" xmlns="http://www.w3.org/2000/svg"><rect width="20" height="20" fill="none" stroke="%23D9A412" stroke-width="0.3" stroke-opacity="0.07"/></svg>');
            z-index: -1;
        }

        .navbar {
            background-color: transparent;
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 0.8rem 1rem;
            position: relative;
            z-index: 100;
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--gray-dark) !important;
            text-shadow: 0 1px 2px rgba(255, 255, 255, 0.5);
        }
        
        .navbar-logo {
            height: 40px;
            margin-right: 10px;
            vertical-align: middle;
        }

        .thank-you-container {
            text-align: center;
            max-width: 700px;
            margin: 50px auto;
            padding: 40px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            position: relative;
            overflow: hidden;
        }

        .thank-you-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--secondary-color), var(--primary-color));
        }

        .thank-you-header {
            color: var(--gray-dark);
            margin-bottom: 30px;
            font-weight: 700;
            font-size: 2.5rem;
        }

        .thank-you-message {
            color: #555;
            margin-bottom: 40px;
            font-size: 1.1rem;
            line-height: 1.6;
        }
        
        /* The check animation container */
        .check-container {
            width: 150px;
            height: 150px;
            position: relative;
            margin: 0 auto 40px;
        }
        
        /* Circle styling */
        .check-circle {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background-color: var(--success-color);
            opacity: 0;
            transform: scale(0);
            animation: scaleIn 0.5s ease-in-out forwards;
        }
        
        /* Check mark styling */
        .check-mark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0);
            color: white;
            font-size: 80px;
            animation: checkIn 0.5s ease-in-out forwards 0.5s;
        }
        
        /* Animations */
        @keyframes scaleIn {
            0% {
                transform: scale(0);
                opacity: 0;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }
        
        @keyframes checkIn {
            0% {
                transform: translate(-50%, -50%) scale(0);
                opacity: 0;
            }
            50% {
                transform: translate(-50%, -50%) scale(1.2);
            }
            100% {
                transform: translate(-50%, -50%) scale(1);
                opacity: 1;
            }
        }
        
        .btn-return {
            background-color: var(--secondary-color);
            color: white;
            border: none;
            padding: 12px 30px;
            font-weight: 600;
            border-radius: 50px;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
            margin-top: 20px;
        }
        
        .btn-return:hover {
            background-color: var(--primary-color);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            color: white;
        }

        footer {
            margin-top: auto;
            background-color: transparent;
            border-top: 1px solid rgba(217, 164, 18, 0.2);
            color: var(--gray-dark);
            padding: 20px 0;
            position: relative;
            z-index: 10;
        }
        
        footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(251, 240, 208, 0.7);
            z-index: -1;
        }
        
        footer::after {
            content: '';
            position: absolute;
            top: 0;
            left: 20%;
            right: 20%;
            height: 3px;
            background: var(--secondary-color);
            opacity: 0.6;
        }
        
        .footer-content {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            gap: 4px;
        }
        
        .footer-text {
            font-weight: 500;
            letter-spacing: 0.3px;
        }
        
        .footer-logo {
            height: 18px;
            /* margin-right: 1px; */
            vertical-align: middle;
            transition: transform 0.3s ease;
        }
        
        .powered-by {
            display: inline-flex;
            align-items: center;
            padding: 6px 12px;
            background-color: rgba(255, 255, 255, 0.7);
            border-radius: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }
        
        .powered-by:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .powered-by:hover .footer-logo {
            transform: scale(1.1);
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg mb-4">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEjj1HMLT7kK_nVmlb-u6wF3TZ0ifeVSulZZEVHhMUUiAUjMow2pgEiUUE3Pi6KCNkAnuV0zTTLbrHQY5kOkbIz59CLtAO-3paFvcmhvsXmQXOrPFSWqdF4MYsrPmk4hAtOoxTAxfQxc8wbJ5TVTnTf6cw5pkK_aAHZSi6QI8ESBGez7k6aefDuJQC-eCmLo/w438-h433/sac_logo.png" alt="SAC Logo" class="navbar-logo">
                Student Activity Council
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <div class="thank-you-container">
            <div class="check-container">
                <div class="check-circle"></div>
                <div class="check-mark">✓</div>
            </div>
            <h1 class="thank-you-header">Thank You for Voting!</h1>
            <p class="thank-you-message">
                Your vote has been successfully recorded. Thank you for participating in the SAC General Elections
                and making your voice heard. Your contribution helps shape our campus future.
            </p>
            <p class="fw-bold mb-4">
                Please exit the voting station to allow the next student to vote.
            </p>
            <a href="welcome.php" class="btn-return">
                <i class="fas fa-home me-2"></i> Return to Login
            </a>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <span class="footer-text">Student Activity Council Election Commission</span>
                <span class="mx-2">|</span>
                <div class="powered-by">
                    <span style="color: var(--gray-dark);" class="fw-bold">Powered by 
                        <img src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEiU2npASF2smKxOnXumMNse1Y1rZ1unDbs0kK-BalmR4p7_jcyR6qq3DgUwtizOIZTyB-TnD07-wvlSgB-nnr7PQii-GmkSv_Pp3R50ZO1d57RsVBlwK5qslgKfM8eOYBA_A8xy4J1bnBhtXp8TWes3Jw2IdD1KTktYMhTi5WyEIDzP8WGcqWn6J7SaHSzB/w501-h282/cnc.png" alt="CNC Logo" class="footer-logo" width="24" height="24">
                        <span style="color: var(--primary-color);">CNC</span>
                    </span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Reset the 'voted' session after a delay to prevent repeated viewings
        setTimeout(() => {
            fetch('ajax/reset_vote_session.php', {
                method: 'POST'
            });
        }, 60000); // Reset after 1 minute
    </script>
</body>

</html>

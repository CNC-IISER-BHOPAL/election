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
    <title>Welcome | Student Council Elections</title>
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
        
        .welcome-container {
            max-width: 900px;
            margin: 30px auto;
            padding: 0;
            position: relative;
        }
        
        .welcome-header {
            background: linear-gradient(135deg, var(--gray-dark), var(--secondary-color));
            padding: 30px;
            border-radius: 15px 15px 0 0;
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .welcome-header::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"><polygon fill="rgba(255,255,255,0.05)" points="0,100 100,0 100,100"/></svg>');
            background-size: cover;
        }
        
        .welcome-header h1 {
            font-weight: 700;
            margin-bottom: 15px;
            position: relative;
            z-index: 1;
        }
        
        .welcome-header p {
            font-size: 1.2rem;
            opacity: 0.9;
            max-width: 80%;
            position: relative;
            z-index: 1;
        }
        
        .welcome-body {
            background-color: white;
            border-radius: 0 0 15px 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }
        
        .welcome-card {
            border: none;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .welcome-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }
        
        .welcome-card-header {
            padding: 15px 20px;
            font-weight: 600;
            border-bottom: none;
            color: var(--gray-dark);
            position: relative;
        }
        
        .welcome-card-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 20px;
            right: 20px;
            height: 2px;
            background-color: var(--secondary-color);
        }
        
        .welcome-card-body {
            padding: 20px;
        }
        
        .welcome-card-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 15px;
            display: block;
            text-align: center;
        }
        
        .voter-info {
            background-color: rgba(203, 113, 57, 0.1);
            border-left: 4px solid var(--primary-color);
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 25px;
        }
        
        .btn-start {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            padding: 12px 35px;
            font-weight: 700;
            border-radius: 50px;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
            box-shadow: 0 4px 15px rgba(203, 113, 57, 0.3);
        }
        
        .btn-start:hover {
            transform: translateY(-3px);
            box-shadow: 0 7px 20px rgba(203, 113, 57, 0.4);
            color: white;
        }
        
        .step-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            background-color: var(--secondary-color);
            color: white;
            border-radius: 50%;
            margin-right: 10px;
            font-weight: bold;
        }
        
        .content-divider {
            height: 1px;
            background: linear-gradient(to right, transparent, var(--gray-medium), transparent);
            margin: 30px 0;
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
        <div class="welcome-container">
            <div class="welcome-header">
                <h1><i class="fas fa-vote-yea me-2"></i>Welcome to SAC Elections</h1>
                <p>Make your voice heard! Your vote will help shape the future of our campus community.</p>
            </div>
            <div class="welcome-body">
                <h4 class="mb-3">How to Vote</h4>
                <div class="mb-3">
                    <p><span class="step-number">1</span> Review the list of candidates for each position</p>
                    <p><span class="step-number">2</span> Select your preferred candidate by clicking on their name</p>
                    <p><span class="step-number">3</span> Confirm your selection when prompted</p>
                    <p><span class="step-number">4</span> Complete the process for all positions</p>
                    <p><span class="step-number">5</span> Submit your final ballot to record your vote</p>
                </div>
                
                <div class="content-divider"></div>
                
                <div class="text-center">
                    <p class="mb-4">Ready to make your voice heard? Click the button below to begin the voting process.</p>
                    <a href="home" class="btn-start">
                        <i class="fas fa-vote-yea me-2"></i> Start Voting
                    </a>
                </div>
            </div>
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
</body>

</html>

<?php
session_start();
require 'ajax/db.php';
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
    <title>Election System | SAC IISERB</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #0066cc;
            --dark-blue: #004080;
            --light-blue: #e6f2ff;
            --accent-gold: #ffcc00;
        }

        html, body {
            height: 100%;
        }

        body {
            background: linear-gradient(135deg, rgb(255, 231, 133), #fbf0d0);
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: rgba(255, 255, 255, 0);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 10px 0;
        }

        .navbar-brand {
            font-weight: 600;
            color: var(--dark-blue) !important;
        }

        .navbar-container {
            width: 100%;
            max-width: 1200px;
            margin: 0 0;
            padding: 0 15px;
        }

        .container {
            max-width: 1200px;
            padding: 30px 15px;
            margin-top: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            color: var(--dark-blue);
            font-weight: 700;
            margin-bottom: 10px;
        }

        .header p {
            color: #666;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 25px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .card-header {
            background: linear-gradient(to right, var(--primary-blue), var(--dark-blue));
            color: white;
            font-weight: 600;
            padding: 15px 20px;
            border-bottom: none;
        }

        .card-body {
            padding: 20px;
        }

        .candidates-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .candidate-option {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 15px;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            border: 2px solid #eee;
            transition: all 0.2s;
            cursor: pointer;
            position: relative;
            text-align: center;
            height: 100%;
        }

        .candidate-option:hover {
            background-color: var(--light-blue);
            border-color: var(--primary-blue);
        }

        .candidate-option input[type="radio"] {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .custom-radio {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 20px;
            height: 20px;
            border: 2px solid #aaa;
            border-radius: 50%;
            transition: all 0.2s;
            background-color: white;
        }

        .candidate-option input[type="radio"]:checked + .custom-radio {
            border-color: var(--primary-blue);
            background-color: var(--primary-blue);
        }

        .candidate-option input[type="radio"]:checked + .custom-radio::after {
            content: '';
            position: absolute;
            top: 4px;
            left: 4px;
            width: 8px;
            height: 8px;
            background-color: white;
            border-radius: 50%;
        }

        .candidate-option input[type="radio"]:checked {
            background-color: var(--light-blue);
            border-color: var(--primary-blue);
        }

        .candidate-photo-container {
            position: relative;
            margin-bottom: 15px;
        }

        .candidate-photo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #eee;
            transition: all 0.3s;
        }

        .candidate-option:hover .candidate-photo {
            border-color: var(--primary-blue);
            transform: scale(1.05);
        }

        .candidate-info {
            width: 100%;
        }

        .candidate-name {
            font-weight: 600;
            margin-bottom: 5px;
            color: #333;
        }

        .candidate-details {
            font-size: 0.85rem;
            color: #666;
            margin-bottom: 10px;
        }

        .candidate-manifesto {
            font-size: 0.8rem;
            color: #555;
            font-style: italic;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px dashed #ddd;
        }

        .btn-primary {
            background: linear-gradient(to right, var(--primary-blue), var(--dark-blue));
            border: none;
            padding: 12px 25px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background: linear-gradient(to right, var(--dark-blue), #002b5c);
            transform: translateY(-2px);
        }
#message {
    position: fixed;
    top: 20px;
    left: 50%;
    transform: translateX(-50%);
    padding: 15px 40px 15px 25px;
    border-radius: 5px;
    z-index: 1000;
    box-shadow: 0 2px 10px rgba(0,0,0,0.2);
    display: flex;
    align-items: center;
    min-width: 300px;
    max-width: 80%;
}

#message.hidden {
    display: none;
}

#message.success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

#message.error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.close-alert {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    font-size: 20px;
    font-weight: bold;
    line-height: 1;
}

.close-alert:hover {
    color: #000;
}
        .submit-section {
            text-align: center;
            margin-top: 30px;
        }

        footer {
            margin-top: 60px;
            background-color: transparent;
            border-top: 1px solid rgba(217, 164, 18, 0.2);
            color: #333;
            padding: 20px 0;
            position: relative;
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

        .footer-content {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .footer-text {
            font-weight: 500;
            letter-spacing: 0.3px;
        }
        
        .footer-logo {
            height: 24px;
            margin-right: 5px;
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

        @media (max-width: 768px) {
            .container {
                padding: 20px 10px;
            }
            
            .card-header {
                padding: 12px 15px;
                font-size: 1rem;
            }
            
            .candidates-grid {
                grid-template-columns: 1fr;
            }
            
            .candidate-photo {
                width: 100px;
                height: 100px;
            }
            
            .footer-content {
                flex-direction: column;
                text-align: center;
            }
        }
        .card-header-color {
            background: rgb(0, 0, 0) !important;
        }
        .card-body-color {
            background: rgb(255, 231, 133) !important;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand navbar-light">
        <div class="navbar-container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEjj1HMLT7kK_nVmlb-u6wF3TZ0ifeVSulZZEVHhMUUiAUjMow2pgEiUUE3Pi6KCNkAnuV0zTTLbrHQY5kOkbIz59CLtAO-3paFvcmhvsXmQXOrPFSWqdF4MYsrPmk4hAtOoxTAxfQxc8wbJ5TVTnTf6cw5pkK_aAHZSi6QI8ESBGez7k6aefDuJQC-eCmLo/w438-h433/sac_logo.png" width="30" height="30" class="d-inline-block align-top mr-2" alt="SAC Logo">
                <span>SAC General Elections</span>
            </a>
        </div>
    </nav>

    <div class="container">
        <div class="header">
            <h1><i class="fas fa-vote-yea mr-2"></i>Cast Your Vote</h1>
            <p>Select your preferred candidates for each position</p>
        </div>

      <div id="message" class="hidden">
        <span class="alert-message"></span>
        <span class="close-alert">&times;</span>
        </div>
        
        <form id="votingForm" action="submit_vote.php" method="post">
            <div id="positionsContainer"></div>
            
            <div class="submit-section">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane mr-2"></i> Submit Your Votes
                </button>
            </div>
        </form>
    </div>
    
    <footer>
        <div class="container">
            <div class="footer-content">
                <span class="footer-text">Student Activity Council Election Commission</span>
                <span class="mx-2 d-none d-md-inline">|</span>
                <div class="powered-by">
                    <span style="color: #333;" class="fw-bold">Powered by 
                        <img src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEiU2npASF2smKxOnXumMNse1Y1rZ1unDbs0kK-BalmR4p7_jcyR6qq3DgUwtizOIZTyB-TnD07-wvlSgB-nnr7PQii-GmkSv_Pp3R50ZO1d57RsVBlwK5qslgKfM8eOYBA_A8xy4J1bnBhtXp8TWes3Jw2IdD1KTktYMhTi5WyEIDzP8WGcqWn6J7SaHSzB/w501-h282/cnc.png" alt="CNC Logo" class="footer-logo" width="34" height="24">
                        <span style="color: var(--primary-blue);">CNC</span>
                    </span>
                </div>
            </div>
        </div>
    </footer>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        $.ajax({
            url: 'ajax/getdata.php',
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    buildVotingForm(response.positions, response.candidates);
                    showCustomAlert(response.message, 'success');
                } else {
                    showCustomAlert(response.message, 'error');
                }
            },
            error: function(xhr, status, error) {
                showCustomAlert('Error: ' + error, 'error');
            }
        });
        
        function buildVotingForm(positions, candidates) {
            var container = $('#positionsContainer');
            container.empty();
            
            if (positions.length === 0) {
                container.html('<div class="alert alert-info">No positions available for voting at this time.</div>');
                return;
            }
            
            positions.forEach(function(position) {
                var positionCard = $(`
                    <div class="card mb-4">
                        <div class="card-header card-header-color">
                            <i class="fas fa-user-tie mr-2"></i>${position.position_name}
                        </div>
                        <div class="card-body card-body-color">
                            <p class="text-muted mb-3">Select your preferred candidate:</p>
                            <div class="candidates-grid" id="position_${position.id}"></div>
                            <input type="hidden" name="position_ids[]" value="${position.id}">
                            <input type="hidden" name="election_ids[]" value="${position.election_id}">
                        </div>
                    </div>
                `);
                
                var candidatesGrid = positionCard.find('.candidates-grid');
                var positionCandidates = candidates.filter(function(candidate) {
                    return candidate.position_id == position.id;
                });
                
                if (positionCandidates.length === 0) {
                    candidatesGrid.html('<div class="alert alert-warning">No candidates available for this position.</div>');
                } else {
                    positionCandidates.forEach(function(candidate) {
                        var photoSrc = candidate.member_pic ? 
                            `data:image/jpeg;base64,${candidate.member_pic}` : 
                            'https://via.placeholder.com/120';
                        
                        var candidateOption = $(`
                            <label class="candidate-option">
                                <input type="radio" id="candidate_${candidate.id}" 
                                       name="position_${position.id}" 
                                       value="${candidate.id}" required>
                                <span class="custom-radio"></span>
                                <div class="candidate-photo-container">
                                    <img src="${photoSrc}" alt="${candidate.name}" class="candidate-photo">
                                </div>
                                <div class="candidate-info">
                                    <div class="candidate-name">${candidate.name}</div>
                                    <div class="candidate-details">
                                        ${candidate.department || ''} 
                                        ${candidate.year ? ' | Year: ' + candidate.year : ''}
                                    </div>
                                    ${candidate.manifesto ? 
                                        `<div class="candidate-manifesto">"${candidate.manifesto}"</div>` : ''}
                                </div>
                            </label>
                        `);
                        
                        candidatesGrid.append(candidateOption);
                    });
                }
                
                container.append(positionCard);
            });
        }
function showCustomAlert(message, type) {
    var messageDiv = $('#message');
    
    // Create the element if it doesn't exist
    if (messageDiv.length === 0) {
        $('body').prepend('<div id="message" class="hidden"><span class="alert-message"></span><span class="close-alert">&times;</span></div>');
        messageDiv = $('#message');
    }
    
    messageDiv.removeClass('hidden success error')
              .addClass(type)
              .find('.alert-message').text(message);
    
    messageDiv.fadeIn();
   
    if (type === 'success') {
        setTimeout(function() {
            messageDiv.fadeOut();
        }, 3000);
    }
}
$(document).on('click', '.close-alert', function() {
    $('#message').fadeOut();
});

        $('#votingForm').submit(function(e) {
            e.preventDefault();
            var submitBtn = $(this).find('button[type="submit"]');
            submitBtn.html('<i class="fas fa-spinner fa-spin mr-2"></i> Processing...');
            
            $.ajax({
                url: 'ajax/submit_vote.php',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        showCustomAlert(response.message, 'success');
                        window.location.href = 'thank_you.php?message=Your+vote+has+been+submitted+successfully';
                        $('#votingForm')[0].reset();
                    } else {
                        showCustomAlert(response.message, 'error');
                    }
                    submitBtn.html('<i class="fas fa-paper-plane mr-2"></i> Submit Your Votes');
                },
                error: function(xhr, status, error) {
                    showCustomAlert('Error: ' + error, 'error');
                    submitBtn.html('<i class="fas fa-paper-plane mr-2"></i> Submit Your Votes');
                }
            });
        });
       
        $(document).on('click', '.candidate-option', function() {
            $(this).find('input[type="radio"]').prop('checked', true);
            $(this).find('.custom-radio').addClass('checked');
            $(this).siblings().find('.custom-radio').removeClass('checked');
            $(this).css({
                'background-color': 'var(--light-blue)',
                'border-color': 'var(--primary-blue)'
            }).siblings().css({
                'background-color': 'rgba(255, 255, 255, 0.8)',
                'border-color': '#eee'
            });
        });
    });
    </script>
</body>
</html>
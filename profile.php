<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "employee") {
    include "DB_connection.php";
    include "app/Model/User.php";
    $user = get_user_by_id($conn, $_SESSION['id']);
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Modern CSS Variables for consistent theming */
        :root {
            --primary-color: #4f46e5;
            --primary-hover: #4338ca;
            --secondary-color: #6b7280;
            --background-color: #f8fafc;
            --white: #ffffff;
            --text-dark: #1f2937;
            --text-light: #6b7280;
            --border-color: #e5e7eb;
            --border-focus: #4f46e5;
            --success-color: #10b981;
            --success-bg: #f0fdf4;
            --success-border: #bbf7d0;
            --error-color: #ef4444;
            --error-bg: #fef2f2;
            --error-border: #fecaca;
            --warning-color: #f59e0b;
            --warning-bg: #fffbeb;
            --warning-border: #fed7aa;
            --info-color: #3b82f6;
            --info-bg: #eff6ff;
            --info-border: #bfdbfe;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --radius-sm: 6px;
            --radius-md: 8px;
            --radius-lg: 12px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--background-color);
            color: var(--text-dark);
            line-height: 1.6;
        }

        .body {
            display: flex;
            min-height: 100vh;
        }

        .section-1 {
            flex: 1;
            padding: 2rem;
            max-width: 800px;
            margin: 0 auto;
        }

        .title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .title::before {
            content: '👤';
            font-size: 1.5rem;
        }

        .title a {
            background: var(--primary-color);
            color: var(--white);
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: var(--radius-md);
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .title a:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .title a::before {
            content: '✏️';
            font-weight: bold;
        }

        /* Profile Card Styling */
        .profile-card {
            background: var(--white);
            padding: 2.5rem;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border-color);
            animation: slideUp 0.3s ease-out;
            max-width: 600px;
            margin: 0 auto;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .profile-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 1rem;
        }

        .profile-table tr {
            transition: all 0.2s ease;
        }

        .profile-table tr:hover {
            background-color: rgba(79, 70, 229, 0.03);
        }

        .profile-table td {
            padding: 1rem;
            vertical-align: middle;
        }

        .profile-table td:first-child {
            font-weight: 600;
            color: var(--text-dark);
            width: 40%;
            border-right: 1px solid var(--border-color);
        }

        .profile-table td:last-child {
            color: var(--text-dark);
            background: var(--white);
            border-radius: 0 var(--radius-sm) var(--radius-sm) 0;
        }

        .profile-table tr:first-child td:last-child {
            border-radius: 0 var(--radius-md) 0 0;
        }

        .profile-table tr:last-child td:last-child {
            border-radius: 0 0 var(--radius-md) 0;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .section-1 {
                padding: 1rem;
            }

            .profile-card {
                padding: 1.5rem;
            }

            .title {
                font-size: 1.5rem;
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .title a {
                align-self: flex-start;
            }

            .profile-table td:first-child {
                width: 50%;
            }
        }

        @media (max-width: 480px) {
            .profile-table {
                display: block;
            }

            .profile-table tbody {
                display: block;
            }

            .profile-table tr {
                display: flex;
                flex-direction: column;
                margin-bottom: 1rem;
                border-radius: var(--radius-md);
                border: 1px solid var(--border-color);
                overflow: hidden;
            }

            .profile-table td {
                display: block;
                width: 100% !important;
                border-right: none !important;
            }

            .profile-table td:first-child {
                background: var(--background-color);
                border-bottom: 1px solid var(--border-color);
            }

            .profile-table td:last-child {
                border-radius: 0 !important;
            }
        }

        /* Alert Styles */
        .danger, .success {
            padding: 1rem 1.25rem;
            border-radius: var(--radius-md);
            margin-bottom: 1.5rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .danger {
            background: var(--error-bg);
            color: var(--error-color);
            border: 1px solid var(--error-border);
        }

        .danger::before {
            content: '⚠';
            font-size: 1.2rem;
        }

        .success {
            background: var(--success-bg);
            color: var(--success-color);
            border: 1px solid var(--success-border);
        }

        .success::before {
            content: '✓';
            font-size: 1.2rem;
        }
    </style>
</head>
<body>
    <input type="checkbox" id="checkbox">
    <?php include "inc/header.php" ?>
    <div class="body">
        <?php include "inc/nav.php" ?>
        <section class="section-1">
            <h4 class="title">Profile <a href="edit_profile.php">Edit Profile</a></h4>
            
            <?php if (isset($_GET['error'])) { ?>
                <div class="danger" role="alert">
                    <?php echo stripcslashes($_GET['error']); ?>
                </div>
            <?php } ?>

            <?php if (isset($_GET['success'])) { ?>
                <div class="success" role="alert">
                    <?php echo stripcslashes($_GET['success']); ?>
                </div>
            <?php } ?>

            <div class="profile-card">
                <table class="profile-table">
                    <tr>
                        <td>Full Name</td>
                        <td><?=htmlspecialchars($user['full_name'])?></td>
                    </tr>
                    <tr>
                        <td>Username</td>
                        <td><?=htmlspecialchars($user['username'])?></td>
                    </tr>
                   
                    <tr>
                        <td>Joined At</td>
                        <td><?=htmlspecialchars($user['created_at'])?></td>
                    </tr>
                    
                </table>
            </div>
        </section>
    </div>

    <script type="text/javascript">
        var active = document.querySelector("#navList li:nth-child(3)");
        if (active) {
            active.classList.add("active");
        }

        // Auto-hide success/error messages
        function autoHideAlert(selector, delay = 5000) {
            const alert = document.querySelector(selector);
            if (alert) {
                setTimeout(() => {
                    alert.style.transition = 'all 0.3s ease';
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-10px)';
                    setTimeout(() => {
                        alert.remove();
                    }, 300);
                }, delay);
            }
        }

        autoHideAlert('.success');
        autoHideAlert('.danger');

        // Prevent form resubmission on page refresh
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
</body>
</html>
<?php } else { 
    $em = "First login";
    header("Location: login.php?error=$em");
    exit();
}
?>
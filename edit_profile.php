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
    <title>Edit Profile</title>
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
            content: '✏️';
            font-size: 1.5rem;
        }

        .title a {
            background: var(--secondary-color);
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
            background: #4b5563;
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .title a::before {
            content: '👤';
            font-weight: bold;
        }

        /* Form Styling */
        .form-1 {
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

        .input-holder {
            margin-bottom: 1.5rem;
        }

        .input-holder lable {
            display: block;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .input-1 {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid var(--border-color);
            border-radius: var(--radius-md);
            font-size: 1rem;
            color: var(--text-dark);
            background: var(--white);
            transition: all 0.2s ease;
            outline: none;
        }

        .input-1:focus {
            border-color: var(--border-focus);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
            transform: translateY(-1px);
        }

        .input-1:hover {
            border-color: var(--secondary-color);
        }

        .input-1::placeholder {
            color: var(--text-light);
        }

        .edit-btn {
            width: 100%;
            background: var(--primary-color);
            color: var(--white);
            padding: 1rem 2rem;
            border: none;
            border-radius: var(--radius-md);
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-top: 1rem;
        }

        .edit-btn:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .edit-btn:active {
            transform: translateY(0);
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

        /* Responsive Design */
        @media (max-width: 768px) {
            .section-1 {
                padding: 1rem;
            }

            .form-1 {
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
        }

        @media (max-width: 480px) {
            .form-1 {
                padding: 1rem;
            }

            .input-1 {
                padding: 0.75rem;
            }

            .edit-btn {
                padding: 0.875rem 1.5rem;
            }
        }

        /* Password field styling */
        .input-1[name="password"] {
            background-color: #f9fafb;
            color: var(--text-light);
            cursor: not-allowed;
        }

        /* Focus states for better accessibility */
        .input-1:focus {
            outline: none;
            ring: 2px;
            ring-color: var(--primary-color);
            ring-opacity: 0.5;
        }

        /* Loading state for button */
        .edit-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .edit-btn:disabled:hover {
            background: var(--primary-color);
            transform: none;
            box-shadow: none;
        }
    </style>
</head>
<body>
    <input type="checkbox" id="checkbox">
    <?php include "inc/header.php" ?>
    <div class="body">
        <?php include "inc/nav.php" ?>
        <section class="section-1">
            <h4 class="title">Edit Profile <a href="profile.php">Profile</a></h4>
            
            <form class="form-1" method="POST" action="app/update-profile.php">
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

                <div class="input-holder">
                    <lable>Full Name</lable>
                    <input type="text" name="full_name" class="input-1" placeholder="Full Name" value="<?=htmlspecialchars($user['full_name'])?>">
                </div>

                <div class="input-holder">
                    <lable>Old Password</lable>
                    <input type="password" value="**********" name="password" class="input-1" placeholder="Old Password" readonly>
                </div>

                <div class="input-holder">
                    <lable>New Password</lable>
                    <input type="password" name="new_password" class="input-1" placeholder="New Password">
                </div>

                <div class="input-holder">
                    <lable>Confirm Password</lable>
                    <input type="password" name="confirm_password" class="input-1" placeholder="Confirm Password">
                </div>

                <button class="edit-btn" type="submit">Update Profile</button>
            </form>
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

        // Form validation
        document.querySelector('.form-1').addEventListener('submit', function(e) {
            const newPassword = document.querySelector('input[name="new_password"]').value;
            const confirmPassword = document.querySelector('input[name="confirm_password"]').value;
            
            if (newPassword && newPassword !== confirmPassword) {
                e.preventDefault();
                alert('New password and confirm password do not match!');
                return false;
            }
        });

        // Enable the old password field when user wants to change password
        const oldPasswordField = document.querySelector('input[name="password"]');
        const newPasswordField = document.querySelector('input[name="new_password"]');
        
        newPasswordField.addEventListener('focus', function() {
            oldPasswordField.removeAttribute('readonly');
            oldPasswordField.value = '';
            oldPasswordField.placeholder = 'Enter your current password';
            oldPasswordField.style.backgroundColor = 'var(--white)';
            oldPasswordField.style.color = 'var(--text-dark)';
            oldPasswordField.style.cursor = 'text';
        });
    </script>
</body>
</html>
<?php } else { 
    $em = "First login";
    header("Location: login.php?error=$em");
    exit();
}
?>
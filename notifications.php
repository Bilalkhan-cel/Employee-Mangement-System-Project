<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])) {
    include "DB_connection.php";
    include "app/Model/Notification.php";
    // include "app/Model/User.php";
    $notifications = get_all_my_notifications($conn, $_SESSION['id']);
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
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
            --warning-bg: #fef3c7;
            --info-color: #3b82f6;
            --info-bg: #eff6ff;
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
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Page Header */
        .page-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
            color: var(--white);
            padding: 2rem;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .page-header i {
            font-size: 2.5rem;
            opacity: 0.9;
        }

        .page-header-content h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .page-header-content p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        /* Title Styling */
        .title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 1.5rem;
            display: none; /* Hidden since we have the header */
        }

        /* Success Message */
        .success {
            background: var(--success-bg);
            color: var(--success-color);
            border: 1px solid var(--success-border);
            padding: 1rem 1.5rem;
            border-radius: var(--radius-md);
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 500;
            animation: slideDown 0.4s ease-out;
        }

        .success::before {
            content: '✅';
            font-size: 1.25rem;
        }

        /* Notifications Container */
        .notifications-container {
            background: var(--white);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
            overflow: hidden;
            animation: slideUp 0.4s ease-out;
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

        /* Modern Table Styling */
        .main-table {
            width: 100%;
            border-collapse: collapse;
            background: var(--white);
        }

        .main-table th {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            color: var(--text-dark);
            font-weight: 700;
            padding: 1.5rem;
            text-align: left;
            border-bottom: 2px solid var(--border-color);
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.025em;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .main-table th:first-child {
            border-top-left-radius: var(--radius-lg);
            width: 80px;
            text-align: center;
        }

        .main-table th:last-child {
            border-top-right-radius: var(--radius-lg);
        }

        .main-table td {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            transition: all 0.2s ease;
            vertical-align: top;
        }

        .main-table td:first-child {
            text-align: center;
            font-weight: 600;
            color: var(--primary-color);
            background: linear-gradient(135deg, #fafbff 0%, #f4f6ff 100%);
            width: 80px;
        }

        .main-table tr {
            transition: all 0.3s ease;
        }

        .main-table tr:hover {
            background: linear-gradient(135deg, #fafbff 0%, #f8faff 100%);
            transform: translateX(4px);
            box-shadow: 4px 0 12px rgba(79, 70, 229, 0.1);
        }

        .main-table tr:hover td:first-child {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
            color: var(--white);
            border-radius: var(--radius-sm);
        }

        .main-table tr:last-child td {
            border-bottom: none;
        }

        /* Notification Type Badges */
        .main-table td:nth-child(3) {
            font-weight: 600;
        }

        .main-table tr:nth-child(odd) td:nth-child(3) {
            color: var(--info-color);
        }

        .main-table tr:nth-child(even) td:nth-child(3) {
            color: var(--success-color);
        }

        /* Message Column Styling */
        .main-table td:nth-child(2) {
            font-weight: 500;
            color: var(--text-dark);
            line-height: 1.5;
            max-width: 400px;
        }

        /* Date Column Styling */
        .main-table td:nth-child(4) {
            color: var(--text-light);
            font-size: 0.9rem;
            white-space: nowrap;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: var(--white);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
            animation: slideUp 0.4s ease-out;
        }

        .empty-state i {
            font-size: 4rem;
            color: var(--text-light);
            margin-bottom: 1.5rem;
            opacity: 0.6;
        }

        .empty-state h3 {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: var(--text-light);
            font-size: 1rem;
        }

        /* Statistics Bar */
        .stats-bar {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            animation: slideDown 0.5s ease-out;
        }

        .stat-card {
            flex: 1;
            background: var(--white);
            padding: 1.5rem;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
            text-align: center;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-md);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
            display: block;
            margin-bottom: 0.25rem;
        }

        .stat-label {
            font-size: 0.9rem;
            color: var(--text-light);
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .section-1 {
                padding: 1.5rem;
            }

            .page-header {
                padding: 1.5rem;
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }

            .page-header i {
                font-size: 2rem;
            }

            .page-header-content h1 {
                font-size: 2rem;
            }

            .main-table th,
            .main-table td {
                padding: 1rem;
            }

            .stats-bar {
                flex-direction: column;
                gap: 0.75rem;
            }
        }

        @media (max-width: 768px) {
            .section-1 {
                padding: 1rem;
            }

            .page-header {
                padding: 1.25rem;
                margin-bottom: 1.5rem;
            }

            .page-header-content h1 {
                font-size: 1.75rem;
            }

            .main-table {
                font-size: 0.9rem;
            }

            .main-table th,
            .main-table td {
                padding: 0.75rem;
            }

            .main-table td:nth-child(2) {
                max-width: 200px;
            }

            .empty-state {
                padding: 3rem 1.5rem;
            }

            .empty-state i {
                font-size: 3rem;
            }
        }

        @media (max-width: 480px) {
            .main-table th:nth-child(1),
            .main-table td:nth-child(1) {
                display: none;
            }

            .main-table th,
            .main-table td {
                padding: 0.5rem;
            }

            .page-header {
                padding: 1rem;
            }

            .page-header-content h1 {
                font-size: 1.5rem;
            }

            .stats-bar {
                grid-template-columns: 1fr;
            }
        }

        /* Animation Delays */
        .main-table tr:nth-child(1) { animation: slideUp 0.4s ease-out 0.1s both; }
        .main-table tr:nth-child(2) { animation: slideUp 0.4s ease-out 0.2s both; }
        .main-table tr:nth-child(3) { animation: slideUp 0.4s ease-out 0.3s both; }
        .main-table tr:nth-child(4) { animation: slideUp 0.4s ease-out 0.4s both; }
        .main-table tr:nth-child(5) { animation: slideUp 0.4s ease-out 0.5s both; }

        /* Loading State */
        .loading {
            opacity: 0.7;
            pointer-events: none;
        }

        .loading::after {
            content: '';
            width: 16px;
            height: 16px;
            border: 2px solid transparent;
            border-top: 2px solid currentColor;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-left: 0.5rem;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Notification Icon Animation */
        .page-header i {
            animation: bellRing 2s ease-in-out infinite;
        }

        @keyframes bellRing {
            0%, 50%, 100% {
                transform: rotate(0deg);
            }
            10%, 30% {
                transform: rotate(-10deg);
            }
            20% {
                transform: rotate(10deg);
            }
        }
    </style>
</head>
<body>
    <input type="checkbox" id="checkbox">
    <?php include "inc/header.php" ?>
    <div class="body">
        <?php include "inc/nav.php" ?>
        <section class="section-1">
            <div class="page-header">
                <i class="fa fa-bell"></i>
                <div class="page-header-content">
                    <h1>Notifications</h1>
                    <p>Stay updated with your latest notifications and alerts</p>
                </div>
            </div>

            <?php if ($notifications != 0) { ?>
                <div class="stats-bar">
                    <div class="stat-card">
                        <span class="stat-number"><?= count($notifications) ?></span>
                        <span class="stat-label">Total Notifications</span>
                    </div>
                    <div class="stat-card">
                        <span class="stat-number"><?= count($notifications) ?></span>
                        <span class="stat-label">Unread</span>
                    </div>
                    <div class="stat-card">
                        <span class="stat-number">0</span>
                        <span class="stat-label">Archived</span>
                    </div>
                </div>
            <?php } ?>

            <h4 class="title">All Notifications</h4>
            
            <?php if (isset($_GET['success'])) {?>
                <div class="success" role="alert">
                    <?php echo stripcslashes($_GET['success']); ?>
                </div>
            <?php } ?>
            
            <?php if ($notifications != 0) { ?>
                <div class="notifications-container">
                    <table class="main-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Message</th>
                                <th>Type</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i=0; foreach ($notifications as $notification) { ?>
                            <tr>
                                <td><?=++$i?></td>
                                <td><?=$notification['message']?></td>
                                <td><?=$notification['type']?></td>
                                <td><?=$notification['date']?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            <?php } else { ?>
                <div class="empty-state">
                    <i class="fa fa-bell-slash"></i>
                    <h3>No Notifications</h3>
                    <p>You're all caught up! No new notifications to display.</p>
                </div>
            <?php } ?>
        </section>
    </div>

    <script type="text/javascript">
        // Navigation active state
        var active = document.querySelector("#navList li:nth-child(4)");
        if (active) {
            active.classList.add("active");
        }

        // Add hover effects for table rows
        document.querySelectorAll('.main-table tr').forEach((row, index) => {
            if (index === 0) return; // Skip header row
            
            row.addEventListener('mouseenter', function() {
                this.style.transform = 'translateX(8px)';
                this.style.boxShadow = '8px 0 24px rgba(79, 70, 229, 0.15)';
            });
            
            row.addEventListener('mouseleave', function() {
                this.style.transform = 'translateX(0)';
                this.style.boxShadow = 'none';
            });
        });

        // Auto-refresh notifications every 2 minutes
        setInterval(function() {
            const indicator = document.createElement('div');
            indicator.innerHTML = '🔔 Checking for new notifications...';
            indicator.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: var(--primary-color);
                color: white;
                padding: 0.75rem 1rem;
                border-radius: var(--radius-md);
                z-index: 1000;
                font-size: 0.9rem;
                font-weight: 500;
                box-shadow: var(--shadow-lg);
                animation: slideDown 0.3s ease-out;
            `;
            document.body.appendChild(indicator);
            
            setTimeout(() => {
                indicator.remove();
                location.reload();
            }, 2000);
        }, 120000); // 2 minutes

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + R for refresh
            if ((e.ctrlKey || e.metaKey) && e.key === 'r') {
                e.preventDefault();
                location.reload();
            }
            
            // Escape to go back to dashboard
            if (e.key === 'Escape') {
                window.location.href = 'dashboard.php';
            }
        });

        // Notification count animation
        const statNumbers = document.querySelectorAll('.stat-number');
        statNumbers.forEach(num => {
            const finalValue = parseInt(num.textContent);
            let currentValue = 0;
            const increment = finalValue / 20;
            
            const timer = setInterval(() => {
                currentValue += increment;
                if (currentValue >= finalValue) {
                    num.textContent = finalValue;
                    clearInterval(timer);
                } else {
                    num.textContent = Math.floor(currentValue);
                }
            }, 50);
        });

        // Add click effect to stat cards
        document.querySelectorAll('.stat-card').forEach(card => {
            card.addEventListener('click', function() {
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = 'translateY(-4px)';
                }, 150);
            });
        });

        // Mark notifications as read on scroll (if you implement this feature)
        let notificationRows = document.querySelectorAll('.main-table tbody tr');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                }
            });
        }, { threshold: 0.5 });

        notificationRows.forEach(row => {
            observer.observe(row);
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
<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) ) {
    include "DB_connection.php";
    include "app/Model/Task.php";
    include "app/Model/User.php";
    
    if ($_SESSION['role'] == "admin") {
        $todaydue_task = count_tasks_due_today($conn);
        $overdue_task = count_tasks_overdue($conn);
        $nodeadline_task = count_tasks_NoDeadline($conn);
        $num_task = count_tasks($conn);
        $num_users = count_users($conn);
        $pending = count_pending_tasks($conn);
        $in_progress = count_in_progress_tasks($conn);
        $completed = count_completed_tasks($conn);
    } else {
        $num_my_task = count_my_tasks($conn, $_SESSION['id']);
        $overdue_task = count_my_tasks_overdue($conn, $_SESSION['id']);
        $nodeadline_task = count_my_tasks_NoDeadline($conn, $_SESSION['id']);
        $pending = count_my_pending_tasks($conn, $_SESSION['id']);
        $in_progress = count_my_in_progress_tasks($conn, $_SESSION['id']);
        $completed = count_my_completed_tasks($conn, $_SESSION['id']);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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

        /* Dashboard Header */
        .dashboard-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
            color: var(--white);
            padding: 2rem;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
            margin-bottom: 2rem;
            text-align: center;
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

        .dashboard-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .dashboard-header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        /* Enhanced Dashboard Grid */
        .dashboard {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .dashboard-item {
            background: var(--white);
            padding: 2rem;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: 1.5rem;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
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

        .dashboard-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
            transform: scaleY(0);
            transition: transform 0.3s ease;
            transform-origin: bottom;
        }

        .dashboard-item:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary-color);
        }

        .dashboard-item:hover::before {
            transform: scaleY(1);
        }

        .dashboard-item i {
            font-size: 2.5rem;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            color: var(--white);
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
            box-shadow: var(--shadow-md);
            transition: all 0.3s ease;
        }

        .dashboard-item:hover i {
            transform: scale(1.1);
        }

        .dashboard-item span {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-dark);
            flex: 1;
        }

        /* Specific icon colors for different types */
        .dashboard-item:nth-child(1) i {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        .dashboard-item:nth-child(2) i {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        }

        .dashboard-item:nth-child(3) i {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }

        .dashboard-item:nth-child(4) i {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }

        .dashboard-item:nth-child(5) i {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }

        .dashboard-item:nth-child(6) i {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        }

        .dashboard-item:nth-child(7) i {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
        }

        .dashboard-item:nth-child(8) i {
            background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
        }

        .dashboard-item:nth-child(9) i {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        /* Quick Actions Section */
        .quick-actions {
            background: var(--white);
            padding: 2rem;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
            animation: slideUp 0.5s ease-out;
        }

        .quick-actions h3 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .quick-actions h3::before {
            content: '⚡';
            font-size: 1.5rem;
        }

        .action-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .action-btn {
            padding: 1rem 1.5rem;
            text-decoration: none;
            border-radius: var(--radius-md);
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            justify-content: center;
            text-transform: uppercase;
            letter-spacing: 0.025em;
            box-shadow: var(--shadow-sm);
        }

        .action-btn.primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
            color: var(--white);
        }

        .action-btn.success {
            background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%);
            color: var(--white);
        }

        .action-btn.info {
            background: linear-gradient(135deg, var(--info-color) 0%, #2563eb 100%);
            color: var(--white);
        }

        .action-btn.warning {
            background: linear-gradient(135deg, var(--warning-color) 0%, #d97706 100%);
            color: var(--white);
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        /* Welcome Message */
        .welcome-message {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border: 1px solid var(--success-border);
            color: var(--success-color);
            padding: 1.5rem 2rem;
            border-radius: var(--radius-lg);
            margin-bottom: 2rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 1rem;
            animation: slideDown 0.3s ease-out;
        }

        .welcome-message::before {
            content: '👋';
            font-size: 2rem;
        }

        .welcome-message h2 {
            font-size: 1.5rem;
            margin-bottom: 0.25rem;
        }

        .welcome-message p {
            opacity: 0.8;
            font-size: 1rem;
        }

        /* Stats Summary */
        .stats-summary {
            background: var(--white);
            padding: 2rem;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
            margin-top: 2rem;
            animation: slideUp 0.6s ease-out;
        }

        .stats-summary h3 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .stats-summary h3::before {
            content: '📊';
            font-size: 1.5rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
        }

        .stat-item {
            text-align: center;
            padding: 1rem;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: var(--radius-md);
            border: 1px solid var(--border-color);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
            display: block;
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

            .dashboard {
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 1rem;
            }

            .dashboard-item {
                padding: 1.5rem;
            }

            .dashboard-item i {
                font-size: 2rem;
                width: 50px;
                height: 50px;
            }

            .dashboard-item span {
                font-size: 1.1rem;
            }
        }

        @media (max-width: 768px) {
            .section-1 {
                padding: 1rem;
            }

            .dashboard-header {
                padding: 1.5rem;
                margin-bottom: 1.5rem;
            }

            .dashboard-header h1 {
                font-size: 2rem;
            }

            .dashboard {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .dashboard-item {
                padding: 1.25rem;
                gap: 1rem;
            }

            .action-buttons {
                grid-template-columns: 1fr;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .dashboard-item {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }

            .dashboard-item i {
                margin-bottom: 0.5rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Loading Animation */
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

        /* Staggered Animation */
        .dashboard-item:nth-child(1) { animation-delay: 0.1s; }
        .dashboard-item:nth-child(2) { animation-delay: 0.2s; }
        .dashboard-item:nth-child(3) { animation-delay: 0.3s; }
        .dashboard-item:nth-child(4) { animation-delay: 0.4s; }
        .dashboard-item:nth-child(5) { animation-delay: 0.5s; }
        .dashboard-item:nth-child(6) { animation-delay: 0.6s; }
        .dashboard-item:nth-child(7) { animation-delay: 0.7s; }
        .dashboard-item:nth-child(8) { animation-delay: 0.8s; }
        .dashboard-item:nth-child(9) { animation-delay: 0.9s; }

        /* Hover Effects Enhancement */
        .dashboard-item:hover {
            animation: none;
        }

        /* Pulse Animation for Important Items */
        .dashboard-item:nth-child(3):hover,
        .dashboard-item:nth-child(5):hover {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: var(--shadow-md);
            }
            50% {
                box-shadow: var(--shadow-lg), 0 0 20px rgba(239, 68, 68, 0.3);
            }
            100% {
                box-shadow: var(--shadow-md);
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
            <div class="welcome-message">
                <div>
                    <h2>Welcome back, <?= ucfirst($_SESSION['role']) ?>!</h2>
                    <p>Here's your dashboard overview for today</p>
                </div>
            </div>

            <?php if ($_SESSION['role'] == "admin") { ?>
                <div class="dashboard">
                    <div class="dashboard-item" onclick="location.href='user.php'">
                        <i class="fa fa-users"></i>
                        <span><?=$num_users?> Employees</span>
                    </div>
                    <div class="dashboard-item" onclick="location.href='tasks.php'">
                        <i class="fa fa-tasks"></i>
                        <span><?=$num_task?> All Tasks</span>
                    </div>
                    <div class="dashboard-item" onclick="location.href='tasks.php?due_date=Overdue'">
                        <i class="fa fa-window-close-o"></i>
                        <span><?=$overdue_task?> Overdue</span>
                    </div>
                    <div class="dashboard-item" onclick="location.href='tasks.php?due_date=No%20Deadline'">
                        <i class="fa fa-clock-o"></i>
                        <span><?=$nodeadline_task?> No Deadline</span>
                    </div>
                    <div class="dashboard-item" onclick="location.href='tasks.php?due_date=Due%20Today'">
                        <i class="fa fa-exclamation-triangle"></i>
                        <span><?=$todaydue_task?> Due Today</span>
                    </div>
                    <div class="dashboard-item" onclick="location.href='notifications.php'">
                        <i class="fa fa-bell"></i>
                        <span><?=$overdue_task?> Notifications</span>
                    </div>
                    <div class="dashboard-item" onclick="location.href='tasks.php?status=pending'">
                        <i class="fa fa-square-o"></i>
                        <span><?=$pending?> Pending</span>
                    </div>
                    <div class="dashboard-item" onclick="location.href='tasks.php?status=in-progress'">
                        <i class="fa fa-spinner"></i>
                        <span><?=$in_progress?> In Progress</span>
                    </div>
                    <div class="dashboard-item" onclick="location.href='tasks.php?status=completed'">
                        <i class="fa fa-check-square-o"></i>
                        <span><?=$completed?> Completed</span>
                    </div>
                </div>

                <div class="quick-actions">
                    <h3>Quick Actions</h3>
                    <div class="action-buttons">
                        <a href="create_task.php" class="action-btn primary">
                            <i class="fa fa-plus"></i> Create New Task
                        </a>
                        <a href="create_user.php" class="action-btn success">
                            <i class="fa fa-user-plus"></i> Add Employee
                        </a>
                       
                    </div>
                </div>
            <?php } else { ?>
                <div class="dashboard">
                    <div class="dashboard-item" onclick="location.href='my_task.php'">
                        <i class="fa fa-tasks"></i>
                        <span><?=$num_my_task?> My Tasks</span>
                    </div>
                    <div class="dashboard-item" >
                        <i class="fa fa-window-close-o"></i>
                        <span><?=$overdue_task?> Overdue</span>
                    </div>
                    <div class="dashboard-item" >
                        <i class="fa fa-clock-o"></i>
                        <span><?=$nodeadline_task?> No Deadline</span>
                    </div>
                    <div class="dashboard-item" >
                        <i class="fa fa-square-o"></i>
                        <span><?=$pending?> Pending</span>
                    </div>
                    <div class="dashboard-item" >
                        <i class="fa fa-spinner"></i>
                        <span><?=$in_progress?> In Progress</span>
                    </div>
                    <div class="dashboard-item" >
                        <i class="fa fa-check-square-o"></i>
                        <span><?=$completed?> Completed</span>
                    </div>
                </div>

                <div class="quick-actions">
                    <h3>Quick Actions</h3>
                    <div class="action-buttons">
                        <a href="my_task.php" class="action-btn primary">
                            <i class="fa fa-list"></i> View My Tasks
                        </a>
                        <a href="profile.php" class="action-btn success">
                            <i class="fa fa-user"></i> My Profile
                        </a>
                        
                    </div>
                </div>
            <?php } ?>

            <div class="stats-summary">
                <h3>Task Statistics</h3>
                <div class="stats-grid">
                    <?php if ($_SESSION['role'] == "admin") { ?>
                        <div class="stat-item">
                            <span class="stat-number"><?= $num_task ?></span>
                            <span class="stat-label">Total Tasks</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number"><?= $num_users ?></span>
                            <span class="stat-label">Total Users</span>
                        </div>
                    <?php } else { ?>
                        <div class="stat-item">
                            <span class="stat-number"><?= $num_my_task ?></span>
                            <span class="stat-label">My Tasks</span>
                        </div>
                    <?php } ?>
                    <div class="stat-item">
                        <span class="stat-number"><?= $pending ?></span>
                        <span class="stat-label">Pending</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number"><?= $in_progress ?></span>
                        <span class="stat-label">In Progress</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number"><?= $completed ?></span>
                        <span class="stat-label">Completed</span>
                    </div>
                </div>
            </div>
        </section>
    </div>
    
    <script type="text/javascript">
        // Navigation active state
        var active = document.querySelector("#navList li:nth-child(1)");
        if (active) {
            active.classList.add("active");
        }

        // Add click handlers for dashboard items
        document.querySelectorAll('.dashboard-item[onclick]').forEach(item => {
            item.addEventListener('click', function() {
                this.classList.add('loading');
                // The onclick attribute will handle the navigation
            });
        });

        // Add hover effects for action buttons
        document.querySelectorAll('.action-btn').forEach(btn => {
            btn.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px) scale(1.02)';
            });
            
            btn.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + N for new task (admin only)
            <?php if ($_SESSION['role'] == "admin") { ?>
            if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
                e.preventDefault();
                window.location.href = 'create_task.php';
            }
            <?php } ?>
            
            // Ctrl/Cmd + D for dashboard (refresh)
            if ((e.ctrlKey || e.metaKey) && e.key === 'd') {
                e.preventDefault();
                location.reload();
            }
        });

        // Auto-refresh dashboard data every 5 minutes
        setInterval(function() {
            const indicator = document.createElement('div');
            indicator.innerHTML = '🔄 Refreshing...';
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
                location.reload();
            }, 1000);
        }, 300000); // 5 minutes

        // Welcome message animation
        setTimeout(() => {
            const welcomeMsg = document.querySelector('.welcome-message');
            if (welcomeMsg) {
                welcomeMsg.style.background = 'linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%)';
                welcomeMsg.style.borderColor = '#93c5fd';
                welcomeMsg.style.color = '#3b82f6';
            }
        }, 3000);

        // Add ripple effect to dashboard items
        document.querySelectorAll('.dashboard-item').forEach(item => {
            item.addEventListener('click', function(e) {
                const ripple = document.createElement('div');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;
                
                ripple.style.cssText = `
                    position: absolute;
                    width: ${size}px;
                    height: ${size}px;
                    left: ${x}px;
                    top: ${y}px;
                    background: rgba(79, 70, 229, 0.3);
                    border-radius: 50%;
                    transform: scale(0);
                    animation: ripple 0.6s ease-out;
                    pointer-events: none;
                `;
                
                this.appendChild(ripple);
                
                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });
        });

        // Add CSS for ripple animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes ripple {
                to {
                    transform: scale(2);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>
<?php 
} else { 
    $em = "First login";
    header("Location: login.php?error=$em");
    exit();
}
?>
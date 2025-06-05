<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "admin") {
    include "DB_connection.php";
    include "app/Model/Task.php";
    include "app/Model/User.php";
    
    $text = "All Task";
    if (isset($_GET['due_date']) &&  $_GET['due_date'] == "Due Today") {
    	$text = "Due Today";
      $tasks = get_all_tasks_due_today($conn);
      $num_task = count_tasks_due_today($conn);

    }else if (isset($_GET['due_date']) &&  $_GET['due_date'] == "Overdue") {
    	$text = "Overdue";
      $tasks = get_all_tasks_overdue($conn);
      $num_task = count_tasks_overdue($conn);

    }else if (isset($_GET['due_date']) &&  $_GET['due_date'] == "No Deadline") {
    	$text = "No Deadline";
      $tasks = get_all_tasks_NoDeadline($conn);
      $num_task = count_tasks_NoDeadline($conn);

    }else{
    	 $tasks = get_all_tasks($conn);
       $num_task = count_tasks($conn);
    }
    $users = get_all_users($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>All Tasks</title>
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

		/* Enhanced Title and Filter Bar */
		.title-2 {
			display: flex;
			align-items: center;
			gap: 1rem;
			margin-bottom: 1.5rem;
			flex-wrap: wrap;
		}

		.title-2 a {
			padding: 0.75rem 1.5rem;
			text-decoration: none;
			border-radius: var(--radius-md);
			font-weight: 500;
			font-size: 0.9rem;
			transition: all 0.2s ease;
			display: inline-flex;
			align-items: center;
			gap: 0.5rem;
			border: 2px solid transparent;
		}

		.title-2 a.btn {
			background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
			color: var(--white);
			box-shadow: var(--shadow-md);
		}

		.title-2 a.btn:hover {
			transform: translateY(-2px);
			box-shadow: var(--shadow-lg);
		}

		.title-2 a.btn::before {
			content: '+';
			font-weight: bold;
			font-size: 1.1rem;
		}

		.title-2 a:not(.btn) {
			background: var(--white);
			color: var(--text-dark);
			border-color: var(--border-color);
			box-shadow: var(--shadow-sm);
		}

		.title-2 a:not(.btn):hover {
			background: var(--primary-color);
			color: var(--white);
			border-color: var(--primary-color);
			transform: translateY(-1px);
		}

		/* Task Count Header */
		.task-count-header {
			background: var(--white);
			padding: 1.5rem 2rem;
			border-radius: var(--radius-lg);
			box-shadow: var(--shadow-md);
			border: 1px solid var(--border-color);
			margin-bottom: 2rem;
			display: flex;
			align-items: center;
			justify-content: space-between;
			animation: slideUp 0.3s ease-out;
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

		.task-count-header h4 {
			font-size: 1.5rem;
			font-weight: 700;
			color: var(--text-dark);
			display: flex;
			align-items: center;
			gap: 0.5rem;
		}

		.task-count {
			background: var(--primary-color);
			color: var(--white);
			padding: 0.25rem 0.75rem;
			border-radius: 50px;
			font-size: 0.9rem;
			font-weight: 600;
			min-width: 2rem;
			text-align: center;
		}

		/* Enhanced Table Styles */
		.table-container {
			background: var(--white);
			border-radius: var(--radius-lg);
			box-shadow: var(--shadow-lg);
			border: 1px solid var(--border-color);
			overflow: hidden;
			animation: slideUp 0.4s ease-out;
		}

		.main-table {
			width: 100%;
			border-collapse: collapse;
			background: var(--white);
		}

		.main-table th {
			background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
			padding: 1.25rem 1rem;
			text-align: left;
			font-weight: 600;
			color: var(--text-dark);
			font-size: 0.9rem;
			text-transform: uppercase;
			letter-spacing: 0.05em;
			border-bottom: 2px solid var(--border-color);
			position: sticky;
			top: 0;
			z-index: 10;
		}

		.main-table th:first-child {
			width: 60px;
			text-align: center;
		}

		.main-table td {
			padding: 1rem;
			border-bottom: 1px solid var(--border-color);
			vertical-align: middle;
			transition: background-color 0.2s ease;
		}

		.main-table tr:hover td {
			background-color: #f8fafc;
		}

		.main-table tr:nth-child(even) td {
			background-color: rgba(248, 250, 252, 0.5);
		}

		.main-table tr:nth-child(even):hover td {
			background-color: #f1f5f9;
		}

		/* Status Badges */
		.status-badge {
			padding: 0.375rem 0.875rem;
			border-radius: 50px;
			font-size: 0.8rem;
			font-weight: 600;
			text-transform: uppercase;
			letter-spacing: 0.025em;
			display: inline-block;
		}

		.status-pending {
			background: var(--warning-bg);
			color: var(--warning-color);
		}

		.status-completed {
			background: var(--success-bg);
			color: var(--success-color);
		}

		.status-in-progress {
			background: var(--info-bg);
			color: var(--info-color);
		}

		/* Action Buttons */
		.action-buttons {
			display: flex;
			gap: 0.5rem;
		}

		.edit-btn, .delete-btn {
			padding: 0.5rem 1rem;
			text-decoration: none;
			border-radius: var(--radius-sm);
			font-size: 0.85rem;
			font-weight: 500;
			transition: all 0.2s ease;
			display: inline-flex;
			align-items: center;
			gap: 0.375rem;
			border: 1px solid transparent;
		}

		.edit-btn {
			background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
			color: var(--white);
		}

		.edit-btn:hover {
			transform: translateY(-1px);
			box-shadow: var(--shadow-md);
		}

		.edit-btn::before {
			content: '✏';
			font-size: 0.9rem;
		}

		.delete-btn {
			background: linear-gradient(135deg, var(--error-color) 0%, #dc2626 100%);
			color: var(--white);
		}

		.delete-btn:hover {
			transform: translateY(-1px);
			box-shadow: var(--shadow-md);
		}

		.delete-btn::before {
			content: '🗑';
			font-size: 0.9rem;
		}

		/* Due Date Styling */
		.due-date {
			display: flex;
			align-items: center;
			gap: 0.5rem;
			font-size: 0.9rem;
		}

		.due-date.overdue {
			color: var(--error-color);
			font-weight: 600;
		}

		.due-date.due-today {
			color: var(--warning-color);
			font-weight: 600;
		}

		.due-date.no-deadline {
			color: var(--text-light);
			font-style: italic;
		}

		.due-date::before {
			font-family: 'FontAwesome';
		}

		.due-date.overdue::before {
			content: '\f071';
			color: var(--error-color);
		}

		.due-date.due-today::before {
			content: '\f017';
			color: var(--warning-color);
		}

		.due-date.no-deadline::before {
			content: '\f073';
			color: var(--text-light);
		}

		/* Task Description Truncation */
		.task-description {
			max-width: 300px;
			overflow: hidden;
			text-overflow: ellipsis;
			white-space: nowrap;
			position: relative;
		}

		.task-description:hover {
			white-space: normal;
			word-wrap: break-word;
			max-width: none;
		}

		/* Alert Styles */
		.success {
			background: var(--success-bg);
			color: var(--success-color);
			border: 1px solid var(--success-border);
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

		.success::before {
			content: '✓';
			font-size: 1.2rem;
			font-weight: bold;
		}

		/* Empty State */
		.empty-state {
			text-align: center;
			padding: 4rem 2rem;
			background: var(--white);
			border-radius: var(--radius-lg);
			box-shadow: var(--shadow-md);
			border: 1px solid var(--border-color);
		}

		.empty-state h3 {
			font-size: 1.5rem;
			color: var(--text-light);
			margin-bottom: 1rem;
		}

		.empty-state::before {
			content: '📋';
			font-size: 4rem;
			display: block;
			margin-bottom: 1rem;
			opacity: 0.5;
		}

		.empty-state p {
			color: var(--text-light);
			font-size: 1rem;
			margin-bottom: 2rem;
		}

		.empty-state a {
			background: var(--primary-color);
			color: var(--white);
			padding: 0.75rem 1.5rem;
			text-decoration: none;
			border-radius: var(--radius-md);
			font-weight: 500;
			display: inline-flex;
			align-items: center;
			gap: 0.5rem;
			transition: all 0.2s ease;
		}

		.empty-state a:hover {
			background: var(--primary-hover);
			transform: translateY(-1px);
			box-shadow: var(--shadow-md);
		}

		/* Responsive Design */
		@media (max-width: 1024px) {
			.section-1 {
				padding: 1.5rem;
			}

			.main-table {
				font-size: 0.9rem;
			}

			.main-table th,
			.main-table td {
				padding: 0.75rem 0.5rem;
			}

			.action-buttons {
				flex-direction: column;
				gap: 0.25rem;
			}
		}

		@media (max-width: 768px) {
			.section-1 {
				padding: 1rem;
			}

			.title-2 {
				flex-direction: column;
				align-items: stretch;
			}

			.title-2 a {
				text-align: center;
				justify-content: center;
			}

			.task-count-header {
				flex-direction: column;
				gap: 1rem;
				text-align: center;
			}

			.table-container {
				overflow-x: auto;
			}

			.main-table {
				min-width: 800px;
			}

			.task-description {
				max-width: 150px;
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

		/* Search and Filter Enhancement */
		.filter-bar {
			background: var(--white);
			padding: 1.5rem;
			border-radius: var(--radius-lg);
			box-shadow: var(--shadow-md);
			border: 1px solid var(--border-color);
			margin-bottom: 1.5rem;
			display: flex;
			align-items: center;
			gap: 1rem;
			flex-wrap: wrap;
		}

		.search-input {
			flex: 1;
			min-width: 250px;
			padding: 0.75rem 1rem;
			border: 2px solid var(--border-color);
			border-radius: var(--radius-md);
			font-size: 0.95rem;
			transition: all 0.2s ease;
		}

		.search-input:focus {
			outline: none;
			border-color: var(--border-focus);
			box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
		}

		/* Priority Indicators */
		.priority-indicator {
			display: inline-block;
			width: 8px;
			height: 8px;
			border-radius: 50%;
			margin-right: 0.5rem;
		}

		.priority-high { background-color: var(--error-color); }
		.priority-medium { background-color: var(--warning-color); }
		.priority-low { background-color: var(--success-color); }

		/* Table Row Animation */
		.main-table tr {
			animation: fadeIn 0.3s ease-out;
		}

		@keyframes fadeIn {
			from {
				opacity: 0;
				transform: translateY(10px);
			}
			to {
				opacity: 1;
				transform: translateY(0);
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
			<div class="title-2">
				<a href="create_task.php" class="btn">Create Task</a>
				<a href="tasks.php?due_date=Due Today">Due Today</a>
				<a href="tasks.php?due_date=Overdue">Overdue</a>
				<a href="tasks.php?due_date=No Deadline">No Deadline</a>
				<a href="tasks.php">All Tasks</a>
			</div>

			<div class="task-count-header">
				<h4><?=$text?> <span class="task-count"><?=$num_task?></span></h4>
			</div>

			<?php if (isset($_GET['success'])) {?>
			<div class="success" role="alert">
				<?php echo stripcslashes($_GET['success']); ?>
			</div>
			<?php } ?>

			<?php if ($tasks != 0) { ?>
			<div class="table-container">
				<table class="main-table">
					<thead>
						<tr>
							<th>#</th>
							<th>Title</th>
							<th>Description</th>
							<th>Assigned To</th>
							<th>Due Date</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php $i=0; foreach ($tasks as $task) { ?>
						<tr>
							<td style="text-align: center; font-weight: 600; color: var(--text-light);"><?=++$i?></td>
							<td style="font-weight: 600; color: var(--text-dark);"><?=$task['title']?></td>
							<td>
								<div class="task-description" title="<?=htmlspecialchars($task['description'])?>">
									<?=htmlspecialchars($task['description'])?>
								</div>
							</td>
							<td>
								<?php 
								foreach ($users as $user) {
									if($user['id'] == $task['assigned_to']){
										echo '<span style="font-weight: 500; color: var(--text-dark);">' . htmlspecialchars($user['full_name']) . '</span>';
									}
								}?>
							</td>
							<td>
								<?php 
								if($task['due_date'] == "") {
									echo '<span class="due-date no-deadline">No Deadline</span>';
								} else {
									$due_date = new DateTime($task['due_date']);
									$today = new DateTime();
									$diff = $today->diff($due_date)->days;
									
									if ($due_date < $today) {
										echo '<span class="due-date overdue">' . $task['due_date'] . '</span>';
									} elseif ($due_date->format('Y-m-d') == $today->format('Y-m-d')) {
										echo '<span class="due-date due-today">' . $task['due_date'] . '</span>';
									} else {
										echo '<span class="due-date">' . $task['due_date'] . '</span>';
									}
								}
								?>
							</td>
							<td>
								<?php
								$status_class = '';
								switch(strtolower($task['status'])) {
									case 'completed':
										$status_class = 'status-completed';
										break;
									case 'in progress':
									case 'in-progress':
										$status_class = 'status-in-progress';
										break;
									default:
										$status_class = 'status-pending';
								}
								?>
								<span class="status-badge <?=$status_class?>"><?=$task['status']?></span>
							</td>
							<td>
								<div class="action-buttons">
									<a href="edit-task.php?id=<?=$task['id']?>" class="edit-btn">Edit</a>
									<a href="delete-task.php?id=<?=$task['id']?>" class="delete-btn" 
									   onclick="return confirm('Are you sure you want to delete this task?')">Delete</a>
								</div>
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
			<?php } else { ?>
			<div class="empty-state">
				<h3>No Tasks Found</h3>
				<p>Get started by creating your first task to organize your team's work efficiently.</p>
				<a href="create_task.php">Create Your First Task</a>
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

		// Add loading state to action buttons
		document.querySelectorAll('.delete-btn').forEach(btn => {
			btn.addEventListener('click', function(e) {
				if (confirm('Are you sure you want to delete this task?')) {
					this.classList.add('loading');
					this.textContent = 'Deleting...';
				} else {
					e.preventDefault();
				}
			});
		});

		// Enhanced table interactions
		document.querySelectorAll('.main-table tr').forEach((row, index) => {
			if (index > 0) { // Skip header row
				row.style.animationDelay = `${index * 0.05}s`;
			}
		});

		// Auto-refresh functionality
		let refreshInterval;
		function startAutoRefresh() {
			refreshInterval = setInterval(() => {
				const urlParams = new URLSearchParams(window.location.search);
				const currentFilter = urlParams.get('due_date') || '';
				
				// Add a subtle loading indicator
				const indicator = document.createElement('div');
				indicator.innerHTML = '🔄';
				indicator.style.cssText = `
					position: fixed;
					top: 20px;
					right: 20px;
					background: var(--primary-color);
					color: white;
					padding: 0.5rem;
					border-radius: 50%;
					z-index: 1000;
					animation: spin 1s linear infinite;
				`;
				document.body.appendChild(indicator);
				
				setTimeout(() => {
					document.body.removeChild(indicator);
				}, 1000);
			}, 30000); // Refresh every 30 seconds
		}

		// Keyboard shortcuts
		document.addEventListener('keydown', function(e) {
			// Ctrl/Cmd + N for new task
			if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
				e.preventDefault();
				window.location.href = 'create_task.php';
			}
			
			// Ctrl/Cmd + R for refresh
			if ((e.ctrlKey || e.metaKey) && e.key === 'r') {
				e.preventDefault();
				location.reload();
			}
		});

		// Task description tooltip enhancement
		document.querySelectorAll('.task-description').forEach(desc => {
			desc.addEventListener('mouseenter', function() {
				if (this.scrollWidth > this.clientWidth) {
					this.style.whiteSpace = 'normal';
					this.style.position = 'relative';
					this.style.zIndex = '10';
					this.style.background = 'var(--white)';
					this.style.boxShadow = 'var(--shadow-lg)';
					this.style.padding = '0.5rem';
					this.style.borderRadius = 'var(--radius-sm)';
					this.style.border = '1px solid var(--border-color)';
				}
			});
			
			desc.addEventListener('mouseleave', function() {
				this.style.whiteSpace = 'nowrap';
				this.style.position = 'static';
				this.style.zIndex = 'auto';
				this.style.background = 'transparent';
				this.style.boxShadow = 'none';
				this.style.padding = '0';
				this.style.borderRadius = '0';
				this.style.border = 'none';
			});
		});

		// Success message auto-hide
		const successAlert = document.querySelector('.success');
		if (successAlert) {
			setTimeout(() => {
				successAlert.style.opacity = '0';
				successAlert.style.transform = 'translateY(-10px)';
				setTimeout(() => {
					successAlert.remove();
				}, 300);
			}, 5000);
		}

		// Start auto-refresh when page loads
		// startAutoRefresh();
	</script>
</body>
</html>
<?php }else{ 
   $em = "First login";
   header("Location: login.php?error=$em");
   exit();
}
?>
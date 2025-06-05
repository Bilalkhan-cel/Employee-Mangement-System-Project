<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])) {
    include "DB_connection.php";
    include "app/Model/Task.php";
    include "app/Model/User.php";

    $tasks = get_all_tasks_by_id($conn, $_SESSION['id']);

 ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>My Tasks</title>
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
			max-width: 1200px;
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
			content: '📋';
			font-size: 1.5rem;
		}

		/* Alert Styles */
		.success {
			padding: 1rem 1.25rem;
			border-radius: var(--radius-md);
			margin-bottom: 1.5rem;
			font-weight: 500;
			display: flex;
			align-items: center;
			gap: 0.5rem;
			animation: slideDown 0.3s ease-out;
			background: var(--success-bg);
			color: var(--success-color);
			border: 1px solid var(--success-border);
		}

		.success::before {
			content: '✓';
			font-size: 1.2rem;
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

		/* Table Container */
		.table-container {
			background: var(--white);
			border-radius: var(--radius-lg);
			box-shadow: var(--shadow-lg);
			border: 1px solid var(--border-color);
			overflow: hidden;
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

		.table-header {
			padding: 1.5rem 2rem;
			border-bottom: 1px solid var(--border-color);
			background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
		}

		.table-header h3 {
			font-size: 1.25rem;
			font-weight: 600;
			color: var(--text-dark);
			margin-bottom: 0.25rem;
		}

		.table-header p {
			color: var(--text-light);
			font-size: 0.9rem;
		}

		/* Modern Table Styles */
		.main-table {
			width: 100%;
			border-collapse: collapse;
			font-size: 0.95rem;
		}

		.main-table th {
			background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
			color: var(--white);
			padding: 1rem 1.5rem;
			text-align: left;
			font-weight: 600;
			font-size: 0.9rem;
			text-transform: uppercase;
			letter-spacing: 0.05em;
			position: relative;
		}

		.main-table th:first-child {
			border-top-left-radius: 0;
		}

		.main-table th:last-child {
			border-top-right-radius: 0;
		}

		.main-table td {
			padding: 1rem 1.5rem;
			border-bottom: 1px solid var(--border-color);
			vertical-align: middle;
			transition: all 0.2s ease;
		}

		.main-table tr {
			transition: all 0.2s ease;
		}

		.main-table tr:hover {
			background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
			transform: translateY(-1px);
			box-shadow: var(--shadow-sm);
		}

		.main-table tr:last-child td {
			border-bottom: none;
		}

		/* Status Badge Styles */
		.status-badge {
			display: inline-flex;
			align-items: center;
			gap: 0.5rem;
			padding: 0.375rem 0.75rem;
			border-radius: var(--radius-sm);
			font-size: 0.8rem;
			font-weight: 600;
			text-transform: uppercase;
			letter-spacing: 0.05em;
		}

		.status-pending {
			background: var(--warning-bg);
			color: var(--warning-color);
			border: 1px solid var(--warning-border);
		}

		.status-pending::before {
			content: '⏳';
		}

		.status-in-progress {
			background: var(--info-bg);
			color: var(--info-color);
			border: 1px solid var(--info-border);
		}

		.status-in-progress::before {
			content: '🔄';
		}

		.status-completed {
			background: var(--success-bg);
			color: var(--success-color);
			border: 1px solid var(--success-border);
		}

		.status-completed::before {
			content: '✅';
		}

		.status-overdue {
			background: var(--error-bg);
			color: var(--error-color);
			border: 1px solid var(--error-border);
		}

		.status-overdue::before {
			content: '⚠️';
		}

		/* Enhanced Edit Button */
		.edit-btn {
			background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
			color: var(--white);
			padding: 0.5rem 1rem;
			border: none;
			border-radius: var(--radius-md);
			font-size: 0.85rem;
			font-weight: 500;
			cursor: pointer;
			transition: all 0.2s ease;
			text-decoration: none;
			display: inline-flex;
			align-items: center;
			gap: 0.5rem;
			min-width: 80px;
			justify-content: center;
		}

		.edit-btn:hover {
			transform: translateY(-2px);
			box-shadow: var(--shadow-md);
		}

		.edit-btn:active {
			transform: translateY(0);
		}

		.edit-btn::before {
			content: '✏️';
			font-size: 0.8rem;
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

		/* Due Date Styling */
		.due-date {
			display: flex;
			align-items: center;
			gap: 0.5rem;
			font-weight: 500;
		}

		.due-date::before {
			content: '📅';
			font-size: 0.9rem;
		}

		.due-date.overdue {
			color: var(--error-color);
		}

		.due-date.due-soon {
			color: var(--warning-color);
		}

		/* Empty State */
		.empty-state {
			text-align: center;
			padding: 4rem 2rem;
			background: var(--white);
			border-radius: var(--radius-lg);
			box-shadow: var(--shadow-lg);
			border: 1px solid var(--border-color);
		}

		.empty-state-icon {
			font-size: 4rem;
			margin-bottom: 1rem;
			opacity: 0.5;
		}

		.empty-state h3 {
			font-size: 1.5rem;
			font-weight: 600;
			color: var(--text-dark);
			margin-bottom: 0.5rem;
		}

		.empty-state p {
			color: var(--text-light);
			margin-bottom: 2rem;
		}

		.empty-state .cta-btn {
			background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
			color: var(--white);
			padding: 0.75rem 1.5rem;
			border: none;
			border-radius: var(--radius-md);
			font-size: 1rem;
			font-weight: 500;
			cursor: pointer;
			transition: all 0.2s ease;
			text-decoration: none;
			display: inline-flex;
			align-items: center;
			gap: 0.5rem;
		}

		.empty-state .cta-btn:hover {
			transform: translateY(-2px);
			box-shadow: var(--shadow-md);
		}

		/* Task Stats */
		.task-stats {
			display: grid;
			grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
			gap: 1rem;
			margin-bottom: 2rem;
		}

		.stat-card {
			background: var(--white);
			padding: 1.5rem;
			border-radius: var(--radius-lg);
			box-shadow: var(--shadow-md);
			border: 1px solid var(--border-color);
			display: flex;
			align-items: center;
			gap: 1rem;
			transition: all 0.2s ease;
		}

		.stat-card:hover {
			transform: translateY(-2px);
			box-shadow: var(--shadow-lg);
		}

		.stat-icon {
			font-size: 2rem;
			width: 50px;
			height: 50px;
			border-radius: var(--radius-md);
			display: flex;
			align-items: center;
			justify-content: center;
		}

		.stat-content h4 {
			font-size: 1.5rem;
			font-weight: 700;
			margin-bottom: 0.25rem;
		}

		.stat-content p {
			color: var(--text-light);
			font-size: 0.9rem;
		}

		/* Responsive Design */
		@media (max-width: 768px) {
			.section-1 {
				padding: 1rem;
			}

			.title {
				font-size: 1.5rem;
			}

			.table-container {
				overflow-x: auto;
			}

			.main-table {
				min-width: 600px;
			}

			.main-table th,
			.main-table td {
				padding: 0.75rem;
				font-size: 0.85rem;
			}

			.task-stats {
				grid-template-columns: 1fr;
			}
		}

		/* Loading Animation */
		.loading {
			display: flex;
			align-items: center;
			justify-content: center;
			padding: 2rem;
		}

		.spinner {
			width: 40px;
			height: 40px;
			border: 4px solid var(--border-color);
			border-top: 4px solid var(--primary-color);
			border-radius: 50%;
			animation: spin 1s linear infinite;
		}

		@keyframes spin {
			0% { transform: rotate(0deg); }
			100% { transform: rotate(360deg); }
		}

		/* Search and Filter Bar (for future enhancement) */
		.toolbar {
			display: flex;
			justify-content: space-between;
			align-items: center;
			margin-bottom: 1.5rem;
			gap: 1rem;
		}

		.search-box {
			flex: 1;
			max-width: 300px;
			position: relative;
		}

		.search-box input {
			width: 100%;
			padding: 0.75rem 1rem 0.75rem 2.5rem;
			border: 2px solid var(--border-color);
			border-radius: var(--radius-md);
			font-size: 0.9rem;
			transition: all 0.2s ease;
		}

		.search-box input:focus {
			outline: none;
			border-color: var(--border-focus);
			box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
		}

		.search-box i {
			position: absolute;
			left: 1rem;
			top: 50%;
			transform: translateY(-50%);
			color: var(--text-light);
		}

		/* Table row number styling */
		.row-number {
			font-weight: 600;
			color: var(--text-light);
			background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
			border-radius: var(--radius-sm);
			padding: 0.25rem 0.5rem;
			min-width: 30px;
			display: inline-flex;
			align-items: center;
			justify-content: center;
		}

		/* Task title truncation */
		.task-title {
			font-weight: 600;
			color: var(--text-dark);
			max-width: 200px;
			overflow: hidden;
			text-overflow: ellipsis;
			white-space: nowrap;
		}

		.task-description {
			max-width: 250px;
			overflow: hidden;
			text-overflow: ellipsis;
			white-space: nowrap;
			color: var(--text-light);
		}
	</style>
</head>
<body>
	<input type="checkbox" id="checkbox">
	<?php include "inc/header.php" ?>
	<div class="body">
		<?php include "inc/nav.php" ?>
		<section class="section-1">
			<h4 class="title">My Tasks</h4>
			
			<?php if (isset($_GET['success'])) {?>
      	  	<div class="success" role="alert">
			  <?php echo stripcslashes($_GET['success']); ?>
			</div>
			<?php } ?>
			
			<?php if ($tasks != 0) { ?>
				<div class="table-container">
					<div class="table-header">
						<h3>Task Overview</h3>
						<p><?php echo count($tasks); ?> task<?php echo count($tasks) != 1 ? 's' : ''; ?> assigned to you</p>
					</div>
					
					<table class="main-table">
						<thead>
							<tr>
								<th>#</th>
								<th>Title</th>
								<th>Description</th>
								<th>Status</th>
								<th>Due Date</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php $i=0; foreach ($tasks as $task) { 
								// Determine status class
								$statusClass = 'status-' . strtolower(str_replace(' ', '-', $task['status']));
								
								// Check if task is overdue
								$dueDate = new DateTime($task['due_date']);
								$today = new DateTime();
								$isOverdue = $dueDate < $today && $task['status'] !== 'completed';
								$isDueSoon = $dueDate->diff($today)->days <= 3 && $dueDate >= $today;
								
								$dueDateClass = '';
								if ($isOverdue) {
									$dueDateClass = 'overdue';
								} elseif ($isDueSoon) {
								    $dueDateClass = 'due-soon';
								}
							?>
							<tr>
								<td><span class="row-number"><?=++$i?></span></td>
								<td><span class="task-title" title="<?=htmlspecialchars($task['title'])?>"><?=htmlspecialchars($task['title'])?></span></td>
								<td><span class="task-description" title="<?=htmlspecialchars($task['description'])?>"><?=htmlspecialchars($task['description'])?></span></td>
								<td>
									<span class="status-badge <?=$statusClass?>">
										<?=ucfirst($task['status'])?>
									</span>
								</td>
								<td>
									<span class="due-date <?=$dueDateClass?>">
										<?=date('M j, Y', strtotime($task['due_date']))?>
									</span>
								</td>
								<td>
									<a href="edit-task-employee.php?id=<?=$task['id']?>" class="edit-btn">Edit</a>
								</td>
							</tr>
						   <?php } ?>
						</tbody>
					</table>
				</div>
			<?php } else { ?>
				<div class="empty-state">
					<div class="empty-state-icon">📋</div>
					<h3>No Tasks Yet</h3>
					<p>You don't have any tasks assigned at the moment. Check back later or contact your supervisor.</p>
				</div>
			<?php } ?>
		</section>
	</div>

<script type="text/javascript">
	// Navigation active state
	var active = document.querySelector("#navList li:nth-child(2)");
	if (active) {
		active.classList.add("active");
	}

	// Add loading animation on page load
	document.addEventListener('DOMContentLoaded', function() {
		const tableContainer = document.querySelector('.table-container');
		if (tableContainer) {
			tableContainer.style.opacity = '0';
			tableContainer.style.transform = 'translateY(20px)';
			
			setTimeout(() => {
				tableContainer.style.transition = 'all 0.3s ease';
				tableContainer.style.opacity = '1';
				tableContainer.style.transform = 'translateY(0)';
			}, 100);
		}
	});

	// Enhanced table interactions
	document.querySelectorAll('.main-table tr').forEach(row => {
		if (row.querySelector('th')) return; // Skip header row
		
		row.addEventListener('mouseenter', function() {
			this.style.transform = 'translateY(-2px)';
		});
		
		row.addEventListener('mouseleave', function() {
			this.style.transform = 'translateY(0)';
		});
	});

	// Auto-hide success messages
	const successAlert = document.querySelector('.success');
	if (successAlert) {
		setTimeout(() => {
			successAlert.style.transition = 'all 0.3s ease';
			successAlert.style.opacity = '0';
			successAlert.style.transform = 'translateY(-10px)';
			setTimeout(() => {
				successAlert.remove();
			}, 300);
		}, 5000);
	}

	// Add tooltips for truncated text
	document.querySelectorAll('.task-title, .task-description').forEach(element => {
		if (element.scrollWidth > element.clientWidth) {
			element.style.cursor = 'help';
		}
	});

	// Keyboard navigation
	document.addEventListener('keydown', function(e) {
		if (e.key === 'r' && (e.ctrlKey || e.metaKey)) {
			e.preventDefault();
			location.reload();
		}
	});

	// Add smooth scroll behavior
	document.documentElement.style.scrollBehavior = 'smooth';

	// Status-based row highlighting
	document.querySelectorAll('.status-badge').forEach(badge => {
		const row = badge.closest('tr');
		const status = badge.textContent.toLowerCase().trim();
		
		if (status === 'completed') {
			row.style.opacity = '0.8';
		} else if (status === 'overdue') {
			row.style.borderLeft = '4px solid var(--error-color)';
		} else if (status === 'in progress') {
			row.style.borderLeft = '4px solid var(--info-color)';
		}
	});

	// Add ripple effect to buttons
	document.querySelectorAll('.edit-btn').forEach(button => {
		button.addEventListener('click', function(e) {
			const ripple = document.createElement('span');
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
				background: rgba(255, 255, 255, 0.5);
				border-radius: 50%;
				transform: scale(0);
				animation: ripple 0.6s ease-out;
				pointer-events: none;
			`;
			
			this.style.position = 'relative';
			this.style.overflow = 'hidden';
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
<?php } else { 
   $em = "First login";
   header("Location: login.php?error=$em");
   exit();
}
?>
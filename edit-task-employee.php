<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "employee") {
    include "DB_connection.php";
    include "app/Model/Task.php";
    include "app/Model/User.php";
    
    if (!isset($_GET['id'])) {
    	 header("Location: tasks.php");
    	 exit();
    }
    $id = $_GET['id'];
    $task = get_task_by_id($conn, $id);
    if ($task == 0) {
    	 header("Location: tasks.php");
    	 exit();
    }
   $users = get_all_users($conn);
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Edit Task</title>
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
			content: '←';
			font-weight: bold;
		}

		.form-1 {
			background: var(--white);
			padding: 2.5rem;
			border-radius: var(--radius-lg);
			box-shadow: var(--shadow-lg);
			border: 1px solid var(--border-color);
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

		.form-header {
			text-align: center;
			margin-bottom: 2rem;
		}

		.form-header h3 {
			font-size: 1.5rem;
			font-weight: 600;
			color: var(--text-dark);
			margin-bottom: 0.5rem;
		}

		.form-header p {
			color: var(--text-light);
			font-size: 0.95rem;
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

		/* Task Information Display */
		.task-info {
			background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
			padding: 1.5rem;
			border-radius: var(--radius-md);
			border: 1px solid var(--border-color);
			margin-bottom: 1rem;
		}

		.task-info-item {
			display: flex;
			align-items: flex-start;
			gap: 1rem;
			margin-bottom: 1rem;
		}

		.task-info-item:last-child {
			margin-bottom: 0;
		}

		.task-info-icon {
			background: var(--primary-color);
			color: var(--white);
			width: 40px;
			height: 40px;
			border-radius: var(--radius-md);
			display: flex;
			align-items: center;
			justify-content: center;
			font-size: 1rem;
			flex-shrink: 0;
		}

		.task-info-content {
			flex: 1;
		}

		.task-info-label {
			font-weight: 600;
			color: var(--text-dark);
			margin-bottom: 0.25rem;
			font-size: 0.9rem;
			text-transform: uppercase;
			letter-spacing: 0.05em;
		}

		.task-info-value {
			color: var(--text-dark);
			font-size: 1rem;
			line-height: 1.5;
			background: var(--white);
			padding: 0.75rem;
			border-radius: var(--radius-sm);
			border: 1px solid var(--border-color);
			word-wrap: break-word;
		}

		.task-info-value.empty {
			color: var(--text-light);
			font-style: italic;
		}

		/* Status Selection Enhancement */
		.status-section {
			background: var(--white);
			padding: 1.5rem;
			border-radius: var(--radius-md);
			border: 2px solid var(--border-color);
			margin-bottom: 1.5rem;
			transition: all 0.2s ease;
		}

		.status-section:hover {
			border-color: var(--primary-color);
			box-shadow: var(--shadow-md);
		}

		.status-section .input-holder {
			margin-bottom: 0;
		}

		.status-label {
			display: flex;
			align-items: center;
			gap: 0.5rem;
			font-weight: 600;
			color: var(--text-dark);
			margin-bottom: 1rem;
			font-size: 1rem;
		}

		.status-label::before {
			content: '🔄';
			font-size: 1.2rem;
		}

		.input-1 {
			width: 100%;
			padding: 0.875rem 1rem;
			border: 2px solid var(--border-color);
			border-radius: var(--radius-md);
			font-size: 1rem;
			transition: all 0.2s ease;
			background: var(--white);
			color: var(--text-dark);
			cursor: pointer;
			appearance: none;
			background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
			background-position: right 1rem center;
			background-repeat: no-repeat;
			background-size: 1rem;
			padding-right: 3rem;
		}

		.input-1:focus {
			outline: none;
			border-color: var(--border-focus);
			box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
			transform: translateY(-1px);
		}

		.input-1:hover {
			border-color: #d1d5db;
		}

		/* Status Options Styling */
		.input-1 option {
			padding: 0.5rem;
			font-size: 1rem;
		}

		.input-1 option[value="pending"] {
			background-color: var(--warning-bg);
			color: var(--warning-color);
		}

		.input-1 option[value="in_progress"] {
			background-color: var(--info-bg);
			color: var(--info-color);
		}

		.input-1 option[value="completed"] {
			background-color: var(--success-bg);
			color: var(--success-color);
		}

		.edit-btn {
			background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
			color: var(--white);
			padding: 0.875rem 2rem;
			border: none;
			border-radius: var(--radius-md);
			font-size: 1rem;
			font-weight: 600;
			cursor: pointer;
			transition: all 0.2s ease;
			display: flex;
			align-items: center;
			gap: 0.5rem;
			min-width: 140px;
			justify-content: center;
			width: 100%;
			margin-top: 1rem;
		}

		.edit-btn:hover {
			transform: translateY(-2px);
			box-shadow: var(--shadow-lg);
		}

		.edit-btn:active {
			transform: translateY(0);
		}

		.edit-btn::before {
			content: '💾';
			font-size: 1rem;
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

		/* Current Status Display */
		.current-status {
			display: inline-flex;
			align-items: center;
			gap: 0.5rem;
			padding: 0.5rem 1rem;
			border-radius: var(--radius-sm);
			font-size: 0.85rem;
			font-weight: 600;
			text-transform: uppercase;
			letter-spacing: 0.05em;
			margin-bottom: 1rem;
		}

		.current-status.pending {
			background: var(--warning-bg);
			color: var(--warning-color);
			border: 1px solid var(--warning-border);
		}

		.current-status.pending::before {
			content: '⏳';
		}

		.current-status.in_progress {
			background: var(--info-bg);
			color: var(--info-color);
			border: 1px solid var(--info-border);
		}

		.current-status.in_progress::before {
			content: '🔄';
		}

		.current-status.completed {
			background: var(--success-bg);
			color: var(--success-color);
			border: 1px solid var(--success-border);
		}

		.current-status.completed::before {
			content: '✅';
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

			.task-info-item {
				flex-direction: column;
				gap: 0.5rem;
			}

			.task-info-icon {
				align-self: flex-start;
			}
		}

		/* Loading Animation */
		.edit-btn.loading {
			pointer-events: none;
			opacity: 0.7;
		}

		.edit-btn.loading::before {
			content: '';
			width: 16px;
			height: 16px;
			border: 2px solid transparent;
			border-top: 2px solid currentColor;
			border-radius: 50%;
			animation: spin 1s linear infinite;
		}

		@keyframes spin {
			to {
				transform: rotate(360deg);
			}
		}

		/* Progress Indicator */
		.progress-indicator {
			display: flex;
			justify-content: space-between;
			align-items: center;
			margin-bottom: 2rem;
			padding: 1rem;
			background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
			border-radius: var(--radius-md);
			border: 1px solid var(--border-color);
		}

		.progress-step {
			display: flex;
			flex-direction: column;
			align-items: center;
			gap: 0.5rem;
			flex: 1;
			position: relative;
		}

		.progress-step:not(:last-child)::after {
			content: '';
			position: absolute;
			top: 15px;
			left: calc(50% + 20px);
			width: calc(100% - 40px);
			height: 2px;
			background: var(--border-color);
		}

		.progress-step.active::after {
			background: var(--primary-color);
		}

		.progress-step.completed::after {
			background: var(--success-color);
		}

		.progress-dot {
			width: 30px;
			height: 30px;
			border-radius: 50%;
			border: 2px solid var(--border-color);
			background: var(--white);
			display: flex;
			align-items: center;
			justify-content: center;
			font-size: 0.8rem;
			z-index: 1;
		}

		.progress-step.active .progress-dot {
			border-color: var(--primary-color);
			background: var(--primary-color);
			color: var(--white);
		}

		.progress-step.completed .progress-dot {
			border-color: var(--success-color);
			background: var(--success-color);
			color: var(--white);
		}

		.progress-label {
			font-size: 0.8rem;
			font-weight: 500;
			color: var(--text-light);
			text-align: center;
		}

		.progress-step.active .progress-label,
		.progress-step.completed .progress-label {
			color: var(--text-dark);
			font-weight: 600;
		}

		/* Task metadata */
		.task-metadata {
			display: grid;
			grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
			gap: 1rem;
			margin-bottom: 2rem;
		}

		.metadata-card {
			background: var(--white);
			padding: 1rem;
			border-radius: var(--radius-md);
			border: 1px solid var(--border-color);
			display: flex;
			align-items: center;
			gap: 0.75rem;
		}

		.metadata-icon {
			width: 35px;
			height: 35px;
			border-radius: var(--radius-sm);
			display: flex;
			align-items: center;
			justify-content: center;
			font-size: 0.9rem;
		}

		.metadata-content h5 {
			font-size: 0.8rem;
			color: var(--text-light);
			margin-bottom: 0.25rem;
			text-transform: uppercase;
			letter-spacing: 0.05em;
		}

		.metadata-content p {
			font-weight: 600;
			color: var(--text-dark);
		}

		.due-date-icon {
			background: var(--warning-bg);
			color: var(--warning-color);
		}

		.assigned-icon {
			background: var(--info-bg);
			color: var(--info-color);
		}

		.priority-icon {
			background: var(--error-bg);
			color: var(--error-color);
		}
	</style>
</head>
<body>
	<input type="checkbox" id="checkbox">
	<?php include "inc/header.php" ?>
	<div class="body">
		<?php include "inc/nav.php" ?>
		<section class="section-1">
			<h4 class="title">Edit Task <a href="my_task.php">Back to Tasks</a></h4>
			
			<form class="form-1"
			      method="POST"
			      action="app/update-task-employee.php"
			      id="editTaskForm">
			      
			      <div class="form-header">
			      	<h3>Update Task Status</h3>
			      	<p>Modify the current status of your assigned task</p>
			      </div>
			      
			      <?php if (isset($_GET['error'])) {?>
      	  		<div class="danger" role="alert">
				  <?php echo stripcslashes($_GET['error']); ?>
				</div>
      	  	  <?php } ?>

      	  	  <?php if (isset($_GET['success'])) {?>
      	  		<div class="success" role="alert">
				  <?php echo stripcslashes($_GET['success']); ?>
				</div>
      	  	  <?php } ?>

      	  	  <!-- Progress Indicator -->
      	  	  <div class="progress-indicator">
				    <div class="progress-step <?php echo ($task['status'] == 'pending' || $task['status'] == 'in_progress' || $task['status'] == 'completed') ? 'completed' : ''; ?>">
				        <div class="progress-dot">1</div>
				        <div class="progress-label">Pending</div>
				    </div>
				    <div class="progress-step <?php echo ($task['status'] == 'in_progress' || $task['status'] == 'completed') ? 'completed' : ($task['status'] == 'pending' ? 'active' : ''); ?>">
				        <div class="progress-dot">2</div>
				        <div class="progress-label">In Progress</div>
				    </div>
				    <div class="progress-step <?php echo ($task['status'] == 'completed') ? 'completed' : ($task['status'] == 'in_progress' ? 'active' : ''); ?>">
				        <div class="progress-dot">✓</div>
				        <div class="progress-label">Completed</div>
				    </div>
				</div>
      	  	  
      	  	  <!-- Task Information Display -->
				<div class="task-info">
					<div class="task-info-item">
						<div class="task-info-icon">
							<i class="fa fa-tasks"></i>
						</div>
						<div class="task-info-content">
							<div class="task-info-label">Task Title</div>
							<div class="task-info-value"><?=htmlspecialchars($task['title'])?></div>
						</div>
					</div>
					
					<div class="task-info-item">
						<div class="task-info-icon">
							<i class="fa fa-align-left"></i>
						</div>
						<div class="task-info-content">
							<div class="task-info-label">Description</div>
							<div class="task-info-value <?php echo empty(trim($task['description'])) ? 'empty' : ''; ?>">
								<?php echo !empty(trim($task['description'])) ? htmlspecialchars($task['description']) : 'No description provided'; ?>
							</div>
						</div>
					</div>
				</div>

				<!-- Current Status Display -->
				<div style="margin-bottom: 1rem;">
					<p style="font-size: 0.9rem; color: var(--text-light); margin-bottom: 0.5rem;">Current Status:</p>
					<div class="current-status <?=$task['status']?>">
						<?=ucwords(str_replace('_', ' ', $task['status']))?>
					</div>
				</div>

				<!-- Status Update Section -->
				<div class="status-section">
					<div class="input-holder">
						<div class="status-label">Update Status</div>
						<select name="status" class="input-1" id="statusSelect">
							<option value="pending" <?php if($task['status'] == "pending") echo "selected"; ?>>
								⏳ Pending
							</option>
							<option value="in_progress" <?php if($task['status'] == "in_progress") echo "selected"; ?>>
								🔄 In Progress
							</option>
							<option value="completed" <?php if($task['status'] == "completed") echo "selected"; ?>>
								✅ Completed
							</option>
						</select>
					</div>
				</div>

				<input type="text" name="id" value="<?=$task['id']?>" hidden>
				<button type="submit" class="edit-btn" id="updateBtn">Update Task Status</button>
			</form>
		</section>
	</div>

<script type="text/javascript">
	// Navigation active state
	var active = document.querySelector("#navList li:nth-child(2)");
	if (active) {
		active.classList.add("active");
	}

	// Form submission with loading state
	document.getElementById('editTaskForm').addEventListener('submit', function(e) {
		const updateBtn = document.getElementById('updateBtn');
		const status = document.querySelector('select[name="status"]').value;

		if (!status) {
			e.preventDefault();
			alert('Please select a status');
			return;
		}

		updateBtn.classList.add('loading');
		updateBtn.textContent = 'Updating...';
	});

	// Status change preview
	const statusSelect = document.getElementById('statusSelect');
	const currentStatusDiv = document.querySelector('.current-status');
	
	statusSelect.addEventListener('change', function() {
		const selectedValue = this.value;
		const selectedText = this.options[this.selectedIndex].text;
		
		// Update current status display for preview
		currentStatusDiv.className = `current-status ${selectedValue}`;
		currentStatusDiv.textContent = selectedText;
		
		// Add visual feedback
		this.style.borderColor = 'var(--success-color)';
		this.style.backgroundColor = '#f0fdf4';
		
		setTimeout(() => {
			this.style.borderColor = 'var(--border-color)';
			this.style.backgroundColor = 'var(--white)';
		}, 1000);
	});

	// Auto-focus status select
	document.getElementById('statusSelect').focus();

	// Enhanced form validation
	statusSelect.addEventListener('change', function() {
		if (this.value !== '') {
			this.style.borderColor = 'var(--success-color)';
		}
	});

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

	// Keyboard shortcuts
	document.addEventListener('keydown', function(e) {
		// Ctrl/Cmd + Enter to submit form
		if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
			e.preventDefault();
			document.getElementById('editTaskForm').requestSubmit();
		}
		
		// Escape to go back
		if (e.key === 'Escape') {
			if (confirm('Go back to tasks without saving?')) {
				window.location.href = 'my_task.php';
			}
		}

		// Arrow keys to change status
		if (e.key === 'ArrowUp' || e.key === 'ArrowDown') {
			if (document.activeElement === statusSelect) {
				e.preventDefault();
				const options = statusSelect.options;
				let currentIndex = statusSelect.selectedIndex;
				
				if (e.key === 'ArrowUp' && currentIndex > 0) {
					statusSelect.selectedIndex = currentIndex - 1;
				} else if (e.key === 'ArrowDown' && currentIndex < options.length - 1) {
					statusSelect.selectedIndex = currentIndex + 1;
				}
				
				statusSelect.dispatchEvent(new Event('change'));
			}
		}
	});

	// Progress indicator animation
	function updateProgressIndicator() {
		const currentStatus = '<?=$task['status']?>';
		const steps = document.querySelectorAll('.progress-step');
		
		steps.forEach((step, index) => {
			if (
				(index === 0 && (currentStatus === 'pending' || currentStatus === 'in_progress' || currentStatus === 'completed')) ||
				(index === 1 && (currentStatus === 'in_progress' || currentStatus === 'completed')) ||
				(index === 2 && currentStatus === 'completed')
			) {
				step.classList.add('completed');
			} else if (
				(index === 1 && currentStatus === 'pending') ||
				(index === 2 && currentStatus === 'in_progress')
			) {
				step.classList.add('active');
			}
		});
	}

	// Initialize progress indicator
	updateProgressIndicator();

	// Add ripple effect to button
	document.getElementById('updateBtn').addEventListener('click', function(e) {
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

	// Form reset after successful submission
	if (window.location.search.includes('success=')) {
		setTimeout(() => {
			// Optional: redirect back to tasks after successful update
			// window.location.href = 'my_task.php';
		}, 2000);
	}

	// Status change confirmation for significant changes
    	// Status change confirmation for significant changes
	statusSelect.addEventListener('change', function() {
		const oldStatus = '<?=$task['status']?>';
		const newStatus = this.value;
		
		// If changing from completed to something else
		if (oldStatus === 'completed' && newStatus !== 'completed') {
			if (!confirm('Are you sure you want to mark this task as not completed? This will reverse its completed status.')) {
				this.value = oldStatus;
				currentStatusDiv.className = `current-status ${oldStatus}`;
				currentStatusDiv.textContent = this.options[this.selectedIndex].text;
				return;
			}
		}
		
		// If changing to completed
		if (newStatus === 'completed') {
			if (!confirm('Mark this task as completed? This will finalize the task.')) {
				this.value = oldStatus;
				currentStatusDiv.className = `current-status ${oldStatus}`;
				currentStatusDiv.textContent = this.options[this.selectedIndex].text;
				return;
			}
		}
	});

	// Accessibility improvements
	statusSelect.addEventListener('focus', function() {
		this.parentElement.style.boxShadow = '0 0 0 3px rgba(79, 70, 229, 0.2)';
	});
	
	statusSelect.addEventListener('blur', function() {
		this.parentElement.style.boxShadow = 'none';
	});

	// Touch device detection and adjustments
	if ('ontouchstart' in window || navigator.maxTouchPoints) {
		document.querySelector('.edit-btn').style.padding = '1rem 2rem';
		document.querySelectorAll('input, select').forEach(el => {
			el.style.minHeight = '44px'; // Better touch target size
		});
	}

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
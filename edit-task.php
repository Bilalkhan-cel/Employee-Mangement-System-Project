<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "admin") {
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
			padding: 0.75rem 1rem;
			border: 2px solid var(--border-color);
			border-radius: var(--radius-md);
			font-size: 1rem;
			transition: all 0.2s ease;
			background: var(--white);
			color: var(--text-dark);
		}

		.input-1:focus {
			outline: none;
			border-color: var(--border-focus);
			box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
			transform: translateY(-1px);
		}

		.input-1::placeholder {
			color: var(--text-light);
		}

		/* Textarea specific styles */
		textarea.input-1 {
			resize: vertical;
			min-height: 120px;
		}

		/* Select specific styles */
		select.input-1 {
			cursor: pointer;
			appearance: none;
			background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
			background-position: right 1rem center;
			background-repeat: no-repeat;
			background-size: 1rem;
			padding-right: 3rem;
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
			content: '✏';
			opacity: 1;
			transition: opacity 0.2s ease;
			font-size: 1.1rem;
		}

		.edit-btn:hover::after {
			content: '';
			opacity: 0;
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

		/* Enhanced Form Focus States */
		.input-holder:focus-within lable {
			color: var(--primary-color);
		}

		/* Additional hover effects */
		.input-holder {
			transition: all 0.2s ease;
		}

		.input-holder:hover .input-1:not(:focus) {
			border-color: #d1d5db;
		}

		/* Form Header Enhancement */
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

		/* Required field indicator */
		.input-holder lable.required::after {
			content: ' *';
			color: var(--error-color);
			font-weight: bold;
		}

		/* Date input enhancement */
		input[type="date"].input-1 {
			position: relative;
		}

		input[type="date"].input-1::-webkit-calendar-picker-indicator {
			cursor: pointer;
			filter: invert(0.5);
		}

		/* Custom scrollbar for textarea */
		textarea.input-1::-webkit-scrollbar {
			width: 8px;
		}

		textarea.input-1::-webkit-scrollbar-track {
			background: #f1f3f4;
			border-radius: 10px;
		}

		textarea.input-1::-webkit-scrollbar-thumb {
			background: var(--primary-color);
			border-radius: 10px;
		}

		textarea.input-1::-webkit-scrollbar-thumb:hover {
			background: var(--primary-hover);
		}

		.icon-input {
			position: relative;
		}

		.icon-input i {
			position: absolute;
			left: 1rem;
			top: 50%;
			transform: translateY(-50%);
			color: var(--text-light);
			z-index: 1;
		}

		.icon-input .input-1 {
			padding-left: 2.5rem;
		}

		/* Task Info Display */
		.task-info {
			background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
			padding: 1.5rem;
			border-radius: var(--radius-md);
			margin-bottom: 2rem;
			border-left: 4px solid var(--primary-color);
		}

		.task-info h4 {
			color: var(--primary-color);
			font-size: 1.1rem;
			font-weight: 600;
			margin-bottom: 0.5rem;
			display: flex;
			align-items: center;
			gap: 0.5rem;
		}

		.task-info h4::before {
			content: 'ℹ';
			font-size: 1.2rem;
		}

		.task-info p {
			color: var(--text-dark);
			font-size: 0.9rem;
			margin: 0;
		}

		/* Status Display */
		.current-status {
			display: inline-block;
			padding: 0.375rem 0.875rem;
			border-radius: 50px;
			font-size: 0.8rem;
			font-weight: 600;
			text-transform: uppercase;
			letter-spacing: 0.025em;
			margin-top: 0.5rem;
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
			background: #eff6ff;
			color: #3b82f6;
		}

		/* Enhanced Due Date Input */
		.date-info {
			font-size: 0.8rem;
			color: var(--text-light);
			margin-top: 0.25rem;
		}

		.date-info.overdue {
			color: var(--error-color);
			font-weight: 600;
		}

		.date-info.due-today {
			color: var(--warning-color);
			font-weight: 600;
		}

		/* Form Actions */
		.form-actions {
			display: flex;
			gap: 1rem;
			margin-top: 2rem;
		}

		.form-actions .edit-btn {
			flex: 1;
			margin-top: 0;
		}

		.cancel-btn {
			background: var(--white);
			color: var(--text-dark);
			border: 2px solid var(--border-color);
			padding: 0.875rem 2rem;
			border-radius: var(--radius-md);
			font-size: 1rem;
			font-weight: 600;
			cursor: pointer;
			transition: all 0.2s ease;
			text-decoration: none;
			display: flex;
			align-items: center;
			justify-content: center;
			gap: 0.5rem;
			flex: 1;
		}

		.cancel-btn:hover {
			background: var(--background-color);
			border-color: var(--secondary-color);
			transform: translateY(-1px);
		}

		.cancel-btn::before {
			content: '✕';
			font-size: 1rem;
		}

		/* Validation States */
		.input-1.valid {
			border-color: var(--success-color);
		}

		.input-1.invalid {
			border-color: var(--error-color);
		}

		.validation-message {
			font-size: 0.8rem;
			margin-top: 0.25rem;
			display: none;
		}

		.validation-message.error {
			color: var(--error-color);
			display: block;
		}

		.validation-message.success {
			color: var(--success-color);
			display: block;
		}

		/* Character Counter */
		.character-counter {
			font-size: 0.8rem;
			color: var(--text-light);
			text-align: right;
			margin-top: 0.25rem;
		}

		.character-counter.warning {
			color: var(--warning-color);
		}

		.character-counter.error {
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
			<h4 class="title">Edit Task <a href="tasks.php">Back to Tasks</a></h4>
			
			<div class="task-info">
				<h4>Editing Task</h4>
				<p>Update the task details below to make changes to the assignment.</p>
				<?php if (isset($task['status']) && $task['status']) { ?>
				<span class="current-status status-<?=strtolower(str_replace(' ', '-', $task['status']))?>"><?=$task['status']?></span>
				<?php } ?>
			</div>
			
			<form class="form-1"
			      method="POST"
			      action="app/update-task.php"
			      id="editTaskForm">
			      
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
      	  	  
				<div class="input-holder">
					<lable class="required">Task Title</lable>
					<div class="icon-input">
						<i class="fa fa-tasks"></i>
						<input type="text" 
						       name="title" 
						       class="input-1" 
						       placeholder="Enter task title" 
						       value="<?=htmlspecialchars($task['title'])?>"
						       required><br>
					</div>
					<div class="validation-message" id="titleValidation"></div>
				</div>
				
				<div class="input-holder">
					<lable>Description</lable>
					<div class="icon-input">
						<i class="fa fa-align-left"></i>
						<textarea name="description" 
						          class="input-1" 
						          placeholder="Describe the task details, requirements, and expected outcomes..."
						          style="padding-left: 2.5rem;"
						          id="descriptionTextarea"><?=htmlspecialchars($task['description'])?></textarea><br>
					</div>
					<div class="character-counter" id="descriptionCounter">0 / 500 characters</div>
				</div>
				
				<div class="input-holder">
					<lable>Due Date</lable>
					<div class="icon-input">
						<i class="fa fa-calendar"></i>
						<input type="date" 
						       name="due_date" 
						       class="input-1" 
						       id="dueDateInput"
						       value="<?=$task['due_date']?>"><br>
					</div>
					<div class="date-info" id="dateInfo"></div>
				</div>
				
				<div class="input-holder">
					<lable class="required">Assigned to</lable>
					<div class="icon-input">
						<i class="fa fa-user"></i>
						<select name="assigned_to" class="input-1" required>
							<option value="">Select employee</option>
							<?php if ($users != 0) { 
								foreach ($users as $user) {
									if ($task['assigned_to'] == $user['id']) { ?>
										<option selected value="<?=$user['id']?>"><?=htmlspecialchars($user['full_name'])?></option>
							<?php } else { ?>
	                  			<option value="<?=$user['id']?>"><?=htmlspecialchars($user['full_name'])?></option>
							<?php } } } ?>
						</select><br>
					</div>
				</div>
				
				<input type="text" name="id" value="<?=$task['id']?>" hidden>

				<div class="form-actions">
					<a href="tasks.php" class="cancel-btn">Cancel</a>
					<button type="submit" class="edit-btn" id="submitBtn">Update Task</button>
				</div>
			</form>
		</section>
	</div>

<script type="text/javascript">
	// Navigation active state
	var active = document.querySelector("#navList li:nth-child(4)");
	if (active) {
		active.classList.add("active");
	}

	// Set minimum date to today for due date
	document.getElementById('dueDateInput').min = new Date().toISOString().split('T')[0];

	// Form submission with loading state
	document.getElementById('editTaskForm').addEventListener('submit', function(e) {
		const submitBtn = document.getElementById('submitBtn');
		const title = document.querySelector('input[name="title"]').value.trim();
		const assignedTo = document.querySelector('select[name="assigned_to"]').value;

		// Basic validation
		if (!title || !assignedTo) {
			e.preventDefault();
			alert('Please fill in all required fields');
			return;
		}

		submitBtn.classList.add('loading');
		submitBtn.textContent = 'Updating Task...';
	});

	// Enhanced form validation
	const titleInput = document.querySelector('input[name="title"]');
	const titleValidation = document.getElementById('titleValidation');

	titleInput.addEventListener('blur', function() {
		if (this.value.trim() === '') {
			this.classList.add('invalid');
			this.classList.remove('valid');
			titleValidation.textContent = 'Title is required';
			titleValidation.classList.add('error');
		} else if (this.value.trim().length < 3) {
			this.classList.add('invalid');
			this.classList.remove('valid');
			titleValidation.textContent = 'Title must be at least 3 characters';
			titleValidation.classList.add('error');
		} else {
			this.classList.remove('invalid');
			this.classList.add('valid');
			titleValidation.textContent = 'Title looks good';
			titleValidation.classList.remove('error');
			titleValidation.classList.add('success');
		}
	});

	titleInput.addEventListener('input', function() {
		if (this.value.trim() !== '' && this.value.trim().length >= 3) {
			this.classList.remove('invalid');
			this.classList.add('valid');
			titleValidation.style.display = 'none';
		}
	});

	// Character counter for description
	const descriptionTextarea = document.getElementById('descriptionTextarea');
	const descriptionCounter = document.getElementById('descriptionCounter');
	const maxLength = 500;

	function updateCharacterCount() {
		const length = descriptionTextarea.value.length;
		descriptionCounter.textContent = `${length} / ${maxLength} characters`;
		
		if (length > maxLength * 0.9) {
			descriptionCounter.classList.add('warning');
			descriptionCounter.classList.remove('error');
		} else if (length >= maxLength) {
			descriptionCounter.classList.add('error');
			descriptionCounter.classList.remove('warning');
		} else {
			descriptionCounter.classList.remove('warning', 'error');
		}
	}

	descriptionTextarea.addEventListener('input', updateCharacterCount);
	
	// Initialize character count
	updateCharacterCount();

	// Enhanced date validation
	const dueDateInput = document.getElementById('dueDateInput');
	const dateInfo = document.getElementById('dateInfo');

	function updateDateInfo() {
		const selectedDate = new Date(dueDateInput.value);
		const today = new Date();
		today.setHours(0, 0, 0, 0);
		selectedDate.setHours(0, 0, 0, 0);
		
		const diffTime = selectedDate - today;
		const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

		if (!dueDateInput.value) {
			dateInfo.textContent = 'No deadline set';
			dateInfo.className = 'date-info';
			return;
		}

		if (diffDays < 0) {
			dateInfo.textContent = `Overdue by ${Math.abs(diffDays)} day(s)`;
			dateInfo.className = 'date-info overdue';
			dueDateInput.style.borderColor = 'var(--error-color)';
		} else if (diffDays === 0) {
			dateInfo.textContent = 'Due today';
			dateInfo.className = 'date-info due-today';
			dueDateInput.style.borderColor = 'var(--warning-color)';
		} else if (diffDays === 1) {
			dateInfo.textContent = 'Due tomorrow';
			dateInfo.className = 'date-info';
			dueDateInput.style.borderColor = 'var(--success-color)';
		} else {
			dateInfo.textContent = `Due in ${diffDays} days`;
			dateInfo.className = 'date-info';
			dueDateInput.style.borderColor = 'var(--success-color)';
		}
	}

	dueDateInput.addEventListener('change', updateDateInfo);
	
	// Initialize date info
	updateDateInfo();

	// Auto-focus first input
	document.querySelector('.input-1').focus();

	// Enhanced validation for all required fields
	const requiredInputs = document.querySelectorAll('.input-1[required]');
	requiredInputs.forEach(input => {
		input.addEventListener('blur', function() {
			if (this.value.trim() === '') {
				this.classList.add('invalid');
				this.classList.remove('valid');
			} else {
				this.classList.remove('invalid');
				this.classList.add('valid');
			}
		});

		input.addEventListener('input', function() {
			if (this.value.trim() !== '') {
				this.classList.remove('invalid');
				this.classList.add('valid');
			}
		});
	});

	// Keyboard shortcuts
	document.addEventListener('keydown', function(e) {
		// Ctrl/Cmd + Enter to submit form
		if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
			e.preventDefault();
			document.getElementById('editTaskForm').requestSubmit();
		}
		
		// Escape to go back
		if (e.key === 'Escape') {
			if (confirm('Discard changes and go back?')) {
				window.location.href = 'tasks.php';
			}
		}
	});

	// Auto-save draft functionality (optional)
	let autoSaveTimeout;
	const formInputs = document.querySelectorAll('.input-1');
	
	formInputs.forEach(input => {
		input.addEventListener('input', function() {
			clearTimeout(autoSaveTimeout);
			autoSaveTimeout = setTimeout(() => {
				// You can implement auto-save functionality here
				console.log('Auto-saving draft...');
			}, 2000);
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

	// Prevent accidental navigation away
	let formChanged = false;
	formInputs.forEach(input => {
		const originalValue = input.value;
		input.addEventListener('input', function() {
			formChanged = (this.value !== originalValue);
		});
	});

	window.addEventListener('beforeunload', function(e) {
		if (formChanged) {
			e.preventDefault();
			e.returnValue = '';
		}
	});

	// Reset form changed flag on successful submission
	document.getElementById('editTaskForm').addEventListener('submit', function() {
		formChanged = false;
	});
</script>
</body>
</html>
<?php }else{ 
   $em = "First login";
   header("Location: login.php?error=$em");
   exit();
}
?>
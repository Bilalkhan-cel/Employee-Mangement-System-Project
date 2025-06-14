<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "admin") {
    include "DB_connection.php";
    include "app/Model/User.php";
    
    if (!isset($_GET['id'])) {
    	 header("Location: user.php");
    	 exit();
    }
    $id = $_GET['id'];
    $user = get_user_by_id($conn, $id);
    if ($user == 0) {
    	 header("Location: user.php");
    	 exit();
    }
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Edit User</title>
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
			--error-color: #ef4444;
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
		}

		.edit-btn:hover {
			transform: translateY(-2px);
			box-shadow: var(--shadow-lg);
		}

		.edit-btn:active {
			transform: translateY(0);
		}

		.edit-btn::after {
			content: '✓';
			opacity: 0;
			transition: opacity 0.2s ease;
		}

		.edit-btn:hover::after {
			opacity: 1;
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
		}

		.danger {
			background: #fef2f2;
			color: var(--error-color);
			border: 1px solid #fecaca;
		}

		.danger::before {
			content: '⚠';
			font-size: 1.2rem;
		}

		.success {
			background: #f0fdf4;
			color: var(--success-color);
			border: 1px solid #bbf7d0;
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

		.edit-btn.loading::after {
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

		/* Subtle animations */
		.form-1 {
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

		/* Hidden input styling */
		input[type="text"][hidden] {
			display: none;
		}

		/* Additional hover effects */
		.input-holder {
			transition: all 0.2s ease;
		}

		.input-holder:hover .input-1:not(:focus) {
			border-color: #d1d5db;
		}
	</style>
</head>
<body>
	<input type="checkbox" id="checkbox">
	<?php include "inc/header.php" ?>
	<div class="body">
		<?php include "inc/nav.php" ?>
		<section class="section-1">
			<h4 class="title">Edit User <a href="user.php">Back to Users</a></h4>
			<form class="form-1"
			      method="POST"
			      action="app/update-user.php"
			      id="editUserForm">
			      
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
					<lable>Full Name</lable>
					<input type="text" 
					       name="full_name" 
					       class="input-1" 
					       placeholder="Enter full name" 
					       value="<?=$user['full_name']?>"
					       required><br>
				</div>
				
				<div class="input-holder">
					<lable>Username</lable>
					<input type="text" 
					       name="user_name" 
					       value="<?=$user['username']?>" 
					       class="input-1" 
					       placeholder="Enter username"
					       required><br>
				</div>
				
				<div class="input-holder">
					<lable>Password</lable>
					<input type="password" 
					       value="" 
					       name="password" 
					       class="input-1" 
					       placeholder="Enter new password"><br>
				</div>
				
				<input type="text" name="id" value="<?=$user['id']?>" hidden>
				<button type="submit" class="edit-btn" id="submitBtn">Update User</button>
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
	document.getElementById('editUserForm').addEventListener('submit', function() {
		const submitBtn = document.getElementById('submitBtn');
		submitBtn.classList.add('loading');
		submitBtn.textContent = 'Updating...';
	});

	// Enhanced form validation
	const inputs = document.querySelectorAll('.input-1[required]');
	inputs.forEach(input => {
		input.addEventListener('blur', function() {
			if (this.value.trim() === '') {
				this.style.borderColor = 'var(--error-color)';
			} else {
				this.style.borderColor = 'var(--border-color)';
			}
		});
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
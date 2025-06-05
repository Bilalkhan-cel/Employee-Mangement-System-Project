<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Login | Task Management System</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
	<style>
		* {
			margin: 0;
			padding: 0;
			box-sizing: border-box;
		}

		body {
			font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
			min-height: 100vh;
			background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
			display: flex;
			align-items: center;
			justify-content: center;
			position: relative;
			overflow: hidden;
		}

		/* Animated background elements */
		body::before {
			content: '';
			position: absolute;
			top: -50%;
			left: -50%;
			width: 200%;
			height: 200%;
			background: 
				radial-gradient(circle at 20% 50%, rgba(59, 130, 246, 0.15) 0%, transparent 50%),
				radial-gradient(circle at 80% 20%, rgba(96, 165, 250, 0.1) 0%, transparent 50%),
				radial-gradient(circle at 40% 80%, rgba(147, 197, 253, 0.15) 0%, transparent 50%);
			animation: float 20s ease-in-out infinite;
		}

		@keyframes float {
			0%, 100% { 
				transform: translate(0, 0) rotate(0deg); 
			}
			33% { 
				transform: translate(30px, -30px) rotate(120deg); 
			}
			66% { 
				transform: translate(-20px, 20px) rotate(240deg); 
			}
		}

		.login-container {
			background: rgba(255, 255, 255, 0.95);
			backdrop-filter: blur(20px);
			border: 1px solid rgba(255, 255, 255, 0.3);
			border-radius: 20px;
			padding: 3rem;
			width: 100%;
			max-width: 450px;
			box-shadow: 
				0 25px 45px rgba(0, 0, 0, 0.1),
				0 0 0 1px rgba(255, 255, 255, 0.1);
			position: relative;
			animation: slideUp 0.8s ease-out;
		}

		@keyframes slideUp {
			from {
				opacity: 0;
				transform: translateY(50px);
			}
			to {
				opacity: 1;
				transform: translateY(0);
			}
		}

		.login-container::before {
			content: '';
			position: absolute;
			top: 0;
			left: 0;
			right: 0;
			height: 4px;
			background: linear-gradient(90deg, #1e40af, #3b82f6, #60a5fa);
			border-radius: 20px 20px 0 0;
		}

		.display-4 {
			color: #1e40af;
			font-weight: 700;
			text-align: center;
			margin-bottom: 2rem;
			font-size: 2.5rem;
			text-shadow: 0 2px 10px rgba(30, 64, 175, 0.1);
			position: relative;
		}

		.display-4::after {
			content: '';
			position: absolute;
			bottom: -10px;
			left: 50%;
			transform: translateX(-50%);
			width: 50px;
			height: 3px;
			background: linear-gradient(90deg, #3b82f6, #60a5fa);
			border-radius: 2px;
		}

		.form-label {
			color: #374151;
			font-weight: 600;
			margin-bottom: 0.5rem;
			font-size: 0.95rem;
		}

		.form-control {
			background: #f8fafc;
			border: 2px solid #e2e8f0;
			border-radius: 12px;
			padding: 0.75rem 1rem;
			color: #1f2937;
			font-size: 1rem;
			transition: all 0.3s ease;
		}

		.form-control:focus {
			background: #ffffff;
			border-color: #3b82f6;
			box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
			color: #1f2937;
			transform: translateY(-2px);
			outline: none;
		}

		.form-control::placeholder {
			color: #9ca3af;
		}

		.input-group {
			position: relative;
			margin-bottom: 1.5rem;
		}

		.input-icon {
			position: absolute;
			left: 15px;
			top: 35px;
			color: #6b7280;
			z-index: 10;
			pointer-events: none;
		}

		.form-control.with-icon {
			padding-left: 45px;
		}

		.btn-login {
			background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
			border: none;
			border-radius: 12px;
			padding: 0.875rem 2rem;
			font-weight: 600;
			font-size: 1.1rem;
			color: white;
			width: 100%;
			transition: all 0.3s ease;
			position: relative;
			overflow: hidden;
			text-transform: uppercase;
			letter-spacing: 0.5px;
			cursor: pointer;
		}

		.btn-login::before {
			content: '';
			position: absolute;
			top: 0;
			left: -100%;
			width: 100%;
			height: 100%;
			background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
			transition: left 0.5s;
		}

		.btn-login:hover {
			transform: translateY(-3px);
			box-shadow: 0 10px 25px rgba(30, 64, 175, 0.3);
			background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%);
		}

		.btn-login:hover::before {
			left: 100%;
		}

		.btn-login:active {
			transform: translateY(-1px);
		}

		.btn-login:focus {
			outline: none;
			box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
		}

		.alert {
			border: none;
			border-radius: 12px;
			margin-bottom: 1.5rem;
			padding: 1rem 1.25rem;
			font-weight: 500;
			animation: fadeIn 0.5s ease-out;
		}

		@keyframes fadeIn {
			from { 
				opacity: 0; 
				transform: translateY(-10px); 
			}
			to { 
				opacity: 1; 
				transform: translateY(0); 
			}
		}

		.alert-danger {
			background: #fef2f2;
			color: #dc2626;
			border: 1px solid #fecaca;
		}

		.alert-success {
			background: #f0f9ff;
			color: #0284c7;
			border: 1px solid #bae6fd;
		}

		.forgot-password {
			text-align: center;
			margin-top: 1.5rem;
		}

		.forgot-password a {
			color: #6b7280;
			text-decoration: none;
			font-size: 0.9rem;
			transition: color 0.3s ease;
		}

		.forgot-password a:hover {
			color: #3b82f6;
			text-decoration: underline;
		}

		.forgot-password a:focus {
			outline: 2px solid #3b82f6;
			outline-offset: 2px;
		}

		/* Mobile responsiveness */
		@media (max-width: 576px) {
			.login-container {
				margin: 1rem;
				padding: 2rem 1.5rem;
			}
			
			.display-4 {
				font-size: 2rem;
			}
		}

		/* Loading animation for form submission */
		.btn-login.loading {
			pointer-events: none;
			position: relative;
		}

		.btn-login.loading::after {
			content: '';
			position: absolute;
			top: 50%;
			left: 50%;
			transform: translate(-50%, -50%);
			width: 20px;
			height: 20px;
			border: 2px solid transparent;
			border-top-color: #ffffff;
			border-radius: 50%;
			animation: spin 1s linear infinite;
		}

		@keyframes spin {
			0% { 
				transform: translate(-50%, -50%) rotate(0deg); 
			}
			100% { 
				transform: translate(-50%, -50%) rotate(360deg); 
			}
		}

		/* Accessibility improvements */
		.sr-only {
			position: absolute;
			width: 1px;
			height: 1px;
			padding: 0;
			margin: -1px;
			overflow: hidden;
			clip: rect(0, 0, 0, 0);
			white-space: nowrap;
			border: 0;
		}

		/* Focus states for accessibility */
		*:focus {
			outline: none;
		}

		.form-control:focus,
		.btn-login:focus,
		.forgot-password a:focus {
			box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
		}
	</style>
</head>
<body>
	<div class="login-container">
		<form method="POST" action="app/login.php">
			<h3 class="display-4">
				<i class="fas fa-tasks" style="color: #3b82f6;" aria-hidden="true"></i>
				LOGIN
			</h3>
			
			<?php if (isset($_GET['error'])) {?>
				<div class="alert alert-danger" role="alert">
					<i class="fas fa-exclamation-triangle" aria-hidden="true"></i>
					<?php echo stripcslashes($_GET['error']); ?>
				</div>
			<?php } ?>

			<?php if (isset($_GET['success'])) {?>
				<div class="alert alert-success" role="alert">
					<i class="fas fa-check-circle" aria-hidden="true"></i>
					<?php echo stripcslashes($_GET['success']); ?>
				</div>
			<?php } ?>

			<div class="input-group">
				<label for="user_name" class="form-label">Username</label>
				<i class="fas fa-user input-icon" aria-hidden="true"></i>
				<input type="text" class="form-control with-icon" name="user_name" id="user_name" placeholder="Enter your username" required>
			</div>

			<div class="input-group">
				<label for="password" class="form-label">Password</label>
				<i class="fas fa-lock input-icon" aria-hidden="true"></i>
				<input type="password" class="form-control with-icon" name="password" id="password" placeholder="Enter your password" required>
			</div>

			<button type="submit" class="btn btn-login" onclick="handleSubmit(this)">
				<span>Sign In</span>
			</button>

			<div class="forgot-password">
				<a href="#" onclick="alert('Contact your administrator to reset your password.'); return false;">
					Forgot your password?
				</a>
			</div>
		</form>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
	
	<script>
		function handleSubmit(button) {
			button.classList.add('loading');
			button.innerHTML = '<span>Signing In...</span>';
		}

		// Add floating label effect
		document.querySelectorAll('.form-control').forEach(input => {
			input.addEventListener('focus', function() {
				this.parentNode.classList.add('focused');
			});
			
			input.addEventListener('blur', function() {
				if (this.value === '') {
					this.parentNode.classList.remove('focused');
				}
			});
		});

		// Add enter key support
		document.addEventListener('keypress', function(e) {
			if (e.key === 'Enter') {
				const button = document.querySelector('.btn-login');
				if (button) {
					button.click();
				}
			}
		});

		// Form validation
		document.querySelector('form').addEventListener('submit', function(e) {
			const username = document.getElementById('user_name').value.trim();
			const password = document.getElementById('password').value.trim();
			
			if (!username || !password) {
				e.preventDefault();
				alert('Please fill in all fields');
				return false;
			}
		});
	</script>
</body>
</html>
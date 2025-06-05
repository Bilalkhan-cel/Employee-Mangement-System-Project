<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) ) {

	 include "DB_connection.php";
     include "app/Model/User.php";

    $users = get_all_users($conn);
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Users Management</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="css/style.css"> 
    <style>
		/* CSS Variables for consistent theming */
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
			background: var(--background-color);
		}

		.title {
			font-size: 2rem;
			font-weight: 700;
			color: var(--text-dark);
			margin-bottom: 2rem;
			display: flex;
			align-items: center;
			justify-content: space-between;
			flex-wrap: wrap;
			gap: 1rem;
		}

		.title a {
			background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%);
			color: var(--white);
			text-decoration: none;
			padding: 0.75rem 1.5rem;
			border-radius: var(--radius-md);
			font-size: 1rem;
			font-weight: 600;
			transition: all 0.2s ease;
			display: inline-flex;
			align-items: center;
			gap: 0.5rem;
			box-shadow: var(--shadow-md);
		}

		.title a:hover {
			transform: translateY(-2px);
			box-shadow: var(--shadow-lg);
			background: linear-gradient(135deg, #059669 0%, #047857 100%);
		}

		.title a::before {
			content: '+';
			font-size: 1.2rem;
			font-weight: bold;
		}

		/* Success Message Styling */
		.success {
			background: var(--success-bg);
			color: var(--success-color);
			border: 1px solid var(--success-border);
			padding: 1rem 1.25rem;
			border-radius: var(--radius-md);
			margin-bottom: 2rem;
			font-weight: 500;
			display: flex;
			align-items: center;
			gap: 0.5rem;
			animation: slideDown 0.3s ease-out;
		}

		.success::before {
			content: '✓';
			font-size: 1.2rem;
			font-weight: bold;
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

		/* Modern Table Styling */
		.main-table {
			width: 100%;
			border-collapse: collapse;
			font-size: 0.95rem;
		}

		.main-table th {
			background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
			color: var(--white);
			padding: 1rem;
			text-align: left;
			font-weight: 600;
			border: none;
			position: sticky;
			top: 0;
			z-index: 10;
		}

		.main-table th:first-child {
			border-top-left-radius: var(--radius-lg);
		}

		.main-table th:last-child {
			border-top-right-radius: var(--radius-lg);
		}

		.main-table td {
			padding: 1rem;
			border-bottom: 1px solid var(--border-color);
			vertical-align: middle;
		}

		.main-table tr:hover {
			background-color: #f9fafb;
			transition: background-color 0.2s ease;
		}

		.main-table tr:last-child td {
			border-bottom: none;
		}

		/* Action Buttons */
		.action-buttons {
			display: flex;
			gap: 0.5rem;
			flex-wrap: wrap;
		}

		.edit-btn, .delete-btn {
			text-decoration: none;
			padding: 0.5rem 1rem;
			border-radius: var(--radius-sm);
			font-size: 0.875rem;
			font-weight: 500;
			transition: all 0.2s ease;
			display: inline-flex;
			align-items: center;
			gap: 0.375rem;
			min-width: 80px;
			justify-content: center;
			border: none;
			cursor: pointer;
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

		/* Role Badge Styling */
		.role-badge {
			display: inline-block;
			padding: 0.375rem 0.75rem;
			border-radius: 9999px;
			font-size: 0.75rem;
			font-weight: 600;
			text-transform: uppercase;
			letter-spacing: 0.025em;
		}

		.role-badge.admin {
			background: #fef3c7;
			color: #92400e;
			border: 1px solid #fde68a;
		}

		.role-badge.user {
			background: #e0f2fe;
			color: #0369a1;
			border: 1px solid #bae6fd;
		}

		.role-badge.manager {
			background: #f3e8ff;
			color: #7c3aed;
			border: 1px solid #ddd6fe;
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
			content: '👥';
			font-size: 4rem;
			display: block;
			margin-bottom: 1rem;
			opacity: 0.5;
		}

		/* User Count Badge */
		.user-count {
			background: var(--primary-color);
			color: var(--white);
			padding: 0.25rem 0.75rem;
			border-radius: 9999px;
			font-size: 0.875rem;
			font-weight: 600;
			margin-left: 0.5rem;
		}

		/* Responsive Design */
		@media (max-width: 768px) {
			.section-1 {
				padding: 1rem;
			}

			.title {
				font-size: 1.5rem;
				flex-direction: column;
				align-items: flex-start;
			}

			.main-table {
				font-size: 0.85rem;
			}

			.main-table th,
			.main-table td {
				padding: 0.75rem 0.5rem;
			}

			.action-buttons {
				flex-direction: column;
			}

			.edit-btn, .delete-btn {
				min-width: 70px;
				padding: 0.375rem 0.75rem;
				font-size: 0.8rem;
			}
		}

		@media (max-width: 480px) {
			.main-table th:first-child,
			.main-table td:first-child {
				display: none;
			}

			.table-container {
				overflow-x: auto;
			}
		}

		/* Loading Animation for Delete Confirmation */
		.delete-btn.loading {
			pointer-events: none;
			opacity: 0.7;
		}

		.delete-btn.loading::before {
			content: '';
			width: 12px;
			height: 12px;
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
    </style>
</head>
<body>
	<input type="checkbox" id="checkbox">
	<?php include "inc/header.php" ?>
	<div class="body">
		<?php include "inc/nav.php" ?>
	
		<section class="section-1">
			<h4 class="title">
				Manage Users
				<?php if ($users != 0) { ?>
					<span class="user-count"><?= count($users) ?> users</span>
				<?php } ?>
				<a href="add-user.php">Add New User</a>
			</h4>

			<?php if (isset($_GET['success'])) {?>
      	  		<div class="success" role="alert">
					<?php echo stripcslashes($_GET['success']); ?>
				</div>
			<?php } ?>

			<?php if ($users != 0) { ?>
				<div class="table-container">
					<table class="main-table">
						<thead>
							<tr>
								<th>#</th>
								<th>Full Name</th>
								<th>Username</th>
								<th>Role</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php $i=0; foreach ($users as $user) { ?>
							<tr>
								<td><strong><?=++$i?></strong></td>
								<td><?=htmlspecialchars($user['full_name'])?></td>
								<td><?=htmlspecialchars($user['username'])?></td>
								<td>
									<span class="role-badge <?=strtolower($user['role'])?>">
										<?=ucfirst($user['role'])?>
									</span>
								</td>
								<td>
									<div class="action-buttons">
										<a href="edit-user.php?id=<?=$user['id']?>" class="edit-btn">Edit</a>
										<a href="delete-user.php?id=<?=$user['id']?>" 
										   class="delete-btn" 
										   onclick="return confirm('Are you sure you want to delete this user?')">
										   Delete
										</a>
									</div>
								</td>
							</tr>
						   <?php } ?>
						</tbody>
					</table>
				</div>
			<?php } else { ?>
				<div class="empty-state">
					<h3>No Users Found</h3>
					<p>Start by adding your first user to the system.</p>
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

	// Enhanced delete confirmation with loading state
	document.querySelectorAll('.delete-btn').forEach(btn => {
		btn.addEventListener('click', function(e) {
			if (!confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
				e.preventDefault();
				return false;
			}
			
			// Add loading state
			this.classList.add('loading');
			this.style.pointerEvents = 'none';
		});
	});

	// Table row hover enhancement
	document.querySelectorAll('.main-table tbody tr').forEach(row => {
		row.addEventListener('mouseenter', function() {
			this.style.transform = 'scale(1.01)';
			this.style.boxShadow = 'var(--shadow-md)';
		});
		
		row.addEventListener('mouseleave', function() {
			this.style.transform = 'scale(1)';
			this.style.boxShadow = 'none';
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
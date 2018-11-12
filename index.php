<?php
include('server.php');

if (!isset($_SESSION['username'])) {
	header('location: login.php');
}

if (isset($_GET['logout'])) {
	session_destroy();
	unset($_SESSION['username']);
	header("location: login.php");
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Home</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

	<div class="header">
		<h2>Home Page</h2>
	</div>

	<div class="content">
		<!-- notification message -->
		<?php if (isset($_SESSION['success'])) : ?>
		  <div class="error success" >
			<h3>
			  <?php
				echo $_SESSION['success'];
				unset($_SESSION['success']);
			  ?>
			</h3>
		  </div>
		<?php endif ?>

		<!-- logged in user information -->
		<?php  if (isset($_SESSION['username'])) : ?>
			<p>Welcome <strong><?php echo $_SESSION['username']; ?></strong>!</p>
			<p> <a href="index.php?logout='1'" style="color: red;">logout</a> </p>
		<?php endif ?>
	</div>

	<div class="header">
		<h2>Search for users</h2>
	</div>
	
	<form method="post" action="results.php">
		<?php
		if ($_SESSION['error'] && isset($_GET['back']) && $_GET['back'] == '1') : ?>
			<div class="error">
				<p><?php echo $_SESSION['error'] ?></p>
			</div>
		<?php  endif ?>

		<div class="input-group">
			<label>Username / Email</label>
			<input type="text" name="search_criteria" >
		</div>
		<div class="input-group">
			<button type="submit" class="btn" name="search_users">Search users</button>
		</div>
	</form>

</body>
</html>
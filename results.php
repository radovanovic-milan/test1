<?php 
session_start();

if (!isset($_SESSION['username'])) {
	header('location: login.php');
}
if (isset($_GET['logout'])) {
	session_destroy();
	unset($_SESSION['username']);
	header("location: login.php");
}

include('server.php') ?>

<!DOCTYPE html>
<html>
<head>
  <title>Test</title>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
  <div class="header">
  	<h2>Results</h2>
  </div>

  <?php
  include('errors.php'); ?>
  
  <?php if (isset($users) && !empty($users)) : ?>
  <table class="table">
	<tr>
		<td>#</td>
		<td>Username</td>
		<td>E-mail</td>
	</tr>
	
	<?php foreach ($users as $key => $user) : ?>
	<tr>
		<td><?php echo $key+1 ?></td>
		<td><?php echo $user['username'] ?></td>
		<td><?php echo $user['email'] ?></td>
	</tr>
	<?php endforeach ?>
  </table>
  <?php endif ?>
  
	<form>
		<p>
			<a href="index.php">Back to search</a>
		</p>
	</form>
</body>
</html>
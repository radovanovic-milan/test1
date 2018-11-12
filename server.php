<?php
if (!isset($_SESSION)) {
	session_start();
}

// initializing variables
$username = "";
$email    = "";
$errors = array();

// connect to the database
$db = mysqli_connect('localhost', 'root', '', 'test');

// REGISTER USER
if (isset($_POST['reg_user'])) {
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
    $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

    // form validation
    if (empty($username)) { array_push($errors, "Username is required"); }
    if (empty($email)) { array_push($errors, "Email is required"); }
    if (empty($password_1)) { array_push($errors, "Password is required"); }
    if ($password_1 != $password_2) {
        array_push($errors, "The two passwords do not match");
    }

    // first check the database to make sure
    // a user does not already exist with the same username and/or email
    $user_check_query = "SELECT username, email FROM users WHERE username='$username' OR email='$email' LIMIT 1";
    $result = mysqli_query($db, $user_check_query);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        if ($user['username'] === $username) {
            array_push($errors, "Username already exists");
        }

        if ($user['email'] === $email) {
            array_push($errors, "email already exists");
        }
    }

    // Finally, register user if there are no errors in the form
    if (count($errors) == 0) {
        $password = md5($password_1);//encrypt the password before saving in the database

        $query = "INSERT INTO users (username, email, password) 
  			  VALUES('$username', '$email', '$password')";
        mysqli_query($db, $query);
        $_SESSION['username'] = $username;
        $_SESSION['success'] = "You are now logged in";
        header('location: index.php');
    }
}

// LOGIN USER
if (isset($_POST['login_user'])) {
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password = mysqli_real_escape_string($db, $_POST['password']);

    if (empty($email)) {
        array_push($errors, "E-mail is required");
    }
    if (empty($password)) {
        array_push($errors, "Password is required");
    }

    if (count($errors) == 0) {
        $password = md5($password);
        $query = "SELECT username FROM users WHERE email='$email' AND password='$password'";
        $results = mysqli_query($db, $query);
		$row=mysqli_fetch_array($results);
        if (mysqli_num_rows($results) == 1) {
            $_SESSION['username'] = $row['username'];
            $_SESSION['success'] = "You are now logged in";
            header('location: index.php');
        }else {
            array_push($errors, "Error logging you in.");
        }
    }
}

// SEARCH USERS
if (isset($_POST['search_users'])) {
    $search_criteria = mysqli_real_escape_string($db, $_POST['search_criteria']);

	if (empty($search_criteria)) {
        $_SESSION['error'] = "Enter username or E-mail";
        header('location: index.php?back=1');
    }
	
    if (count($errors) == 0) {
        $query = "SELECT * FROM users WHERE email LIKE '%$search_criteria%' OR username LIKE '%$search_criteria%'";
        $results = mysqli_query($db, $query);
		while ($row = mysqli_fetch_array($results)){
			$users[] = $row;
		}
        if (mysqli_num_rows($results) == 0) {
            array_push($errors, "No results found matching serach criteria.");
        }
    }
}
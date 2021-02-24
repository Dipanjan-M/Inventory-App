<?php 
require_once('../private/initialize.php');

$errors = [];
$email = '';
$password = '';

if(is_post_request()) {
    $email = $_POST['aEmail'] ?? '';
    $password = $_POST['aPassword'] ?? '';
    
    // Validations
    if(is_blank($email)) {
        $errors[] = "Email cannot be blank.";
    }

    if(!has_valid_email_format($email)) {
        $errors[] = "Email is not in a valid format.";
    }

    if(is_blank($password)) {
        $errors[] = "Password cannot be blank.";
    }

    // if there were no errors, try to login
    if(empty($errors)) {
        $admin = Admin::find_by_email($email);
        // test if admin found and password is correct
        if($admin != false && $admin->verify_password($password)) {
            // Mark admin as logged in
            $session->login($admin);
            $session->message("Last successful login time ".date("d-m-Y h:i:s a"));
            redirect_to(url_for('public/admin/'));
        } else {
            // Email not found or password does not match
            $errors[] = "Log in was unsuccessful.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Piya Motors | Admin Login</title>
        
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Fontawesome 5.7.0 -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

    <!-- External local style sheet -->
    <link rel="stylesheet" href="assets/CSS/custom.css">
</head>
<body>
    <div class="container pt-3">
        <?php echo display_errors($errors); ?>
    </div>
    <div class="mb-3 mt-5 text-center" style="font-size: 30px;">
        <i class="fas fa-stethoscope"></i>
        <span>PIYA MOTORS</span>
    </div>
    <p class="text-center" style="font-size: 20px;"><i class="fas fa-user text-danger"></i> Admin Area</p>
    <div class="container-fluid">
        <div class="row justify-content-center custom-margin">
            <div class="col-sm-6 col-md-4">
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" class="shadow-lg p-4" method="POST">
                    <div class="form-group">
                        <i class="fas fa-user"></i>
                        <label for="email" class="font-weight-bold pl-2">Email</label>
                        <input type="email" name="aEmail" class="form-control" placeholder="Email">
                        <small class="form-text">We'll never share your email with anyone else.</small>
                    </div>
                    <div class="form-group">
                        <i class="fas fa-key"></i>
                        <label for="pass" class="font-weight-bold pl-2">Password</label>
                        <input type="password" name="aPassword" class="form-control" placeholder="Password">
                    </div>
                    <button type="submit" class="btn btn-outline-danger mt-4 font-weight-bold btn-block shadow-sm">Login</button>
                </form>
            </div>
        </div>
    </div>
    <div class="container text-center">
        <br>
        <span><a href="../" style="text-decoration: none;color: #333;cursor: pointer;"><i class="fa fa-home"></i> Home</a></span>
        <br><br><br>
        <span>&copy; <small id="year"></small> | piyamotors </span>
    </div>
    <script src="assets/JS/public_index.js"></script>
</body>
</html>
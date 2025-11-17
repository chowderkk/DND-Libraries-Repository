<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../js/bootstrap.bundle.min.js">
    <style>
        .error {
            color: red;
        }

        .card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            padding-top: 50px;
        }
    </style>
    <title>Register</title>
</head>
<body>

    <section class="container mt-5">
        <div class="card p-4">
            <header class="text-center mb-4">
                <h2>Register</h2>
            </header>

            <?php
            require_once '../includes/config.php';

            function isEmailRegistered($email)
            {
                global $mysqli;

                $sql = "SELECT * FROM users WHERE email = '$email'";
                $result = $mysqli->query($sql);

                return ($result->num_rows > 0);
            }

            // Check if the form is submitted
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Retrieve form data
                $firstName = $_POST['firstName'];
                $lastName = $_POST['lastName'];
                $email = $_POST['email'];
                $password = $_POST['password'];
                $confirmPassword = $_POST['confirm_password'];

                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Check if email is already registered
                if (isEmailRegistered($email)) {
                    $error = "Email already registered. Please choose a different email.";
                } else {
                    // Insert data into the database
                    $sql = "INSERT INTO users (firstName, lastName, email, password, created_at) 
                            VALUES ('$firstName', '$lastName', '$email', '$hashedPassword', NOW())";
                    if (mysqli_query($mysqli, $sql)) {
                        echo '<div class="alert alert-success" role="alert">
                                Success! <a href="login.php" class="alert-link">Login</a>
                            </div>';
                        exit();
                    } else {
                        echo '<div class="alert alert-danger" role="alert">
                                Error: ' . mysqli_error($mysqli) . '
                            </div>';
                    }
                }
            }
            ?>

            <?php if (isset($error)) : ?>
                <div class="alert alert-danger">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="row g-3">
                <div class="col-md-6">
                    <label for="firstName" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="firstName" name="firstName" placeholder="Enter First name" required>
                </div>
                <div class="col-md-6">
                    <label for="lastName" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Enter Last name" required>
                </div>
                <div class="col-12">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter email address" required>
                </div>
                <div class="col-md-6">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
                </div>
                <div class="col-md-6">
                    <label for="confirm_password" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm password" required>
                </div>
                <div class="col-12">
    <button type="submit" class="btn btn-primary" style="background-color: black; color: white; border: 1px solid white;">Submit</button>
</div>
                <div class="col-12">
                    <p class="text-center">Already have an account? <a href="login.php">Login</a></p>
                </div>
            </form>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-d54D5id3aCmKaLQl6NzwkFzN0C4KhlD5FVq8oQw3xIeZBRZzNl3Yx3v1Embp4mei" crossorigin="anonymous"></script>
</body>
</html>

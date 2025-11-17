<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../js/bootstrap.bundle.min.js">
    <title>Login</title>
    <link rel="icon" type="image/x-icon" href="../img/logo.png">

    
    <style>
        .error-message {
            color: red;
        }
    </style>
</head>
<body>
    <section>
        <div class="container-fluid">
            <div class="formBox">
                <div class="formValue">
                    <?php
                    require_once '../includes/config.php';
                    session_start();

                    if ($_SERVER["REQUEST_METHOD"] === "POST") {
                        $email = mysqli_real_escape_string($mysqli, $_POST["email"]);
                        $password = mysqli_real_escape_string($mysqli, $_POST["password"]);

                        $stmt = $mysqli->prepare("SELECT password, user_id, firstName, roles FROM users WHERE BINARY email = ?");
                        $stmt->bind_param("s", $email);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result && mysqli_num_rows($result) > 0) {
                            $row = mysqli_fetch_assoc($result);
                            $storedHashedPassword = $row['password'];
                            $userID = $row['user_id'];
                            $firstName = $row['firstName'];
                            $role = $row['roles'];

                            if (password_verify($password, $storedHashedPassword)) {
                                $_SESSION["email"] = $email;
                                $_SESSION["userID"] = $userID;
                                $_SESSION["firstName"] = $firstName;
                                $_SESSION["role"] = $role;

                                if ($role === 'admin') {
                                    header("Location: ../admin/admin_dashboard.php");
                                    exit();
                                } else {
                                    header("Location: ../pages/index.php");
                                    exit();
                                }
                            } else {
                                // Incorrect password
                                $errorMessage = "Incorrect email or password.";
                            }
                        } else {
                            // User not found
                            $errorMessage = "Incorrect email or password.";
                        }
                    }
                    ?>

                    <div class="login-container">
                        <img src="../img/logo.png" alt="logo" class="logo">
                        <form action="../auth/login.php" method="post" class="form">
                            <h2>Login</h2>
                            <?php
                            if (isset($errorMessage)) {
                                echo '<div class="error-message">' . $errorMessage . '</div>';
                            }
                            ?>
                            <div class="inputbox">
                                <ion-icon name="mail-outline"></ion-icon>
                                <input type="email" placeholder="Email" name="email" required>
                            </div>
                            <div class="inputbox">
                                <ion-icon name="lock-closed-outline"></ion-icon>
                                <input type="password" placeholder="Password" name="password" required>
                            </div>
                            <button type="submit">Log in</button>
                            <div class="register">
                                <p>Don't Have an account? <a href="../auth/register.php">Register</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>

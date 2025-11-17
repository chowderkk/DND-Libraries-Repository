<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: ../auth/login.php");
    exit();
}

require_once '../includes/config.php';

$userID = $_SESSION['userID'];

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['save'])) {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];

    // Perform input validation and error handling as needed

    // Update the user's profile information in the database
    $updateProfileSQL = "UPDATE users SET firstName = '$firstName', lastName = '$lastName', email = '$email' WHERE user_id = $userID";
    if (mysqli_query($mysqli, $updateProfileSQL)) {
        // Profile updated successfully
        header("Location: ../pages/profile.php");
        exit();
    } else {
        // Handle the case where the update fails
        $error = "Failed to update profile. Please try again.";
    }
}

// Retrieve the user's current profile information
$profileSQL = "SELECT * FROM users WHERE user_id = $userID";
$profileResult = mysqli_query($mysqli, $profileSQL);
$profile = mysqli_fetch_assoc($profileResult);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../js/bootstrap.bundle.min.js">
    <style>
        .btn-black-white {
            color: #000;
            background-color: #fff;
            border-color: #000;
        }

        .btn-black-white:hover {
            color: #fff;
            background-color: #000;
            border-color: #fff;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-body">
                <h1 class="card-title">Edit Profile</h1>
                <?php if (isset($error)) { ?>
                    <p class="text-danger"><?php echo $error; ?></p>
                <?php } ?>

                <form method="post" action="../actions/edit_profile.php">
                    <div class="mb-3">
                        <label for="first_name" class="form-label">First Name:</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo $profile['firstName']; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="last_name" class="form-label">Last Name:</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo $profile['lastName']; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo $profile['email']; ?>" required>
                    </div>

                    <button type="submit" name="save" class="btn btn-black-white">Save Changes</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
session_start();
require_once '../includes/config.php';

// Query to retrieve user information, excluding the admin user
$sql = "SELECT user_id, firstName, lastName, email, roles, created_at FROM users WHERE user_id != 0";

$result = mysqli_query($mysqli, $sql);

if ($result) {
    $users = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    die("Error: " . mysqli_error($mysqli));
}

// Notification message example (you can set this in other parts of your code)
// $_SESSION['notification'] = "This is a notification message.";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- Add your custom CSS or other stylesheets here -->
    <title>View Users</title>
    <style>
        body {
            padding-top: 56px; /* Adjust the padding-top to accommodate the fixed navbar */
        }

        @media (min-width: 768px) {
            body {
                padding-top: 76px; /* Adjust the padding-top for larger screens if needed */
            }
        }
    </style>
</head>
<body>

<!-- Modified Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
    <a class="navbar-brand" href="admin_dashboard.php">DND Libraries</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
               
            </li>
        </ul>
    </div>
</nav>

<!-- Content Container -->
<div class="container mt-5">
    <?php
    // Check if there is any notification message
    if (isset($_SESSION['notification'])) {
        echo '<div class="alert alert-dismissible alert-info">';
        echo '<button type="button" class="close" data-dismiss="alert">&times;</button>';
        echo $_SESSION['notification'];
        echo '</div>';

        // Clear the notification message after displaying it
        unset($_SESSION['notification']);
    }
    ?>

    <h1>View Users</h1>

    <!-- Add a search bar for author names -->
    <form id="authorSearchForm">
        <label for="authorSearch">Search by Author:</label>
        <input type="text" id="authorSearch" placeholder="Enter author's name">
        <button type="button" onclick="searchByAuthor()">Search</button>
        <button type="button" onclick="clearAuthorSearch()">Clear</button>
    </form>

    <table class="table">
        <thead>
            <tr>
                <th>User ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Account Created</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($users)) {
                foreach ($users as $user) {
                    echo "<tr>";
                    echo "<td>" . $user['user_id'] . "</td>";
                    echo "<td>" . $user['firstName'] . "</td>";
                    echo "<td>" . $user['lastName'] . "</td>";
                    echo "<td>" . $user['email'] . "</td>";
                    echo "<td>" . $user['created_at'] . "</td>"; // Display the created_at value
                    echo '<td>';
                    echo '<a href="../pages/uploaded_files.php?user_id=' . $user['user_id'] . '">View Uploaded Files</a>';
                    echo ' | ';
                    echo '<a href="../admin/delete_user.php?user_id=' . $user['user_id'] . '">Delete</a>';
                    echo '</td>';
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No users found.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Add JavaScript for author search -->
    <script>
        function searchByAuthor() {
            var authorSearchText = document.getElementById('authorSearch').value.toLowerCase();

            var rows = document.querySelectorAll('table tbody tr');
            rows.forEach(function (row) {
                var authorName = (row.querySelector('td:nth-child(2)').textContent + ' ' + row.querySelector('td:nth-child(3)').textContent).toLowerCase();

                if (authorName.includes(authorSearchText)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        function clearAuthorSearch() {
            document.getElementById('authorSearch').value = '';
            searchByAuthor(); // Clear the search results
        }
    </script>

    <!-- Bootstrap Scripts (jQuery and Bootstrap JS) -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</div>

</body>
</html>

<?php
session_start();
require_once '../includes/config.php';

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Check if the user ID is provided as a parameter
if (isset($_GET['user_id'])) {
    $userID = $_GET['user_id'];

    // Query to retrieve the user's uploaded documents with additional information
    $sql = "SELECT d.file_id, d.title, d.category, d.description, d.author, d.tags, d.infoViews, d.favorites, d.dateCreated, d.status, d.visibility, u.firstName, u.lastName
            FROM documents d
            LEFT JOIN users u ON d.author = u.user_id
            WHERE d.author = $userID";
    $result = mysqli_query($mysqli, $sql);

    if ($result) {
        $uploadedDocuments = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        die("Error: " . mysqli_error($mysqli));
    }
} else {
    // Handle the case where the user ID parameter is missing
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Uploaded Files</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        body {
            padding-top: 56px; /* Adjust the padding-top to accommodate the fixed navbar */
        }

        @media (min-width: 768px) {
            body {
                padding-top: 76px; /* Adjust the padding-top for larger screens if needed */
            }
        }

        .navbar-brand {
            padding-left: 20px; /* Adjust the left padding for the brand */
        }

        .search-bar {
            margin-bottom: 15px; /* Add margin to the bottom of the search bar for spacing */
        }
    </style>
</head>
<body>

<!-- Bootstrap Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
    <a class="navbar-brand" href="../admin/admin_dashboard.php">DND Libraries</a>
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

<div class="container mt-5">
    <h1>Uploaded Files</h1>

    <!-- Elegant Search Bar -->
    <form class="form-inline search-bar">
        <div class="input-group">
            <input type="text" class="form-control" id="authorSearch" placeholder="Search by Author">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="button" onclick="searchByAuthor()">Search</button>
                <button class="btn btn-outline-secondary" type="button" onclick="clearAuthorSearch()">Clear</button>
            </div>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>File ID</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Author</th>
                    <th>Tags</th>
                    <th>Views</th>
                    <th>Favorites</th>
                    <th>Date Created</th>
                    <th>Status</th>
                    <th>Visibility</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($uploadedDocuments)) {
                    foreach ($uploadedDocuments as $document) {
                        echo "<tr>";
                        echo "<td>" . $document['file_id'] . "</td>";
                        echo "<td>" . $document['title'] . "</td>";
                        echo "<td>" . $document['category'] . "</td>";
                        echo "<td>" . $document['description'] . "</td>";
                        echo "<td>" . $document['firstName'] . ' ' . $document['lastName'] . "</td>";
                        echo "<td>" . $document['tags'] . "</td>";
                        echo "<td>" . $document['infoViews'] . "</td>";
                        echo "<td>" . $document['favorites'] . "</td>";
                        echo "<td>" . $document['dateCreated'] . "</td>";
                        echo "<td>" . $document['status'] . "</td>";
                        echo "<td>" . ($document['visibility'] == 1 ? 'Public' : 'Private') . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='11'>No uploaded files found for this user.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Bootstrap Scripts (jQuery and Bootstrap JS) -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>
</html>

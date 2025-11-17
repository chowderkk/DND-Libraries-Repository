<?php
session_start();
require_once '../includes/config.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>View Document Details</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Add padding to the left of the navbar brand */
        .navbar-brand {
            padding-left: 20px;
        }
        .custom-container {
            margin-top: 100px; /* Adjust the margin-top value according to your preference */
            align-items: center;
        }
      
        </style>
</head>
<body>
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
<div class="custom-container">
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 offset-md-2">

            <?php
            if (isset($_GET['fileID'])) {
                $fileID = $_GET['fileID'];

                // Query to retrieve document details
                $sql = "SELECT d.file_id, d.title, d.category, d.author, d.description, d.tags, d.infoViews, d.favorites, u.firstName, u.lastName, d.file
                        FROM documents d
                        LEFT JOIN users u ON d.author = u.user_id
                        WHERE d.file_id = $fileID";

                $result = mysqli_query($mysqli, $sql);

                if ($result && mysqli_num_rows($result) > 0) {
                    $document = mysqli_fetch_assoc($result);
                    $title = $document['title'];
                    $file = $document['file'];
                    $authorName = !empty($document['firstName']) ? $document['firstName'] . ' ' . $document['lastName'] : "Deleted User";
                    $category = $document['category'];
                    $description = $document['description'];
                    $tags = $document['tags'];
                    $views = $document['infoViews'];
                    $favorites = $document['favorites'];
                    ?>

                    <div class="card">
                        <div class="card-body">
                            <h1 class="card-title">Document Details</h1>
                            <ul class="list-group">
                                <li class="list-group-item">File ID: <?php echo $fileID; ?></li>
                                <li class="list-group-item">Title: <?php echo $title; ?></li>
                                <li class="list-group-item">Author: <?php echo $authorName; ?></li>
                                <li class="list-group-item">Category: <?php echo $category; ?></li>
                                <li class="list-group-item">Description: <?php echo $description; ?></li>
                                <li class="list-group-item">Tags: <?php echo $tags; ?></li>
                                <li class="list-group-item">Views: <?php echo $views; ?></li>
                                <li class="list-group-item">Favorites: <?php echo $favorites; ?></li>
                                <li class="list-group-item">
                                    <a href="<?php echo $file; ?>" target="_blank" class="btn btn-dark text-white">View File</a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <?php
                } else {
                    echo "<p class='alert alert-danger'>Document not found.</p>";
                }
            }
            ?>
        </div>
    </div>
</div>

<!-- Bootstrap JS and Popper.js (required for Bootstrap) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

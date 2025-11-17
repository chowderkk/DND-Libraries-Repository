<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Details</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
        crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../js/bootstrap.bundle.min.js">

    <style>
        body {

        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }

        .document-details {
            margin: 20px;
        }

        .embed-container {
            max-width: 100%;
        }

        .card {
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .btn-black-white {
            background-color: #000;
            color: #fff;
            border: none;
        }

        .btn-black-white:hover {
            background-color: #000;
            color: #fff;
            border: none;
        }

        .btn-smaller {
            padding: 5px 5px;
            font-size: 15px;
        }
            /* Add this style for the black and white submit button */
    .btn-black-white {
        background-color: #000;
        color: #fff;
        border: none;
    }

    .btn-black-white:hover {
        background-color: #000;
        color: #fff;
        border: none;
    }

    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <img src="../img/logo.png" alt="DND Collab Logo" height="30" class="d-inline-block align-top">DND Libraries
            </a>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php">PROFILE</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="notifications.php">NOTIFICATIONS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="upload.php">UPLOAD</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">LOGOUT</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container document-details mx-auto">
        <?php
        session_start();
        require_once '../includes/config.php';

        if (isset($_GET['fileID'])) {
            $fileID = $_GET['fileID'];

            // Query to retrieve document details based on $fileID
            $documentSQL = "SELECT d.file_id, d.file, d.title, d.category, d.description, d.infoViews, d.favorites, d.tags, d.visibility, u.firstName, u.lastName, d.author
                            FROM documents d
                            LEFT JOIN users u ON d.author = u.user_id
                            WHERE d.file_id = $fileID";

            // Update the infoViews count
            $updateViewsSQL = "UPDATE documents SET infoViews = infoViews + 1 WHERE file_id = $fileID";
            mysqli_query($mysqli, $updateViewsSQL);

            $documentResult = mysqli_query($mysqli, $documentSQL);

            if (mysqli_num_rows($documentResult) > 0) {
                $documentRow = mysqli_fetch_assoc($documentResult);
                $title = $documentRow['title'];
                $category = $documentRow['category'];
                $views = $documentRow['infoViews'];
                $favorites = $documentRow['favorites'];
                $description = $documentRow['description'];
                $tags = $documentRow['tags'];
                $visibility = $documentRow['visibility'];
                $author = isset($documentRow['firstName']) && isset($documentRow['lastName']) ? $documentRow['firstName'] . ' ' . $documentRow['lastName'] : 'Deleted User';
                $file = $documentRow['file'];

                // Check if the current user is the owner
                $isOwner = ($_SESSION['userID'] == $documentRow['author']);

                // Display document details within a card
                echo '<div class="card">';
                echo '<h2 class="mt-4">Title: ' . $title . '</h2>';
                echo '<p>Author: ' . $author . '</p>';
                echo '<p>File ID: ' . $fileID . '</p>';
                echo '<p>Category: ' . $category . '</p>';
                echo '<p>Views: ' . $views . '</p>';
                echo '<p>Favorites: ' . $favorites . '</p>';
                echo '<p>Description: ' . $description . '</p>';
                echo '<p>Tags: ' . $tags . '</p>';
                echo '<p>Visibility: ' . ($visibility == 1 ? 'Public' : 'Private') . '</p>'; // Display visibility

                if ($category === 'PDF' || $category === 'Images') {
                    echo '<div class="mt-3">';
                    echo '<a href="' . $file . '" target="_blank" class="btn btn-primary btn-black-white btn-smaller">Open Document</a>';
                    echo '</div>';
                } elseif ($category === 'Compressed Folder') {
                    echo '<p class="alert alert-warning mt-3">Compressed folders cannot be embedded.</p>';
                } else {
                    echo '<div class="mt-3">';
                    echo '<a href="' . $file . '" target="_blank" class="btn btn-primary btn-black-white btn-smaller">Open Document</a>';
                    echo '</div>';
                }
                // View button
                echo '<a href="' . $file . '" target="_blank" class="btn btn-primary mt-3 btn-smaller btn-black-white">Download File</a>';
             
            // Display ratings
            echo '<h3 class="mt-4">Ratings:</h3>';
            echo '<div class="mb-3">';
            echo '<label for="sortOrder" class="form-label">Sort by:</label>';
            echo '<div class="btn-group" role="group" aria-label="Sort Ratings">';
            echo '<a href="?fileID=' . $fileID . '&sort=asc" class="btn btn-secondary ' . ((isset($_GET['sort']) && $_GET['sort'] == 'asc') ? 'active' : '') . '">Least Ratings</a>';
            echo '<a href="?fileID=' . $fileID . '&sort=desc" class="btn btn-secondary ' . ((isset($_GET['sort']) && $_GET['sort'] == 'desc') ? 'active' : '') . '">Most Ratings</a>';
            
            echo '</div>';
            echo '</div>';
        // Use the sort order parameter based on the user's choice
        $sortOrder = (isset($_GET['sort']) && $_GET['sort'] == 'asc') ? 'ASC' : 'DESC';
        displayRatings($mysqli, $fileID, $sortOrder);

        // Rating form (only visible to authenticated users who are not the owner)
        if (isset($_SESSION['userID']) && !$isOwner) {
            echo '<h3 class="mt-4">Rate this document:</h3>';
            echo '<form method="post" action="submit_rating.php" class="mb-4">';
            echo '<input type="hidden" name="fileID" value="' . $fileID . '">';

            // Dropdown for rating
            echo '<div class="mb-3">';
            echo '<label for="rating" class="form-label">Rating:</label>';
            echo '<select name="rating" class="form-select">';
            for ($i = 1; $i <= 5; $i++) {
                echo '<option value="' . $i . '">' . $i . ' star' . ($i > 1 ? 's' : '') . '</option>';
            }
            echo '</select>';
            echo '</div>';

            // Message input
            echo '<div class="mb-3">';
            echo '<label for="message" class="form-label">Message:</label>';
            echo '<textarea name="message" class="form-control" rows="3"></textarea>';
            echo '</div>';

            echo '<button type="submit" name="submitRating" class="btn btn-primary btn-black-white">Submit Rating</button>';

            echo '</form>';
        }
            
                // Edit button
                if ($isOwner) {
                    echo '<a href="edit_file.php?fileID=' . $fileID . '" class="btn btn-secondary btn-black-white mt-3 btn-smaller">Edit File</a>';

                    // Update visibility form
                    echo '<form method="post" action="update_visibility.php" class="mt-3">';
                    echo '<input type="hidden" name="fileID" value="' . $fileID . '">';
                    
                    // Dropdown for updating visibility
                    echo '<label for="updateVisibility" class="mt-3">Update Visibility:</label>';
                    echo '<select name="updateVisibility" class="form-control">';
                    echo '<option value="1" ' . ($visibility == 1 ? 'selected' : '') . '>Public</option>';
                    echo '<option value="0" ' . ($visibility == 0 ? 'selected' : '') . '>Private</option>';
                    echo '</select>';
                    echo '<input type="submit" name="submitUpdateVisibility" value="Update Visibility" class="btn btn-primary btn-black-white btn-smaller mt-3">';
                    echo '</form>'; // Close the form here

                    //delete button
                    echo '<form method="post" action="delete_file.php" class="mt-3">';
                    echo '<input type="hidden" name="fileID" value="' . $fileID . '">';
                    echo '<input type="submit" name="deleteFile" value="Delete File" class="btn btn-danger btn-black-white btn-smaller">';
                    echo '</form>'; // Make sure to close the form here
                }

                echo '</div>';
            } else {
                echo '<p class="alert alert-danger">Document not found.</p>';
            }
        }

        function displayRatings($mysqli, $fileID, $sortOrder = 'DESC') {
            $ratingsSQL = "SELECT r.rating, r.message, u.firstName, u.lastName
                           FROM ratings r
                           LEFT JOIN users u ON r.user_id = u.user_id
                           WHERE r.file_id = $fileID
                           ORDER BY r.rating $sortOrder";

            $ratingsResult = mysqli_query($mysqli, $ratingsSQL);

            if (mysqli_num_rows($ratingsResult) > 0) {
                echo '<table class="table">';
                echo '<thead><tr><th>Rated By</th><th>Rate</th><th>Message</th></tr></thead>';
                echo '<tbody>';
                while ($ratingRow = mysqli_fetch_assoc($ratingsResult)) {
                    $raterName = isset($ratingRow['firstName']) && isset($ratingRow['lastName']) ? $ratingRow['firstName'] . ' ' . $ratingRow['lastName'] : 'Anonymous';
                    $rating = $ratingRow['rating'];
                    $message = $ratingRow['message'];

                    echo '<tr>';
                    echo '<td>' . $raterName . '</td>';
                    echo '<td>' . $rating . '</td>';
                    echo '<td>' . $message . '</td>';
                    echo '</tr>';
                }
                echo '</tbody>';
                echo '</table>';
            } else {
                echo '<p>No ratings yet.</p>';
            }
        }
        ?>
    </div>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>

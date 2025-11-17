<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$userID = $_SESSION['userID'];

// Process category filter
$categoryFilter = isset($_GET['category']) ? strtolower($_GET['category']) : 'all categories';
$categoryFilterSQL = ($categoryFilter !== 'all categories') ? "AND d.category = '$categoryFilter'" : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome!</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../js/bootstrap.bundle.min.js">
    <style>
     body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
        }

        #body {
            flex: 1;
        }

        .navbar-nav {
            margin-left: 950px;
        }

        /* Add the rest of the styles from the first code here */
        /* ... */
        #body {
                font-family: 'Arial', sans-serif;
            }

            .welcome-heading {
                margin-top: 30px;
            }

            .form-control-sm {
                width: 500px;
            }

            #tabs .tab a {
                color: black;
                font-size: 16px;
                text-decoration: none;
            }

            #tabs .tab a:hover {
                text-decoration: underline;
            }

            .tab {
                margin: 0 5px;
                border-right: 2px solid black;
                padding-right: 10px;
                margin-top: 20px;
                font-size: 15px;
                color: black;
            }

            .tab:last-child {
                border-right: none;
            }

            #body form {
        margin-top: 20px; /* Adjust the margin-top value as needed */
    }

    #search {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 8px;
            width: 250px; /* Adjust the width as needed */
            height: 50px;
            margin-right: 10px; /* Add margin-right for spacing */
        
            transition: border-color 0.3s ease-in-out;
        }

        #searchButton {
            background-color: #333;
            color: white;
            border: 1px solid #333;
            border-radius: 5px;
            padding: 
            height: 50px;
            transition: background-color 0.3s ease-in-out;
        }

        #searchButton:hover {
            background-color: #555;
        }
            #documents {
        margin-bottom: 20px;
        margin-top: 10px;
        padding: 30px;
        text-align: center; /* Center the cards */
        
    }

    #documents .card {
        width: 400px; /* Set the width of the card */
        height: 500px; /* Set the height of the card */
        margin-right: 20px;
        margin-bottom: 20px;
        float: none;
        display: inline-block;

    }
    @media (max-width: 768px) {
        #documents .card {
            width: 100%; /* Full width on smaller screens */
            margin-right: 0;
        }
    }

    @media (max-width: 576px) {
        #documents .card {
            width: 100%; /* Full width on even smaller screens */
            margin-right: 0;
        }
    }


            a.card-link {
                text-decoration: none;
                color: inherit;
            }

            a.card-link:hover {
                text-decoration: none;
            }

            #footer {
                background-color: #f1f1f1;
                padding: 20px;
                display: flex;
                flex-shrink: 0;
                flex-grow: 0;
                justify-content: space-between;
                align-items: center;
            }

            #footer div {
                flex: 1;
                text-align: center;
            }

            #footer h3 {
                color: #333;
                margin-bottom: 10px;
            }

            #footer p {
                color: #555;
                margin: 5px 0;
            }

            #footer a {
                color: #007bff;
                text-decoration: none;
            }

            #footer a:hover {
                color: #0056b3;
            }
            .pagination li.page-item {
            background-color: white;
            border-color: black; /* Change the border color to black */
        }

        .pagination a.page-link {
            color: black;
        }

        .pagination .active a.page-link {
            background-color: black;
            color: white;
            border-color: black; /* Change the border color to black */
        }


        .btn-outline-primary:hover {
            background-color: black;
            color: white;
            border-color: white;
        }
        .btn-group {
            margin-top: 10px; /* Adjust the margin-top value as needed */
        }

        .btn-outline-primary {
            background-color: white;
            color: black;
            border-color: black;
            margin-right: 5px; /* Adjust the margin-right value as needed */
        }

        .btn-outline-primary:last-child {
            margin-right: 0;
        }

        .btn-outline-primary:hover {
            background-color: black;
            color: white;
            border-color: white;
        }

        /* Additional styles for "View Document" button */
        .btn-outline-success {
            margin-top: 10px; /* Adjust the margin-top value as needed */
            color: black;
            border-color: black;
        }

        .btn-outline-success:hover {
            background-color: black;
            color: white;
            border-color: white;
        }
        /* Existing styles from the second code */
        #pagination a {
            text-decoration: none;
        }
</style>
</head>
<body>


<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">
            <img src="../img/logo.png" alt="DND Collab Logo" height="30" class="d-inline-block align-top">DND Libraries</a>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
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
<div id="body" class="text-center">
    <h1 class="display-4 welcome-heading">WELCOME!</h1>
    <div class="d-flex justify-content-center">
            <form class="d-flex justify-content-center" method="get" action="search_results.php">
                <input type="form-control form-control-sm" id="search" name="search" placeholder="Search for: #tags, Title, or Author">
                <button class="btn btn-outline-success" type="submit" id="searchButton">Search</button>
            </form>
        </div>
        <div id="tabs" class="d-flex justify-content-center">
            <div class="tab">
                <select id="categoryDropdown" onchange="filterByCategory(this.value)">
                    <option value="All Categories">All Categories</option>
                    <option value="PDF">PDF</option>
                    <option value="Compressed Folder">Compressed Folder</option>
                    <option value="Other">Other</option>
                </select>
            </div>

        <div class="tab">
            <a href="index.php?sort=views">Most Viewed</a>
        </div>
        <div class="tab">
            <a href="index.php?sort=favorites">Most Favorites</a>
        </div>
    </div>

    <div id="documents" class="row row-cols-3 g-4">
        <?php
        require_once '../includes/config.php';

        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['fileID'])) {
            $fileID = $_POST['fileID'];
            $userID = $_SESSION['userID'];

            if (isset($_POST['favorite'])) {
                $checkFavoriteSQL = "SELECT * FROM user_favorites WHERE user_id = $userID AND file_id = $fileID";
                $checkFavoriteResult = mysqli_query($mysqli, $checkFavoriteSQL);

                if (mysqli_num_rows($checkFavoriteResult) === 0) {
                    $insertFavoriteSQL = "INSERT INTO user_favorites (user_id, file_id) VALUES ($userID, $fileID)";
                    if (mysqli_query($mysqli, $insertFavoriteSQL)) {
                        $updateFavoritesSQL = "UPDATE documents SET favorites = favorites + 1 WHERE file_id = $fileID";
                        mysqli_query($mysqli, $updateFavoritesSQL);
                    }
                }
            } elseif (isset($_POST['unfavorite'])) {
                $checkFavoriteSQL = "SELECT * FROM user_favorites WHERE user_id = $userID AND file_id = $fileID";
                $checkFavoriteResult = mysqli_query($mysqli, $checkFavoriteSQL);

                if (mysqli_num_rows($checkFavoriteResult) > 0) {
                    $deleteFavoriteSQL = "DELETE FROM user_favorites WHERE user_id = $userID AND file_id = $fileID";
                    if (mysqli_query($mysqli, $deleteFavoriteSQL)) {
                        $updateFavoritesSQL = "UPDATE documents SET favorites = favorites - 1 WHERE file_id = $fileID";
                        mysqli_query($mysqli, $updateFavoritesSQL);
                    }
                }
            }
        }

        $sql = "SELECT d.file_id, d.title, d.category, d.description, d.tags, IFNULL(CONCAT(u.firstName, ' ', u.lastName), 'Deleted User') AS author, d.size, IFNULL(d.infoViews, 0) AS infoViews, IFNULL(d.favorites, 0) AS favorites, IFNULL(d.status, 'active') AS status, d.file, d.visibility
        FROM documents d
        LEFT JOIN users u ON d.author = u.user_id
        WHERE d.status != 'rejected' AND d.visibility = 1 $categoryFilterSQL";

        if (isset($row['infoViews'])) {
            $infoViews = $row['infoViews'];
        } else {
            $infoViews = 0;
        }

        if (isset($row['favorites'])) {
            $favorites = $row['favorites'];
        } else {
            $favorites = 0;
        }

        if (isset($row['status'])) {
            $status = $row['status'];
        } else {
            $status = 'active';
        }

        if (isset($_GET['sort'])) {
            $sortOption = $_GET['sort'];

            if ($sortOption === 'views') {
                $sql .= " ORDER BY d.infoViews DESC";
            } elseif ($sortOption === 'favorites') {
                $sql .= " ORDER BY d.favorites DESC";
            }
        }

        $documentsPerPage = 3;
        $current_page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $offset = ($current_page - 1) * $documentsPerPage;

        $sql .= " LIMIT $documentsPerPage OFFSET $offset";

        $result = mysqli_query($mysqli, $sql);

        // Calculate the total number of documents
        $totalDocumentsSQL = "SELECT COUNT(*) AS total FROM documents WHERE status != 'rejected'";
        $totalDocumentsResult = mysqli_query($mysqli, $totalDocumentsSQL);
        $totalDocumentsRow = mysqli_fetch_assoc($totalDocumentsResult);
        $totalDocuments = $totalDocumentsRow['total'];

        $totalPages = ceil($totalDocuments / $documentsPerPage);

        
        if ($result && mysqli_num_rows($result) > 0) {
             while ($row = mysqli_fetch_assoc($result)) {
                $fileID = $row['file_id'];
                $title = $row['title'];
                $category = $row['category'];
                $description = $row['description'];
                $tags = $row['tags'];
                $author = $row['author'];
                $size = $row['size'];
                $infoViews = $row['infoViews'];
                $favorites = $row['favorites'];
                $status = $row['status'];
        
            $isFavorited = false;
            $checkFavoriteSQL = "SELECT * FROM user_favorites WHERE user_id = $userID AND file_id = $fileID";
            $checkFavoriteResult = mysqli_query($mysqli, $checkFavoriteSQL);
            if (mysqli_num_rows($checkFavoriteResult) > 0) {
                $isFavorited = true;
            }
        
            // Skip files with the status "removed" or "inactive"
            if ($status === 'removed' || $status === 'inactive') {
                continue;
            }
        
            echo "<div class='col'>
            <div class='card mb-3'>
                <img src='../img/\Document.jpg' class='img-fluid rounded-start' alt='File Icon'>
                <div class='card-body'>
                    <h5 class='card-title'>$title</h5>
                    <p class='card-text'>$description</p>
                    <p class='card-text'><small class='text-muted'>Author: $author | Category: $category | Size: $size KB | Views: $infoViews | Favorites: $favorites</small></p>
                    <form method='post' action='../pages/index.php'>
                        <input type='hidden' name='fileID' value='$fileID'>
                        <button type='submit' class='btn btn-outline-primary' name='favorite'>Favorite</button>
                        <button type='submit' class='btn btn-outline-primary' name='unfavorite'>Unfavorite</button>
                    </form>
                    <a href='../pages/document_details.php?fileID=$fileID' class='btn btn-outline-success'>View Document</a>
                </div>
            </div>
        </div>";
}
} else {
echo "<p>No documents found.</p>";
}

        ?>
    </div>
    <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                    <li class="page-item <?php echo ($current_page === $i) ? 'active' : ''; ?>">
                        <a class="page-link" href="index.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>
        
  
</div>

<footer id="footer">
        <div>
            <h3>Contact Us</h3>
            <p>Email: info@dndcollab.com</p>
        </div>
        <div>
            <h3>Follow Us</h3>
            <a href="#" target="_blank"><i class="fab fa-facebook-square fa-2x"></i></a>
            <a href="#" target="_blank"><i class="fab fa-twitter-square fa-2x"></i></a>
            <a href="#" target="_blank"><i class="fab fa-instagram-square fa-2x"></i></a>
        </div>
        <div>
            <h3>Privacy Policy</h3>
            <p><a href="#" target="_blank">Read our Privacy Policy</a></p>
        </div>
    </footer>

<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

<script>
    function filterByCategory(selectedCategory) {
        if (selectedCategory !== 'All Categories') {
            window.location.href = 'categoryResults.php?category=' + encodeURIComponent(selectedCategory);
        } else {
            window.location.href = 'index.php'; // Redirect to index.php for 'All Categories'
        }
    }
</script>
</body>
</html>

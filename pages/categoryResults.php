<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: ../auth/login.php");
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
    <title>Category Results</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../js/bootstrap.bundle.min.js">
   <style>
        /* Add custom styles if needed */
        #body {
            padding: 20px;
        }
        .document {
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .document img {
            max-width: 100%;
            height: auto;
        }
        .favorite-container {
            display: flex;
            align-items: center;
        }
        .favorite-button, .unfavorite-button {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 18px;
            margin-right: 5px;
        }
    </style>
</head>

<body>
    <?php include '../includes/navbar.php'; ?>

    <div id="body">
        <h1 class="mb-4 text-center">Category Results - <?php echo ucfirst($categoryFilter); ?></h1>
        <div id="documents" class="row row-cols-1 row-cols-md-3">
            <?php
            require_once '../includes/config.php';

            $sql = "SELECT d.file_id, d.title, d.category, d.description, d.tags, IFNULL(CONCAT(u.firstName, ' ', u.lastName), 'Deleted User') AS author, d.size, IFNULL(d.infoViews, 0) AS infoViews, IFNULL(d.favorites, 0) AS favorites, IFNULL(d.status, 'active') AS status, d.file, d.visibility
                    FROM documents d
                    LEFT JOIN users u ON d.author = u.user_id
                    WHERE d.status != 'rejected' $categoryFilterSQL AND (d.visibility = 1 OR d.author = $userID)";

            $result = mysqli_query($mysqli, $sql);

            while ($row = mysqli_fetch_assoc($result)) {
                $fileID = $row['file_id'];
                $title = $row['title'];
                $author = isset($row['author']) ? $row['author'] : '';
                $tags = isset($row['tags']) ? $row['tags'] : '';
                $file = $row['file'];
                $category = $row['category'];
                $views = $row['infoViews'];
                $favorites = $row['favorites'];
                $status = $row['status'];

                $isFavorited = false;
                $checkFavoriteSQL = "SELECT * FROM user_favorites WHERE user_id = $userID AND file_id = $fileID";
                $checkFavoriteResult = mysqli_query($mysqli, $checkFavoriteSQL);
                if (mysqli_num_rows($checkFavoriteResult) > 0) {
                    $isFavorited = true;
                }

                if ($status === 'inactive') {
                    continue;
                }
                ?>
                <div class="col mb-4">
                    <div class="card document shadow">
                        <div class="card-body">
                            <h5 class="card-title mb-3"><?php echo $title; ?></h5>
                            <a href="../pages/document_details.php?fileID=<?php echo $fileID; ?>&file=<?php echo $file; ?>">
                                <img src="../img/document.jpg" alt="DocumentImage" class="card-img-top img-fluid">
                            </a>
                            <form method="post" action="../pages/index.php">
                                <input type="hidden" name="fileID" value="<?php echo $fileID; ?>">
                                <p class="card-text mb-1">Category: <?php echo $category; ?></p>
                                <p class="card-text mb-1">Author: <?php echo $author; ?></p>
                                <p class="card-text mb-1">Tags: <?php echo $tags; ?></p>
                                <p class="card-text mb-1"><i class="far fa-eye"></i> <?php echo $views; ?></p>
                                <div class="favorite-container">
                                    <?php if ($isFavorited) : ?>
                                        <button class="unfavorite-button" name="unfavorite">
                                            <i class="fa-solid fa-bookmark"></i>
                                        </button>
                                    <?php else : ?>
                                        <button class="favorite-button" name="favorite">
                                            <i class="fa-regular fa-bookmark"></i>
                                        </button>
                                    <?php endif; ?>
                                    <?php echo $favorites; ?>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>
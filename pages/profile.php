<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: ../auth/login.php");
    exit();
}

require_once '../includes/config.php';

$userID = $_SESSION['userID'];

$sql = "SELECT * FROM users WHERE user_id = $userID";
$result = mysqli_query($mysqli, $sql);

if ($row = mysqli_fetch_assoc($result)) {
    $firstName = ucfirst($row['firstName']);
    $lastName = ucfirst($row['lastName']);
    $email = $row['email'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Profile</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../js/bootstrap.bundle.min.js">
    <style>
        #body {
            padding-left: 50px; /* Adjust the left padding as needed */
            margin-bottom: 20px;
            padding-right:50px
        }

        #userInfo {
            margin-bottom: 20px;
            font-size: 20px; /* Adjust the font size as needed */
        }

        #userInfo p {
            margin: 5px 0;
        }

        #editProfileLink {
            color: black; /* Set the link color */
        }

        #uploadsContent,
        #tabButtons,
        #favoritesContent {
            margin-bottom: 20px;
        }

        #tabButtons {
            margin-top: 20px;
        }

        .document {
            display: inline-block;
            margin-right: 20px;
            vertical-align: top;
        }
    </style>
</head>
<body>
    <?php include '../includes/navbar.php'; ?>

    <div id="body">
        <center><h1>Profile</h1></center>

        <!-- User Information Section -->
        <div id="userInfo">
            <p><strong>First Name:</strong> <?php echo $firstName; ?></p>
            <p><strong>Last Name:</strong> <?php echo $lastName; ?></p>
            <p><strong>Email:</strong> <?php echo $email; ?></p>
            <a href="../actions/edit_profile.php" id="editProfileLink">Edit Profile</a>
        </div>

        <div class="card" id="tabButtons">
            <div class="card-body">
                <button class="btn btn-primary" id="uploadsTab">Uploads</button>
                <button class="btn btn-primary" id="favoritesTab">Favorites</button>
            </div>
        </div>

        <!-- Bootstrap Card for uploadsContent -->
        <div class="card" id="uploadsContent">
            <div class="card-body">
                <h2 class="card-title">Your Uploaded Documents</h2>
                <div class="row g-4">
                    <?php
                    require_once '../includes/config.php';

                    function getUploadedDocuments($userID, $mysqli) {
                        $query = "SELECT * FROM documents WHERE author = ? AND status = 'active'";
                        $stmt = $mysqli->prepare($query);
                        $stmt->bind_param("i", $userID);

                        $uploadedDocuments = array();

                        if ($stmt->execute()) {
                            $result = $stmt->get_result();

                            while ($row = $result->fetch_assoc()) {
                                $uploadedDocuments[] = $row;
                            }

                            $stmt->close();

                            return $uploadedDocuments;
                        } else {
                            $stmt->close();

                            return false;
                        }
                    }                

                    $userID = $_SESSION['userID'];
                    $uploadedDocuments = getUploadedDocuments($userID, $mysqli);

                    if ($uploadedDocuments) {
                        foreach ($uploadedDocuments as $document) {
                            $fileID = $document['file_id'];
                            $title = $document['title'];
                            $category = $document['category'];
                            $infoViews = $document['infoViews'];
                            $file = $document['file'];

                            echo '<div class="col mb-4">';
                            echo "<h3>$title</h3>";
                            echo '<a href="../pages/document_details.php?fileID=' . $fileID . '&file=' . $file . '"><img src="../img/document.jpg" alt="DocumentImage"></a>';
                            echo "<p>Category: $category</p>";
                            echo '<p><i class="far fa-eye"></i> ' . $infoViews . '</p>';
                            echo '</div>';
                        }
                    } else {
                        echo "No uploaded documents found.";
                    }
                    ?>
                </div>
            </div>
        </div>

        <!-- Bootstrap Card for favoritesContent -->
        <div id="favoritesContent">
            <div class="card" id="uploadsContent">
                <div class="card-body">
                    <h2 class="card-title">Your Favorite Documents</h2>
                    <div class="row g-4">
                        <?php
                        require_once '../includes/config.php';

                        function getFavoriteDocuments($userID, $mysqli) {
                            $query = "SELECT d.* FROM documents d
                                        INNER JOIN user_favorites uf ON d.file_id = uf.file_id
                                        WHERE uf.user_id = ? AND d.status != 'rejected'";
                            $stmt = $mysqli->prepare($query);
                            $stmt->bind_param("i", $userID);
                            $stmt->execute();

                            $favoriteDocuments = array();
                            $result = $stmt->get_result();

                            while ($row = $result->fetch_assoc()) {
                                $favoriteDocuments[] = $row;
                            }

                            $stmt->close();

                            return $favoriteDocuments;
                        }            

                        $userID = $_SESSION['userID'];
                        $favoriteDocuments = getFavoriteDocuments($userID, $mysqli);

                        if ($favoriteDocuments) {
                            foreach ($favoriteDocuments as $document) {
                                $fileID = $document['file_id'];
                                $title = $document['title'];
                                $category = $document['category'];
                                $views = $document['infoViews'];
                                $file = $document['file'];

                                echo '<div class="col-md-4">';
                                echo "<h3>$title</h3>";
                                echo '<a href="../pages/document_details.php?fileID=' . $fileID . '&file=' . $file . '"><img src="../img/document.jpg" alt="DocumentImage"></a>';
                                echo "<p>Category: $category</p>";
                                echo '<p><i class="far fa-eye"></i>' . $views . '</p>';
                                echo '</div>';
                            }
                        } else {
                            echo "No favorite documents found.";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const uploadsTab = document.getElementById("uploadsTab");
            const favoritesTab = document.getElementById("favoritesTab");
            const uploadsContent = document.getElementById("uploadsContent");
            const favoritesContent = document.getElementById("favoritesContent");

            uploadsTab.classList.add("active");
            favoritesContent.style.display = "none";

            uploadsTab.addEventListener("click", function () {
                uploadsTab.classList.add("active");
                favoritesTab.classList.remove("active");
                uploadsContent.style.display = "block";
                favoritesContent.style.display = "none";
            });

            favoritesTab.addEventListener("click", function () {
                favoritesTab.classList.add("active");
                uploadsTab.classList.remove("active");
                favoritesContent.style.display = "block";
                uploadsContent.style.display = "none";
            });
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-hwNlg9srC2bZJUE3xQnIyB79zjg9Zc3rnTzMx8u8p94NX9Rsq9ImLiRT9OkJoBn1" crossorigin="anonymous"></script>
</body>
</html>

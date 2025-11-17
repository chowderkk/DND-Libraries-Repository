<?php
session_start(); 

require_once '../includes/config.php'; 

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['approve'])) {
        // Admin approved a file
        $fileID = $_POST['fileID'];
        
        // Update the status in the "documents" table to 'active'
        $updateDocumentStatusSQL = "UPDATE documents SET status = 'active' WHERE file_id = $fileID";
        if (mysqli_query($mysqli, $updateDocumentStatusSQL)) {
            // Remove the file from the "authorization" table
            $deleteAuthorizationSQL = "DELETE FROM authorization WHERE file_id = $fileID";
            if (mysqli_query($mysqli, $deleteAuthorizationSQL)) {
                // File approved and removed from authorization
    
                // Get the document title
                $documentTitleSQL = "SELECT title FROM documents WHERE file_id = $fileID";
                $documentTitleResult = mysqli_query($mysqli, $documentTitleSQL);
    
                if ($documentTitleRow = mysqli_fetch_assoc($documentTitleResult)) {
                    $documentTitle = $documentTitleRow['title'];
    
                    // Notify the user with the approval
                    $authorIDSQL = "SELECT author FROM documents WHERE file_id = $fileID";
                    $authorIDResult = mysqli_query($mysqli, $authorIDSQL);
                    if ($authorIDRow = mysqli_fetch_assoc($authorIDResult)) {
                        $userID = $authorIDRow['author'];
                        $action = 'Approved';
    
                        // Insert a notification into the notifications table
                        $insertNotificationSQL = "INSERT INTO notifications (user_id, action, document_title, timestamp) VALUES ($userID, '$action', '$documentTitle', NOW())";
                        mysqli_query($mysqli, $insertNotificationSQL);
                    }
    
                    header("Location: authorization.php");
                } else {
                    echo "Error getting document title: " . mysqli_error($mysqli);
                }
            } else {
                echo "Error removing file from authorization table: " . mysqli_error($mysqli);
            }
        } else {
            echo "Error updating document status: " . mysqli_error($mysqli);
        }
    }   elseif (isset($_POST['reject'])) {
        // Admin rejected a file
        $fileID = $_POST['fileID'];
    
        header("Location: reject.php?fileID=$fileID");
    }    
}


// Retrieve files with 'pending' status from the "authorization" table
$authorizationSQL = "SELECT a.authorization_id, a.user_id, a.file_id, d.title, d.category, d.description, d.tags, IFNULL(CONCAT(u.firstName, ' ', u.lastName), 'Deleted User') AS author, d.size, d.file, a.status
                    FROM authorization a
                    JOIN documents d ON a.file_id = d.file_id
                    LEFT JOIN users u ON d.author = u.user_id
                    WHERE a.status = 'pending'";

$result = mysqli_query($mysqli, $authorizationSQL);

if (!$result) {
    die("Error: " . mysqli_error($mysqli));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Authorization</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../js/bootstrap.bundle.min.js">
    <style>
        body {
            padding: 1px;
        }

        h1 {
            margin-bottom: 20px;
        }

        .navbar {
            margin-bottom: 10px;
            padding-left: 10px;
        }

        table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        .btn-approve, .btn-reject {
            padding: 6px 12px;
            margin-right: 5px;
        }
        
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="admin_index.php">DND Libraries</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                   
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1>Admin Authorization</h1>
          
                </div>
                <?php
                if (mysqli_num_rows($result) > 0) {
                ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Tags</th>
                            <th>Author</th>
                            <th>File Size</th>
                            <th>Action</th>
                            <th>View</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>{$row['title']}</td>";
                            echo "<td>{$row['category']}</td>";
                            echo "<td>{$row['description']}</td>";
                            echo "<td>{$row['tags']}</td>";
                            echo "<td>{$row['author']}</td>";
                            echo "<td>{$row['size']} MB</td>";
                            echo "<td>";
                            echo "<form method='post'>";
                            echo "<input type='hidden' name='fileID' value='{$row['file_id']}'>";
                            echo "<button type='submit' name='approve' class='btn btn-success btn-approve'>Approve</button>";
                            echo "</form>";
                            echo "<form method='post' action='reject.php'>";
                            echo "<input type='hidden' name='fileID' value='{$row['file_id']}'>";
                            echo "<a href='reject.php?fileID={$row['file_id']}' class='btn btn-danger btn-reject'>Reject</a>";
                            echo "</form>";
                            echo "</td>";
                            echo "<td><a href='{$row['file']}' target='_blank' class='btn btn-info'>View File</a></td>";
                            echo "</tr>";    
                        }
                        ?>
                    </tbody>
                </table>
                <?php
                } else {
                    echo "<p class='alert alert-info'>There are no files to approve yet.</p>";
                }
                ?>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-eWStqQBEpA+LBMB7Ee2b5Ff5GimrXszCzIfe2bI4J3kBIi1MOmO5TOCJxW0DBYjz" crossorigin="anonymous"></script>
</body>
</html>
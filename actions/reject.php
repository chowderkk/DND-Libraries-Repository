<?php
session_start();
require_once '../includes/config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['reject'])) {
        $fileID = $_POST['fileID'];
        $message = mysqli_real_escape_string($mysqli, $_POST['message']);

        // Update the status in the "documents" table to 'rejected'
        $updateDocumentStatusSQL = "UPDATE documents SET status = 'rejected' WHERE file_id = $fileID";
        if (mysqli_query($mysqli, $updateDocumentStatusSQL)) {
            // Remove the file from the "authorization" table
            $deleteAuthorizationSQL = "DELETE FROM authorization WHERE file_id = $fileID";
            if (mysqli_query($mysqli, $deleteAuthorizationSQL)) {
                // File rejected and removed from authorization

                // Notify the user with the rejection message
                $documentTitleSQL = "SELECT title FROM documents WHERE file_id = $fileID";
                $documentTitleResult = mysqli_query($mysqli, $documentTitleSQL);
                if ($documentTitleRow = mysqli_fetch_assoc($documentTitleResult)) {
                    $documentTitle = $documentTitleRow['title'];
                    $authorIDSQL = "SELECT author FROM documents WHERE file_id = $fileID";
                    $authorIDResult = mysqli_query($mysqli, $authorIDSQL);
                    if ($authorIDRow = mysqli_fetch_assoc($authorIDResult)) {
                        $userID = $authorIDRow['author'];
                        $action = 'Rejected';
                        // Insert the message into the notifications table
                        $insertNotificationSQL = "INSERT INTO notifications (user_id, action, document_title, timestamp, message) VALUES ($userID, '$action', '$documentTitle', NOW(), '$message')";
                        mysqli_query($mysqli, $insertNotificationSQL);
                    }
                }
                header("Location: authorization.php");
            } else {
                echo "Error removing file from authorization table: " . mysqli_error($mysqli);
            }
        } else {
            echo "Error updating document status: " . mysqli_error($mysqli);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Rejection Page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../js/bootstrap.bundle.min.js">
    <style>
        /* Custom styles for the submit rejection button */
        .btn-reject {
            color: #fff; /* White font color */
            background-color: #000; /* Black background color */
            border: none; /* Remove border */
        }

        .btn-reject:hover,
        .btn-reject:active,
        .btn-reject:focus {
            color: #fff; /* White font color */
            background-color: #000; /* Black background color */
            border: none; /* Remove border */
        }

        /* Add spacing around the form */
        .form-container {
            margin-top: 20px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h1 class="text-center">Rejection Page</h1>
                <form method="post" class="form-container">
                    <input type='hidden' name='fileID' value='<?php echo isset($_GET['fileID']) ? $_GET['fileID'] : ''; ?>'>
                    <div class="form-group">
                        <label for="message">Message:</label>
                        <textarea class="form-control" name="message" id="message" rows="4" cols="50"></textarea>
                    </div>
                    <button type="submit" class="btn btn-reject" name="reject">Submit</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Include Bootstrap JS and Popper.js -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.2/js/bootstrap.min.js"></script>
</body>
</html>


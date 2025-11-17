<?php
session_start();

require_once '../includes/config.php';

// Check for a successful database connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Check if a delete action is requested
if (isset($_POST['delete']) && isset($_POST['notification_id'])) {
    $notificationId = $_POST['notification_id'];
    
    // Delete the selected notification
    $deleteSql = "DELETE FROM notifications WHERE notification_id = ?";
    $stmt = $mysqli->prepare($deleteSql);
    $stmt->bind_param("i", $notificationId);
    
    if ($stmt->execute()) {
        // Notification deleted successfully
    } else {
        // Handle the error
        echo "Error deleting notification: " . $stmt->error;
    }
}

// Fetch notifications with author names from your database, including notification_id
$sql = "SELECT n.notification_id, n.action, n.document_title, n.timestamp, n.message, CONCAT(u.firstName, ' ', u.lastName) AS author
        FROM notifications n
        INNER JOIN users u ON n.user_id = u.user_id";

$result = $mysqli->query($sql);

// Check if there are notifications
if ($result->num_rows > 0) {
    $notifications = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $notifications = [];
}

// Close the database connection
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../js/bootstrap.bundle.min.js">
    <title>Notification History</title>
</head>
<body>
    <!-- Bootstrap Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="../admin/admin_index.php">DND Libraries</a>
    </nav>

    <div id="body" class="container mt-5">
        <div>
            <h2>Notification History</h2>
            
            <!-- Add a search bar for author names -->
            <form id="authorSearchForm" class="mb-3">
                <label for="authorSearch">Search by Author:</label>
                <div class="input-group">
                    <input type="text" id="authorSearch" class="form-control" placeholder="Enter author's name">
                    <div class="input-group-append">
                        <button type="button" onclick="searchByAuthor()" class="btn btn-primary">Search</button>
                        <button type="button" onclick="clearAuthorSearch()" class="btn btn-secondary">Clear</button>
                    </div>
                </div>
            </form>

            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th>Action</th>
                        <th>Title</th>
                        <th>Timestamp</th>
                        <th>Message</th>
                        <th>Author</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($notifications as $notification) : ?>
                        <tr>
                            <td><?php echo $notification['action']; ?></td>
                            <td><?php echo $notification['document_title']; ?></td>
                            <td><?php echo $notification['timestamp']; ?></td>
                            <td><?php echo $notification['message']; ?></td>
                            <td><?php echo $notification['author']; ?></td>
                            <td>
                                <form method="post">
                                    <input type="hidden" name="notification_id" value="<?php echo $notification['notification_id']; ?>">
                                    <button type="submit" name="delete" class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add JavaScript for author search -->
    <script>
        function searchByAuthor() {
            var authorSearchText = document.getElementById('authorSearch').value.toLowerCase();

            var rows = document.querySelectorAll('table tbody tr');
            rows.forEach(function (row) {
                var authorName = row.querySelector('td:nth-child(5)').textContent.toLowerCase();

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
</body>
</html>

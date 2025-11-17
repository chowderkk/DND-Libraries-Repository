<?php
// Include your database configuration
require_once '../includes/config.php';

// Check if the delete form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete']) && isset($_POST['history_id'])) {
    $historyId = $_POST['history_id'];

    // Delete the selected history record
    $deleteSql = "DELETE FROM edit_history WHERE id = ?";
    $stmt = $mysqli->prepare($deleteSql);
    $stmt->bind_param("i", $historyId);
    
    if ($stmt->execute()) {
        // history record deleted successfully
        header("Location: {$_SERVER['PHP_SELF']}"); // Redirect to refresh the page
        exit();
    } else {
        // Handle the error
        echo "Error deleting history record: " . $stmt->error;
    }
}

// Query to retrieve file modification history including title and author
$sql = "SELECT h.id, d.title, CONCAT(u.firstName, ' ', u.lastName) AS author, h.date_of_modification AS modifiedDate
        FROM edit_history h
        JOIN documents d ON h.file_id = d.file_id
        JOIN users u ON h.user_id = u.user_id
        ORDER BY h.date_of_modification DESC";

 // You can adjust the ORDER BY clause as needed

$result = mysqli_query($mysqli, $sql);

if ($result) {
    $historyRecords = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    die("Error: " . mysqli_error($mysqli));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   
    <title>File Modification history</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../js/bootstrap.bundle.min.js">
    <style>
        .search-bar {
            margin-bottom: 15px; /* Add margin to the bottom of the search bar for spacing */
        }
        .navbar-brand {
            padding-left: 15px; /* Add padding-left to the navbar-brand */
        }
    </style>
</head>
<body>

<!-- Bootstrap Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="../admin/admin_index.php">DND Libraries</a>
</nav>

<div class="container mt-5">
    <h1>File Modification history</h1>
    
    <!-- Search Bar with Bootstrap -->
    <div class="input-group mb-3">
        <input type="text" class="form-control search-bar" id="search" placeholder="Search by author">
        <div class="input-group-append">
            <button class="btn btn-outline-secondary" type="button" onclick="searchUsers()">Search</button>
            <button class="btn btn-outline-secondary" type="button" onclick="clearSearch()">Clear</button>
        </div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>history ID</th>
                <th>Title</th>
                <th>Author</th>
                <th>Modified Date</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($historyRecords)) {
                foreach ($historyRecords as $record) {
                    echo "<tr>";
                    echo "<td>" . $record['id'] . "</td>";
                    echo "<td>" . $record['title'] . "</td>";
                    echo "<td>" . $record['author'] . "</td>";
                    echo "<td>" . $record['modifiedDate'] . "</td>";
                    echo "<td>
                            <form method='post'>
                                <input type='hidden' name='history_id' value='" . $record['id'] . "'>
                                <button type='submit' name='delete' class='btn btn-danger'>Delete</button>
                            </form>
                        </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No file modification history found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Bootstrap Scripts (jQuery and Bootstrap JS) -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

<script>
function searchUsers() {
    var searchValue = document.getElementById("search").value.toLowerCase();
    var rows = document.querySelectorAll("tbody tr");

    rows.forEach(function (row) {
        var authorCell = row.querySelector("td:nth-child(3)");
        var author = authorCell.textContent.toLowerCase();
        if (author.indexOf(searchValue) > -1) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    });
}

function clearSearch() {
    document.getElementById("search").value = "";
    searchUsers();
}
</script>

</body>
</html>

<?php
session_start();
require_once '../includes/config.php';

// Query to retrieve all uploaded documents with additional information, including author status
$sql = "SELECT d.file_id, d.title, d.category, d.author, d.tags, d.infoViews, d.favorites, d.dateCreated, d.status, d.visibility, u.firstName, u.lastName, u.roles
        FROM documents d
        LEFT JOIN users u ON d.author = u.user_id";

$result = mysqli_query($mysqli, $sql);

if ($result) {
    $uploadedDocuments = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    die("Error: " . mysqli_error($mysqli));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Documents</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        .search-bar {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<!-- Bootstrap Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="admin_index.php">DND Libraries</a>
    </div>
</nav>

<div class="container mt-5">
 
    <h1>View Documents</h1>

    <!-- Search Bar with Bootstrap -->
    <div class="input-group mb-3">
        <input type="text" class="form-control search-bar" id="search" placeholder="Search by title, author, or tags">
        <div class="input-group-append">
            <button class="btn btn-outline-secondary" type="button" onclick="searchDocuments()">Search</button>
            <button class="btn btn-outline-secondary" type="button" onclick="clearSearch()">Clear</button>
        </div>
    </div>

    <div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>File ID</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Author</th>
                    <th>Tags</th>                        
                    <th>Views</th>
                    <th>Favorites</th>
                    <th>Date Created</th>
                    <th>Status</th>
                    <th>Visibility</th>
                    <th>Action</th>
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
                        $authorName = !empty($document['firstName']) ? $document['firstName'] . ' ' . $document['lastName'] : "Deleted User";
                        echo "<td>" . $authorName . "</td>";
                        echo "<td>" . $document['tags'] . "</td>";                    
                        echo "<td>" . $document['infoViews'] . "</td>";
                        echo "<td>" . $document['favorites'] . "</td>";
                        echo "<td>" . $document['dateCreated'] . "</td>";
                        echo "<td>" . $document['status'] . "</td>";
                        echo "<td>" . ($document['visibility'] == 1 ? 'Public' : 'Private') . "</td>";
                        echo '<td>
                                  <a href="../pages/view_details.php?fileID=' . $document['file_id'] . '" class="btn btn-primary btn-sm">View</a>
                                  <a href="../admin/delete_document.php?fileID=' . $document['file_id'] . '" class="btn btn-danger btn-sm ml-1" onclick="return confirm(\'Are you sure you want to delete this document?\')">Delete</a>
                              </td>';
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='11'>No documents found.</td></tr>";
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

<script>
    function searchDocuments() {
        var searchText = document.getElementById('search').value.toLowerCase();

        var rows = document.querySelectorAll('table tbody tr');
        rows.forEach(function (row) {
            var title = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
            var author = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
            var tags = row.querySelector('td:nth-child(5)').textContent.toLowerCase();

            if (title.includes(searchText) || author.includes(searchText) || tags.includes(searchText)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    function clearSearch() {
        document.getElementById('search').value = '';
        searchDocuments();
    }
</script>

</body>
</html>

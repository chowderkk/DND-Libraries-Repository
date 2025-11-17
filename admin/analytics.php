<?php
// Function to execute SQL queries
function executeQuery($query)
{
    // Implement your database connection logic here
    $conn = new mysqli("localhost", "root", "", "opensource");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $result = $conn->query($query);

    $conn->close();

    return $result;
}

// Total views across all days
$totalViewsQuery = "SELECT SUM(infoViews) AS TotalViews FROM documents";
$totalViewsResult = executeQuery($totalViewsQuery);

// Favorites
$favoritesQuery = "SELECT COUNT(*) AS TotalFavorites FROM user_favorites";
$favoritesResult = executeQuery($favoritesQuery);

// Ratings distribution
$ratingsDistributionQuery = "SELECT rating, COUNT(*) AS RatingCount FROM ratings GROUP BY rating";
$ratingsDistributionResult = executeQuery($ratingsDistributionQuery);

// Documents per category
$documentsPerCategoryQuery = "SELECT category, COUNT(*) AS DocumentsCount FROM documents GROUP BY category";
$documentsPerCategoryResult = executeQuery($documentsPerCategoryQuery);

// Total Approvals
$totalApprovalsQuery = "SELECT COUNT(*) AS TotalApprovals FROM notifications WHERE action = 'Approved'";
$totalApprovalsResult = executeQuery($totalApprovalsQuery);

// Total Rejects
$totalRejectsQuery = "SELECT COUNT(*) AS TotalRejects FROM notifications WHERE action = 'Rejected'";
$totalRejectsResult = executeQuery($totalRejectsQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../js/bootstrap.bundle.min.js">
   <style>
        body {
            padding-top: 56px; /* Adjust the padding-top to accommodate the fixed navbar */
        }

        @media (min-width: 768px) {
            body {
                padding-top: 76px; /* Adjust the padding-top for larger screens if needed */
            }
        }

        .navbar-brand {
            padding-left: 15px; /* Add padding-left to the navbar-brand */
        }

        h1, h2, p {
            margin-bottom: 20px; /* Add margin-bottom to create space between elements */
        }

        table {
            width: 100%;
            margin-bottom: 20px;
        }
        p {
            font-size: 30px; /* Adjust the font size as needed */
        }
    </style>
</head>
<body>

<!-- Bootstrap Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
    <a class="navbar-brand" href="admin_index.php">DND Libraries</a>
</nav>

<div class="container mt-5">
    
    <h1>Admin Analytics</h1>
 
    <!-- Views -->
    <h2>Total Views:</h2>
    <p><?php echo $totalViewsResult->fetch_assoc()['TotalViews']; ?></p>

    <!-- Favorites -->
    <h2>Total Favorites:</h2>
    <p><?php echo $favoritesResult->fetch_assoc()['TotalFavorites']; ?></p>

    <!-- Ratings distribution -->
    <h2>Ratings distribution:</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Rating</th>
                <th>Count</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $ratingsDistributionResult->fetch_assoc()) : ?>
                <tr>
                    <td><?php echo $row['rating']; ?></td>
                    <td><?php echo $row['RatingCount']; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Documents per category -->
    <h2>Documents per category:</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Category</th>
                <th>Count</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $documentsPerCategoryResult->fetch_assoc()) : ?>
                <tr>
                    <td><?php echo $row['category']; ?></td>
                    <td><?php echo $row['DocumentsCount']; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Total Approvals -->
    <h2>Total Approvals:</h2>
    <p><?php echo $totalApprovalsResult->fetch_assoc()['TotalApprovals']; ?></p>

    <!-- Total Rejects -->
    <h2>Total Rejects:</h2>
    <p><?php echo $totalRejectsResult->fetch_assoc()['TotalRejects']; ?></p>
</div>

<!-- Bootstrap Scripts (jQuery and Bootstrap JS) -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>
</html>

<?php
session_start();

require_once '../includes/config.php';

if (!isset($_SESSION['userID'])) {
    header("Location: ../auth/login.php");
    exit();
}

$fileID = $_GET['fileID'];

date_default_timezone_set('Asia/Manila');

$sql = "SELECT * FROM documents WHERE file_id = $fileID";
$result = mysqli_query($mysqli, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $document = mysqli_fetch_assoc($result);
    $currentTitle = $document['title'];
    $currentCategory = $document['category'];
    $currentDescription = $document['description'];
    $currentTags = $document['tags'];
} else {
    header("Location: ../pages/index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $newTitle = $_POST["title"];
    $newCategory = $_POST["category"];
    $newDescription = $_POST["description"];
    $newTags = $_POST["tags"];

    $formattedTags = formatTags($newTags);

    $checkDuplicateTitleSQL = "SELECT COUNT(*) FROM documents WHERE title = '$newTitle' AND file_id != $fileID";
    $duplicateTitleResult = mysqli_query($mysqli, $checkDuplicateTitleSQL);
    $duplicateTitleCount = mysqli_fetch_row($duplicateTitleResult)[0];
    if ($duplicateTitleCount > 0) {
        echo "Error: Title '$newTitle' is already in the system. <a href='javascript:history.back()'>Go back</a>";
        exit();
    }

    $allowedCategories = array(
        'pdf' => 'PDF',
        'zip' => 'Compressed Folder',
        'rar' => 'Compressed Folder',
        'jpg' => 'Other',
        'png' => 'Other',
        'mp4' => 'Other',
        'mp3' => 'Other',
        'gif' => 'Other',
    );

    $fileExtension = strtolower(pathinfo($document['file'], PATHINFO_EXTENSION));
    if (!isset($allowedCategories[$fileExtension]) || $newCategory !== $allowedCategories[$fileExtension]) {
        echo "Error: Wrong category for file. Please choose the correct category. <a href='javascript:history.back()'>Go back</a>";
        exit();
    }

    $sql = "UPDATE documents 
            SET title = '$newTitle', category = '$newCategory', description = '$newDescription', tags = '$formattedTags'
            WHERE file_id = $fileID";

    if (mysqli_query($mysqli, $sql)) {
        // Log edit history
        $user_id = $_SESSION['userID'];
        $date_of_modification = date('Y-m-d H:i:s');
        $status = "Updated";
        $logHistorySQL = "INSERT INTO edit_history (file_id, user_id, date_of_modification, status) 
                          VALUES ($fileID, $user_id, '$date_of_modification', '$status')";
        mysqli_query($mysqli, $logHistorySQL);

        header("Location: ../pages/index.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($mysqli) . " <a href='javascript:history.back()'>Go back</a>";
    }
}

function formatTags($tags) {
    $tagArray = explode(',', $tags);
    $formattedTags = array_map(function($tag) {
        $formattedTag = trim($tag);
        if (substr($formattedTag, 0, 1) !== '#') {
            $formattedTag = '#' . $formattedTag;
        }
        return $formattedTag;
    }, $tagArray);

    return implode(', ', $formattedTags);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit File</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../js/bootstrap.bundle.min.js">
    <style>
        body {
            ;
        }

        form {
            max-width: 600px;
            margin: 0 auto;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
        }

        textarea {
            resize: vertical;
        }

        /* Optional: Adjust styling for better form element display */
        .form-control {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <?php include '../includes/navbar.php'; ?>
    <div class="container">
        <h1>EDIT FILE</h1>
        <form action="../actions/edit_file.php?fileID=<?php echo $fileID; ?>" method="POST">
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" placeholder="Title" required value="<?php echo $currentTitle; ?>">
            </div>
            <div class="mb-3">
                <label for="category" class="form-label">Category</label>
                <select class="form-select" id="category" name="category" required>
                    <option value="PDF" <?php echo ($currentCategory === 'PDF') ? 'selected' : ''; ?>>PDF</option>
                    <option value="Compressed Folder" <?php echo ($currentCategory === 'Compressed Folder') ? 'selected' : ''; ?>>Compressed Folder</option>
                    <option value="Other" <?php echo ($currentCategory === 'Other') ? 'selected' : ''; ?>>Other</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" placeholder="Description"><?php echo $currentDescription; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="tags" class="form-label">Tags (comma-separated)</label>
                <input type="text" class="form-control" id="tags" name="tags" placeholder="Tags (comma-separated)" value="<?php echo $currentTags; ?>">
            </div>
            <button type="submit" class="btn btn-dark">Update</button>
            <a href="../pages/index.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>
<?php
session_start();

require_once '../includes/config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $errorMessages = array();
    $uploadSuccessful = true;
    $duplicateFound = false;

    $maxFiles = 1;
    if (count($_FILES['file']['name']) > $maxFiles) {
        $uploadSuccessful = false;
        $errorMessages[] = "You can only upload up to $maxFiles files. Please select only one file.";
    }

    if (!empty($_FILES['file']['name'][0]) && $uploadSuccessful) {
        // Arrays to store details for duplicate removal, if needed
        $filesToRemove = array();
        $titlesToRemove = array();

        foreach ($_FILES['file']['name'] as $key => $filename) {
            $title = $_POST["title"][$key];
            $category = $_POST["category"][$key];
            $description = $_POST["description"][$key];
            $tags = $_POST["tags"][$key];

            // Check for duplicate title
            if (in_array($title, $titlesToRemove)) {
                $uploadSuccessful = false;
                $duplicateFound = true;
                $errorMessages[] = "Error: Title '$title' is already in the system.";
                continue;
            }

            $size = $_FILES['file']['size'][$key] / (1024 * 1024);
            $maxFileSize = 5;
            if ($size > $maxFileSize) {
                $uploadSuccessful = false;
                $errorMessages[] = "File '$filename' exceeds the maximum allowed size of $maxFileSize MB.";
                continue;
            }

            $allowedFileTypes = array('pdf', 'zip', 'rar', 'jpg', 'png', 'mp4', 'mp3', 'gif', 'jpeg');
            $fileExtension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            if (!in_array($fileExtension, $allowedFileTypes)) {
                $uploadSuccessful = false;
                $errorMessages[] = "File '$filename' has an invalid file type. File/s must be: pdf, zip, rar, jpg, png, mp4, mp3, gif, jpeg";
                continue;
            }

            // Check for duplicate title in the database
            $checkDuplicateTitleSQL = "SELECT COUNT(*) FROM documents WHERE title = '$title'";
            $duplicateTitleResult = mysqli_query($mysqli, $checkDuplicateTitleSQL);
            $duplicateTitleCount = mysqli_fetch_row($duplicateTitleResult)[0];
            if ($duplicateTitleCount > 0) {
                $uploadSuccessful = false;
                $duplicateFound = true;
                $errorMessages[] = "Error: Title '$title' is already in the system.";
                continue;
            }

            $targetFileInDB = "uploadedDocs/" . basename($_FILES['file']['name'][$key]);

            // Check for duplicate file
            $checkDuplicateFileSQL = "SELECT COUNT(*) FROM documents WHERE file = '$targetFileInDB'";
            $duplicateFileResult = mysqli_query($mysqli, $checkDuplicateFileSQL);
            $duplicateFileCount = mysqli_fetch_row($duplicateFileResult)[0];
            if ($duplicateFileCount > 0) {
                $uploadSuccessful = false;
                $duplicateFound = true;
                // Store details for potential removal
                $filesToRemove[] = $targetFileInDB;
                $titlesToRemove[] = $title;
                $errorMessages[] = "Error: File '$filename' is already in the system.";
                continue;
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

            if (!isset($allowedCategories[$fileExtension]) || $category !== $allowedCategories[$fileExtension]) {
                $uploadSuccessful = false;
                $errorMessages[] = "Wrong category for file '$filename'. Please choose the correct category.";
                continue;
            }

            $formattedTags = formatTags($tags);

            if (isset($_SESSION["userID"])) {
                $author = $_SESSION["userID"];
            } else {
                header("Location: ../auth/login.php");
                exit();
            }

            $isFavorite = isset($_POST["favorite"][$key]) ? 1 : 0;
            $visibility = isset($_POST["visibility"][$key]) ? $_POST["visibility"][$key] : 1;

            $targetDirectory = "../uploadedDocs/";
            $targetFile = $targetDirectory . basename($_FILES['file']['name'][$key]);

            if (move_uploaded_file($_FILES['file']['tmp_name'][$key], $targetFile)) {
                $sqlDocuments = "INSERT INTO documents (file, title, category, description, tags, author, size, favorites, dateCreated, status, visibility)
                        VALUES ('$targetFileInDB', '$title', '$category', '$description', '$formattedTags', $author, $size, $isFavorite, NOW(), 'inactive', $visibility)";

                if (!mysqli_query($mysqli, $sqlDocuments)) {
                    $uploadSuccessful = false;
                    $errorMessages[] = "Error inserting into the documents table: " . mysqli_error($mysqli);
                    echo "Document insert error. File upload failed.";
                    unlink($targetFile);
                    exit();
                }

                $fileID = mysqli_insert_id($mysqli);

                $sqlAuthorization = "INSERT INTO authorization (user_id, file_id, status) VALUES ($author, $fileID, 'pending')";

                if (!mysqli_query($mysqli, $sqlAuthorization)) {
                    $uploadSuccessful = false;
                    $errorMessages[] = "Error inserting into the authorization table: " . mysqli_error($mysqli);
                    $deleteDocumentSQL = "DELETE FROM documents WHERE file_id = $fileID";
                    mysqli_query($mysqli, $deleteDocumentSQL);
                    unlink($targetFile);
                    echo "Authorization error. File upload failed.";
                    exit();
                }
            } else {
                $uploadSuccessful = false;
                $errorMessages[] = "Sorry, there was an error uploading your file.";
                echo "File upload error. File upload failed.";
            }
        }

        if ($duplicateFound) {
            echo '<h1>Error in File Upload:</h1>';
            echo '<p>Please fix the following issues:</p>';
            echo '<ul>';
            foreach ($errorMessages as $errorMessage) {
                echo '<li>' . $errorMessage . '</li>';
            }
            echo '</ul>';
            echo '<p><a href="' . $_SERVER['HTTP_REFERER'] . '">Go back</a></p>';
            exit();
        }

        if (!$uploadSuccessful) {
            echo '<h1>Error in File Upload:</h1>';
            echo '<p>Please fix the following issues:</p>';
            echo '<ul>';
            foreach ($errorMessages as $errorMessage) {
                echo '<li>' . $errorMessage . '</li>';
            }
            echo '</ul>';
            echo '<p><a href="' . $_SERVER['HTTP_REFERER'] . '">Go back</a></p>';
            exit();
        }

        header("Location: ../admin/needs_approval.php");
        exit();
    } else {
        echo "Please select one file to upload.";
    }
}

function formatTags($tags)
{
    $tagArray = explode(',', $tags);
    $formattedTags = array_map(function ($tag) {
        return '#' . trim($tag);
    }, $tagArray);

    return implode(', ', $formattedTags);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        
    <style>
        .file-input-container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .file-input {
            display: flex;
            flex-direction: row;
            align-items: flex-start; /* Align items to the start horizontally */
        }

        .file-input input[type="file"] {
            display: none;
        }

        .file-input label {
            display: inline-block;
            background-color: #000; /* Black background */
            color: #fff; /* White text */
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 10px;
            width: 150px; /* Fixed width for the label */
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        .file-input i {
            margin-right: 5px;
        }

        #thumbnail {
            width: 100px;
            height: 100px;
            object-fit: cover;
            margin-top: 10px;
            margin-right: 10px; /* Add right margin to separate thumbnail and label */
            display: none;
        }

        #file-details {
            align-items: flex-start; /* Align the file details to the start horizontally */
        }

        #file-details ul {
            list-style: none;
            padding: 0;
        }

        #file-details li {
            margin-bottom: 10px;
        }

        .form-control {
            width: 200%;
        }

        .btn-upload {
        background-color: #000; /* Black background */
        color: #fff; /* White text */
        border: none;
        padding: 10px 15px;
        border-radius: 5px;
        cursor: pointer;
        margin-top: 10px;
        transition: background-color 0.3s, color 0.3s; /* Add transition for smooth effect */
    }

    .btn-upload:hover {
        background-color: #000; /* White background on hover */
        color: #fff; /* Black text on hover */
    }


        .card-body {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Box shadow */
        }
    </style>
</head>
<body>
<?php include '../includes/navbar.php'; ?>
<div class="container mt-5">
<div class="card">
    <div class="card-body">
    <h1><center>UPLOAD FILE</center></h1>
    <form action="upload.php" method="POST" enctype="multipart/form-data">
        <div class="file-input-container">
            <div id="fileInputs" class="file-input">
                <div class="left-container">
                <img id="thumbnail" src="../img/document.jpg" alt="Thumbnail">
                                <input type="file" name="file[]" id="fileInput" multiple required>
                                <label for="fileInput">
                                    <i class="fas fa-file-upload"></i> Choose Files
                                </label>
                            </div>
                            <div id="file-details" class="details-container">
                                <!-- Your title, category, description, tags, and public/private button go here -->
                                <ul>
                                    <li>Title (Required)
                                        <input type="text" name="title[]" class="form-control" rows="2" required>
                                    </li>
                                    <li>Description (Required)
                                        <textarea name="description[]" class="form-control" rows="4" required></textarea>
                                    </li>
                                    <li>Category
                                        <select name="category[]" class="form-control" required>
                                            <option value="PDF">PDF</option>
                                            <option value="Compressed Folder">Compressed Folder</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </li>
                                    <li>Tags
                                        <input type="text" name="tags[]" placeholder="Tags (comma-separated)" class="form-control" required>
                                    </li>
                                    <li>Visibility
                                        <select name="visibility[]" class="form-control">
                                            <option value="1">Public</option>
                                            <option value="0">Private</option>
                                        </select>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    <input type="submit" value="Upload" class="btn btn-primary btn-upload">
        <div id="fileValidation" class="mt-3">
            <ul>
                <li>File must be 5 MB and lower</li>
                <li>Maximum of 1 file to upload</li>
                <li>File and Category must match</li>
                <li>File type must be: pdf, zip, rar, jpg, png, mp4, mp3, gif, jpeg</li>
                <li>No Duplicate files or titles</li>
            </ul>
        </div>
    </form>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const fileInput = document.getElementById("fileInput");
                        const label = document.querySelector("label[for='fileInput']");
                        const thumbnail = document.getElementById("thumbnail");
                        const fileDetails = document.getElementById("file-details");
                        const icon = label.querySelector("i");

                        fileInput.addEventListener("change", function () {
                            if (fileInput.files.length > 0) {
                                const file = fileInput.files[0];

                                // Displaying the default file icon
                                icon.className = 'fas fa-file';

                                // Displaying your own thumbnail
                                thumbnail.src = '../img/document.jpg';
                                thumbnail.style.display = 'block';

                                label.textContent = ' ' + file.name;
                            } else {
                                icon.className = 'fas fa-file-upload';
                                label.textContent = ' Choose Files';
                                thumbnail.style.display = 'none';
                            }
                        });           
            const fileInputsContainer = document.getElementById("fileInputs");
            const addMoreButton = document.getElementById("addMore");
            let addMoreButtonClickCount = 0;

            addMoreButton.addEventListener("click", function () {
                if (addMoreButtonClickCount < 4) {  
                    addMoreButtonClickCount++;

                    const newFileInputDiv = document.createElement("div");
                    newFileInputDiv.className = "file-input";

                    const newFileInput = document.createElement("input");
                    newFileInput.type = "file";
                    newFileInput.name = "file[]";

                    const newTitleInput = document.createElement("input");
                    newTitleInput.type = "text";
                    newTitleInput.name = "title[]";
                    newTitleInput.placeholder = "Title";
                    newTitleInput.required = true;

                    const newCategorySelect = document.createElement("select");
                    newCategorySelect.name = "category[]";
                    const pdfOption = document.createElement("option");
                    pdfOption.value = "PDF";
                    pdfOption.textContent = "PDF";
                    const compressedOption = document.createElement("option");
                    compressedOption.value = "Compressed Folder";
                    compressedOption.textContent = "Compressed Folder";
                    const otherOption = document.createElement("option");
                    otherOption.value = "Other";
                    otherOption.textContent = "Other";
                    newCategorySelect.appendChild(pdfOption);
                    newCategorySelect.appendChild(compressedOption);
                    newCategorySelect.appendChild(otherOption);

                    const newDescriptionTextarea = document.createElement("textarea");
                    newDescriptionTextarea.name = "description[]";
                    newDescriptionTextarea.placeholder = "Description";

                    const newTagsInput = document.createElement("input");
                    newTagsInput.type = "text";
                    newTagsInput.name = "tags[]";
                    newTagsInput.placeholder = "Tags (comma-separated)";

                    const newVisibilitySelect = document.createElement("select");
                    newVisibilitySelect.name = "visibility[]";
                    const publicOption = document.createElement("option");
                    publicOption.value = "1";
                    publicOption.textContent = "Public";
                    const privateOption = document.createElement("option");
                    privateOption.value = "0";
                    privateOption.textContent = "Private";
                    newVisibilitySelect.appendChild(publicOption);
                    newVisibilitySelect.appendChild(privateOption);

                    const deleteRowButton = document.createElement("button");
                    deleteRowButton.type = "button";
                    deleteRowButton.className = "deleteRow";
                    deleteRowButton.textContent = "Delete Row";
                    deleteRowButton.onclick = function () {
                        deleteFileRow(this);
                    };

                    newFileInputDiv.appendChild(newFileInput);
                    newFileInputDiv.appendChild(newTitleInput);
                    newFileInputDiv.appendChild(newCategorySelect);
                    newFileInputDiv.appendChild(newDescriptionTextarea);
                    newFileInputDiv.appendChild(newTagsInput);
                    newFileInputDiv.appendChild(newVisibilitySelect);
                    newFileInputDiv.appendChild(deleteRowButton);

                    fileInputsContainer.appendChild(newFileInputDiv);

                    if (addMoreButtonClickCount === 4) {
                        addMoreButton.style.display = "none";
                    }
                } else {
                    alert("You cannot add more rows.");
                }
            });

            function deleteFileRow(button) {
                const fileInputDiv = button.parentElement;
                const fileInputsContainer = document.getElementById("fileInputs");

                if (fileInputsContainer.children.length > 1) {
                    fileInputsContainer.removeChild(fileInputDiv);
                    addMoreButtonClickCount--;
                    if (addMoreButtonClickCount < 4) {
                        addMoreButton.style.display = "block";  
                    }
                } else {
                    alert("You cannot delete the last row.");
                }
            }
        });
    </script>
</body>
</html>

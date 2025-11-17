<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../js/bootstrap.bundle.min.js">
    <link rel="stylesheet" href="../js/bootstrap.bundle.js">


    <title>Hello Admin!</title>
    <style>
        body {
            padding-top: 56px;
        }

        #header {
            background-color: #f8f9fa; /* Light gray background color */
            color: #212529; /* Black font color */
            padding: 10px 0;
        }

        #navigation ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        #navigation ul li {
            display: inline-block;
            margin-right: 15px;
        }

        #body {
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 60vh;
        }

        .card {
            width: 18rem;
            margin: 5px;
        }

        /* Custom styles for the "Go" button */
        .btn-go {
            background-color: #000; /* Black background color */
            color: #fff !important; /* White font color */
            border: none;
        }

        .btn-go:hover {
            background-color: #555; /* Darker shade of gray on hover */
        }
        h1 {
    margin-top: 20px; /* Adjust the value according to your preference */
    margin-bottom: 20px; /* Optional: Add bottom margin for spacing */
    text-align: center;
}
.navbar {
    margin-bottom: 20px; /* Adjust the value according to your preference */
}
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
    <a class="navbar-brand" href="admin_dashboard.php">DND Libraries</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="logout.php"><strong>Logout</strong></a>
            </li>
        </ul>
    </div>
</nav>
<h1><center>WELCOME ADMIN!</center></h1>
<div id="body">
    

    <div class="card-columns">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">View Users</h5>
                <p class="card-text">View and manage user information.</p>
                <a href="view_users.php" class="btn btn-go">Go</a>
            </div>
        </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">View Documents</h5>
                    <p class="card-text">Browse and handle documents.</p>
                    <a href="view_documents.php" class="btn btn-go">Go</a>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">File Modification History</h5>
                    <p class="card-text">Browse and handle modified files.</p>
                    <a href="history.php" class="btn btn-go">Go</a>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">File Authorization</h5>
                    <p class="card-text">Accept or reject upload requests.</p>
                    <a href="authorization.php" class="btn btn-go">Go</a>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Analytics</h5>
                    <p class="card-text">DND Libraries analytics</p>
                    <a href="analytics.php" class="btn btn-go">Go</a>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Notification</h5>
                    <p class="card-text">Notification history.</p>
                    <a href="notification_history.php" class="btn btn-go">Go</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</body>
</html>

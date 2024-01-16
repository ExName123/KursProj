<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Style/styleAuthorization.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <title>Registration</title>
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            background-color: #f8f9fa;
            max-width: 25%;
            margin-top: 50px;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 10px;
            background-color: #fff;
        }

        #error-message {
            color: red;
            display: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <form action="registration.php" method="post">
            <!-- Display the error message if it exists -->
            <div id="error-message"><?php echo $error_message; ?></div>

            <div class="mb-3">
                <label for="username" class="form-label">Username:</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Name:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <button type="submit" class="btn btn-primary" name="register">Register</button>
            <a href="login.html" class="ms-2 btn btn-link">Login</a>
        </form>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // JavaScript to show the error message if it exists
            var errorMessage = "<?php echo $error_message; ?>";
            if (errorMessage !== "") {
                document.getElementById('error-message').style.display = 'block';
                console.log("exc");
            }
        });
    </script>
</body>

</html>

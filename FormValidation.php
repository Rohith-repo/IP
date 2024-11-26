<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Validation</title>
    <script>
        function validateForm() {
            const name = document.getElementById("name").value.trim();
            const email = document.getElementById("email").value.trim();
            const password = document.getElementById("password").value;

            if (name === "") {
                alert("Name must be filled out");
                return false;
            }
            if (email === "") {
                alert("Email must be filled out");
                return false;
            }
            if (!email.match(/^\S+@\S+\.\S+$/)) {
                alert("Invalid email format");
                return false;
            }
            if (password.length < 6) {
                alert("Password must be at least 6 characters long");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <?php
    $name = $email = $password = "";
    $errors = [];

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $name = htmlspecialchars(trim($_POST["name"]));
        $email = htmlspecialchars(trim($_POST["email"]));
        $password = trim($_POST["password"]);

        // Validate name
        if (empty($name)) {
            $errors[] = "Name is required.";
        }

        // Validate email
        if (empty($email)) {
            $errors[] = "Email is required.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        }

        // Validate password
        if (strlen($password) < 6) {
            $errors[] = "Password must be at least 6 characters long.";
        }

        // If no errors, process the form
        if (empty($errors)) {
            echo "<p style='color:green;'>Form submitted successfully!</p>";
            // Additional processing (e.g., save to a database) can be done here
        }
    }
    ?>
    <form id="myForm" method="post" action="" onsubmit="return validateForm()">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo $name; ?>"><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo $email; ?>"><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password"><br><br>

        <input type="submit" value="Submit">
    </form>

    <?php
    if (!empty($errors)) {
        echo "<div style='color:red;'>";
        foreach ($errors as $error) {
            echo "<p>$error</p>";
        }
        echo "</div>";
    }
    ?>
</body>
</html>

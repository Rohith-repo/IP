<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "employee_system");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission (Add or Update)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'] ?? null;
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $address = $conn->real_escape_string($_POST['address']);
    $salary = $conn->real_escape_string($_POST['salary']);

    if ($id) {
        // Update employee record
        $sql = "UPDATE employees SET name='$name', email='$email', phone='$phone', address='$address', salary='$salary' WHERE id=$id";
    } else {
        // Add new employee
        $sql = "INSERT INTO employees (name, email, phone, address, salary) VALUES ('$name', '$email', '$phone', '$address', '$salary')";
    }

    if ($conn->query($sql)) {
        header("Location: employee_system.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

// Handle deletion
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM employees WHERE id=$id");
    header("Location: employee_system.php");
    exit();
}

// Fetch employees for listing
$employees = $conn->query("SELECT * FROM employees");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Information System</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 10px; text-align: left; }
        th { background-color: #f4f4f4; }
        form { margin-bottom: 20px; }
        input, textarea { width: 100%; padding: 8px; margin: 5px 0; }
        button { padding: 10px 15px; background-color: #28a745; color: white; border: none; cursor: pointer; }
        button.delete { background-color: #dc3545; }
    </style>
</head>
<body>

<h1>Employee Information System</h1>

<!-- Add / Edit Form -->
<?php
$edit = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $result = $conn->query("SELECT * FROM employees WHERE id=$id");
    $edit = $result->fetch_assoc();
}
?>
<form method="post">
    <input type="hidden" name="id" value="<?php echo $edit['id'] ?? ''; ?>">
    <label>Name:</label>
    <input type="text" name="name" required value="<?php echo $edit['name'] ?? ''; ?>">

    <label>Email:</label>
    <input type="email" name="email" required value="<?php echo $edit['email'] ?? ''; ?>">

    <label>Phone:</label>
    <input type="text" name="phone" value="<?php echo $edit['phone'] ?? ''; ?>">

    <label>Address:</label>
    <textarea name="address"><?php echo $edit['address'] ?? ''; ?></textarea>

    <label>Salary:</label>
    <input type="number" name="salary" required value="<?php echo $edit['salary'] ?? ''; ?>" step="0.01">

    <button type="submit"><?php echo $edit ? "Update" : "Add"; ?> Employee</button>
</form>

<!-- Employee List -->
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Address</th>
            <th>Salary</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $employees->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['phone']; ?></td>
                <td><?php echo $row['address']; ?></td>
                <td><?php echo $row['salary']; ?></td>
                <td>
                    <a href="?edit=<?php echo $row['id']; ?>">Edit</a>
                    <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?')" class="delete">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

</body>
</html>

<!--Create Database in mYSQL and link to this file-->
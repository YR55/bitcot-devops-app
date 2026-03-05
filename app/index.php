<?php
$host = "bitcot-db.c6fmciaoqc63.us-east-1.rds.amazonaws.com";
$user = "admin";
$pass = "Bitcot55";
$db   = "bitcotdb";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

/* ---------- DELETE LOGIC ---------- */
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM users WHERE id=$id");

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

/* ---------- INSERT LOGIC ---------- */
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];

    $sql = "INSERT INTO users (name, email) VALUES ('$name', '$email')";
    $conn->query($sql);

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Bitcot DevOps App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card p-4 shadow">
        <h3 class="mb-4">Bitcot DevOps Test App - AUTO DEPLOY</h3>

        <form method="POST">
            <input type="text" name="name" class="form-control mb-3" placeholder="Enter Name" required>
            <input type="email" name="email" class="form-control mb-3" placeholder="Enter Email" required>
            <button class="btn btn-primary">Submit</button>
        </form>
    </div>

    <div class="card mt-4 p-4 shadow">
        <h4>Stored Users</h4>
        <table class="table table-bordered">
            <tr class="table-dark">
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Created</th>
                <th>Action</th>
            </tr>

            <?php
            $result = $conn->query("SELECT * FROM users ORDER BY id DESC");

            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['created_at']}</td>
                        <td>
                            <a href='?delete={$row['id']}' 
                               class='btn btn-danger btn-sm'
                               onclick=\"return confirm('Are you sure you want to delete this user?');\">
                               Delete
                            </a>
                        </td>
                      </tr>";
            }
            ?>
        </table>
    </div>
</div>

</body>
</html>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>

    <?php
    session_start(); // Start the session

    // Check if the user is logged in
    if (!isset($_SESSION['login_user'])) {
        header("Location: login.php"); // Redirect to login page if session variable is not set
        exit();
    }

    // Display user profile
    // echo "<h1> Welcome to your profile,</h1> " . $_SESSION['login_user'];

    // Include database connection file
    include 'regdata.php';

    // Retrieve user details from registerform_table
    $loginemail = $_SESSION['login_user'];
    $stmt = $conn->prepare("SELECT id, name, email, country, date_of_birth, image FROM registerform_table WHERE email = ?");
    $stmt->bind_param("s", $loginemail);

    if (!$stmt->execute()) {
        die("Error executing query: " . $stmt->error);
    }

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $userid = $row['id'];
        $username = $row['name'];
        $useremail = $row['email'];
        $usercountry = $row['country'];
        $userdob = $row['date_of_birth'];
        $userimage = $row['image'];
        // echo "User details found.";
    } else {
        // Handle the case where user details are not found
        echo "User details not found.";
        exit();
    }

    // Close the statement and result set
    $stmt->close();
    $result->close();

    ?>
    <div class="container mt-5">
        <h2 class="text-uppercase h1 "><?php echo $username; ?></h2>
        <table class="table">
            <tr>
                <th>ID</th>
                <td><?php echo $userid; ?></td>
            </tr>
            <tr>
                <th>Name</th>
                <td><?php echo $username; ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?php echo $useremail; ?></td>
            </tr>
            <tr>
                <th>Country</th>
                <td><?php echo $usercountry; ?></td>
            </tr>
            <tr>
                <th>Date of Birth</th>
                <td><?php echo $userdob; ?></td>
            </tr>
            <tr>
                <th>Image</th>
                <td><img src="<?php echo $userimage; ?>" alt="User Image" style="max-width: 100px;"></td>
            </tr>
        </table>
      
             <a href="logout.php"><button type="submit" class="btn btn-danger">Logout</button></a>
            <a href="resetpswd.php"><button type="submit" class="btn btn-warning">Reset Password</button></a>
           
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>
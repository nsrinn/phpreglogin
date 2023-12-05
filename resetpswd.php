<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        .rounded-t-5 {
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
        }

        @media (min-width: 992px) {
            .rounded-tr-lg-0 {
                border-top-right-radius: 0;
            }

            .rounded-bl-lg-5 {
                border-bottom-left-radius: 0.5rem;
            }
        }

        /* Custom styles for centering and adjusting width */
        .custom-container {
            max-width: 50%;
            /* Set the maximum width to half of the current width */
            margin-left: 25% !important;
            /* Center the container horizontally */
        }

        .custom-card {
            width: 100%;
            /* Set the card width to 100% to match the container width */
        }
    </style>
</head>

<body>

    <!-- Section: Design Block -->
    <section class="text-center text-lg-start container p-5 m-3 custom-container">
        <div class="row">
            <div class="card mb-3 custom-card">
                <div class="row g-0 d-flex align-items-center">
                    <div class="col-md-12">
                        <div class="card-body py-5 px-md-5">
                            <form action="" method="post" id="ResetForm" enctype="multipart/form-data">
                                <?php
                                error_reporting(E_ALL);
                                ini_set('display_errors', 1);
                                session_start();
                                include 'regdata.php';

                                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email']) && isset($_POST['currentpassword']) && isset($_POST['newpassword'])) {
                                    $resetemail = test_input($_POST['email']);
                                    $currentpassword = test_input($_POST['currentpassword']);
                                    $newpassword = test_input($_POST['newpassword']);

                                    if (empty($resetemail) || empty($currentpassword) || empty($newpassword)) {
                                        echo "<div class='alert alert-danger'>All fields are required.</div>";
                                    } else {
                                        // Query the registerform_table to check if the email exists
                                        $stmt = $conn->prepare("SELECT id, name, email, password FROM registerform_table WHERE email = ?");
                                        $stmt->bind_param("s", $resetemail);

                                        if (!$stmt->execute()) {
                                            die("Error executing query: " . $stmt->error);
                                        }

                                        $result = $stmt->get_result();

                                        if ($result->num_rows > 0) {
                                            $row = $result->fetch_assoc();
                                            $hashedpassword = $row['password'];
                                    
                                            // Check if the provided password matches the hashed password
                                            if (password_verify($currentpassword, $hashedpassword)) {
                                                // update the password in the database
                                                $newHashedPassword = password_hash($newpassword, PASSWORD_DEFAULT);
                                                $updateStmt = $conn->prepare("UPDATE registerform_table SET password = ? WHERE email = ?");
                                                $updateStmt->bind_param("ss", $newHashedPassword, $resetemail);
                                                $updateStmt->execute();
                                                echo "<div class='alert alert-danger'>Password Changed</div>";
                                                // Redirect to profile.php or perform any other actions for successful login
                                                header("Location: login.php");
                                                exit();
                                            } else {
                                                echo "<div class='alert alert-danger'>Incorrect Current Password</div>";
                                            }
                                        } else {
                                            echo "<div class='alert alert-danger'>Invalid email or password </div>";
                                        }
                                    

                                        // Close the statement and result set
                                        $stmt->close();
                                        $result->close();
                                    }
                                }

                                function test_input($data)
                                {
                                    $data = trim($data);
                                    $data = stripslashes($data);
                                    $data = htmlspecialchars($data);
                                    return $data;
                                }
                                ?>

                                <div class="form-outline mb-4">
                                    <h1 class="text-center">Reset Password</h1>
                                </div>
                                <!-- Email input -->
                                <div class="form-outline mb-4">
                                    <input type="email" id="foremail" class="form-control" name="email" />
                                    <label class="form-label" for="foremail">Email address</label>
                                </div>

                                <!-- Password input -->
                                <div class="form-outline mb-4">
                                    <input type="password" id="forpassword" class="form-control" name="currentpassword" />
                                    <label class="form-label" for="forpassword"> Current Password</label>
                                </div>

                                <!-- Confirm Password input -->
                                <div class="form-outline mb-4">
                                    <input type="password" id="forpassword" class="form-control" name="newpassword" />
                                    <label class="form-label" for="forpassword">New Password</label>
                                </div>

                                <!-- Submit button -->
                                <button type="submit" class="btn btn-warning btn-block mb-4 px-5">Change Password</button>

                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Section: Design Block -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>
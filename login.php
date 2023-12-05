<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
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
    </style>
</head>

<body>

    <!-- Section: Design Block -->
    <section class="text-center text-lg-start container p-5 m-5">
        <div class="row">
            <div class="card mb-3">
                <div class="row g-0 d-flex align-items-center">
                    <div class="col-md-6 d-none d-lg-flex">
                        <img src="https://www.suburbandiagnostics.com/Content/assets/images/login-bg-img@2x.png" alt="Trendy Pants and Shoes" class="w-100 rounded-t-5 rounded-tr-lg-0 rounded-bl-lg-5 p-5" />
                    </div>
                    <div class="col-md-6 ">
                        <div class="card-body py-5 px-md-5">
                            <?php
                            error_reporting(E_ALL);
                            ini_set('display_errors', 1);
                            session_start();
                            include 'regdata.php';

                            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['loginemail']) && isset($_POST['loginpassword'])) {
                                $loginemail = test_input($_POST['loginemail']);
                                $loginpassword = test_input($_POST['loginpassword']);

                                if (empty($loginemail) || empty($loginpassword)) {
                                    echo "<div class='alert alert-danger'>Email and password are required.</div>";
                                } else {
                                    // Query the registerform_table to check if the email exists
                                    $stmt = $conn->prepare("SELECT id, name, email, password FROM registerform_table WHERE email = ?");
                                    $stmt->bind_param("s", $loginemail);

                                    if (!$stmt->execute()) {
                                        die("Error executing query: " . $stmt->error);
                                    }

                                    $result = $stmt->get_result();

                                    if ($result->num_rows > 0) {
                                        $row = $result->fetch_assoc();
                                        $hashedpassword = $row['password'];

                                        // Check if the provided password matches the hashed password
                                        if (password_verify($loginpassword, $hashedpassword)) {
                                            // Insert user data into the login table
                                            // $stmtInsert = $conn->prepare("INSERT INTO login (email, password) VALUES (?, ?)");
                                            // $hashedLoginPassword = password_hash($loginpassword, PASSWORD_DEFAULT);

                                            // if (!$stmtInsert) {
                                            //     die("Error in insert statement: " . $conn->error);
                                            // }

                                            // $stmtInsert->bind_param("ss", $loginemail, $hashedLoginPassword);

                                            // if (!$stmtInsert->execute()) {
                                            //     die("Error executing insert statement: " . $stmtInsert->error);
                                            // }

                                            // // Store user email in session
                                            $_SESSION['login_user'] = $loginemail;

                                            // Redirect to profile.php or perform any other actions for successful login
                                            echo "Redirecting to profile.php";
                                            echo $_SESSION['login_user'];
                                            header("Location: profile.php");
                                            exit();
                                        } else {
                                            echo "<div class='alert alert-danger'>Invalid email or password </div>";
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


                            <form action="" method="post" id="loginForm" enctype="multipart/form-data">

                                <div class="form-outline mb-4">
                                    <h1 class="text-center">Log In</h1>
                                </div>
                                <!-- Email input -->
                                <div class="form-outline mb-4">
                                    <input type="email" id="foremail" class="form-control" name="loginemail" />
                                    <label class="form-label" for="foremail">Email address</label>
                                </div>

                                <!-- Password input -->
                                <div class="form-outline mb-4">
                                    <input type="password" id="forpassword" class="form-control" name="loginpassword" />
                                    <label class="form-label" for="forpassword">Password</label>
                                </div>

                                <!-- 2 column grid layout for inline styling -->
                                <div class="row mb-4">
                                    <div class="col d-flex justify-content-center">
                                        <!-- Checkbox -->
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="rememberme" name="loginremember" checked />
                                            <label class="form-check-label" for="rememberme"> Remember me </label>
                                        </div>
                                    </div>

                                    <div class="col">
                                        <!-- Simple link -->
                                        <a href="forgetpswd.php">Forgot password?</a>
                                    </div>
                                </div>

                                <!-- Submit button -->
                                <button type="submit" class="btn btn-primary btn-block mb-4 px-5">Sign in</button>

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
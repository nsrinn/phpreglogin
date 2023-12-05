<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Registration</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <style>
        .form-row {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .error {
            color: red;
        }

        .message-box {
            margin: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            background-color: #d4edda;
            color: #155724;
        }
    </style>
    <title>Hello, world!</title>
</head>

<body>
    <?php
    include 'regdata.php';

    $nameErr = $emailErr = $countryErr = $dateErr = $imageErr = $passwordErr = $conpasswordErr = "";
    $name = $email = $country = $date = $image = $password = $conpassword = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Validate name
        $name = test_input($_POST["name"]);
        if (empty($name)) {
            $nameErr = "Name is required";
        } else {
            // Check if name contains only letters and whitespace
            if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
                $nameErr = "Only letters and white space allowed";
            }
        }

        // Validate Email
        if (empty($_POST["email"])) {
            $emailErr = "Email is required";
        } else {
            $email = test_input($_POST["email"]);
            // Check if email is valid
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $emailErr = "Invalid email format";
            }
        }

        // Check if email is unique
        $stmt = $conn->prepare("SELECT id FROM registerform_table WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $emailErr = "This email is already registered";
        }

        // Check if username (name) is unique
        $stmt = $conn->prepare("SELECT id FROM registerform_table WHERE name = ?");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $nameErr = "This username is already taken";
        }


        // Validate Country
        if (empty($_POST["country"])) {
            $countryErr = "Country is required";
        } else {
            $country = test_input($_POST["country"]);
        }


        // Validate Date
        if (empty($_POST["date"])) {
            $dateErr = "Date of Birth is required";
        } else {
            $date = test_input($_POST["date"]);
        }

        $password = test_input($_POST["password"]);
        if (empty($password)) {
            $passwordErr = "Password is required";
        } elseif (strlen($password) < 8) {
            $passwordErr = "Password must be at least 8 characters";
        }

        // Validate confirm password
        $conpassword = test_input($_POST["conpassword"]);

        if (empty($conpassword)) {
            $conpasswordErr = "Confirm Password is required";
        } elseif ($password != $conpassword) {
            $conpasswordErr = "Passwords do not match";
        }

        // Validate File
        if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            $imageErr = "File upload failed";
        }


        if (empty($nameErr) && empty($emailErr) && empty($countryErr) && empty($dateErr) && empty($imageErr) && empty($passwordErr) && empty($conpasswordErr)) {

            $allowedImageTypes = ["image/jpeg", "image/png", "image/jpg", "image/svg+xml", "image/gif"];

            // Perform file upload and get the file URL
            $uploadDir = "C:/xampp1/htdocs/php/phpreglogin/files/";
            $uploadFile = $uploadDir . basename($_FILES["file"]["name"]);

            if (in_array($_FILES["file"]["type"], $allowedImageTypes)) {
                // Move the uploaded file to the destination directory
                if (move_uploaded_file($_FILES["file"]["tmp_name"], $uploadFile)) {
                    // File upload successful, save the file URL
                    $fileUrl = "http://localhost/php/phpreglogin/files/" . basename($_FILES["file"]["name"]);

                    // Perform database insertion
                    $password = password_hash(test_input($_POST["password"]), PASSWORD_DEFAULT);
                    $stmt = $conn->prepare("INSERT INTO registerform_table(name, email, country,date_of_birth, password,image) VALUES (?, ?, ?, ?, ?, ?)");

                    // Check for a successful prepare
                    if ($stmt) {
                        $stmt->bind_param("ssssss", $name, $email, $country, $date, $password, $fileUrl);


                        // Execute the prepared statement
                        if ($stmt->execute()) {

                            echo "<div class='message-box'>Data Entered successfully</div>";
                            header("Location: login.php");
                            $name = $email = $country = $date = $password = $conpassword = $image = "";
                        } else {
                            echo "Error: " . $stmt->error;
                        }

                        // Close the statement
                        $stmt->close();
                    } else {
                        echo "Error in the prepared statement: " . $conn->error;
                    }
                } else {
                    echo "File upload failed!";
                }
            } else {
                echo "<div class='message-box'>Only image files (JPEG, PNG, GIF) are allowed.</div>";
            }
        } else {
            //    echo" <div style='display:flex; justify-content:center; align-items:center; width:80%; backgrpound-color:light-green; >";
            echo "<div class='message-box'><span>Validation errors!!.<br> Please check your input.</span></div>";
            // echo"</div>";
        }
        // If there are no errors, process the form


        // Rest of your code...




        // Add similar validation for other fields

        // If there are no errors, process the form
        // if (empty($nameErr) && empty($emailErr) && empty($countryErr) && empty($dateErr) && empty($resumeErr)) {
        //     // Process the form data (e.g., store it in a database)
        //     // Redirect to a success page or display a success message
        //     header("Location: success.php");
        //     exit();
        // }
    }

    // Helper function to sanitize and validate input data
    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    ?>

    <div class="container">
        <h1 class="text-center mt-5">Register</h1>
        <div class="form ">
            <form method="post" enctype="multipart/form-data">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo $name; ?>" placeholder="Your Name">
                        <span class="error"><?php echo $nameErr; ?></span>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" value="<?php echo $email; ?>" placeholder="Your Email">
                        <span class="error"><?php echo $emailErr; ?></span>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="country">Country</label>
                        <input type="text" class="form-control" name="country" value="<?php echo $country; ?>" placeholder="Your Country">
                        <span class="error"><?php echo $countryErr; ?></span>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="date">Date of Birth</label>
                        <input type="date" class="form-control" name="date" value="<?php echo $date; ?>" placeholder="Your Date of Birth">
                        <span class="error"><?php echo $dateErr; ?></span>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" name="password" value="<?php echo $password; ?>" minlength="8" placeholder="Password (8 characters minimum)">
                        <span class="error"><?php echo $passwordErr; ?></span>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="conpassword">Confirm Password</label>
                        <input type="password" class="form-control" id="conpassword" name="conpassword" value="<?php echo $conpassword; ?>" minlength="8" placeholder="Password (8 characters minimum)">
                        <span class="error"><?php echo $conpasswordErr; ?></span>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="image">Image</label>
                        <input type="file" class="form-control" value="<?php echo $file; ?>" name="file" id="file" placeholder="Your Photo">
                        <span class="error"><?php echo $imageErr; ?></span>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <button type="submit" name="submit" value="submit" class="btn btn-primary">Register</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>

</html>
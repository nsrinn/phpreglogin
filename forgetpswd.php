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
            max-width: 50%; /* Set the maximum width to half of the current width */
            margin-left:25% !important; /* Center the container horizontally */
        }

        .custom-card {
            width: 100%; /* Set the card width to 100% to match the container width */
        }
    </style>
</head>

<body>

    <!-- Section: Design Block -->
    <section class="text-center text-lg-start container p-5 m-5 custom-container">
        <div class="row">
            <div class="card mb-3 custom-card">
                <div class="row g-0 d-flex align-items-center">
                    <div class="col-md-12">
                        <div class="card-body py-5 px-md-5">
                            <form action="" method="post" id="loginForm" enctype="multipart/form-data">

                                <div class="form-outline mb-4">
                                    <h1 class="text-center">Forget Password</h1>
                                </div>
                                <!-- Email input -->
                                <div class="form-outline mb-4">
                                    <input type="email" id="foremail" class="form-control" name="loginemail" />
                                    <label class="form-label" for="foremail">Email address</label>
                                </div>

                               

                                <!-- Submit button -->
                                <button type="submit" class="btn btn-success btn-block mb-4 px-5">Reset Password </button>

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

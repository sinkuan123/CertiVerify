<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="css/signup.css">
    <title>Sign Up</title>
</head>

<body>
    <?php
    include "config/database.php";
    if ($_POST) {
        try {
            $user_name = htmlspecialchars(strip_tags($_POST['user_name']));
            $password = htmlspecialchars(strip_tags($_POST['password']));
            $confirm_password = htmlspecialchars(strip_tags($_POST['confirm_password']));
            $phone = htmlspecialchars(strip_tags($_POST['phone']));
            $email = htmlspecialchars(strip_tags($_POST['email']));
            $status = "recipient";
            $error = array();
            if (empty($user_name)) {
                $error[] = "Please fill the username field.";
            }

            if (empty($password) || empty($confirm_password)) {
                $error[] = "Please fill the password and confirm password field.";
            } elseif ($password != $confirm_password) {
                $error[] = "The confirm password didn't match with password.";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            }

            if (empty($phone)) {
                $error[] = "Please fill the phone number field.";
            } else if (!is_numeric($phone)) {
                $error[] = "Phone number can be numbers only.";
            }

            $email_check_query = "SELECT * FROM user WHERE email=:email";
            $email_check_stmt = $con->prepare($email_check_query);
            $email_check_stmt->bindParam(":email", $email);
            $email_check_stmt->execute();
            $email_check = $email_check_stmt->fetch(PDO::FETCH_ASSOC);
            if (empty($email)) {
                $error[] = "Email field is empty.";
            } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error[] = "Invalid Email format.";
            } else if (!empty($email_check)) {
                $error[] = "Email has been taken.";
            }

            if (!empty($error)) {
                echo "<div class='alert alert-danger m-3'>";
                foreach ($error as $displayErrorMessage) {
                    echo $displayErrorMessage . "<br>";
                }
                echo "</div>";
            } else {
                $user_query = "INSERT INTO user SET user_name=?, password=?, phone=?, email=?, status=?";
                $user_stmt = $con->prepare($user_query);
                $user_stmt->bindParam(1, $user_name);
                $user_stmt->bindParam(2, $hashed_password);
                $user_stmt->bindParam(3, $phone);
                $user_stmt->bindParam(4, $email);
                $user_stmt->bindParam(5, $status);
                if ($user_stmt->execute()) {
                    header("Location: login.php?action=register_success");
                } else {
                    echo "<div class='alert alert-danger'>Unable to Sign up. Contact the admin.</div>";
                }
            }
        } catch (PDOException $exception) {
            if ($exception->getCode() == 23000) {
                echo '<div class="alert alert-danger role=alert">' . 'Username has been taken.' . '</div>';
            } else {
                echo '<div class="alert alert-danger role=alert">' . $exception->getMessage() . '</div>';
            }
        }
    }
    ?>
    <div class="border border-3 border-black rounded rounded-3 text-center text-black loginform bg-white bg-opacity-10">
        <h2 class="my-5">Sign Up</h2>
        <form action="" method="post" class="m-5">
            <input type="text" name="user_name" id="user_name" class="form-control bg-transparent border-dark border-0 border-bottom text-black blackholder" placeholder="Username">
            <input type="password" name="password" id="password" class="form-control bg-transparent border-dark border-0 border-bottom text-black my-3 blackholder" placeholder="New Password">
            <input type="password" name="confirm_password" id="confirm_password" class="form-control bg-transparent border-dark border-0 border-bottom text-black my-3 blackholder" placeholder="Confirm Password">
            <input type="text" name="phone" id="phone" class="form-control bg-transparent border-dark border-0 border-bottom text-black blackholder my-3" placeholder="Phone Number">
            <input type="email" name="email" id="email" class="form-control bg-transparent border-dark border-0 border-bottom text-black blackholder my-3" placeholder="Email">

            <button class="btn btn-warning rounded-4 text-white px-5 my-4" name="submit" type="submit">Sign Up</button><br>
        </form>
    </div>
    <div class="logo">
        <img src="img/neuc_logo.png" alt="">
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
</body>

</html>
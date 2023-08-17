<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="css/signup.css">
    <title>Sign Up</title>
    <style>
    </style>
</head>

<body>
    <?php
    include "config/database.php";
    if ($_POST) {
        try {
            $user_name = htmlspecialchars(strip_tags($_POST['user_name']));
            $password = htmlspecialchars(strip_tags($_POST['password']));
            $confirm_password = htmlspecialchars(strip_tags($_POST['confirm_password']));
            $status = "recipient";
            $error = array();

            if ($password != $confirm_password) {
                $error[] = "The confirm password didn't match with password.";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            }

            if (!empty($error)) {
                foreach ($error as $no => $val) {
                    echo $val;
                }
            } else {
                $user_query = "INSERT INTO user SET user_name=?, password=?, status=?";
                $user_stmt = $con->prepare($user_query);
                $user_stmt->bindParam(1, $user_name);
                $user_stmt->bindParam(2, $hashed_password);
                $user_stmt->bindParam(3, $status);
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
            <input type="text" name="user_name" id="user_name" class="form-control bg-transparent border-dark border-0 border-bottom text-black blackholder" placeholder="User Name/Email">
            <input type="password" name="password" id="password" class="form-control bg-transparent border-dark border-0 border-bottom text-black my-3 blackholder" placeholder="New Password">
            <input type="password" name="confirm_password" id="confirm_password" class="form-control bg-transparent border-dark border-0 border-bottom text-black my-3 blackholder" placeholder="Confirm Password">
            <input type="checkbox" id="terms" name="terms" class="mt-3">
            <label for="terms">I agree to the terms and conditions</label><br>
            <button class="btn btn-warning rounded-4 text-white px-5 my-4" name="submit" type="submit">Sign Up</button><br>
        </form>
    </div>
    <div class="logo">
        <img src="img/neuc_logo.png" alt="">
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
</body>

</html>
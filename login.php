<?php
session_start();
if (isset($_SESSION['id'])) {
    header("Location: home.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="css/login.css">
    <title>Login</title>
</head>

<body>
    <?php
    if ($_POST) {
        include "config/database.php";

        $username = $_POST['user_name'];
        $password = htmlspecialchars(strip_tags($_POST['password']));

        if (empty($username)) {
            $user_input_err = "Please enter the User Name field.";
        }
        if (empty($password)) {
            $password_input_err = "Please enter the Password field";
        }

        if (empty($user_input_err) && empty($password_input_err)) {
            try {
                $login_query = "SELECT * FROM user where user_name=?";
                $login_stmt = $con->prepare($login_query);
                $login_stmt->bindParam(1, $username);
                $login_stmt->execute();
                $login = $login_stmt->fetch(PDO::FETCH_ASSOC);
                var_dump($login['status']);
                if ($login) {
                    if (password_verify($password, $login['password'])) {
                        $_SESSION['id'] = $login['id'];
                        $_SESSION['status'] = $login['status'];
                        if ($login['status'] === "admin") {
                            header("Location: admin_dashboard.php");
                        } else {
                            header("Location: home.php");
                        }
                        exit();
                    } else {
                        $password_input_err = "Incorrect Password";
                    }
                } else {
                    $user_input_err = "User Name Not Found";
                }
            } catch (PDOException $exception) {
                $exception->getMessage();
            }
        }
    }
    ?>
    <div class="container">
        <?php
        $action = isset($_GET['action']) ? $_GET['action'] : "";
        if ($action == "register_success") {
            echo "<div class='alert alert-info'>Register Success. Please Log In to continue browsing.</div>";
        }
        if ($action == "warning") {
            echo "<div class='alert alert-danger'>Please Log In to continue browsing.</div>";
        }
        ?>
        <div class="container border border-3 border-white rounded rounded-3 text-center text-white loginform bg-white bg-opacity-10">
            <h2 class="my-5">LOGIN</h2>
            <form action="" method="post" class="m-5">
                <input type="text" name="user_name" id="user_name" class="form-control bg-transparent border-0 border-bottom text-white whiteholder my-3" placeholder="User Name">
                <span class="text-danger"><?php echo isset($user_input_err) ? "<strong>" . $user_input_err . "</strong>" : ""; ?></span>
                <input type="password" name="password" id="password" class="form-control bg-transparent border-0 border-bottom text-white my-3 whiteholder" placeholder="Password">
                <span class="text-danger"><?php echo isset($password_input_err) ? "<strong>" . $password_input_err . "</strong><br>" : ""; ?></span>
                <button class="btn btn-warning rounded-4 text-white px-5 my-4" name="submit" type="submit">Login</button><br>
                <a href="signup.php" class="btn btn-transparent text-white link">Register</a>
            </form>
        </div>
    </div>
    <div class="logo">
        <img src="img/neuc_logo.png" alt="">
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
</body>

</html>
<?php
include "config/database.php";

$user_query = "SELECT * FROM user WHERE id=?";
$user_stmt = $con->prepare($user_query);
$user_stmt->bindParam(1, $_SESSION['id']);
$user_stmt->execute();
$user = $user_stmt->fetch(PDO::FETCH_ASSOC);
?>


<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container d-flex justify-content-between">
        <div>
            <a class="navbar-brand" href="home.php">
                <img src="img/logo.png" alt="">
            </a>
        </div>
        <div class="collapse navbar-collapse justify-content-end align-items-center" id="navbarText">
            <ul class="navbar-nav nav-underline mb-2 mb-lg-0 fs-5">
                <li class="nav-item m-auto mx-3">
                    <a class="nav-link active" aria-current="page" href="home.php">Home</a>
                </li>
                <li class="nav-item m-auto mx-3">
                    <a class="nav-link" href="document_view.php">View Document</a>
                </li>
                <li class="nav-item m-auto mx-3">
                    <a class="nav-link" href="document_upload.php">Upload_Document</a>
                </li>
                <li class="nav-item m-auto mx-3">
                    <a class="nav-link" href="?logout=true">Log Out</a>
                </li>
                <li class="nav-item m-auto mx-3">
                    <?php if (isset($user['image'])) { ?>
                        <a class="nav-link" href="profile.php"><img src="pictures/<?php echo $user['image']; ?>" alt="" width="74px"></a>
                    <?php } else { ?>
                        <a class="nav-link" href="profile.php"><img src="img/profile.png" alt=""></a>
                    <?php } ?>
                </li>
            </ul>
        </div>
    </div>
</nav>
<?php
include "validate_login.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="css/document.css">
    <title>View Document</title>
    <style>
        .bgdocumentview {
            background-color: #D9D9D9;
        }
    </style>
</head>

<body>
    <div class="container">
        <?php include "nav/navbar.php"; ?>
        <div class="bgdocument container text-white pt-3 pb-5">
            <div>
                <h2>Admin Dashboard</h2>
            </div>
            <?php
            include "config/database.php";

            if ($_SESSION['status'] !== "admin") {
                header("Location: home.php");
            }
            // delete message prompt will be here
            $action = isset($_GET['action']) ? $_GET['action'] : "";
            // if it was redirected from delete.php
            if ($action == 'approved') {
                echo "<div class='alert alert-success'>Record was approved.</div>";
            }

            if ($action == 'denied') {
                echo "<div class='alert alert-success'>Record was denied.</div>";
            }
            ?>
            <h4>Verify Issuer</h4>
            <?php
            $pending = "pending";
            $issuer_list_query = "SELECT * FROM update_issuer WHERE status=:status order by id desc";
            $issuer_list_stmt = $con->prepare($issuer_list_query);
            $issuer_list_stmt->bindParam(":status", $pending);
            $issuer_list_stmt->execute();
            $issuer_list = $issuer_list_stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($issuer_list) == 0) {
                echo "<div class='alert alert-danger'>There is no new issuer request.</div>";
            } else {
                for ($i = 0; $i < count($issuer_list); $i++) {
                    $user_query = "SELECT * FROM user WHERE id=:id";
                    $user_stmt = $con->prepare($user_query);
                    $user_stmt->bindParam(":id", $issuer_list[$i]['user_id']);
                    $user_stmt->execute();
                    $user = $user_stmt->fetch(PDO::FETCH_ASSOC);
            ?>
                    <div class='bgdocumentview d-flex justify-content-between align-items-center m-4  p-3 text-black'>
                        <div><?php echo $user['user_name'] ?></div>
                        <div class='d-flex justify-content-end align-items-center'>
                            <div class='d-flex'>
                                <a href='uploads/<?php echo $issuer_list[$i]['document']; ?>' target="_blank" class='btn btn-info text-white px-3 mx-2'>View</a>
                                <a href='#' onclick='approve_issuer_request(<?php echo $issuer_list[$i]["id"]; ?>)' class='btn btn-primary  mx-2'>Approve</a>
                                <a href='#' onclick='deny_issuer_request(<?php echo $issuer_list[$i]["id"]; ?>)' class='btn btn-danger  mx-2'>Deny</a>
                            </div>
                            <div><?php echo $issuer_list[$i]['date']; ?></div>
                        </div>
                    </div>
            <?php }
            }
            ?>
            <h4>Record</h4>
            <?php
            $pending = "pending";
            $issuer_list_query = "SELECT * FROM update_issuer WHERE status!=:status order by id desc";
            $issuer_list_stmt = $con->prepare($issuer_list_query);
            $issuer_list_stmt->bindParam(":status", $pending);
            $issuer_list_stmt->execute();
            $issuer_list = $issuer_list_stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($issuer_list) == 0) {
                echo "<div class='alert alert-danger'>There is no new issuer request.</div>";
            } else {
                for ($i = 0; $i < count($issuer_list); $i++) {
                    $user_query = "SELECT * FROM user WHERE id=:id";
                    $user_stmt = $con->prepare($user_query);
                    $user_stmt->bindParam(":id", $issuer_list[$i]['user_id']);
                    $user_stmt->execute();
                    $user = $user_stmt->fetch(PDO::FETCH_ASSOC);
            ?>
                    <div class='bgdocumentview d-flex justify-content-between align-items-center m-4  p-3 text-black'>
                        <div><?php echo $user['user_name'] ?></div>
                        <div class='d-flex justify-content-end align-items-center'>
                            <div><?php echo $issuer_list[$i]['status']; ?></div>
                            <div class="mx-3">
                                <a href='uploads/<?php echo $issuer_list[$i]['document']; ?>' target="_blank" class='btn btn-info text-white px-3 mx-2'>View</a>
                            </div>
                            <div><?php echo $issuer_list[$i]['date']; ?></div>
                        </div>
                    </div>
            <?php }
            }
            ?>
        </div>
        <?php include "nav/footer.php"; ?>
    </div>
    <script type='text/javascript'>
        // confirm record deletion
        function approve_issuer_request(id) {
            if (confirm('Are you sure?')) {
                // if user clicked ok,
                // pass the id to delete.php and execute the delete query
                window.location = 'approve_issuer?id=' + id;
            }
        }
    </script>
    <script type='text/javascript'>
        // confirm record deletion
        function deny_issuer_request(id) {
            if (confirm('Are you sure?')) {
                // if user clicked ok,
                // pass the id to delete.php and execute the delete query
                window.location = 'deny_issuer.php?id=' + id;
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>


</body>

</html>
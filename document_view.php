<?php include "validate_login.php"; ?>

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
            <h2>Your Document:</h2>
            <?php
            include "config/database.php";

            // delete message prompt will be here
            $action = isset($_GET['action']) ? $_GET['action'] : "";

            // if it was redirected from delete.php
            if ($action == 'deleted') {
                echo "<div class='alert alert-success'>Record was deleted.</div>";
            }

            $file_query = "SELECT * FROM file ORDER BY id ASC";
            $file_stmt = $con->prepare($file_query);
            $file_stmt->execute();
            $files = $file_stmt->fetchAll(PDO::FETCH_ASSOC);
            $file_loop = count($files);
            for ($i = 0; $i < $file_loop; $i++) {
                $id = $files[$i]['id'];
            ?>
                <div class='bgdocumentview d-flex justify-content-between align-items-center m-4  p-3 text-black'>
                    <div><?php echo $files[$i]['name'] ?></div>
                    <div class='d-flex justify-content-end align-items-center'>
                        <div class='d-flex'>
                            <a href='uploads/<?php echo $files[$i]['name']; ?>' target="_blank" class='btn btn-primary text-white px-3'>View</a>
                            <a href='#' onclick='delete_file(<?php echo $id; ?>)' class='btn btn-danger mx-2'>Delete</a>
                        </div>
                        <div><?php echo $files[$i]['date']; ?></div>
                    </div>
                </div>
            <?php }
            ?>
        </div>
        <?php include "nav/footer.php"; ?>
    </div>
    <script type='text/javascript'>
        // confirm record deletion
        function delete_file(id) {
            if (confirm('Are you sure?')) {
                // if user clicked ok,
                // pass the id to delete.php and execute the delete query
                window.location = 'document_delete.php?id=' + id;
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>


</body>

</html>
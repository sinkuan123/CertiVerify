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
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2>View Document:</h2>
                </div>
                <div class="w-50">
                    <form method="GET" action="" class="mb-3">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search by name" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </form>
                </div>
            </div>
            <?php
            include "config/database.php";

            // delete message prompt will be here
            $action = isset($_GET['action']) ? $_GET['action'] : "";

            $search = isset($_GET['search']) ? $_GET['search'] : '';
            // if it was redirected from delete.php
            if ($action == 'deleted') {
                echo "<div class='alert alert-success'>Record was deleted.</div>";
            }
            ?>
            <h4>Issued Document</h4>
            <?php
            $ifile_query = "SELECT * FROM file_issued WHERE recipient_id=:id";
            $ifile_stmt = $con->prepare($ifile_query);
            $ifile_stmt->bindParam("id", $_SESSION['id']);
            $ifile_stmt->execute();
            $ifiles = $ifile_stmt->fetchAll(PDO::FETCH_ASSOC);
            $ifile_loop = count($ifiles);

            if ($ifile_loop == 0) {
                echo "<div class='alert alert-danger'>You don't have any document issued by others.</div>";
            } else {
                for ($i = 0; $i < $ifile_loop; $i++) {
                    $ifiles_id = $ifiles[$i]['id'];
                    $ifile_detail_query = "SELECT * FROM file WHERE id=:id";
                    if (!empty($search)) {
                        $ifile_detail_query .= " AND name LIKE :search";
                        $search = "%{$search}%";
                    }
                    $ifile_detail_stmt = $con->prepare($ifile_detail_query);
                    $ifile_detail_stmt->bindParam(":id", $ifiles[$i]['file_id']);
                    if (!empty($search)) {
                        $ifile_detail_stmt->bindParam(":search", $search);
                    }
                    $ifile_detail_stmt->execute();
                    $ifiles_detail = $ifile_detail_stmt->fetchAll(PDO::FETCH_ASSOC);

                    for ($x = 0; $x < count($ifiles_detail); $x++) {
            ?>
                        <div class='bgdocumentview d-flex justify-content-between align-items-center m-4  p-3 text-black'>
                            <div><?php echo $ifiles_detail[$x]['name'] ?></div>
                            <div class='d-flex justify-content-end align-items-center'>
                                <div class='d-flex'>
                                    <a href='uploads/<?php echo $ifiles_detail[$x]['name']; ?>' target="_blank" class='btn btn-primary text-white px-3 mx-2'>View</a>
                                    <a href='#' onclick='delete_issued_file(<?php echo $ifiles_id; ?>)' class='btn btn-danger  mx-2'>Delete</a>
                                </div>
                                <div><?php echo $ifiles_detail[$x]['date']; ?></div>
                            </div>
                        </div>
            <?php }
                }
            }
            ?>
            <h4>Your Document</h4>
            <?php
            $file_query = "SELECT * FROM file WHERE user_id=:user_id";
            if (!empty($search)) {
                $file_query .= " AND name LIKE :search";
                $search = "%{$search}%";
            }
            $file_query .= "  ORDER BY id DESC";
            $file_stmt = $con->prepare($file_query);
            $file_stmt->bindParam(":user_id", $_SESSION['id']);
            if (!empty($search)) {
                $file_stmt->bindParam(":search", $search);
            }
            $file_stmt->execute();
            $files = $file_stmt->fetchAll(PDO::FETCH_ASSOC);
            $file_loop = count($files);
            if ($file_loop == 0) {
                echo "<div class='alert alert-danger'>You haven't upload any document.</div>";
            } else {
                for ($i = 0; $i < $file_loop; $i++) {
                    $id = $files[$i]['id'];
            ?>
                    <div class='bgdocumentview d-flex justify-content-between align-items-center m-4  p-3 text-black'>
                        <div><?php echo $files[$i]['name'] ?></div>
                        <div class='d-flex justify-content-end align-items-center'>
                            <div class='d-flex'>
                                <?php if ($_SESSION['status'] == "issuer") { ?>
                                    <a href="document_issue.php?id=<?php echo $files[$i]['id']; ?>" target="_blank" class="btn btn-info text-white mx-2">Issue</a>
                                <?php } ?>
                                <a href='uploads/<?php echo $files[$i]['name']; ?>' target="_blank" class='btn btn-primary text-white px-3 mx-2'>View</a>
                                <a href='#' onclick='delete_file(<?php echo $id; ?>)' class='btn btn-danger  mx-2'>Delete</a>
                            </div>
                            <div><?php echo $files[$i]['date']; ?></div>
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
        function delete_file(id) {
            if (confirm('Are you sure?')) {
                // if user clicked ok,
                // pass the id to delete.php and execute the delete query
                window.location = 'document_delete.php?id=' + id;
            }
        }
    </script>
    <script type='text/javascript'>
        // confirm record deletion
        function delete_issued_file(id) {
            if (confirm('Are you sure?')) {
                // if user clicked ok,
                // pass the id to delete.php and execute the delete query
                window.location = 'issued_file_delete.php?id=' + id;
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>


</body>

</html>
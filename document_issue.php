<?php include "validate_login.php"; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="css/document.css">
    <title>Issue Document</title>
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
                    <h2>Issue Document</h2>
                </div>

            </div>
            <?php
            include "config/database.php";
            $id = isset($_GET['id']) ? $_GET['id'] : "";

            $user_query = "SELECT * FROM user";
            $user_stmt = $con->prepare($user_query);
            $user_stmt->execute();
            $users = $user_stmt->fetchAll(PDO::FETCH_ASSOC);

            $file_query = "SELECT * FROM file WHERE id=?";
            $file_stmt = $con->prepare($file_query);
            $file_stmt->bindParam(1, $id);
            $file_stmt->execute();
            $file = $file_stmt->fetch(PDO::FETCH_ASSOC);

            try {
                if ($_POST) {
                    $recipient_id = $_POST['recipient_id'];
                    $recipient_loop = count($_POST['recipient_id']);
                    $subject = "Someone issue a certificate for you.";
                    $body = 'Click the follow link to see the certificate.
                    <br>
                    "http://localhost/certiverify/uploads/' . $file['name'] . '"';

                    $error = array();

                    for ($i = 0; $i < $recipient_loop; $i++) {
                        $useremail_query = "SELECT email from user Where id = :id";
                        $useremail_stmt = $con->prepare($useremail_query);
                        $useremail_stmt->bindParam(":id", $recipient_id[$i]);
                        $useremail_stmt->execute();
                        $useremail = $useremail_stmt->fetch(PDO::FETCH_COLUMN);

                        $receipt = $useremail;
                        if ($recipient_id[$i] == "") {
                            $error[] = "Please choose the recipient.";
                        }
                        $issued_file_query = "SELECT * FROM file_issued WHERE file_id=:id AND recipient_id=:recipient_id";
                        $issued_file_stmt = $con->prepare($issued_file_query);
                        $issued_file_stmt->bindParam(":id", $id);
                        $issued_file_stmt->bindParam(":recipient_id", $recipient_id[$i]);
                        $issued_file_stmt->execute();
                        $issued_file = $issued_file_stmt->fetchAll(PDO::FETCH_ASSOC);
                        if (count($issued_file) != 0) {
                            $error[] = "The user chosen have been issued with this certificate.";
                        }
                    }

                    $noduplicate = array_unique($recipient_id);

                    if (sizeof($noduplicate) != sizeof($recipient_id)) {
                        foreach ($recipient_id as $key => $val) {
                            if (!array_key_exists($key, $noduplicate)) {
                                $error[] = "Duplicated recipient not allowed.";
                            }
                        }
                    }



                    if (!empty($error)) {
                        echo "<div class='alert alert-danger m-3'>";
                        foreach ($error as $displayErrorMessage) {
                            echo $displayErrorMessage . "<br>";
                        }
                        echo "</div>";
                    } else {
                        for ($i = 0; $i < $recipient_loop; $i++) {
                            $file_issue_query = "INSERT INTO file_issued SET file_id=:file_id, issuer_id=:issuer_id, recipient_id=:recipient_id";
                            $file_issue_stmt = $con->prepare($file_issue_query);
                            $file_issue_stmt->bindParam(":file_id", $id);
                            $file_issue_stmt->bindParam(":issuer_id", $_SESSION['id']);
                            $file_issue_stmt->bindParam(":recipient_id", $recipient_id[$i]);
                            $file_issue_stmt->execute();
                        }
                        include "email.php";
                    }
                }
            } catch (PDOException $exception) {
                echo '<div class="alert alert-danger role=alert">' . $exception->getMessage() . '</div>';
            }

            ?>
            <form action="" method="POST">
                <table class=' table table-dark table-hover table-responsive table-bordered' id="row_del">

                    <tr>
                        <td class="text-center text-light col-1">#</td>
                        <td class="text-center text-light col-10">Recipient</td>
                        <td class="text-center text-light">Action</td>
                    </tr>
                    <tr class="pRow">
                        <td class="text-center">1</td>
                        <td class="d-flex">
                            <select class="form-select form-select-lg mb-3 col" name="recipient_id[]" aria-label=".form-select-lg example">

                                <option value="">Choose a Recipient</option>
                                <?php
                                for ($i = 0; $i < count($users); $i++) {
                                    echo "<option value='{$users[$i]['id']}'a>{$users[$i]['user_name']}</option>";
                                }
                                ?>
                            </select>
                        </td>
                        <td><input href='#' onclick='deleteRow(this)' class='btn d-flex justify-content-center btn-danger mt-1' value="Delete" /></td>
                    </tr>
                    <tr>
                        <td>

                        </td>
                        <td colspan="4">
                            <input type="button" value="Add More Recipient" class="btn btn-success add_one" />
                            <input type="submit" value="submit" class="btn btn-primary">
                        </td>
                    </tr>
                </table>
            </form>

            <script>
                document.addEventListener('click', function(event) {
                    if (event.target.matches('.add_one')) {
                        var rows = document.getElementsByClassName('pRow');
                        // Get the last row in the table
                        var lastRow = rows[rows.length - 1];
                        // Clone the last row
                        var clone = lastRow.cloneNode(true);
                        const [recipientsSelect, quantityInput] = clone.querySelectorAll('select[name="recipient_id[]"]');
                        recipientsSelect.value = "";
                        // Insert the clone after the last row
                        lastRow.insertAdjacentElement('afterend', clone);

                        // Loop through the rows
                        for (var i = 0; i < rows.length; i++) {
                            // Set the inner HTML of the first cell to the current loop iteration number
                            rows[i].cells[0].innerHTML = i + 1;
                        }
                    }
                }, false);

                function deleteRow(r) {
                    var total = document.querySelectorAll('.pRow').length;
                    if (total > 1) {
                        var i = r.parentNode.parentNode.rowIndex;
                        document.getElementById("row_del").deleteRow(i);

                        var rows = document.getElementsByClassName('pRow');
                        for (var i = 0; i < rows.length; i++) {
                            // Set the inner HTML of the first cell to the current loop iteration number
                            rows[i].cells[0].innerHTML = i + 1;
                        }
                    } else {
                        alert("You need choose at least one recipient.");
                    }
                }
            </script>

        </div>
        <!-- end .container -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous">

        </script>s

    </div>
    <?php include "nav/footer.php"; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>


</body>

</html>
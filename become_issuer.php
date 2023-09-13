<?php include "validate_login.php"; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="css/document.css">
    <title>Upload Document</title>
    <style>
        .dndupload-arrow {
            background: url(https://elp.newera.edu.my/moodle/theme/image.php/boost/theme/1681200880/fp/dnd_arrow) center no-repeat;
            width: 100%;
            height: 80px;
            position: absolute;
            top: 10%;
        }

        .fileinput {
            position: absolute;
            top: 60%;
        }

        .file-drop-area {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 350px;
            max-width: 100%;
            min-height: 200px;
            padding: 25px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, .1);
            transition: .3s;
        }

        .file-drop-area.is-active {
            background-image: linear-gradient(102.7deg, rgba(253, 218, 255, 1) 8.2%, rgba(223, 173, 252, 1) 19.6%, rgba(173, 205, 252, 1) 36.8%, rgba(173, 252, 244, 1) 73.2%, rgba(202, 248, 208, 1) 90.9%);
        }

        .fake-btn {
            flex-shrink: 0;
            background-color: #9699b3;
            border-radius: 3px;
            padding: 8px 15px;
            margin-right: 10px;
            font-size: 12px;
            text-transform: uppercase;
        }

        .file-msg {
            color: #9699b3;
            font-size: 16px;
            font-weight: 300;
            line-height: 1.4;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .item-delete {
            display: none;
            position: absolute;
            width: 18px;
            height: 18px;
            cursor: pointer;
            margin-left: 10px;
        }

        .item-delete:before {
            content: "";
            position: absolute;
            left: 0;
            transition: .3s;
            top: 0;
            z-index: 1;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg fill='%23bac1cb' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 438.5 438.5'%3e%3cpath d='M417.7 75.7A8.9 8.9 0 00411 73H323l-20-47.7c-2.8-7-8-13-15.4-18S272.5 0 264.9 0h-91.3C166 0 158.5 2.5 151 7.4c-7.4 5-12.5 11-15.4 18l-20 47.7H27.4a9 9 0 00-6.6 2.6 9 9 0 00-2.5 6.5v18.3c0 2.7.8 4.8 2.5 6.6a8.9 8.9 0 006.6 2.5h27.4v271.8c0 15.8 4.5 29.3 13.4 40.4a40.2 40.2 0 0032.3 16.7H338c12.6 0 23.4-5.7 32.3-17.2a64.8 64.8 0 0013.4-41V109.6h27.4c2.7 0 4.9-.8 6.6-2.5a8.9 8.9 0 002.6-6.6V82.2a9 9 0 00-2.6-6.5zm-248.4-36a8 8 0 014.9-3.2h90.5a8 8 0 014.8 3.2L283.2 73H155.3l14-33.4zm177.9 340.6a32.4 32.4 0 01-6.2 19.3c-1.4 1.6-2.4 2.4-3 2.4H100.5c-.6 0-1.6-.8-3-2.4a32.5 32.5 0 01-6.1-19.3V109.6h255.8v270.7z'/%3e%3cpath d='M137 347.2h18.3c2.7 0 4.9-.9 6.6-2.6a9 9 0 002.5-6.6V173.6a9 9 0 00-2.5-6.6 8.9 8.9 0 00-6.6-2.6H137c-2.6 0-4.8.9-6.5 2.6a8.9 8.9 0 00-2.6 6.6V338c0 2.7.9 4.9 2.6 6.6a8.9 8.9 0 006.5 2.6zM210.1 347.2h18.3a8.9 8.9 0 009.1-9.1V173.5c0-2.7-.8-4.9-2.5-6.6a8.9 8.9 0 00-6.6-2.6h-18.3a8.9 8.9 0 00-9.1 9.1V338a8.9 8.9 0 009.1 9.1zM283.2 347.2h18.3c2.7 0 4.8-.9 6.6-2.6a8.9 8.9 0 002.5-6.6V173.6c0-2.7-.8-4.9-2.5-6.6a8.9 8.9 0 00-6.6-2.6h-18.3a9 9 0 00-6.6 2.6 8.9 8.9 0 00-2.5 6.6V338a9 9 0 002.5 6.6 9 9 0 006.6 2.6z'/%3e%3c/svg%3e");
        }

        .item-delete:after {
            content: "";
            position: absolute;
            opacity: 0;
            left: 50%;
            top: 50%;
            width: 100%;
            height: 100%;
            transform: translate(-50%, -50%) scale(0);
            background-color: #f3dbff;
            border-radius: 50%;
            transition: .3s;
        }

        .item-delete:hover:after {
            transform: translate(-50%, -50%) scale(2.2);
            opacity: 1;
        }

        .item-delete:hover:before {
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg fill='%234f555f' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 438.5 438.5'%3e%3cpath d='M417.7 75.7A8.9 8.9 0 00411 73H323l-20-47.7c-2.8-7-8-13-15.4-18S272.5 0 264.9 0h-91.3C166 0 158.5 2.5 151 7.4c-7.4 5-12.5 11-15.4 18l-20 47.7H27.4a9 9 0 00-6.6 2.6 9 9 0 00-2.5 6.5v18.3c0 2.7.8 4.8 2.5 6.6a8.9 8.9 0 006.6 2.5h27.4v271.8c0 15.8 4.5 29.3 13.4 40.4a40.2 40.2 0 0032.3 16.7H338c12.6 0 23.4-5.7 32.3-17.2a64.8 64.8 0 0013.4-41V109.6h27.4c2.7 0 4.9-.8 6.6-2.5a8.9 8.9 0 002.6-6.6V82.2a9 9 0 00-2.6-6.5zm-248.4-36a8 8 0 014.9-3.2h90.5a8 8 0 014.8 3.2L283.2 73H155.3l14-33.4zm177.9 340.6a32.4 32.4 0 01-6.2 19.3c-1.4 1.6-2.4 2.4-3 2.4H100.5c-.6 0-1.6-.8-3-2.4a32.5 32.5 0 01-6.1-19.3V109.6h255.8v270.7z'/%3e%3cpath d='M137 347.2h18.3c2.7 0 4.9-.9 6.6-2.6a9 9 0 002.5-6.6V173.6a9 9 0 00-2.5-6.6 8.9 8.9 0 00-6.6-2.6H137c-2.6 0-4.8.9-6.5 2.6a8.9 8.9 0 00-2.6 6.6V338c0 2.7.9 4.9 2.6 6.6a8.9 8.9 0 006.5 2.6zM210.1 347.2h18.3a8.9 8.9 0 009.1-9.1V173.5c0-2.7-.8-4.9-2.5-6.6a8.9 8.9 0 00-6.6-2.6h-18.3a8.9 8.9 0 00-9.1 9.1V338a8.9 8.9 0 009.1 9.1zM283.2 347.2h18.3c2.7 0 4.8-.9 6.6-2.6a8.9 8.9 0 002.5-6.6V173.6c0-2.7-.8-4.9-2.5-6.6a8.9 8.9 0 00-6.6-2.6h-18.3a9 9 0 00-6.6 2.6 8.9 8.9 0 00-2.5 6.6V338a9 9 0 002.5 6.6 9 9 0 006.6 2.6z'/%3e%3c/svg%3e");
        }

        .file-input {
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 100%;
            cursor: pointer;
            opacity: 0;
        }

        .file-input:focus {
            outline: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <?php include "nav/navbar.php"; ?>
        <div class="bgdocument container pt-3 pb-5">
            <?php
            include "config/database.php";

            $user_query = "SELECT * FROM user WHERE id=?";
            $user_stmt = $con->prepare($user_query);
            $user_stmt->bindParam(1, $_SESSION['id']);
            $user_stmt->execute();
            $user = $user_stmt->fetch(PDO::FETCH_ASSOC);

            $pending = "pending";
            $check_query = "SELECT * FROM update_issuer where user_id=:user_id AND status=:status";
            $check_stmt = $con->prepare($check_query);
            $check_stmt->bindParam(":user_id", $_SESSION['id']);
            $check_stmt->bindParam(":status", $pending);
            $check_stmt->execute();
            $check = $check_stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($check) !== 0) {
                echo '<script>window.location.assign("profile.php?issuer=requested")</script>';
            } else {
                if ($_FILES) {
                    $file = basename($_FILES["file"]["name"]);
                    $file = htmlspecialchars(strip_tags($file));
                    date_default_timezone_set('Asia/Kuala_Lumpur');
                    $date = date('Y-m-d H:i:s');
                    $error = array();
                    try {
                        if ($_FILES) {
                            $file_query = "INSERT INTO update_issuer set user_id=:user_id,document=:document, date=:date";
                            $file_stmt = $con->prepare($file_query);
                            $file_stmt->bindParam(":user_id", $_SESSION['id']);
                            $file_stmt->bindParam(":document", $file);
                            $file_stmt->bindParam(":date", $date);
                            if (empty($file)) {
                                echo "<div class='alert alert-danger'>Please Select a file to upload.</div>";
                            } else if ($file_stmt->execute()) {
                                echo "<div class='alert alert-success'>Record was saved.</div>";
                                // now, if image is not empty, try to upload the image
                                if ($file) {
                                    $recipient_loop = 1;
                                    $receipt = "limsinkuan123@gmail.com";
                                    $subject = $user['user_name'] . "want to become an issuer.";
                                    $body = '"http://localhost/certiverify/admin_dashboard.php"';
                                    include "email.php";
                                    // upload to file to folder
                                    $target_directory = "uploads/";
                                    $target_file = $target_directory . $file;
                                    $file_type = pathinfo($target_file, PATHINFO_EXTENSION);

                                    // error message is empty
                                    $file_upload_error_messages = "";
                                    // make sure certain file types are allowed
                                    $allowed_file_types = array("pdf", "doc");
                                    if (!in_array($file_type, $allowed_file_types)) {
                                        $file_upload_error_messages .= "<div>Only PDF and DOC files are allowed.</div>";
                                    }
                                    // make sure file does not exist
                                    if (file_exists($target_file)) {
                                        $file_upload_error_messages = "<div>File name already exists. Try to change file name.</div>";
                                    }
                                    // make sure the 'uploads' folder exists
                                    // if not, create it
                                    if (!is_dir($target_directory)) {
                                        mkdir($target_directory, 0777, true);
                                    }
                                    // if $file_upload_error_messages is still empty
                                    if (empty($file_upload_error_messages)) {
                                        // it means there are no errors, so try to upload the file
                                        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
                                            // it means photo was uploaded
                                        } else {
                                            echo "<div class='alert alert-danger'>";
                                            echo "<div>Unable to upload photo.</div>";
                                            echo "<div>Update the record to upload photo.</div>";
                                            echo "</div>";
                                        }
                                    }

                                    // if $file_upload_error_messages is NOT empty
                                    else {
                                        // it means there are some errors, so show them to user
                                        echo "<div class='alert alert-danger'>";
                                        echo "<div>{$file_upload_error_messages}</div>";
                                        echo "<div>Update the record to upload photo.</div>";
                                        echo "</div>";
                                    }
                                }

                                $_FILES = array();
                            } else {
                                echo "<div class='alert alert-danger m-3'>Unable to save the record.</div>";
                            }
                        }
                    } catch (PDOException $exception) {
                        echo '<div class="alert alert-danger role=alert">' . $exception->getMessage() . '</div>';
                    }
                }
            }


            ?>
            <div class="container text-white">
                <h1>Upload Your Personal Certificate</h1>
                <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST" enctype="multipart/form-data">
                    <div class="file-drop-area text-center mx-auto my-5 w-75 ">
                        <div class="dndupload-arrow"></div>
                        <div class="fileinput">
                            <span class="fake-btn">Choose file</span>
                            <span class="file-msg">or drop file here</span>
                            <input type="file" class="file-input" name="file" id="file" accept=".pdf">
                            <div class="item-delete"></div>
                        </div>
                    </div>
                    <div class="text-center">
                        <input type="submit" class="btn btn-primary px-5 rounded-3" value="Upload">
                    </div>
                </form>
            </div>
        </div>
        <?php
        include "nav/footer.php";
        ?>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script>
        const $fileInput = $('.file-input');
        const $droparea = $('.file-drop-area');
        const $delete = $('.item-delete');

        $fileInput.on('dragenter focus click', function() {
            $droparea.addClass('is-active');
        });

        $fileInput.on('dragleave blur drop', function() {
            $droparea.removeClass('is-active');
        });

        $fileInput.on('change', function() {
            let filesCount = $(this)[0].files.length;
            let $textContainer = $(this).prev();

            if (filesCount === 1) {
                let fileName = $(this).val().split('\\').pop();
                $textContainer.text(fileName);
                $('.item-delete').css('display', 'inline-block');
            } else if (filesCount === 0) {
                $textContainer.text('or drop files here');
                $('.item-delete').css('display', 'none');
            }
        });

        $delete.on('click', function() {
            $('.file-input').val(null);
            $('.file-msg').text('or drop files here');
            $('.item-delete').css('display', 'none');
        });
    </script>
</body>

</html>
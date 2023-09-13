<?php
// include database connection
include 'config/database.php';
try {
    // get record ID
    // isset() is a PHP function used to verify if a value is there or not
    $id = isset($_GET['id']) ? $_GET['id'] :  die('ERROR: Record ID not found.');
    $status = "issuer";
    $ustatus = "approved";

    $user_query = "SELECT * from update_issuer WHERE id=:id";
    $user_stmt = $con->prepare($user_query);
    $user_stmt->bindParam(":id", $id);
    $user_stmt->execute();
    $user = $user_stmt->fetch(PDO::FETCH_ASSOC);

    $email_query = "SELECT email FROM user WHERE id=:id";
    $email_stmt = $con->prepare($email_query);
    $email_stmt->bindParam(":id", $user['user_id']);
    $email_stmt->execute();
    $email = $email_stmt->fetch(PDO::FETCH_ASSOC);

    $update_user_query = "UPDATE user SET status=:status WHERE id=:id";
    $update_user_stmt = $con->prepare($update_user_query);
    $update_user_stmt->bindParam(":status", $status);
    $update_user_stmt->bindParam(":id", $user['user_id']);
    $update_user_stmt->execute();

    $update_request_query = "UPDATE update_issuer SET status=:status WHERE id=:id";
    $update_request_stmt = $con->prepare($update_request_query);
    $update_request_stmt->bindParam(":status", $ustatus);
    $update_request_stmt->bindParam(":id", $id);

    if ($update_request_stmt->execute()) {
        $recipient_loop = 1;
        $receipt = $email['email'];
        $subject = "Your issuer request have been approved.";
        $body = "http://localhost/certiverify/home.php";
        include "email.php";
        // redirect to read records page and
        // tell the user record was deleted
        if ($mail->send()) {
            header('Location: admin_dashboard.php?action=approved');
        }
    } else {
        die('Unable to delete record.');
    }
}
// show error
catch (PDOException $exception) {
    die('ERROR: ' . $exception->getMessage());
}

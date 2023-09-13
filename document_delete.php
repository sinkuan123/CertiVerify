<?php
// include database connection
include 'config/database.php';
try {
    // get record ID
    // isset() is a PHP function used to verify if a value is there or not
    $id = isset($_GET['id']) ? $_GET['id'] :  die('ERROR: Record ID not found.');

    // delete query
    $file_delete_query = "DELETE FROM file WHERE id = ?";
    $file_delete_stmt = $con->prepare($file_delete_query);
    $file_delete_stmt->bindParam(1, $id);

    $image_query = "SELECT name from file where id=?";
    $image_stmt = $con->prepare($image_query);
    $image_stmt->bindParam(1, $id);
    $image_stmt->execute();
    $image = $image_stmt->fetch(PDO::FETCH_ASSOC);


    if ($file_delete_stmt->execute()) {
        unlink("uploads/" . $image['name']);
        // redirect to read records page and
        // tell the user record was deleted
        header('Location: document_view.php?action=deleted');
    } else {
        die('Unable to delete record.');
    }
}
// show error
catch (PDOException $exception) {
    die('ERROR: ' . $exception->getMessage());
}

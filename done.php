<?php
// check for record ID
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int) $_GET['id'];
    try {
        // connect to database
        $dbh = new PDO('todo.db3');
        
        // update item record
        // change status field
        $now = mktime();
        $dbh->exec("UPDATE items SET status = '0',complete = '$now' WHERE id = '$id'");
        
        // redirect to index page
        header('Location: index.php');
        exit();
    } catch (PDOException $e) {
       die('Error: ' . $e->getMessage());
    }    
} else {
    die('Error in form submission');    
}
?>

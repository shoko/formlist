<?php
if (isset($_POST['submit'])) {
    
    // check if record ID exists in the form submission
    if (isset($_POST['id']) && is_numeric(trim($_POST['id']))) {
        $id = (int) $_POST['id'];
    }
         
    // perform input validation
    // add errors to $errors array
    $errors = array();
    
    // check name
    $name = stripslashes($_POST['name']);
    if (!isset($_POST['name']) || !preg_match(
       "/^[a-z0-9][a-z0-9\'\.[:space:]]+$/i", $name)) {
       $errors[] = 'Invalid title';
    }
             
    // check priority
    if (!isset($_POST['priority']) || 
       !is_numeric($_POST['priority'])) {
       $errors[] = 'Invalid priority';
    }
    
    // check date validity
    if (!checkdate($_POST['due_mm'], $_POST['due_dd'], 
       $_POST['due_yy'])) {
       $errors[] = 'Invalid date';
    }
    
    // print errors and exit
    // if no errors, proceed to save the record to the database
    if (sizeof($errors) > 0) {
       die('Error: <br/>' . implode($errors, '<br/>'));
    } else {
        
        try {
            // connect to database
            $dbh = new PDO('sqlite:todo.db3');
            $dbh->setAttribute(
              PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        
            // quote all input characters
            $name = $dbh->quote($name);        
            $priority = (int) $_POST['priority'];
            // generate UNIX timestamp for due date
            $due = $dbh->quote(mktime(
               0,0,0,$_POST['due_mm'],$_POST['due_dd'],
               $_POST['due_yy']));
            
            // generate UPDATE or INSERT query
            if (isset($id)) {
                $sql = "UPDATE items SET name=$name, 
                  due=$due, priority='$priority',status='1' 
                  WHERE id='$id'";
            } else {
                $sql = "INSERT INTO items (
                name, due, priority, status) VALUES (
                $name, $due, '$priority', '1')";
            }
            
            // execute query
            $dbh->exec($sql);
            unset($dbh);
            
            // redirect to index page
            header('Location: index.php');
            exit();        
        } catch (PDOException $e) {
           die('Error: ' . $e->getMessage());
        }    
    }    
} else {
    die('Error in form submission');    
}
?>

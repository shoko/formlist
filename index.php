<?php
require 'include.php';
try {
    // connect to database
    $dbh = new PDO('sqlite:todo.db3');
    
    // query and retrieve pending items
    $sth = $dbh->query("SELECT * FROM items WHERE status = '1' 
       ORDER BY due DESC, priority DESC");
    $pending = $sth->fetchAll();
    $sth = null;
    
    // query and retrieve completed items
    $sth = $dbh->query("SELECT * FROM items WHERE status = '0' 
       ORDER BY due DESC, priority DESC");
    $complete = $sth->fetchAll();
    unset($dbh);
} catch (PDOException $e) {
   die('Error: ' . $e->getMessage());
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
<head>
  <title>To-Do List</title>
  <link rel="stylesheet" href="main.css" />
</head>

<body>
  <h2>To-Do List</h2>
  <div class="category">Pending Items</div>
  
  <!-- generate listing of pending items -->    
  <?php if (sizeof($pending) > 0) {  ?>
  
  <table>
    <tr>
      <td class="head"></td>
      <td class="head">Due date</td>
      <td class="head">Priority</td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
    
  <?php foreach ($pending as $p) {
    $class = (mktime() > $p['due']) ? 'late' : 'ontime';
  ?>
    <tr>
      <td class="<?php echo $class; ?>">
         <?php echo $p['name']; ?></td>
      <td class="<?php echo $class; ?>">
         <?php echo date('d M Y', $p['due']); ?></td>
      <td class="<?php echo $class; ?>">
         <?php echo $priorities[$p['priority']]; ?></td>
      <td><a href="done.php?id=
         <?php echo (int) $p['id']; ?>">Mark as done</a> | </td>
      <td><a href="form.php?id=
        <?php echo (int) $p['id']; ?>">Change</a> | </td>
      <td><a href="delete.php?id=
         <?php echo (int) $p['id']; ?>">Remove</a></td>
    </tr>
    
  <?php } ?>
  
  </table>
  
  <?php } else { ?>
    <p>None</p>
  <?php } ?>
  
  <!-- link to add a new to-do item -->    
  <p><a href="form.php">Add a new entry</a></p>
  
  <!-- generate listing of completed items -->    
  <div class="category">Completed Items</div>
  
  <?php if (sizeof($complete) > 0) {  ?>
  
  <table>
    <tr>
      <td class="head"></td>
      <td class="head">Due date</td>
      <td class="head">Completion date</td>
      <td></td>
    </tr>
    
  <?php foreach ($complete as $c) { 
    $class = ($c['complete'] > $c['due']) ? 'late' : 'ontime';
  ?>
  
    <tr>
      <td class="<?php echo $class; ?>">
         <?php echo $c['name']; ?></td>
      <td class="<?php echo $class; ?>">
         <?php echo date('d M Y', $c['due']); ?></td>
      <td class="<?php echo $class; ?>">
         <?php echo date('d M Y', $c['complete']); ?></td>
      <td><a href="delete.php?id=
         <?php echo (int) $c['id']; ?>">Remove</a></td>
    </tr>
    
  <?php } ?>
  
  </table>
  
  <?php } else { ?>
    <p>None</p>
  <?php } ?>
  
  
</body>
</html>

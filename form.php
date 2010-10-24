<?php
require 'include.php';
// get current year
$year = date('Y');
$record = null;
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int) $_GET['id'];
    try {
        // connect to database
        $dbh = new PDO('sqlite:../../db/todo.db3');
        
        // retrieve item record
        $sth = $dbh->query("SELECT * FROM items WHERE id = '$id'");
        $record = $sth->fetch();
        
        // break due date timestamp into constituent parts
        $date = getdate($record['due']);
        $record['due_mm'] = $date['mon'];
        $record['due_yy'] = $date['year'];
        $record['due_dd'] = $date['mday'];        
        unset($dbh);        
    } catch (PDOException $e) {
       die('Error: ' . $e->getMessage());
    }            
}        
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
<head>
  <title>To-Do Form</title>
  <link rel="stylesheet" href="main.css" />
</head>

<body>
  <h2>Enter To-Do Item</h2>
  <form method="post" action="save.php">
    <table>
      <tr>
        <td><label>Title:</label></td>
        <td><input type="text" name="name" class="txt" value="
           <?php echo $record['name']; ?>"></td>
      </tr>

      <tr>
        <td><label>Due Date:</label></td>
        <td>
          <select name="due_dd" class="txt">
          
          <?php for ($x=1; $x<=31; $x++) { 
            $selected = ($record['due_dd'] == $x) ? 'selected' : '';    
          ?>                
            <option value="<?php echo $x; ?>" 
              <?php echo $selected; ?>><?php echo $x; ?></option>
          <?php } ?>
          
          </select>
          
          <select name="due_mm" class="txt">
          
          <?php for ($x=1; $x<=12; $x++) { 
            $selected = ($record['due_mm'] == $x) ? 'selected' : '';    
          ?>                
            <option value="<?php echo $x; ?>" 
              <?php echo $selected; ?>><?php echo $months[$x]; ?>
            </option>
          <?php } ?>
          
          </select>
          
          <select name="due_yy" class="txt">
          
          <?php for ($x=$year; $x<=($year+5); $x++) { 
              $selected = ($record['due_yy'] == $x) ? 'selected' : '';
           ?>                
            <option value="<?php echo $x; ?>" 
              <?php echo $selected; ?>><?php echo $x; ?></option>
          <?php } ?>
          
          </select>
        </td>
      </tr>
      
      <tr>
        <td><label>Priority:</label></td>
        <td>
          <select name="priority" class="txt">
          
          <?php for ($x=1; $x<=5; $x++) {
             $selected = ($record['priority'] == $x) ? 'selected' : '';
          ?>                
            <option value="<?php echo $x; ?>" 
              <?php echo $selected; ?>>
              <?php echo $priorities[$x]; ?></option>
          <?php } ?>
          
          </select>
        </td>
      </tr>
    </table>
    
    <input type="hidden" name="id" 
           value="<?php echo $record['id']; ?>">    
    
    <p>
      <input type="submit" name="submit" value="Submit" class="btn">
    </p>
  </form>
</body>
</html>

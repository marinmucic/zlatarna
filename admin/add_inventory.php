<?php

require('../includes/config.inc.php');
$page_title = 'Add Inventory';
include('./includes/header.php');
require (MYSQL);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add']) && is_array($_POST['add'])) {
        
        
        $q1 = 'UPDATE products SET stock=stock+? WHERE id=?';
        $stmt1 = mysqli_prepare($dbc, $q1);
        mysqli_stmt_bind_param($stmt1, 'ii', $qty, $id);
        
        
        $affected = 0;
        foreach ($_POST['add'] as $id=>$qty) {
            
            if (filter_var($qty, FILTER_VALIDATE_INT, array('min_range' => 1))) {
                
            
                    mysqli_stmt_execute($stmt1);
                    $affected += mysqli_stmt_affected_rows($stmt1);
                
                    
                
                }
                
            } // End of IF.
        } // End of FOREACH.
        echo "<h4>$affected Items(s) Were Updated!</h4>";            
                
 } // End of $_POST['add'] IF.




?>




<form action="add_inventory.php" method="post" accept-charset="utf-8" style="">
<fieldset><span style="font-size:1.5em;text-align:center;display:block;padding:0.5em;">Indicate how many additional quantity of each product should be added to the
inventory.</span>
    
    <table border="0" width="100%" cellspacing="4" cellpadding="4" style="margin:1em;">
        <thead>
            <tr>
                <th align="center">Item</th>
                <th align="center">Normal Price</th>
                <th align="center">Quantity in Stock</th>
                <th align="center">Add</th>
            </tr>
        </thead>
        <tbody>
            
<?php
            
$q = 'SELECT p.id,p.description,p.price,p.stock,c.category FROM products AS p INNER JOIN categories AS c ON c.id=p.categories_id ORDER BY p.id';
$r = mysqli_query($dbc, $q);
            
            while ($row = mysqli_fetch_array ($r, MYSQLI_ASSOC)) {
                echo '<tr>
                <td align="center">' . htmlspecialchars($row['category']) . '::' . htmlspecialchars($row['description']) .'</td>
                <td align="center">' . $row['price'] .'</td>
                <td align="center">' . $row['stock'] .'</td>
                <td align="center"><input type="text" name="add[' . $row['id'] . ']" id="add[' . $row['id'] . ']"
                size="5" class="small" /></td>
                </tr>';
            }
            
?> </tbody></table> 
    
    <div class="field"><input type="submit" value="Add The Inventory" class="button" style="margin:0 auto;" /></div>
</fieldset>
</form>

<?php include('./includes/footer.php');?>
    
            
            
<?php
require('../includes/config.inc.php');
$page_title = 'Add a Product';
include('./includes/header.php');
require(MYSQL);

$add_product_errors = array();
 require('../includes/form_functions.inc.php');


$add_product_errors = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (!isset($_POST['category']) || !filter_var($_POST['category'], FILTER_VALIDATE_INT,array('min_range' => 1))) {
        $add_product_errors['category'] = 'Please select a category!';
    }
    
    if (empty($_POST['price']) || !filter_var($_POST['price'], FILTER_VALIDATE_FLOAT) || ($_POST['price']<= 0)) {
        $add_product_errors['price'] = 'Please enter a valid price!';
    }
    if (empty($_POST['stock']) || !filter_var($_POST['stock'], FILTER_VALIDATE_INT, array('min_range' =>1))) {
        $add_product_errors['stock'] = 'Please enter the quantity in stock!';
    }
    
    if (empty($_POST['description'])) {
        $add_product_errors['description'] = 'Please enter the description!';
    } 
    
    if (empty($_POST['weight']) || !filter_var($_POST['weight'], FILTER_VALIDATE_FLOAT) || ($_POST['weight']<= 0)) {
        $add_product_errors['price'] = 'Please enter a valid price!';
    }
    
     if (empty($_POST['materials'])) {
        $add_product_errors['materials'] = 'Please enter material of the product!';
    } 
    
     if (empty($_POST['karats'])) {
        $add_product_errors['karats'] = 'Please enter karats of the product!';
    } 
    
    if (is_uploaded_file($_FILES['image']['tmp_name']) && ($_FILES['image']['error'] === UPLOAD_ERR_OK)) {
        $file = $_FILES['image'];
        $size = ROUND($file['size']/1024);
        if ($size > 512) {
            $add_product_errors['image'] = 'The uploaded file was too large.';
        }
        
        $allowed_mime = array ('image/gif', 'image/pjpeg', 'image/jpeg', 'image/JPG', 'image/X-PNG','image/PNG', 'image/png', 'image/x-png');
        
        $allowed_extensions = array ('.jpg', '.gif', '.png', 'jpeg');
        
        $fileinfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($fileinfo, $file['tmp_name']);
        finfo_close($fileinfo);
        $file_ext = substr($file['name'], -4);
        
        if ( !in_array($file_type, $allowed_mime) || !in_array($file_ext, $allowed_extensions) ) {
            $add_product_errors['image'] = 'The uploaded file was not of the proper type.';
        }
        
        
        if (!array_key_exists('image', $add_product_errors)) {
            $new_name = sha1($file['name'] . uniqid('',true));
            $new_name .= ((substr($file_ext, 0, 1) != '.') ? ".{$file_ext}" : $file_ext);
            $dest = "../products/$new_name";
            if (move_uploaded_file($file['tmp_name'], $dest)) {
                $_SESSION['image']['new_name'] = $new_name;
                $_SESSION['image']['file_name'] = $file['name'];
                echo '<h4>The file has been uploaded!</h4>';
            } else {
                trigger_error('The file could not be moved.');
                unlink ($file['tmp_name']);
            }
            
        } // End of array_key_exists() IF.
    
    } elseif (!isset($_SESSION['image'])) {
        switch ($_FILES['image']['error']) {
            case 1:
            case 2:
                $add_product_errors['image'] = 'The uploaded file was too large.';
                break;
            case 3:
                $add_product_errors['image'] = 'The file was only partially uploaded.';
                break;
            case 6:
            case 7:
            case 8:
                $add_product_errors['image'] = 'The file could not be uploaded due to a system error.';
                break;
            case 4:
            default:
                $add_product_errors['image'] = 'No file was uploaded.';
                break;
        } // End of SWITCH.
    } // End of $_FILES IF-ELSEIF-ELSE. 
    
    
     if (empty($add_product_errors)) {
        $q = 'INSERT INTO products (materials_id,categories_id,karats, description, image,weight, price,
        stock) VALUES (?, ?, ?, ?, ?, ?,?,?)';
        $stmt = mysqli_prepare($dbc, $q);
        mysqli_stmt_bind_param($stmt, 'iisssddi',$_POST['materials'] ,$_POST['category'],$_POST['karats'] , $desc, $img,$_POST['weight'], $price, $_POST['stock']);
        $desc = strip_tags($_POST['description']);
        $price = $_POST['price'];
        $img_array=array();
        $img_array=explode('.',$_SESSION['image']['new_name']);
         $img=$img_array[0];
         
        mysqli_stmt_execute($stmt);
            if (!$stmt)
                echo mysqli_stmt_error($stmt);
    
        if (mysqli_stmt_affected_rows($stmt) === 1) {
            echo '<h4>The product has been added!</h4>';
            $_POST = array();
            $_FILES = array();
            unset($file, $_SESSION['image']);
        
        } else {
            trigger_error('The product could not be added due to a system error. We apologize for any inconvenience.');
            unlink ($dest);
        }
        
    } // End of $errors IF.
    
    } else {
        unset($_SESSION['image']);
    } // End of the submission IF.       
    
    
    

?>






<form enctype="multipart/form-data" action="add_other_products.php" method="post" accept-charset="utf8">
<input type="hidden" name="MAX_FILE_SIZE" value="524288" />
<fieldset><legend style="font-size:1.5em;">Fill out the form to add
product to the catalog. All fields are required.</legend> 

<div class="field"><label for="category"><strong>Category</strong></label><br />
    <select name="category"
            <?php if (array_key_exists('category', $add_product_errors)) echo ' class="error"'; ?>>
            <option>Select One</option>
            <?php
                $q = 'SELECT id, category FROM categories ORDER BY category ASC';
                    $r = mysqli_query($dbc, $q);
                    while ($row = mysqli_fetch_array ($r, MYSQLI_NUM)) {
                        echo "<option value=\"$row[0]\"";
                        if (isset($_POST['category']) && ($_POST['category'] == $row[0]) ) echo ' selected="selected"';
                        echo '>' . htmlspecialchars($row[1]) . '</option>';
                    } 
            ?>
    </select>
    <?php if (array_key_exists('category', $add_product_errors)) echo ' <span class="error">'.
            $add_product_errors['category'] . '</span>'; ?>
</div>
    
    
<div class="field"><label for="description"><strong>Description</strong></label><br /><?php
create_form_input('description', 'textarea', $add_product_errors); ?>
</div>  
    

<div class="field"><label for="price"><strong>Price</strong></label><br /><?php
create_form_input('price', 'text', $add_product_errors); ?> <small>Without the dollar sign.</small>
</div>
    
<div class="field"><label for="stock"><strong>Initial Quantity in Stock</strong></label><br /><?php
create_form_input('stock', 'text', $add_product_errors); ?>
</div>



<div class="field"><label for="weight"><strong>Weight</strong></label><br /><?php create_form_input('weight',
'text', $add_product_errors); ?>
</div>
  
    
    
<div class="field"><label for="karats"><strong>Karats</strong></label><br />
 <select name="karats">
    <?php if (isset($_POST['karats']) )
    echo '<option value="'.$_POST['karats'].'" selected="selected">'.$_POST['karats'].'</option>';?>
    <option value="14k">14k</option>
    <option value="18k">18k</option>
    <option value="22k">22k</option>
 </select>
</div>
    
    
   
    
    
    
<div class="field"><label for="materials"><strong>Materials</strong></label><br />
    <select name="materials"
            <?php if (array_key_exists('materials', $add_product_errors)) echo ' class="error"'; ?>>
            <option>Select One</option>
            <?php
                $q = 'SELECT id, gold_color FROM materials ORDER BY gold_color ASC';
                    $r = mysqli_query($dbc, $q);
                    while ($row = mysqli_fetch_array ($r, MYSQLI_NUM)) {
                        echo "<option value=\"$row[0]\"";
                        if (isset($_POST['category']) && ($_POST['category'] == $row[0]) ) echo ' selected="selected"';
                        echo '>' . htmlspecialchars($row[1]) . '</option>';
                    } 
            ?>
    </select>
    <?php if (array_key_exists('materials', $add_product_errors)) echo ' <span class="error">'.
            $add_product_errors['materials'] . '</span>'; ?>
</div>
    
    
<div class="field"><label for="image"><strong>Image</strong></label><br />
<?php
if (array_key_exists('image', $add_product_errors)) {
    echo '<span class="error">' . $add_product_errors['image'] . '</span><br /><input type="file" name="image" class="error" />';
    
    } else { // No error.
        echo '<input type="file" name="image" class="button" />';
        if (isset($_SESSION['image'])) {
            echo "<br />Currently '{$_SESSION['image']['file_name']}'";
        }
    } // end of errors IF-ELSE.
?>
</div>
    
<br clear="all" />
<div class="field"><input type="submit" value="Add This Product" class="button" /></div>
</fieldset>
</form> 
    
  <?php include('./includes/footer.php'); echo var_dump($_POST)?>  
    
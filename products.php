<?php
include "../includes/db.php";

/* -----------------------------------------
   DELETE PRODUCT
------------------------------------------ */
if(isset($_GET['delete'])){
    $pid = intval($_GET['delete']);

    $img_q = mysqli_query($conn,"SELECT image FROM products WHERE product_id=$pid");
    $img = mysqli_fetch_assoc($img_q);

    if($img && $img['image'] != "" && file_exists("../assets/".$img['image'])){
        unlink("../assets/".$img['image']);
    }

    mysqli_query($conn,"DELETE FROM products WHERE product_id=$pid");
    echo "<script>alert('Product deleted'); window.location='products.php';</script>";
}

/* -----------------------------------------
   FETCH PRODUCT FOR EDIT
------------------------------------------ */
$editData = null;

if(isset($_GET['edit'])){
    $pid = intval($_GET['edit']);
    $editData = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM products WHERE product_id=$pid"));
}

/* -----------------------------------------
   UPDATE PRODUCT
------------------------------------------ */
if(isset($_POST['update_product'])){
    $pid = intval($_POST['product_id']);

    $name = $_POST['product_name'];
    $cat = $_POST['category_id'];
    $sub = $_POST['subcat_id'];
    $type = $_POST['type_id'];
    $size = $_POST['size'];
    $price = $_POST['price_per_day'];
    $max_days = $_POST['max_rental_days'];
    $deposit = $_POST['deposit_amount'];
    $status = $_POST['stock_status'];

    $oldImage = $_POST['old_image'];
    $newImage = $oldImage;

    if($_FILES['image']['name'] != ""){
        $newImage = time()."_".$_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "../assets/".$newImage);

        if($oldImage != "" && file_exists("../assets/".$oldImage)){
            unlink("../assets/".$oldImage);
        }
    }

    mysqli_query($conn,"UPDATE products SET
        product_name='$name',
        category_id='$cat',
        subcat_id='$sub',
        type_id='$type',
        size='$size',
        price_per_day='$price',
        max_rental_days='$max_days',
        deposit_amount='$deposit',
        stock_status='$status',
        image='$newImage'
        WHERE product_id=$pid");

    echo "<script>alert('Product updated'); window.location='products.php';</script>";
}

/* -----------------------------------------
   ADD NEW PRODUCT
------------------------------------------ */
if(isset($_POST['add_product'])){
    $name = $_POST['product_name'];
    $cat = $_POST['category_id'];
    $sub = $_POST['subcat_id'];
    $type = $_POST['type_id'];
    $size = $_POST['size'];
    $price = $_POST['price_per_day'];
    $max_days = $_POST['max_rental_days'];
    $deposit = $_POST['deposit_amount'];
    $status = $_POST['stock_status'];

    $image = "";

    if($_FILES['image']['name'] != ""){
        $image = time()."_".$_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "../assets/".$image);
    }

    mysqli_query($conn,"INSERT INTO products
        (product_name,category_id,subcat_id,type_id,size,price_per_day,max_rental_days,deposit_amount,stock_status,image)
        VALUES
        ('$name','$cat','$sub','$type','$size','$price','$max_days','$deposit','$status','$image')");

    echo "<script>alert('Product added'); window.location='products.php';</script>";
}

/* -----------------------------------------
   GET ALL DATA
------------------------------------------ */
$categories = mysqli_query($conn,"SELECT * FROM category");
$subcategories = mysqli_query($conn,"SELECT * FROM subcategory");
$types = mysqli_query($conn,"SELECT * FROM type");

$products = mysqli_query($conn,"
    SELECT p.*, c.category_name, s.subcat_name, t.type_name
    FROM products p
    JOIN category c ON p.category_id=c.category_id
    JOIN subcategory s ON p.subcat_id=s.subcat_id
    JOIN type t ON p.type_id=t.type_id
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Products</title>
<style>
body{font-family:Arial;background:#fafafa;padding:20px;}
.container{max-width:1100px;margin:auto;}
h2{text-align:center;}
table{width:100%;border-collapse:collapse;margin-top:20px;}
table,th,td{border:1px solid #aaa;}
th,td{padding:10px;text-align:center;}
.edit-btn{background:#008CBA;padding:5px 10px;color:#fff;text-decoration:none;border-radius:4px;}
.delete-btn{background:#e60000;padding:5px 10px;color:#fff;text-decoration:none;border-radius:4px;}
.form-container{background:#fff;padding:20px;border-radius:6px;box-shadow:0 0 10px #ddd;margin-bottom:20px;}
input,select,textarea{width:100%;padding:8px;margin-bottom:12px;}
.btn{background:#28a745;color:#fff;padding:10px 20px;border:none;border-radius:4px;cursor:pointer;}
</style>
</head>

<body>
<div class="container">

<!-- ADD PRODUCT FORM -->
<h2>Add Product</h2>
<div class="form-container">
<form method="POST" enctype="multipart/form-data">
<label>Product Name</label>
<input type="text" name="product_name" required>

<label>Category</label>
<select name="category_id" required>
<option disabled selected>Select</option>
<?php while($c=mysqli_fetch_assoc($categories)){ ?>
<option value="<?= $c['category_id'] ?>"><?= $c['category_name'] ?></option>
<?php } ?>
</select>

<label>Subcategory</label>
<select name="subcat_id" required>
<option disabled selected>Select</option>
<?php while($s=mysqli_fetch_assoc($subcategories)){ ?>
<option value="<?= $s['subcat_id'] ?>"><?= $s['subcat_name'] ?></option>
<?php } ?>
</select>

<label>Type</label>
<select name="type_id" required>
<option disabled selected>Select</option>
<?php while($t=mysqli_fetch_assoc($types)){ ?>
<option value="<?= $t['type_id'] ?>"><?= $t['type_name'] ?></option>
<?php } ?>
</select>

<label>Size</label>
<input type="text" name="size" required>

<label>Price Per Day</label>
<input type="number" name="price_per_day" required>

<label>Max Rental Days</label>
<input type="number" name="max_rental_days" required>

<label>Deposit Amount</label>
<input type="number" name="deposit_amount" required>

<label>Status</label>
<select name="stock_status">
<option value="available">Available</option>
<option value="rented">Rented</option>
<option value="damaged">Damaged</option>
<option value="repair">Repair</option>
</select>

<label>Product Image</label>
<input type="file" name="image">

<button type="submit" name="add_product" class="btn">Add Product</button>
</form>
</div>

<!-- EDIT FORM -->
<?php if($editData){ ?>
<h2>Edit Product (ID: <?= $editData['product_id'] ?>)</h2>
<div class="form-container">
<form method="POST" enctype="multipart/form-data">

<input type="hidden" name="product_id" value="<?= $editData['product_id'] ?>">
<input type="hidden" name="old_image" value="<?= $editData['image'] ?>">

<label>Product Name</label>
<input type="text" name="product_name" value="<?= $editData['product_name'] ?>" required>

<label>Category</label>
<select name="category_id">
<?php
$cat2=mysqli_query($conn,"SELECT * FROM category");
while($c=mysqli_fetch_assoc($cat2)){ $sel=$editData['category_id']==$c['category_id']?"selected":""; ?>
<option value="<?= $c['category_id'] ?>" <?= $sel ?>><?= $c['category_name'] ?></option>
<?php } ?>
</select>

<label>Subcategory</label>
<select name="subcat_id">
<?php
$sub2=mysqli_query($conn,"SELECT * FROM subcategory");
while($s=mysqli_fetch_assoc($sub2)){ $sel=$editData['subcat_id']==$s['subcat_id']?"selected":""; ?>
<option value="<?= $s['subcat_id'] ?>" <?= $sel ?>><?= $s['subcat_name'] ?></option>
<?php } ?>
</select>

<label>Type</label>
<select name="type_id">
<?php
$type2=mysqli_query($conn,"SELECT * FROM type");
while($t=mysqli_fetch_assoc($type2)){ $sel=$editData['type_id']==$t['type_id']?"selected":""; ?>
<option value="<?= $t['type_id'] ?>" <?= $sel ?>><?= $t['type_name'] ?></option>
<?php } ?>
</select>

<label>Size</label>
<input type="text" name="size" value="<?= $editData['size'] ?>">

<label>Price Per Day</label>
<input type="number" name="price_per_day" value="<?= $editData['price_per_day'] ?>">

<label>Max Rental Days</label>
<input type="number" name="max_rental_days" value="<?= $editData['max_rental_days'] ?>">

<label>Deposit Amount</label>
<input type="number" name="deposit_amount" value="<?= $editData['deposit_amount'] ?>">

<label>Status</label>
<select name="stock_status">
<option value="available" <?= $editData['stock_status']=="available"?"selected":"" ?>>Available</option>
<option value="rented" <?= $editData['stock_status']=="rented"?"selected":"" ?>>Rented</option>
<option value="damaged" <?= $editData['stock_status']=="damaged"?"selected":"" ?>>Damaged</option>
<option value="repair" <?= $editData['stock_status']=="repair"?"selected":"" ?>>Repair</option>
</select>

<label>Current Image</label><br>
<img src="../assets/<?= $editData['image'] ?>" width="90"><br><br>
<input type="file" name="image">

<button type="submit" name="update_product" class="btn">Update Product</button>
</form>
</div>
<?php } ?>

<!-- PRODUCT TABLE -->
<h2>All Products</h2>
<table>
<tr>
<th>ID</th><th>Name</th><th>Category</th><th>Subcategory</th><th>Type</th>
<th>Size</th><th>Price/Day</th><th>Max Days</th><th>Deposit</th><th>Status</th>
<th>Image</th><th>Action</th>
</tr>

<?php while($p=mysqli_fetch_assoc($products)){ ?>
<tr>
<td><?= $p['product_id'] ?></td>
<td><?= $p['product_name'] ?></td>
<td><?= $p['category_name'] ?></td>
<td><?= $p['subcat_name'] ?></td>
<td><?= $p['type_name'] ?></td>
<td><?= $p['size'] ?></td>
<td><?= $p['price_per_day'] ?></td>
<td><?= $p['max_rental_days'] ?></td>
<td><?= $p['deposit_amount'] ?></td>
<td><?= $p['stock_status'] ?></td>
<td><img src="../assets/<?= $p['image'] ?>" width="50"></td>

<td>
<a href="products.php?edit=<?= $p['product_id'] ?>" class="edit-btn">Edit</a>
<a href="products.php?delete=<?= $p['product_id'] ?>" class="delete-btn"
onclick="return confirm('Delete this product?');">Delete</a>
</td>
</tr>
<?php } ?>

</table>

</div>
</body>
</html>

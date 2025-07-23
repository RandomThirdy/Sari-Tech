<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
}
if (isset($_POST['add_product'])) { // Check if the user clicked the "Add Product" button
    // Get the product info from the form and clean it to avoid bad data
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING); // Clean the product name
    $price = filter_var($_POST['price'], FILTER_SANITIZE_STRING); // Clean the product price
    $category = filter_var($_POST['category'], FILTER_SANITIZE_STRING); // Clean the category
    $stock = filter_var($_POST['stock'], FILTER_SANITIZE_NUMBER_INT); // Clean the stock (make sure it's a number)
    $discount = filter_var($_POST['discount'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION); // Clean the discount as a number with decimals

    // Calculate the discounted price for the product
    $discount_price = $price - ($price * ($discount / 100));

    // Prepare the image for upload
    $image = time() . '_' . filter_var($_FILES['image']['name'], FILTER_SANITIZE_STRING); // Add time to make the file name unique
    $image_size = $_FILES['image']['size']; // Check how big the file is
    $image_tmp_name = $_FILES['image']['tmp_name']; // Get the temporary location of the uploaded image
    $image_folder = '../uploaded_img/' . $image; // Set where the image will be saved

    // Check if a product with the same name already exists in the database
    $select_products = $conn->prepare("SELECT * FROM `products` WHERE name = ?");
    $select_products->execute([$name]);

    if ($select_products->rowCount() > 0) { // If there's already a product with the same name, show a message
        echo "<script>alert('Product name already exists!');</script>";
    } else {
        if ($image_size > 2000000) { // If the image file is bigger than 2MB, show an error
            echo "<script>alert('Image size is too large');</script>";
        } else {
            // Save the image to the folder
            move_uploaded_file($image_tmp_name, $image_folder);

            // Add the product to the database
            $insert_product = $conn->prepare("INSERT INTO `products`(name, category, price, image, stock, discount, discount_price) VALUES(?,?,?,?,?,?,?)");
            $insert_product->execute([$name, $category, $price, $image, $stock, $discount, $discount_price]);

            // Let the user know the product was added successfully
            echo "<script>alert('New product added!');</script>";
        }
    }
}


$search_query = "";
if (isset($_POST['search'])) {
    $search_query = $_POST['search'];
}

if (isset($_GET['delete'])) { // Check if the "delete" button or link was clicked
    $delete_id = $_GET['delete']; // Get the ID of the product to be deleted

    // Fetch the details of the product that needs to be deleted
    $delete_product_details = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
    $delete_product_details->execute([$delete_id]);
    $product_details = $delete_product_details->fetch(PDO::FETCH_ASSOC); // Get all the product info as an associative array

    // Save the product details to the archive table before deleting
    $insert_archive = $conn->prepare("INSERT INTO `product_archive` (name, category, price, image, stock, date_deleted) VALUES (?, ?, ?, ?, ?, NOW())");
    $insert_archive->execute([$product_details['name'], $product_details['category'], $product_details['price'], $product_details['image'], $product_details['stock']]);

    // Delete the product from the products table
    $delete_product = $conn->prepare("DELETE FROM `products` WHERE id = ?");
    $delete_product->execute([$delete_id]);

    // Remove the product from the cart table in case it exists there
    $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE pid = ?");
    $delete_cart->execute([$delete_id]);

    // Redirect the user back to the products page
    header('location:products.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>

    <link rel="icon" type="image/png" href="../images/sari-sari.png">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="search-bar">
   <form action="products.php" method="POST">
      <input type="text" name="search" placeholder="Search products..." value="<?= $search_query; ?>" class="box">
      <button type="submit" class="icon-btn">
         <i class="fas fa-search"></i>
      </button>
   </form>
</section>
<section class="add-products">
    <form action="" method="POST" enctype="multipart/form-data" class="add-product-form">
        <div class="form-header">
            <h3>Add Product</h3>
        </div>

        <div class="form-group">
            <label for="product-name">Product Name</label>
            <input type="text" 
                   id="product-name"
                   class="form-control" 
                   name="name" 
                   required 
                   placeholder="Enter product name"
                   maxlength="100">
        </div>

        <div class="form-group">
            <label for="stock">Stock Quantity</label>
            <input type="number" 
                   id="stock"
                   class="form-control" 
                   name="stock" 
                   required 
                   placeholder="Enter stock quantity"
                   min="0">
        </div>

        <div class="form-group">
            <label for="price">Price (₱)</label>
            <input type="number" 
                   id="price"
                   class="form-control" 
                   name="price" 
                   required 
                   placeholder="Enter product price"
                   min="0" 
                   max="9999999999"
                   onkeypress="if(this.value.length == 10) return false;">
        </div>

        <div class="form-group">
            <label for="discount">Discount Percentage</label>
            <input type="number" 
                   id="discount"
                   class="form-control" 
                   name="discount" 
                   placeholder="Enter discount percentage"
                   min="0" 
                   max="100">
        </div>

        <div class="form-group">
            <label for="category">Category</label>
            <div class="form-select">
                <select id="category" name="category" required>
                    <option value="" disabled selected>Select Category</option>
                    <option value="Beverages">Beverages</option>
                    <option value="Snacks">Snacks</option>
                    <option value="Essentials">Essentials</option>
                    <option value="Personal Care">Personal Care</option>
                </select>
            </div>
        </div>

        <div class="file-input-wrapper">
            <label class="file-input-label" for="product-image">
                <i class="fas fa-cloud-upload-alt"></i>
                <span>Choose Product Image</span>
                <input type="file" 
                       id="product-image"
                       class="file-input" 
                       name="image" 
                       accept="image/jpg, image/jpeg, image/png, image/webp" 
                       required>
            </label>
        </div>

        <button type="submit" name="add_product" class="submit-btn">
            Add Product
        </button>
    </form>
</section>


<section class="show-products" style="padding-top: 0;">
    <div class="box-container">
        <?php
        $show_products_query = "SELECT * FROM `products`";
        if (!empty($search_query)) {
            $show_products_query .= " WHERE name LIKE ?";
            $show_products = $conn->prepare($show_products_query);
            $show_products->execute(["%$search_query%"]);
        } else {
            $show_products = $conn->prepare($show_products_query);
            $show_products->execute();
        }

        if ($show_products->rowCount() > 0) {
            while ($fetch_products = $show_products->fetch(PDO::FETCH_ASSOC)) {
        ?>
        <div class="box">
            <img src="../uploaded_img/<?= $fetch_products['image']; ?>" alt="">
            <div class="flex">
                <div class="price">
                    <span>Php: </span>
                    <?php if ($fetch_products['discount'] > 0): ?>
                        <span style="text-decoration: line-through; color: red;"> <?= $fetch_products['price']; ?></span>
                        <span> → <?= $fetch_products['discount_price']; ?> </span>
                    <?php else: ?>
                        <span> <?= $fetch_products['price']; ?></span>
                    <?php endif; ?>
                </div>
                <div class="category"><?= $fetch_products['category']; ?></div>
            </div>
            <div class="name"><?= $fetch_products['name']; ?></div>
            <div class="stock">
                <?php
                if ($fetch_products['stock'] > 0) {
                    echo '<span style="color:green;">In Stock (' . $fetch_products['stock'] . ' available)</span>';
                } else {
                    echo '<span style="color:red;">Out of Stock</span>';
                }
                ?>
            </div>
            <div class="flex-btn">
                <a href="update_product.php?update=<?= $fetch_products['id']; ?>" class="option-btn">update</a>
                <a href="products.php?delete=<?= $fetch_products['id']; ?>" class="delete-btn" onclick="return confirmDelete();">delete</a>
            </div>
        </div>
        <?php
            }
        } else {
            echo '<p class="empty">no products added yet!</p>';
        }
        ?>
    </div>
    <button class="scroll-to-top" onclick="scrollToTop()">
      <i class="fas fa-arrow-up"></i>
   </button>
</section>

<script>
    function confirmDelete() {
        return confirm('Are you sure you want to delete this product?');
    }
document.addEventListener('DOMContentLoaded', function() {
    // File input preview
    const fileInput = document.getElementById('product-image');
    const fileLabel = document.querySelector('.file-input-label span');
    
    fileInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            fileLabel.textContent = this.files[0].name;
        } else {
            fileLabel.textContent = 'Choose Product Image';
        }
    });
});

</script>

<script src="../js/admin_script.js"></script>

</body>
</html>

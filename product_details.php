
<?php
// Add this where you want to display the price comparison
function displayPriceComparison($product_id) {
   global $conn;
   
   $query = "SELECT p.name, p.price as our_price,
             pc.grocery_store_name, pc.grocery_price,
             pc.price_difference
             FROM products p
             LEFT JOIN price_comparisons pc ON p.id = pc.product_id
             WHERE p.id = ?";
             
   $stmt = $conn->prepare($query);
   $stmt->bind_param("i", $product_id);
   $stmt->execute();
   $result = $stmt->get_result()->fetch_assoc();
   
   if($result) {
      ?>
      <div class="price-comparison">
         <h3>Price Comparison</h3>
         <div class="comparison-details">
            <p>Our Price: ₱<?= number_format($result['our_price'], 2) ?></p>
            <p><?= $result['grocery_store_name'] ?> Price: ₱<?= number_format($result['grocery_price'], 2) ?></p>
            
            <?php if($result['price_difference'] < 0) { ?>
               <p class="savings">You Save: ₱<?= number_format(abs($result['price_difference']), 2) ?></p>
            <?php } else { ?>
               <div class="convenience-note">
                  <p>Slightly higher but consider:</p>
                  <ul>
                     <li>No transportation cost</li>
                     <li>Save time</li>
                     <li>Buy exactly what you need</li>
                     <li>Support local business</li>
                  </ul>
               </div>
            <?php } ?>
         </div>
      </div>
      <?php
   }
}
?>
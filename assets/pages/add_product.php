<?php 
require_once __DIR__ . '/../php/configu.php';
require_once __DIR__ . '/../php/functions.php';
?>

<div style="display: flex; justify-content: center; align-items: center; min-height: 100vh; background-color: black; color: gold; padding: 20px;">
  <div style="background: #111; width: 70%; max-width: 650px; border: 0.2px solid gold; border-radius: 10px; padding: 30px; box-sizing: border-box; color: gold; box-shadow: 0 0 20px rgba(255, 215, 0, 0.3);">
    <form method="POST" id="addProductForm" enctype="multipart/form-data">
      <div style="text-align: center; margin-bottom: 25px;">
        <!-- <img src="../images/abc.png" alt="Logo" style="height: 100px; margin-bottom: 10px;"> -->
        <h3 style="margin: 0; font-weight: 600;"> Add New Product</h3>
      </div>

      <label for="name" style="display: block; margin-bottom: 6px; font-weight: 600;">Product Name*</label>
      <input type="text" id="name" name="name" required
        style="width: 100%; padding: 10px 12px; margin-bottom: 16px; border: 1px solid gold; border-radius: 5px; background-color: #222; color: gold; font-size: 16px; outline: none;">
      <?= showError('name') ?>

      <label for="description" style="display: block; margin-bottom: 6px; font-weight: 600;">Description</label>
      <textarea id="description" name="description"
        style="width: 100%; padding: 10px 12px; margin-bottom: 16px; border: 1px solid gold; border-radius: 5px; background-color: #222; color: gold; font-size: 16px; outline: none;"></textarea>
      <?= showError('description') ?>

      <label for="category" style="display: block; margin-bottom: 6px; font-weight: 600;">Category*</label>
      <select id="category" name="category" required
        style="width: 100%; padding: 10px 12px; margin-bottom: 16px; border: 1px solid gold; border-radius: 5px; background-color: #222; color: gold; font-size: 16px; outline: none;">
        <option disabled selected>-- Select Category --</option>
        <option value="T-Shirts">T-Shirts</option>
        <option value="Jeans">Jeans</option>
        <option value="Dresses">Dresses</option>
        <option value="Accessories">Accessories</option>
        <option value="Footwear">Footwear</option>
      </select>
      <?= showError('category') ?>

      <label for="target_audience" style="display: block; margin-bottom: 6px; font-weight: 600;">Target Audience*</label>
      <select id="target_audience" name="target_audience" required
        style="width: 100%; padding: 10px 12px; margin-bottom: 16px; border: 1px solid gold; border-radius: 5px; background-color: #222; color: gold; font-size: 16px; outline: none;">
        <option disabled selected>-- Select Audience --</option>
        <option value="Male">Male</option>
        <option value="Female">Female</option>
        <option value="Kid">Kid</option>
      </select>
      <?= showError('target_audience') ?>

      <label for="price" style="display: block; margin-bottom: 6px; font-weight: 600;">Price (₹)*</label>
      <input type="number" step="0.01" id="price" name="price" required
        style="width: 100%; padding: 10px 12px; margin-bottom: 16px; border: 1px solid gold; border-radius: 5px; background-color: #222; color: gold; font-size: 16px; outline: none;">
      <?= showError('price') ?>

      <label for="product_image" style="display: block; margin-bottom: 6px; font-weight: 600;">Product Image*</label>
      <input type="file" id="product_image" name="product_image" accept="image/*" required
        style="width: 100%; padding: 10px 12px; margin-bottom: 16px; border: 1px solid gold; border-radius: 5px; background-color: #222; color: gold; font-size: 16px; outline: none;">
      <?= showError('product_image') ?>

      <label for="stock" style="display: block; margin-bottom: 6px; font-weight: 600;">Stock*</label>
      <input type="number" id="stock" name="stock" required
        style="width: 100%; padding: 10px 12px; margin-bottom: 20px; border: 1px solid gold; border-radius: 5px; background-color: #222; color: gold; font-size: 16px; outline: none;">
      <?= showError('stock') ?>

      <button type="submit"
        style="width: 100%; padding: 12px; background-color: gold; border: none; border-radius: 5px; font-weight: 600; font-size: 16px; color: black; cursor: pointer;">
         Add Product
      </button>
    </form>
  </div>
</div>

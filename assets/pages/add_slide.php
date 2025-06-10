<?php 
require_once __DIR__ . '/../php/configu.php';
require_once __DIR__ . '/../php/functions.php';
?>

<div style="display: flex; justify-content: center; align-items: center; min-height: 100vh; background-color: black; color: gold; padding: 20px;">
  <div style="background: #111; width: 70%; max-width: 650px; border: 0.2px solid gold; border-radius: 10px; padding: 30px; box-sizing: border-box; color: gold; box-shadow: 0 0 20px rgba(255, 215, 0, 0.3);">
    <form method="POST" enctype="multipart/form-data" id="addSlideForm">

      <div style="text-align: center; margin-bottom: 25px;">
        <h3 style="margin: 0; font-weight: 600;">📸 Add New Slide</h3>
      </div>

      <label for="slide_image" style="display: block; margin-bottom: 6px; font-weight: 600;">Slide Image*</label>
      <input type="file" id="slide_image" name="slide_image" accept="image/*" required
        style="width: 100%; padding: 10px 12px; margin-bottom: 16px; border: 1px solid gold; border-radius: 5px; background-color: #222; color: gold; font-size: 16px; outline: none;">
      <?= showError('slide_image') ?>

      <button type="submit" name="submit"
        style="width: 100%; padding: 12px; background-color: gold; border: none; border-radius: 5px; font-weight: 600; font-size: 16px; color: black; cursor: pointer;">
        ➕ Upload Slide
      </button>
    </form>
  </div>
</div>



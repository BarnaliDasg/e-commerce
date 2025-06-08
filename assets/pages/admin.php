<?php
require_once __DIR__ . '/../php/configu.php';
require_once __DIR__ . '/../php/functions.php';

$selectedTable = $_GET['table'] ?? null;
?>

<!-- Open Sidebar Button -->
<div class="container mt-3">
  <button class="btn btn-danger" id="openSidebar">Menu</button>
</div>

<!-- Sidebar -->
<div id="sidebar">
  <span class="close-btn" id="closeSidebar">&times;</span>
  <h4>Tables</h4>
  <ul style="list-style: none; padding-left: 0;">
    <?php
    $tables = getAllTableNames();
    foreach ($tables as $table) {
        $safeTable = htmlspecialchars($table, ENT_QUOTES, 'UTF-8');
        echo "<li class='nav-item'>
                <a href='?table=" . urlencode($safeTable) . "' class='nav-link'>📁 $safeTable</a>
              </li>";
    }
    ?>
  </ul>
  <h4>add contents</h4>
  <div class="mt-4 text-center">
    <ul type="none">
      <a href="#" class="ajax-link" data-page="add_product" style="text-decoration: none; color: gold;">➕ Add Product</a>

    </ul>
    
  </div>

</div>

<!-- Overlay -->
<div id="overlay"></div>

<!-- Main Content -->
<div id="tableContentArea" style="width: 75%; float: right; padding: 10px;">
  <?php if ($selectedTable): ?>
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h3>📋 Table: <code><?= htmlspecialchars($selectedTable) ?></code></h3>
    </div>

    <?php
    $data = getTableData($selectedTable);
    if (!$data) {
        echo "<p>Error loading table data.</p>";
    } else {
        echo "<table class='table table-bordered'>";
        echo "<thead><tr>";
        foreach ($data['columns'] as $col) {
            echo "<th>" . htmlspecialchars($col->name) . "</th>";
        }
        echo "</tr></thead><tbody>";

        foreach ($data['rows'] as $row) {
            echo "<tr>";
            foreach ($row as $cell) {
                echo "<td>" . htmlspecialchars($cell) . "</td>";
            }
            echo "</tr>";
        }

        echo "</tbody></table>";
    }
    ?>
  <?php else: ?>
    <p>Select a table from the sidebar to view its contents.</p>
  <?php endif; ?>
</div>

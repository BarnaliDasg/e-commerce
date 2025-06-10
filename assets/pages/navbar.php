<?php global $user; ?>
<nav class="navbar navbar-expand-lg navbar-dark" style="width:100%; background-color: #000000; border-bottom: 2px solid gold;">
    <div class="container-fluid d-flex justify-content-between">
        <div class="d-flex justify-content-between w-75">
            <a class="navbar-brand" href="?">
                <img src="assets/images/abc.png" alt="Logo" style="height:50px;">
            </a>
            <!-- 🌟 Search Form -->
            <form id="SearchForm" class="d-flex">
                <input type="text" id="Input" name="item" class="form-control me-2" placeholder="Enter item">
                <button type="submit" class="btn btn-primary mx-2">Search</button>
            </form>



            <!-- 🌟 Modal (Displays Search Results) -->
            <div class="modal fade" id="SearchModal" tabindex="-1" role="dialog" aria-labelledby="SearchModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="SearchModalLabel">Search Results</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" id="Results">
                            <p class="text-muted">Enter item to search</p>
                        </div>
                    </div>
                </div>
            </div>


        </div>
        <ul class="navbar-nav mb-2 mb-lg-0">
            <!-- Home -->
            <li class="nav-item mx-2">
                <a class="nav-link" href="?home" aria-label="Home">
                    <i class="fas fa-home fa-2x"></i>
                </a>
            </li>

            <!-- Add Post Icon
            <li class="nav-item mx-2">
                <a class="nav-link" href="#" id="Modal" data-toggle="modal" data-target="#exampleModal" aria-label="addpost">
                    <i class="fas fa-plus-square fa-2x"></i>
                </a>
            </li> -->

            <!-- 🛒 Cart Icon -->
            <li class="nav-item mx-2">
                <a class="nav-link" href="?cart" aria-label="cart">
                    <i class="fas fa-shopping-cart fa-2x"></i>
                </a>
            </li>


            <!-- About us -->
            <!-- <li class="nav-item mx-2">
                <a class="nav-link" href="?about_us" aria-label="About">
                <i class="fas fa-info-circle fa-2x"></i>
                </a>
            </li> -->


            <!-- User Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" style="color: gold;" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Hello, <?=$user['fname']?>
                </a>

                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="?edit_profile">Edit Profile</a>
                    <a class="dropdown-item" href="#">Account Settings</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="assets/php/actions.php?logout">Logout</a>
                </div>
            </li>
        </ul>
    </div>
</nav>
<?php if (isset($_GET['cart'])) echo "<div style='color: lime;'>Cart mode is active</div>"; ?>
<section class="py-5">
    <div class="container">
        <div class="mb-4">
            <h2 class="fw-bold text-uppercase" style="letter-spacing: -1px;">Our Collections</h2>
        </div>

        <div class="d-flex flex-wrap gap-2 mb-5 align-items-center">
            <?php
            // Lấy category hiện tại để đánh dấu nút
            $current_cat = isset($_GET['cat']) ? $_GET['cat'] : 'all';
            ?>

            <a href="index.php?page=products&cat=all"
                class="btn <?php echo ($current_cat == 'all' || !isset($_GET['cat'])) ? 'btn-dark' : 'btn-outline-secondary border-0 bg-light text-dark'; ?> rounded-pill px-4 fw-medium">All</a>

            <a href="index.php?page=products&cat=new"
                class="btn <?php echo ($current_cat == 'new') ? 'btn-dark' : 'btn-outline-secondary border-0 bg-light text-dark'; ?> rounded-pill px-4 fw-medium">New</a>

            <a href="index.php?page=products&cat=keyboards"
                class="btn <?php echo ($current_cat == 'keyboards') ? 'btn-dark' : 'btn-outline-secondary border-0 bg-light text-dark'; ?> rounded-pill px-4 fw-medium">Keyboards</a>

            <a href="index.php?page=products&cat=modules"
                class="btn <?php echo ($current_cat == 'modules') ? 'btn-dark' : 'btn-outline-secondary border-0 bg-light text-dark'; ?> rounded-pill px-4 fw-medium">Modules</a>

            <a href="index.php?page=products&cat=switches"
                class="btn <?php echo ($current_cat == 'switches') ? 'btn-dark' : 'btn-outline-secondary border-0 bg-light text-dark'; ?> rounded-pill px-4 fw-medium">Switches</a>

            <a href="index.php?page=products&cat=keycaps"
                class="btn <?php echo ($current_cat == 'keycaps') ? 'btn-dark' : 'btn-outline-secondary border-0 bg-light text-dark'; ?> rounded-pill px-4 fw-medium">Keycaps</a>

            <a href="index.php?page=products&cat=cases"
                class="btn <?php echo ($current_cat == 'cases') ? 'btn-dark' : 'btn-outline-secondary border-0 bg-light text-dark'; ?> rounded-pill px-4 fw-medium">Cases</a>

            <div class="ms-auto d-flex align-items-center gap-3">
                <div class="search-container d-flex align-items-center">
                    <div id="searchBox" class="search-box">
                        <input type="text" class="form-control form-control-sm rounded-pill px-3"
                            placeholder="Search...">
                    </div>
                    <button class="btn btn-link text-dark p-0 ms-2" onclick="toggleSearch()">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>

                <div class="dropdown">
                    <button
                        class="btn btn-link text-dark p-0 text-decoration-none fw-bold small dropdown-toggle custom-sort-btn"
                        type="button" data-bs-toggle="dropdown">
                        SORT <i class="fa-solid fa-chevron-down ms-1" style="font-size: 10px;"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-4 mt-2 py-2 sort-menu">
                        <li><a class="dropdown-item sort-item active-sort" href="#"
                                onclick="selectSort(this)">Featured</a></li>
                        <li>
                            <hr class="dropdown-divider mx-2">
                        </li>
                        <li><a class="dropdown-item sort-item" href="#" onclick="selectSort(this)">Alphabetical, A-Z</a>
                        </li>
                        <li><a class="dropdown-item sort-item" href="#" onclick="selectSort(this)">Alphabetical, Z-A</a>
                        </li>
                        <li><a class="dropdown-item sort-item" href="#" onclick="selectSort(this)">Price, Low to
                                High</a></li>
                        <li><a class="dropdown-item sort-item" href="#" onclick="selectSort(this)">Price, High to
                                Low</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card border-0 h-100 product-card">
                    <div class="bg-light rounded-4 p-4 mb-3 position-relative">
                        <img src="../public/carousel/pic1.webp" class="img-fluid" alt="product">
                    </div>
                    <div class="px-2">
                        <h6 class="fw-bold mb-1">RABU 80HE</h6>
                        <p class="text-secondary small mb-2">Magnetic Gaming Keyboard</p>
                        <span class="fw-bold">4.500.000 ₫</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
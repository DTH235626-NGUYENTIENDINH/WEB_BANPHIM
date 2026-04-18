<?php
// 1. Nhận các tham số từ URL
$current_cat = isset($_GET['cat']) ? $_GET['cat'] : 'all';
$current_brand = isset($_GET['brand']) ? $_GET['brand'] : 'all'; // NHẬN THÊM BRAND
$search_query = isset($_GET['q']) ? mysqli_real_escape_string($conn, $_GET['q']) : '';
$sort_type = isset($_GET['sort']) ? $_GET['sort'] : 'newest';

// 2. Xây dựng câu SQL
$sql = "SELECT p.*, c.ten AS ten_danh_muc 
        FROM PRODUCTS p 
        LEFT JOIN CATEGORIES c ON p.category_id = c.id 
        WHERE p.deleted_at IS NULL";

// 3. Nối thêm điều kiện lọc Danh mục
if ($current_cat != 'all' && $current_cat != 'new') {
    $sql .= " AND c.slug = '$current_cat'";
}

// 4. NỐI THÊM ĐIỀU KIỆN LỌC HÃNG (BRAND)
if ($current_brand != 'all') {
    $sql .= " AND p.brand_id = " . (int) $current_brand;
}

// 5. Nối thêm điều kiện lọc Tìm kiếm
if ($search_query != '') {
    $sql .= " AND p.ten LIKE '%$search_query%'";
}

// 6. Sắp xếp (ORDER BY) - Giữ nguyên switch case của ông
switch ($sort_type) {
    case 'price_asc':
        $sql .= " ORDER BY p.gia_hien_thi ASC";
        break;
    case 'price_desc':
        $sql .= " ORDER BY p.gia_hien_thi DESC";
        break;
    case 'name_asc':
        $sql .= " ORDER BY p.ten ASC";
        break;
    case 'name_desc':
        $sql .= " ORDER BY p.ten DESC";
        break;
    case 'newest':
    default:
        $sql .= " ORDER BY p.ngay_tao DESC";
        break;
}

// 7. Giới hạn nếu là 'new'
if ($current_cat == 'new') {
    $sql .= " LIMIT 8";
}

$result = mysqli_query($conn, $sql);

// Hàm buildUrl giữ nguyên - nó sẽ tự động giữ cả cat và brand trên URL cho ông
function buildUrl($new_params)
{
    $params = $_GET;
    foreach ($new_params as $key => $value) {
        $params[$key] = $value;
    }
    return "index.php?" . http_build_query($params);
}

if (!$result) {
    die("Lỗi SQL: " . mysqli_error($conn) . " | Câu lệnh: " . $sql);
}
?>
<section class="py-5">
    <div class="container">
        <div class="mb-4">
            <h2 class="fw-bold text-uppercase" style="letter-spacing: -1px;">Our Collections</h2>
        </div>

        <div class="d-flex flex-wrap gap-2 mb-5 align-items-center">
            <?php
            // Mảng các danh mục tĩnh hoặc ông có thể lấy từ DB bảng CATEGORIES
            $categories = array(
                'all' => 'All',
                'new' => 'New',
                'keyboards' => 'Keyboards',
                'modules' => 'Modules',
                'switches' => 'Switches',
                'keycaps' => 'Keycaps',
                'cases' => 'Cases'
            );

            foreach ($categories as $slug => $name):
                $isActive = ($current_cat == $slug);
                $btnClass = $isActive ? 'btn-dark' : 'btn-outline-secondary border-0 bg-light text-dark';
                ?>
                <a href="index.php?page=products&cat=<?php echo $slug; ?>"
                    class="btn <?php echo $btnClass; ?> rounded-pill px-4 fw-medium filter-chip">
                    <?php echo $name; ?>
                </a>
            <?php endforeach; ?>

            <div class="ms-auto d-flex align-items-center gap-3">
                <div class="search-container d-flex align-items-center">
                    <form action="index.php" method="GET" id="searchBox" class="search-box">
                        <input type="hidden" name="page" value="products">
                        <input type="text" name="q" class="form-control form-control-sm rounded-pill px-3"
                            placeholder="Search...">
                    </form>
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
                        <li><a class="dropdown-item sort-item active-sort"
                                href="<?php echo buildUrl(array('sort' => 'newest')); ?>">Featured</a></li>
                        <li>
                            <hr class="dropdown-divider mx-2">
                        </li>
                        <li><a class="dropdown-item sort-item"
                                href="<?php echo buildUrl(array('sort' => 'name_asc')); ?>"
                                onclick="selectSort(this)">Alphabetical, A-Z</a>
                        </li>
                        <li><a class="dropdown-item sort-item"
                                href="<?php echo buildUrl(array('sort' => 'name_desc')); ?>"
                                onclick="selectSort(this)">Alphabetical, Z-A</a>
                        </li>
                        <li><a class="dropdown-item sort-item"
                                href="<?php echo buildUrl(array('sort' => 'price_asc')); ?>"
                                onclick="selectSort(this)">Price, Low to
                                High</a></li>
                        <li><a class="dropdown-item sort-item"
                                href="<?php echo buildUrl(array('sort' => 'price_desc')); ?>"
                                onclick="selectSort(this)">Price, High to
                                Low</a></li>
                    </ul>
                </div>
            </div>

            <div class="brand-filter-wrapper mb-4">
                <div class="brand-trigger" onclick="toggleBrands()">
                    <span class="fw-bold small text-uppercase" style="letter-spacing: 1px;">
                        Brand <i class="fa-solid fa-chevron-right ms-1" id="brandChevron"
                            style="font-size: 10px; transition: transform 0.3s;"></i>
                    </span>
                </div>

                <div id="brandContainer" class="brand-toggle-container">
                    <div class="brand-inner-scroll overflow-x-auto hide-scrollbar bg-light p-2 px-3 rounded-pill">
                        <a href="<?php echo buildUrl(array('brand' => 'all')); ?>"
                            class="btn rounded-pill px-3 py-1 fw-medium flex-shrink-0 <?php echo $current_brand == 'all' ? 'btn-dark' : 'bg-white text-dark border'; ?>"
                            style="font-size: 11px;">
                            All Brands
                        </a>

                        <?php
                        $sql_brands = "SELECT * FROM BRANDS ORDER BY ten ASC";
                        $res_brands = mysqli_query($conn, $sql_brands);
                        while ($brand = mysqli_fetch_assoc($res_brands)):
                            $isBrandActive = ($current_brand == $brand['id']);
                            ?>
                            <a href="<?php echo buildUrl(array('brand' => $brand['id'])); ?>"
                                class="btn rounded-pill px-3 py-1 fw-medium flex-shrink-0 <?php echo $isBrandActive ? 'btn-dark' : 'bg-white text-dark border'; ?>"
                                style="font-size: 11px; border-color: #eee !important;">
                                <?php echo $brand['ten']; ?>
                            </a>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <?php
            if (mysqli_num_rows($result) > 0):
                while ($row = mysqli_fetch_assoc($result)):
                    ?>
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="card border-0 h-100 product-card">
                            <a href="index.php?page=detail&id=<?php echo $row['id']; ?>" class="text-decoration-none text-dark">
                                <div class="bg-light rounded-4 p-4 mb-3 position-relative product-img-holder">
                                    <img src="../public/products/<?php echo $row['anh_dai_dien']; ?>" class="img-fluid d-block mx-auto"
                                        alt="<?php echo $row['ten']; ?>"
                                        style="width: 200px; height: 200px; object-fit: contain; mix-blend-mode: darken;">
                                </div>
                                <div class="px-2">
                                    <h6 class="fw-bold mb-1"><?php echo $row['ten']; ?></h6>
                                    <p class="text-secondary small mb-2"><?php echo $row['ten_danh_muc']; ?></p>
                                    <span class="fw-bold text-dark">
                                        <?php echo number_format($row['gia_hien_thi'], 0, ',', '.'); ?> ₫
                                    </span>
                                </div>
                            </a>
                        </div>
                    </div>
                    <?php
                endwhile;
            else:
                ?>
                <div class="col-12 text-center py-5">
                    <p class="text-muted">No products found in this category.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
DROP DATABASE IF EXISTS keyboard_store;
CREATE DATABASE keyboard_store CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE keyboard_store;

-- --------------------------------------------------------
-- 1. NGƯỜI DÙNG (USERS)
-- --------------------------------------------------------
CREATE TABLE USERS (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    ho_ten VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    mat_khau VARCHAR(255) NOT NULL,
    so_dien_thoai VARCHAR(20) NULL,
    dia_chi TEXT NULL,
    vai_tro ENUM('khach', 'admin') DEFAULT 'khach',
    reset_token VARCHAR(255) NULL,
    ngay_tao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 2. DANH MỤC & THƯƠNG HIỆU (CATEGORIES & BRANDS)
-- --------------------------------------------------------
CREATE TABLE CATEGORIES (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ten VARCHAR(100) NOT NULL,
    slug VARCHAR(120) NOT NULL UNIQUE,
    parent_id INT DEFAULT NULL,
    thu_tu INT DEFAULT 0,
    FOREIGN KEY (parent_id) REFERENCES CATEGORIES(id) ON DELETE SET NULL
);

CREATE TABLE BRANDS (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ten VARCHAR(100) NOT NULL,
    logo VARCHAR(255),
    mo_ta TEXT
);

-- --------------------------------------------------------
-- 3. SẢN PHẨM CHÍNH (PRODUCTS)
-- --------------------------------------------------------
CREATE TABLE PRODUCTS (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ten VARCHAR(200) NOT NULL,
    slug VARCHAR(191) NOT NULL UNIQUE,
    mo_ta TEXT,
    anh_dai_dien VARCHAR(255), -- Hiện ở trang danh sách
    gia_hien_thi DECIMAL(12,0), -- Giá "mồi" để load nhanh
    category_id INT,
    brand_id INT,
    loai_san_pham ENUM('keyboards', 'modules', 'switches', 'keycaps', 'cases') NOT NULL,
    layout VARCHAR(50),
    ket_noi VARCHAR(100),
    ngay_tao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    deleted_at DATETIME DEFAULT NULL,
    FOREIGN KEY (category_id) REFERENCES CATEGORIES(id) ON DELETE SET NULL,
    FOREIGN KEY (brand_id) REFERENCES BRANDS(id) ON DELETE SET NULL
);

-- --------------------------------------------------------
-- 4. BIẾN THỂ SẢN PHẨM (PRODUCT_VARIANTS)
-- --------------------------------------------------------
CREATE TABLE PRODUCT_VARIANTS (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    sku VARCHAR(100) UNIQUE, -- Ví dụ: Q1P-RED-BLK
    ten_bien_the VARCHAR(150), -- Ví dụ: Switch Red / Case Black
    thuoc_tinh_1 VARCHAR(50), -- Ví dụ: Màu sắc, Loại Switch
    thuoc_tinh_2 VARCHAR(50), -- Ví dụ: Đen, Red, Blue...
    gia_ban DECIMAL(12,0) NOT NULL,
    gia_goc DECIMAL(12,0),
    so_luong_ton INT DEFAULT 0,
    hinh_anh_bien_the VARCHAR(255),
    FOREIGN KEY (product_id) REFERENCES PRODUCTS(id) ON DELETE CASCADE
);

-- --------------------------------------------------------
-- 5. GIỎ HÀNG (CART)
-- --------------------------------------------------------
CREATE TABLE CART (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    variant_id INT NULL, -- Sửa thành NULL để nhận SP không biến thể
    so_luong INT DEFAULT 1,
    FOREIGN KEY (user_id) REFERENCES USERS(id) ON DELETE CASCADE,
    FOREIGN KEY (variant_id) REFERENCES PRODUCT_VARIANTS(id) ON DELETE CASCADE
);

-- --------------------------------------------------------
-- 6. MÃ GIẢM GIÁ (COUPONS)
-- --------------------------------------------------------
CREATE TABLE COUPONS (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) NOT NULL UNIQUE,
    loai_giam ENUM('phan_tram', 'so_tien') DEFAULT 'so_tien',
    gia_tri DECIMAL(12,0) NOT NULL,
    don_hang_toi_thieu DECIMAL(12,0) DEFAULT 0,
    ngay_het_han DATETIME,
    trang_thai BOOLEAN DEFAULT TRUE
);

-- --------------------------------------------------------
-- 7. ĐƠN HÀNG (ORDERS)
-- --------------------------------------------------------
CREATE TABLE ORDERS (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    ma_don VARCHAR(50) NOT NULL UNIQUE,
    ten_nguoi_nhan VARCHAR(100),
    sdt_nguoi_nhan VARCHAR(20),
    dia_chi_giao TEXT NOT NULL,
    tong_tien_hang DECIMAL(12,0) NOT NULL,
    phi_ship DECIMAL(12,0) DEFAULT 0,
    giam_gia DECIMAL(12,0) DEFAULT 0,
    tong_thanh_toan DECIMAL(12,0) NOT NULL,
    phuong_thuc_tt ENUM('COD','Visa/MaterCard') DEFAULT 'COD',
    trang_thai_don ENUM('Pending','Processing','Shipping','Delivered','Cancelled') DEFAULT 'cho_xac_nhan',
    ngay_dat TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES USERS(id) ON DELETE SET NULL
    
);

-- --------------------------------------------------------
-- 8. CHI TIẾT ĐƠN HÀNG (ORDER_ITEMS)
-- --------------------------------------------------------
CREATE TABLE ORDER_ITEMS (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL, -- THÊM MỚI: Để luôn biết đây là sản phẩm nào
    variant_id INT NULL,     -- SỬA: Cho phép NULL đối với hàng Standard
    so_luong INT NOT NULL,
    don_gia DECIMAL(12,0) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES ORDERS(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES PRODUCTS(id) ON DELETE RESTRICT,
    FOREIGN KEY (variant_id) REFERENCES PRODUCT_VARIANTS(id) ON DELETE SET NULL
);


-- --------------------------------------------------------
-- 9. DỮ LIỆU MẪU (DUMMY DATA)
-- --------------------------------------------------------

    -- --------------------------------------------------------
    -- 1. DỮ LIỆU THƯƠNG HIỆU (BRANDS)
    -- --------------------------------------------------------
    INSERT INTO BRANDS (ten, logo, mo_ta) VALUES 
    ('Wooting', 'wooting_logo.png', 'The leader in analog input keyboards.'),
    ('Keychron', 'keychron_logo.png', 'Premium mechanical keyboards for Mac and Windows.'),
    ('Akko', 'akko_logo.png', 'Fashionable mechanical keyboards and switches.'),
    ('Gateron', 'gateron_logo.png', 'Famous mechanical switch manufacturer.');

    -- --------------------------------------------------------
    -- 2. DỮ LIỆU DANH MỤC (CATEGORIES)
    -- --------------------------------------------------------
    INSERT INTO CATEGORIES (ten, slug, thu_tu) VALUES 
    ('Mechanical Keyboards', 'mechanical-keyboards', 1),
    ('Custom Modules', 'custom-modules', 2),
    ('Switch Packs', 'switch-packs', 3),
    ('Premium Keycaps', 'premium-keycaps', 4);

    -- --------------------------------------------------------
    -- 3. DỮ LIỆU SẢN PHẨM (PRODUCTS)
    -- --------------------------------------------------------
    INSERT INTO PRODUCTS (ten, slug, mo_ta, anh_dai_dien, gia_hien_thi, category_id, brand_id, loai_san_pham, layout, ket_noi) VALUES 
    ('Wooting 60HE+', 'wooting-60he-plus', 'Analog input 60% mechanical keyboard with Rapid Trigger.', '60he_main.png', 4500000, 1, 1, 'keyboards', '60%', 'Wired USB-C'),
    ('Keychron Q1 Pro', 'keychron-q1-pro', 'Full aluminum QMK/VIA wireless mechanical keyboard.', 'q1_pro.png', 3800000, 1, 2, 'keyboards', '75%', 'Bluetooth / Wired'),
    ('Lekker Switch L60', 'lekker-switch-l60', 'Magnetic Hall Effect switches for Wooting keyboards.', 'lekker_l60.png', 950000, 3, 1, 'switches', NULL, NULL),
    ('Akko Marrow Keycaps', 'akko-marrow-keycaps', 'PBT Double-shot keycaps in Marrow colorway.', 'marrow_keycaps.png', 1200000, 4, 3, 'keycaps', 'All layouts', NULL);

    -- --------------------------------------------------------
    -- 4. DỮ LIỆU BIẾN THỂ (PRODUCT_VARIANTS)
    -- --------------------------------------------------------
    -- Biến thể cho Wooting 60HE+ (ID: 1)
    INSERT INTO PRODUCT_VARIANTS (product_id, sku, ten_bien_the, thuoc_tinh_1, thuoc_tinh_2, gia_ban, gia_goc, so_luong_ton, hinh_anh_bien_the) VALUES 
    (1, 'W60-ANSI', '60HE+ ANSI Layout', 'Layout', 'ANSI', 4500000, 4800000, 50, '60he_ansi.png'),
    (1, 'W60-ISO', '60HE+ ISO Layout', 'Layout', 'ISO', 4500000, 4800000, 20, '60he_iso.png');

    -- Biến thể cho Keychron Q1 Pro (ID: 2)
    INSERT INTO PRODUCT_VARIANTS (product_id, sku, ten_bien_the, thuoc_tinh_1, thuoc_tinh_2, gia_ban, gia_goc, so_luong_ton, hinh_anh_bien_the) VALUES 
    (2, 'Q1P-RED-BLK', 'Carbon Black - Red Switch', 'Color', 'Black', 3800000, 4200000, 15, 'q1_black_red.png'),
    (2, 'Q1P-BLU-WHT', 'Shell White - Blue Switch', 'Color', 'White', 3900000, 4300000, 10, 'q1_white_blue.png');

    -- --------------------------------------------------------
    -- 5. NGƯỜI DÙNG MẪU (USERS) - Pass: 123456
    -- --------------------------------------------------------
    INSERT INTO USERS (username, ho_ten, email, mat_khau, so_dien_thoai, dia_chi, vai_tro) VALUES 
    ('admin', 'Administrator', 'admin@keyboard.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0123456789', 'Main Warehouse, HCM City', 'admin'),
    ('john_doe', 'John Doe', 'john@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0327277404', '123 Street, District 1, HCM', 'khach');

    -- --------------------------------------------------------
    -- 6. MÃ GIẢM GIÁ (COUPONS)
    -- --------------------------------------------------------
    INSERT INTO COUPONS (code, loai_giam, gia_tri, don_hang_toi_thieu, ngay_het_han) VALUES 
    ('WOOTINGNEW', 'so_tien', 200000, 2000000, '2026-12-31 23:59:59'),
    ('HELLOSPRING', 'phan_tram', 10, 500000, '2026-05-01 00:00:00');

    -- --------------------------------------------------------
    -- 7. ĐƠN HÀNG MẪU (ORDERS & ORDER_ITEMS)
    -- --------------------------------------------------------
    -- Đơn hàng của John Doe (user_id: 2)
    INSERT INTO ORDERS (user_id, ma_don, ten_nguoi_nhan, sdt_nguoi_nhan, dia_chi_giao, tong_tien_hang, phi_ship, tong_thanh_toan, phuong_thuc_tt, trang_thai_don) VALUES 
    (2, 'ORD-69E0E212CA0AE', 'John Doe', '0327277404', '123 Street, District 1, HCM', 4500000, 0, 4500000, 'COD', 'Pending');

    -- Chi tiết đơn hàng (Mua 1 con Wooting 60HE+ ANSI - variant_id: 1)
    INSERT INTO ORDER_ITEMS (order_id, product_id, variant_id, so_luong, don_gia) VALUES 
    (1, 1, 1, 1, 4500000);
DROP DATABASE IF EXISTS keyboard_store;
CREATE DATABASE keyboard_store CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE keyboard_store;

-- --------------------------------------------------------
-- 1. NGƯỜI DÙNG (USERS)
-- --------------------------------------------------------
CREATE TABLE USERS (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ho_ten VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    mat_khau VARCHAR(255) NOT NULL,
    so_dien_thoai VARCHAR(20),
    dia_chi TEXT,
    vai_tro ENUM('khach','admin') DEFAULT 'khach',
    reset_token VARCHAR(255) NULL,
    ngay_tao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

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
    variant_id INT NOT NULL,
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
    phuong_thuc_tt ENUM('tien_mat','chuyen_khoan','vnpay') DEFAULT 'tien_mat',
    trang_thai_don ENUM('cho_xac_nhan','dang_xu_ly','dang_giao','da_giao','da_huy') DEFAULT 'cho_xac_nhan',
    ngay_dat TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES USERS(id) ON DELETE SET NULL
);

-- --------------------------------------------------------
-- 8. CHI TIẾT ĐƠN HÀNG (ORDER_ITEMS)
-- --------------------------------------------------------
CREATE TABLE ORDER_ITEMS (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    variant_id INT NOT NULL,
    so_luong INT NOT NULL,
    don_gia DECIMAL(12,0) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES ORDERS(id) ON DELETE CASCADE,
    FOREIGN KEY (variant_id) REFERENCES PRODUCT_VARIANTS(id) ON DELETE RESTRICT
);

-- --------------------------------------------------------
-- 9. DỮ LIỆU MẪU (DUMMY DATA)
-- --------------------------------------------------------

-- Insert Brands
INSERT INTO BRANDS (ten, mo_ta) VALUES 
('Keychron', 'Bàn phím cơ cao cấp'),
('Akko', 'Bàn phím và Keycap giá tốt');

-- Insert Categories (Khớp với cái Chip của ông)
INSERT INTO CATEGORIES (ten, slug, thu_tu) VALUES
('Keyboards', 'keyboards', 1),
('Modules',   'modules',   2),
('Switches',  'switches',  3),
('Keycaps',   'keycaps',   4),
('Cases',     'cases',     5);

-- Insert một sản phẩm mẫu
INSERT INTO PRODUCTS (ten, slug, mo_ta, anh_dai_dien, gia_hien_thi, category_id, brand_id, loai_san_pham, layout, ket_noi) 
VALUES ('Keychron Q1 Pro', 'keychron-q1-pro', 'Mô tả bàn phím xịn', 'q1.jpg', 3290000, 1, 1, 'keyboards', '75%', 'Wireless');

-- Insert biến thể cho sản phẩm trên
INSERT INTO PRODUCT_VARIANTS (product_id, sku, ten_bien_the, thuoc_tinh_1, thuoc_tinh_2, gia_ban, so_luong_ton)
VALUES (1, 'Q1P-RED', 'Q1 Pro - Red Switch', 'Loại Switch', 'Red', 3290000, 10);
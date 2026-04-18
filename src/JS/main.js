// main.js - Viết kiểu thuần túy nhất để trình duyệt nào cũng đọc được

function toggleSearch() {
    console.log("Đã bấm kính lúp!");
    var box = document.getElementById('searchBox');
    if (box) {
        box.classList.toggle('active');
        if (box.classList.contains('active')) {
            var input = box.querySelector('input');
            if (input) input.focus();
        }
    } else {
        console.error("Lỗi: Không tìm thấy ID searchBox trong HTML");
    }
}

function selectSort(element) {
    const items = document.querySelectorAll('.sort-item');
    items.forEach(item => {
        item.classList.remove('active-sort');
    });
    element.classList.add('active-sort');
}

function toggleBrands() {
    const container = document.getElementById('brandContainer');
    const chevron = document.getElementById('brandChevron');
    
    container.classList.toggle('active');
    
    // Xoay mũi tên 90 độ khi mở
    if (container.classList.contains('active')) {
        chevron.style.transform = 'rotate(90deg)';
    } else {
        chevron.style.transform = 'rotate(0deg)';
    }
}

// Tự động mở nếu đang lọc hãng sẵn
window.addEventListener('load', () => {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('brand') && urlParams.get('brand') !== 'all') {
        toggleBrands();
    }
});
/**
 * XỬ LÝ TRANG CHI TIẾT SẢN PHẨM
 */
document.addEventListener('DOMContentLoaded', function() {
    // 1. Khai báo các phần tử
    const variantSelect = document.getElementById('variant-select');
    const priceDisplay = document.querySelector('.text-danger.fw-bold');
    const addToCartBtn = document.querySelector('button[type="submit"]');

    // 2. Xử lý nhảy giá khi chọn Switch/Version
    if (variantSelect && priceDisplay) {
        variantSelect.addEventListener('change', function() {
            // Lấy option đang được chọn
            const selectedOption = this.options[this.selectedIndex];
            
            // Lấy giá từ thuộc tính data-price mà mình đã đặt ở PHP
            const newPrice = selectedOption.getAttribute('data-price');
            
            if (newPrice) {
                // Hiệu ứng mờ dần rồi hiện để tạo cảm giác mượt mà
                priceDisplay.style.transition = 'opacity 0.2s';
                priceDisplay.style.opacity = '0';

                setTimeout(() => {
                    // Định dạng tiền VNĐ (1.500.000)
                    const formattedPrice = new Intl.NumberFormat('vi-VN').format(newPrice) + ' ₫';
                    priceDisplay.innerText = formattedPrice;
                    priceDisplay.style.opacity = '1';
                }, 200);
            }
        });
    }

    // 3. Kiểm tra trước khi Add to Cart (Ràng buộc chọn biến thể)
    if (addToCartBtn && variantSelect) {
        const cartForm = addToCartBtn.closest('form');
        
        cartForm.addEventListener('submit', function(e) {
            if (variantSelect.value === "") {
                e.preventDefault(); // Chặn gửi form
                
                // Hiệu ứng rung nhẹ cái ô select để nhắc khách chọn
                variantSelect.classList.add('is-invalid');
                variantSelect.style.borderColor = '#dc3545';
                
                alert('Vui lòng chọn phiên bản hoặc Switch trước khi thêm vào giỏ hàng!');
                
                variantSelect.focus();
            }
        });

        // Xóa viền đỏ khi khách bắt đầu chọn
        variantSelect.addEventListener('change', function() {
            if (this.value !== "") {
                this.style.borderColor = '#dee2e6'; // Trả về màu mặc định
                this.classList.remove('is-invalid');
            }
        });
    }
});

// 1. Khi trang vừa tải xong
    window.addEventListener("load", function() {
        const scrollPos = sessionStorage.getItem("scrollPos");
        if (scrollPos) {
            document.documentElement.style.scrollBehavior = "auto";
            window.scrollTo(0, scrollPos);
            setTimeout(() => {
                document.documentElement.style.scrollBehavior = "smooth";
            }, 10);
            sessionStorage.removeItem("scrollPos");
        }
    });
    document.addEventListener('mousedown', function(e) {
        const target = e.target.closest('.btn-step, .hover-danger');
        if (target) {
            sessionStorage.setItem("scrollPos", window.scrollY);
        }
    });



function updateProductUI() {
    const select = document.getElementById('variantSelect');
    const selectedOption = select.options[select.selectedIndex];
    
    // 1. Lấy dữ liệu từ option
    const price = selectedOption.getAttribute('data-price');
    const stock = parseInt(selectedOption.getAttribute('data-stock'));
    
    // 2. Cập nhật giá hiển thị (Giá thật)
    if (price) {
        document.getElementById('main-price').innerText = price;
    }
    
    // 3. Cập nhật trạng thái nút và thông báo kho
    const btn = document.getElementById('addToCartBtn');
    const statusDiv = document.getElementById('stock-status');

    if (stock === -1) {
        statusDiv.innerHTML = "";
        btn.disabled = false;
        btn.innerText = "ADD TO CART";
        return;
    }

    if (stock <= 0) {
        statusDiv.innerHTML = '<span class="text-danger">Out of Stock - Please choose another version</span>';
        btn.disabled = true;
        btn.innerText = "OUT OF STOCK";
    } else {
        statusDiv.innerHTML = '<span class="text-success small">Available in stock: ' + stock + ' items</span>';
        btn.disabled = false;
        btn.innerText = "ADD TO CART";
    }
}

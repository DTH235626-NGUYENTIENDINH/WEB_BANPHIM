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
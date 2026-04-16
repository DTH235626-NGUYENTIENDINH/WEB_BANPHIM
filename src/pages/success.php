<div class="container py-5 d-flex justify-content-center align-items-center" style="min-height: 60vh;">
    <div class="text-center p-5 border-0 shadow-lg rounded-5 bg-white animate__animated animate__zoomIn"
        style="max-width: 600px;">

        <div class="mb-4">
            <div class="success-checkmark d-inline-flex align-items-center justify-content-center bg-success-subtle rounded-circle animate__animated animate__bounceIn animate__delay-1s"
                style="width: 120px; height: 120px;">
                <i class="fa-solid fa-circle-check text-success" style="font-size: 80px;"></i>
            </div>
        </div>

        <h1 class="fw-bold mb-3 text-dark animate__animated animate__fadeInUp animate__delay-1s"
            style="letter-spacing: -1px;">
            ORDER PLACED SUCCESSFULLY!
        </h1>

        <p class="text-muted fs-5 mb-4 animate__animated animate__fadeInUp animate__delay-2s">
            Thank you for your purchase. We've received your order <span
                class="badge bg-dark text-white fw-bold">#<?php echo $_GET['id']; ?></span> and it's being processed.
        </p>

        <div
            class="d-grid gap-3 d-sm-flex justify-content-center animate__animated animate__fadeInUp animate__delay-2s">
            <a href="index.php" class="btn btn-checkout px-5 py-3 rounded-pill fw-bold shadow-sm text-decoration-none">
                BACK TO HOME
            </a>
            <a href="index.php?page=orders" class="btn btn-outline-dark px-5 py-3 rounded-pill fw-bold">
                VIEW ORDER
            </a>
        </div>
    </div>
</div>

<style>
    /* Style riêng cho nút vàng đen của ông */
    .btn-checkout {
        background-color: #ffcc00 !important;
        color: #000 !important;
        border: none;
        transition: all 0.3s;
    }

    .btn-checkout:hover {
        background-color: #e6b800 !important;
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(255, 204, 0, 0.3) !important;
    }

    /* Hiệu ứng nền cho icon */
    .success-checkmark {
        width: 120px;
        height: 120px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    body {
        background: #f0f2f5;
    }
    
</style>


<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>

<script>
    // Khi trang load xong thì bắn pháo giấy
    window.onload = function() {
        confetti({
            particleCount: 150,
            spread: 70,
            origin: { y: 0.6 },
            colors: ['#ffcc00', '#000000', '#28a745'] // Vàng, đen, xanh cho đúng bài
        });
    };
</script>

<script>
    // Ép trang Success luôn ở đầu
    if (history.scrollRestoration) {
        history.scrollRestoration = 'manual';
    }
    window.scrollTo(0, 0);
    sessionStorage.removeItem("scrollPos");
</script>
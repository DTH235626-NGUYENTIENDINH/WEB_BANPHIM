<style>
    .register-wrapper {
        background-color: #f2f4f7;
        min-height: 80vh; /* Đảm bảo trang luôn đủ cao để không hụt Footer */
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 50px 0;
    }
    .register-card {
        width: 100%;
        max-width: 500px; /* Trang đăng ký rộng hơn login một chút vì có nhiều ô nhập */
        border: none;
        border-radius: 24px;
        padding: 40px;
        background: #ffffff;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }
    /* Đảm bảo ô nhập liệu đồng bộ với giao diện của ông */
    .register-card .form-control {
        border-radius: 12px;
        padding: 10px 15px;
        background-color: #f9f9f9;
        border: 1px solid #e0e0e0;
    }
    .register-card .form-control:focus {
        box-shadow: none;
        border-color: #000;
        background-color: #fff;
    }
</style>

<div class="register-wrapper">
    <div class="register-card">
        <div class="text-center mb-4">
            <h2 class="fw-bold" style="letter-spacing: -1.5px;">CREATE ACCOUNT</h2>
            <p class="text-secondary small">Join the RABU community today.</p>
        </div>

        <form action="register_action.php" method="POST">
            <div class="mb-3">
                <label class="form-label small fw-bold text-uppercase">Full Name</label>
                <input type="text" name="fullname" class="form-control" placeholder="Your full name" required>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-bold text-uppercase">Username</label>
                <input type="text" name="username" class="form-control" placeholder="Choose a username" required>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-bold text-uppercase">Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="name@example.com" required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label small fw-bold text-uppercase">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
                <div class="col-md-6 mb-4">
                    <label class="form-label small fw-bold text-uppercase">Confirm</label>
                    <input type="password" name="confirm_password" class="form-control" placeholder="••••••••" required>
                </div>
            </div>

            <button type="submit" class="btn btn-dark w-100 rounded-3 py-3 fw-bold mb-4">
                CREATE ACCOUNT
            </button>

            <div class="text-center">
                <p class="small text-secondary mb-0">Already have an account? 
                    <a href="index.php?page=login" class="text-dark fw-bold text-decoration-none border-bottom border-dark">Login here</a>
                </p>
            </div>
        </form>
    </div>
</div>
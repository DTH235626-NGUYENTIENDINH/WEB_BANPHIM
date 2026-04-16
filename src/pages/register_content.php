<style>
    .register-wrapper {
        background-color: #f2f4f7;
        min-height: 90vh;
        /* Tăng nhẹ để cân đối hơn */
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 60px 0;
    }

    .register-card {
        width: 100%;
        max-width: 550px;
        /* Tăng nhẹ chiều rộng để form không bị quá dài */
        border: none;
        border-radius: 24px;
        padding: 40px;
        background: #ffffff;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.06);
    }

    .register-card .form-control {
        border-radius: 12px;
        padding: 12px 15px;
        background-color: #f9f9f9;
        border: 1px solid #e0e0e0;
        transition: all 0.3s ease;
    }

    .register-card .form-control:focus {
        box-shadow: none;
        border-color: #000;
        background-color: #fff;
    }

    /* Hiệu ứng cho nút tạo tài khoản */
    .btn-create {
        transition: transform 0.2s ease, background-color 0.3s ease;
    }

    .btn-create:hover {
        background-color: #333 !important;
        transform: translateY(-2px);
    }
</style>


<div class="register-wrapper">
    <div class="register-card">
        <div class="text-center mb-4">
            <h2 class="fw-bold" style="letter-spacing: -1.5px;">CREATE ACCOUNT</h2>
            <p class="text-secondary small">Join the RABU community today.</p>
        </div>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger py-2 small">
                <?php
                if ($_GET['error'] == 'password_mismatch')
                    echo "Mật khẩu xác nhận không khớp!";
                if ($_GET['error'] == 'user_exists')
                    echo "Username hoặc Email đã được sử dụng!";
                ?>
            </div>
        <?php endif; ?>

        <form action="./handles/register_action.php" method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label small fw-bold text-uppercase">Full Name</label>
                    <input type="text" name="ho_ten" class="form-control" placeholder="John Doe" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label small fw-bold text-uppercase">Username</label>
                    <input type="text" name="username" class="form-control" placeholder="johndoe123" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-bold text-uppercase">Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="name@example.com" required>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-bold text-uppercase">Phone Number (Optional)</label>
                <input type="text" name="so_dien_thoai" class="form-control" placeholder="090x xxx xxx">
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label small fw-bold text-uppercase">Password</label>
                    <input type="password" name="mat_khau" class="form-control" placeholder="••••••••" required>
                </div>
                <div class="col-md-6 mb-4">
                    <label class="form-label small fw-bold text-uppercase">Confirm</label>
                    <input type="password" name="confirm_password" class="form-control" placeholder="••••••••" required>
                </div>
            </div>

            <button type="submit" name="register" class="btn btn-dark w-100 rounded-3 py-3 fw-bold mb-4 btn-create">
                CREATE ACCOUNT
            </button>

            <div class="text-center">
                <p class="small text-secondary mb-0">Already have an account?
                    <a href="index.php?page=login"
                        class="text-dark fw-bold text-decoration-none border-bottom border-dark">Login here</a>
                </p>
            </div>
        </form>
    </div>
</div>
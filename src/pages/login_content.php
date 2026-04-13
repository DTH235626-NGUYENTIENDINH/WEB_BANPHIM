<style>
    .login-wrapper {
        background-color: #f2f4f7;
        min-height: 80vh; /* Giúp trang không bị hụt khi ít nội dung */
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 50px 0;
    }
    .login-card {
        width: 100%;
        max-width: 420px;
        border-radius: 24px;
        padding: 40px;
        background: #ffffff;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }
</style>

<div class="login-wrapper">
    <div class="login-card shadow-sm border-0 rounded-4 bg-white p-5" style="max-width: 420px; width: 100%;">
        <div class="text-center mb-5">
            <h2 class="fw-bold" style="letter-spacing: -1.5px;">SIGN IN</h2>
            <p class="text-secondary small">Welcome back to RABU Keyboard</p>
        </div>

        <form action="login_action.php" method="POST">
            <div class="mb-4">
                <label class="form-label small fw-bold">USERNAME</label>
                <input type="text" name="username" class="form-control rounded-3 border-0 bg-light p-3" required>
            </div>
            <div class="mb-4">
                <label class="form-label small fw-bold">PASSWORD</label>
                <input type="password" name="password" class="form-control rounded-3 border-0 bg-light p-3" required>
            </div>
            <button type="submit" class="btn btn-dark w-100 rounded-3 py-3 fw-bold mb-4">SIGN IN</button>
            <div class="text-center">
                <p class="small text-secondary mb-0">Don't have an account? 
                    <a href="index.php?page=register" class="text-dark fw-bold text-decoration-none border-bottom border-dark">Create one</a>
                </p>
            </div>
        </form>
    </div>
</div>
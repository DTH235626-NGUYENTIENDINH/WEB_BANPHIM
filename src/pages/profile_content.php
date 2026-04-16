<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?page=login");
    exit();
}

$u_id = $_SESSION['user_id'];
$sql_user = "SELECT * FROM USERS WHERE id = $u_id";
$res_user = mysqli_query($conn, $sql_user);
$user_info = mysqli_fetch_assoc($res_user);
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <?php if (isset($_GET['status'])): ?>
                <?php if ($_GET['status'] == 'success'): ?>
                    <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 mb-4" role="alert">
                        <strong>Success!</strong> Your profile information has been updated.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php elseif ($_GET['status'] == 'error'): ?>
                    <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 mb-4" role="alert">
                        <strong>Error!</strong> Something went wrong during the update. Please try again.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5">
                <div class="d-flex align-items-center mb-4">
                    <div class="ms-0">
                        <h3 class="fw-bold mb-0">MY PROFILE</h3>
                        <p class="text-muted small mb-0">Manage your account information and shipping details</p>
                    </div>
                </div>

                <form action="handles/update_profile.php" method="POST">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary text-uppercase">Full Name</label>
                            <input type="text" class="form-control bg-light border-0 p-3 rounded-3"
                                value="<?php echo $user_info['ho_ten']; ?>" name="ho_ten" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary text-uppercase">Username</label>
                            <input type="text" class="form-control bg-light border-0 p-3 rounded-3"
                                value="<?php echo $user_info['username']; ?>" readonly style="cursor: not-allowed;">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary text-uppercase">Email Address</label>
                            <input type="email" class="form-control bg-light border-0 p-3 rounded-3"
                                value="<?php echo $user_info['email']; ?>" name="email" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary text-uppercase">Phone Number</label>
                            <input type="text" class="form-control bg-light border-0 p-3 rounded-3"
                                value="<?php echo $user_info['so_dien_thoai']; ?>" name="so_dien_thoai" placeholder="e.g. 090x xxx xxx">
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold text-secondary text-uppercase">Shipping Address</label>
                            <textarea class="form-control bg-light border-0 p-3 rounded-3" rows="3" name="dia_chi"
                                placeholder="House number, street name, ward, district..."><?php echo $user_info['dia_chi']; ?></textarea>
                        </div>
                    </div>

                    <div class="mt-5 d-flex gap-2">
                        <button type="submit" class="btn btn-dark px-5 py-3 rounded-pill fw-bold shadow-sm">
                            UPDATE PROFILE <i class="fa-solid fa-check-circle ms-2"></i>
                        </button>
                        <a href="index.php" class="btn btn-outline-secondary px-5 py-3 rounded-pill fw-bold">CANCEL</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
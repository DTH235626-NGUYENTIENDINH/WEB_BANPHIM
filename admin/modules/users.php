<?php
// --- USERS LIST ---
$search = isset($_GET['q']) ? mysqli_real_escape_string($conn, $_GET['q']) : '';
$role_filter = isset($_GET['role']) ? mysqli_real_escape_string($conn, $_GET['role']) : '';

$sql = "SELECT u.*, (SELECT COUNT(*) FROM ORDERS WHERE user_id = u.id) AS order_count 
        FROM USERS u WHERE 1=1";

if ($search != '') {
    $sql .= " AND (u.ho_ten LIKE '%$search%' OR u.email LIKE '%$search%' OR u.username LIKE '%$search%')";
}
if ($role_filter != '') {
    $sql .= " AND u.vai_tro = '$role_filter'";
}

$sql .= " ORDER BY u.ngay_tao DESC";
$users = mysqli_query($conn, $sql);
?>

<div class="data-table-wrapper">
    <div class="data-table-header">
        <h3 class="data-table-title mb-0">
            All Users
            <span class="text-muted" style="font-size: 13px; font-weight: 400;">(<?php echo mysqli_num_rows($users); ?>)</span>
        </h3>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <form method="GET" class="d-flex gap-2 m-0">
                <input type="hidden" name="page" value="users">
                <input type="text" name="q" value="<?php echo htmlspecialchars($search); ?>" 
                       class="form-control form-control-sm" placeholder="Search users..." style="width: 180px; border-radius: 6px;">
                <button type="submit" class="btn-admin btn-admin-sm"><i class="fa-solid fa-search"></i></button>
            </form>

            <a href="index.php?page=users" 
               class="btn-admin btn-admin-sm <?php echo $role_filter == '' ? '' : 'btn-admin-outline'; ?>"
               style="font-size: 11px;">All</a>
            <a href="index.php?page=users&role=khach" 
               class="btn-admin btn-admin-sm <?php echo $role_filter == 'khach' ? '' : 'btn-admin-outline'; ?>"
               style="font-size: 11px;">Customers</a>
            <a href="index.php?page=users&role=admin" 
               class="btn-admin btn-admin-sm <?php echo $role_filter == 'admin' ? '' : 'btn-admin-outline'; ?>"
               style="font-size: 11px;">Admins</a>
        </div>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Username</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Role</th>
                <th>Orders</th>
                <th>Joined</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($users) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($users)): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><strong><?php echo htmlspecialchars($row['username']); ?></strong></td>
                    <td><?php echo htmlspecialchars($row['ho_ten']); ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['so_dien_thoai'] ? $row['so_dien_thoai'] : '-'; ?></td>
                    <td>
                        <?php if ($row['vai_tro'] === 'admin'): ?>
                            <span class="badge bg-dark">Admin</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Customer</span>
                        <?php endif; ?>
                    </td>
                    <td><span class="badge bg-light text-dark border"><?php echo $row['order_count']; ?></span></td>
                    <td><?php echo date('d/m/Y', strtotime($row['ngay_tao'])); ?></td>
                    <td>
                        <?php if ($row['vai_tro'] === 'khach'): ?>
                            <a href="modules/user_action.php?action=make_admin&id=<?php echo $row['id']; ?>" 
                               class="btn-admin btn-admin-sm btn-admin-outline" title="Make Admin"
                               onclick="return confirm('Make this user an admin?')">
                                <i class="fa-solid fa-user-shield"></i>
                            </a>
                        <?php else: ?>
                            <a href="modules/user_action.php?action=make_customer&id=<?php echo $row['id']; ?>" 
                               class="btn-admin btn-admin-sm btn-admin-outline" title="Make Customer"
                               onclick="return confirm('Remove admin role from this user?')">
                                <i class="fa-solid fa-user"></i>
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9">
                        <div class="empty-state">
                            <i class="fa-solid fa-users d-block"></i>
                            <p>No users found</p>
                        </div>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

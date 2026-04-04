<?php
include 'auth_check.php';
include '../includes/db.php';
include '../includes/header.php';

// Get statistics
$totalProducts = $conn->query("SELECT COUNT(*) FROM products")->fetchColumn();
$totalOrders = $conn->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$totalUsers = $conn->query("SELECT COUNT(*) FROM users")->fetchColumn();
$totalRevenue = $conn->query("SELECT SUM(total) FROM orders")->fetchColumn() ?? 0;

// Recent orders
$recentOrders = $conn->query("SELECT * FROM orders ORDER BY order_date DESC LIMIT 5")->fetchAll();
?>

<section class="page" style="max-width:1200px;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
        <h2 style="margin:0;">Admin Dashboard</h2>
        <div>
            <span style="color:#888; margin-right:15px;">
                Welcome, <strong style="color:gold;"><?php echo htmlspecialchars($_SESSION['admin_username']); ?></strong>
            </span>
            <a href="logout.php" style="color:#ff5555; font-weight:bold;">Logout</a>
        </div>
    </div>
    
    <!-- Statistics Cards -->
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(240px, 1fr)); gap:20px; margin-bottom:40px;">
        <div style="background:#1a1a1a; padding:25px; border-radius:10px; border-left:4px solid gold;">
            <div style="font-size:14px; color:#888; margin-bottom:8px;">Total Products</div>
            <div style="font-size:32px; font-weight:bold; color:#fff;"><?php echo $totalProducts; ?></div>
        </div>
        
        <div style="background:#1a1a1a; padding:25px; border-radius:10px; border-left:4px solid #4caf50;">
            <div style="font-size:14px; color:#888; margin-bottom:8px;">Total Orders</div>
            <div style="font-size:32px; font-weight:bold; color:#fff;"><?php echo $totalOrders; ?></div>
        </div>
        
        <div style="background:#1a1a1a; padding:25px; border-radius:10px; border-left:4px solid #2196f3;">
            <div style="font-size:14px; color:#888; margin-bottom:8px;">Total Users</div>
            <div style="font-size:32px; font-weight:bold; color:#fff;"><?php echo $totalUsers; ?></div>
        </div>
        
        <div style="background:#1a1a1a; padding:25px; border-radius:10px; border-left:4px solid #ff9800;">
            <div style="font-size:14px; color:#888; margin-bottom:8px;">Total Revenue</div>
            <div style="font-size:32px; font-weight:bold; color:#fff;">Ksh <?php echo number_format($totalRevenue, 2); ?></div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div style="margin-bottom:40px;">
        <h3 style="margin-bottom:15px; color:gold;">Quick Actions</h3>
        <div style="display:flex; gap:15px; flex-wrap:wrap;">
            <a href="add_product.php" class="button" style="background:gold; color:#111; padding:12px 24px; text-decoration:none; font-weight:bold;">
                + Add New Product
            </a>
            <a href="products.php" class="button" style="background:#555; color:#fff; padding:12px 24px; text-decoration:none;">
                Manage Products
            </a>
            <a href="orders.php" class="button" style="background:#555; color:#fff; padding:12px 24px; text-decoration:none;">
                View Orders
            </a>
        </div>
    </div>
    
    <!-- Recent Orders -->
    <div>
        <h3 style="margin-bottom:15px; color:gold;">Recent Orders</h3>
        <?php if (empty($recentOrders)): ?>
            <p style="color:#888;">No orders yet.</p>
        <?php else: ?>
            <table style="width:100%; border-collapse:collapse; background:#1a1a1a; border-radius:8px; overflow:hidden;">
                <thead>
                    <tr style="background:#0f0f10;">
                        <th style="padding:15px; text-align:left;">Order #</th>
                        <th style="padding:15px; text-align:left;">Customer</th>
                        <th style="padding:15px; text-align:left;">Total</th>
                        <th style="padding:15px; text-align:left;">Date</th>
                        <th style="padding:15px; text-align:left;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentOrders as $order): ?>
                        <tr style="border-bottom:1px solid #333;">
                            <td style="padding:15px;">#<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?></td>
                            <td style="padding:15px;"><?php echo htmlspecialchars($order['name']); ?></td>
                            <td style="padding:15px; color:gold; font-weight:bold;">Ksh <?php echo number_format($order['total'], 2); ?></td>
                            <td style="padding:15px; color:#888;"><?php echo date('M d, Y', strtotime($order['order_date'])); ?></td>
                            <td style="padding:15px;">
                                <span style="background:#ff9800; color:#111; padding:4px 12px; border-radius:12px; font-size:12px; font-weight:bold;">
                                    <?php echo htmlspecialchars($order['status'] ?? 'Pending'); ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</section>

<?php include '../includes/footer.php'; ?>

<?php require_once 'includes/header.php'; ?>

<?php 

$sql = "SELECT * FROM product WHERE status = 1";
$query = $connect->query($sql);
$countProduct = $query->num_rows;

$orderSql = "SELECT * FROM orders WHERE order_status = 1";
$orderQuery = $connect->query($orderSql);
$countOrder = $orderQuery->num_rows;

$totalRevenue = 0;
while ($orderResult = $orderQuery->fetch_assoc()) {
    $totalRevenue += $orderResult['paid'];
}

$lowStockSql = "SELECT * FROM product WHERE quantity <= 3 AND status = 1";
$lowStockQuery = $connect->query($lowStockSql);
$countLowStock = $lowStockQuery->num_rows;

$userwisesql = "SELECT users.username , SUM(orders.grand_total) as totalorder FROM orders INNER JOIN users ON orders.user_id = users.user_id WHERE orders.order_status = 1 GROUP BY orders.user_id";
$userwiseQuery = $connect->query($userwisesql);
$userwieseOrder = $userwiseQuery->num_rows;

// កូដសម្រាប់ទាញទិន្នន័យយកទៅគូរក្រាហ្វិក (Chart)
$chartLabels = [];
$chartData = [];
$chartSql = "SELECT product_name, quantity FROM product WHERE status = 1 LIMIT 8";
$chartQuery = $connect->query($chartSql);
while($chartRow = $chartQuery->fetch_assoc()) {
    $chartLabels[] = $chartRow['product_name'];
    $chartData[] = $chartRow['quantity'];
}

// កូដសម្រាប់ទាញបញ្ជីឈ្មោះទំនិញជិតអស់ពីស្តុក (Low Stock Alert)
$lowStockItems = [];
$lowStockAlertSql = "SELECT product_name, quantity FROM product WHERE quantity <= 3 AND status = 1";
$lowStockAlertQuery = $connect->query($lowStockAlertSql);
while($alertRow = $lowStockAlertQuery->fetch_assoc()) {
    $lowStockItems[] = $alertRow;
}

$connect->close();

?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style type="text/css">
    body {
        background-color: #d7dfe7 !important;
        font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        transition: background-color 0.3s ease, color 0.3s ease;
    }
    .ui-datepicker-calendar {
        display: none;
    }
    
    .stat-card {
        border: none;
        border-radius: 16px;
        padding: 24px;
        background: #ffffff;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }
    .stat-icon-box {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }
    
    .card-product .stat-icon-box { background-color: #ecfdf5; color: #10b981; }
    .card-product::before { content: ''; position: absolute; top: 0; left: 0; width: 4px; height: 100%; background: #10b981; }
    
    .card-stock .stat-icon-box { background-color: #fef2f2; color: #ef4444; }
    .card-stock::before { content: ''; position: absolute; top: 0; left: 0; width: 4px; height: 100%; background: #ef4444; }
    
    .card-order .stat-icon-box { background-color: #eff6ff; color: #3b82f6; }
    .card-order::before { content: ''; position: absolute; top: 0; left: 0; width: 4px; height: 100%; background: #3b82f6; }

    .content-box {
        background: #fff;
        border-radius: 16px;
        border: none;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        padding: 24px;
        transition: background-color 0.3s ease;
    }
    .table thead th {
        background-color: #f8fafc;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 11px;
        padding: 12px 16px;
        border-bottom: 1px solid #e2e8f0;
    }
    .table tbody td {
        padding: 16px;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
    }
    
    .alert-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 15px;
        margin-bottom: 8px;
        border-radius: 8px;
        background-color: #fff5f5;
        border-left: 4px solid #ef4444;
    }

    /* ទម្រង់ស្ទីលសម្រាប់ DARK MODE */
    body.dark-mode {
        background-color: #0f172a !important;
        color: #f8fafc !important;
    }
    body.dark-mode .stat-card, 
    body.dark-mode .content-box {
        background-color: #1e293b !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2);
    }
    body.dark-mode h2, 
    body.dark-mode h5,
    body.dark-mode .text-dark,
    body.dark-mode td {
        color: #f1f5f9 !important;
    }
    body.dark-mode .table thead th {
        background-color: #334155;
        color: #cbd5e1;
        border-bottom: 1px solid #475569;
    }
    body.dark-mode .table tbody td {
        border-bottom: 1px solid #334155;
    }
    body.dark-mode .alert-item {
        background-color: #2d2121;
    }
    
    /* ស្ទីលប៊ូតុងទាញហ្វាលចេញស្អាតៗ */
    .custom-export-btn {
        padding: 6px 14px;
        font-size: 12px;
        font-weight: 600;
        border-radius: 8px;
        margin-left: 5px;
        border: none;
        cursor: pointer;
        transition: opacity 0.2s;
    }
    .custom-export-btn:hover {
        opacity: 0.85;
    }
</style>

<div class="container-fluid" style="padding: 28px;">
    
    <div class="d-flex justify-content-end mb-4">
        <button id="darkModeToggle" class="btn btn-default" style="border-radius: 20px; font-weight: 600; padding: 8px 16px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
            <i class="fa-solid fa-moon me-2"></i> Dark Mode
        </button>
    </div>

    <div class="row g-4">
        <?php if(isset($_SESSION['userId']) && $_SESSION['userId'] == 1) { ?>
        
        <div class="col-md-4">
            <div class="card stat-card card-product">
                <a href="product.php" style="text-decoration:none;" class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-uppercase fw-bold d-block mb-1" style="color: #64748b; font-size: 12px; letter-spacing: 0.5px;">
                            <?php echo isset($lang['total_product']) ? $lang['total_product'] : 'Total Product'; ?>
                        </span>
                        <h2 class="fw-bold m-0" style="color: #1e293b; font-size: 32px;"><?php echo $countProduct; ?></h2>
                    </div>
                    <div class="stat-icon-box">
                        <i class="fa-solid fa-box-open"></i>
                    </div>
                </a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card stat-card card-stock">
                <a href="product.php" style="text-decoration:none;" class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-uppercase fw-bold d-block mb-1" style="color: #64748b; font-size: 12px; letter-spacing: 0.5px;">
                            <?php echo isset($lang['low_stock']) ? $lang['low_stock'] : 'Low Stock'; ?>
                        </span>
                        <h2 class="fw-bold m-0" style="color: #1e293b; font-size: 32px;"><?php echo $countLowStock; ?></h2>
                    </div>
                    <div class="stat-icon-box">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                </a>
            </div>
        </div>
        
        <?php } ?>  
        
        <div class="col-md-4">
            <div class="card stat-card card-order">
                <a href="orders.php?o=manord" style="text-decoration:none;" class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-uppercase fw-bold d-block mb-1" style="color: #64748b; font-size: 12px; letter-spacing: 0.5px;">
                            <?php echo isset($lang['total_orders']) ? $lang['total_orders'] : 'Total Orders'; ?>
                        </span>
                        <h2 class="fw-bold m-0" style="color: #1e293b; font-size: 32px;"><?php echo $countOrder; ?></h2>
                    </div>
                    <div class="stat-icon-box">
                        <i class="fa-solid fa-cart-shopping"></i>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-2">
        <div class="col-md-4">
            <div class="card content-box h-100">
                <h5 class="fw-bold mb-3" style="color: #1e293b; font-size: 15px;">
                    <i class="fa-solid fa-bell text-danger me-2"></i> Low Stock Alerts
                </h5>
                <div style="max-height: 290px; overflow-y: auto; padding-right: 5px;">
                    <?php if(!empty($lowStockItems)) { 
                        foreach($lowStockItems as $item) { ?>
                            <div class="alert-item">
                                <span class="fw-semibold text-dark" style="font-size: 13px;"><?php echo $item['product_name']; ?></span>
                                <span class="badge bg-danger rounded-pill">សល់: <?php echo $item['quantity']; ?></span>
                            </div>
                        <?php } 
                    } else { ?>
                        <div class="text-center text-muted py-5">
                            <i class="fa-solid fa-circle-check text-success display-6 mb-2"></i>
                            <p class="m-0 small">មិនមានទំនិញជិតអស់ពីស្តុកទេ!</p>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card content-box h-100">
                <h5 class="fw-bold mb-2" style="color: #1e293b; font-size: 15px;">
                    <i class="fa-solid fa-chart-bar text-primary me-2"></i>Stock Level Chart
                </h5>
                <div>
                    <canvas id="stockLevelChart" style="width: 100%; height: 280px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-2">
        <div class="col-md-4">
            <div class="card content-box text-center mb-4" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); color: white;">
                <div class="py-2">
                    <span class="text-uppercase small fw-bold tracking-wider" style="color: #3b82f6; letter-spacing: 1px;">
                        <?php echo isset($lang['calendar']) ? $lang['calendar'] : 'Calendar'; ?>
                    </span>
                    <h1 class="display-3 fw-bold my-2" style="color: #ffffff;"><?php echo date('d'); ?></h1>
                    <p class="m-0 small" style="color: #94a3b8;"><i class="fa-regular fa-clock me-1"></i> <?php echo date('l') .', '.date('Y'); ?></p>
                </div>
            </div> 

            <div class="card content-box">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-uppercase fw-bold" style="color: #64748b; font-size: 12px; letter-spacing: 0.5px;">
                        <?php echo isset($lang['total_revenue']) ? $lang['total_revenue'] : 'Total Revenue'; ?>
                    </span>
                    <span class="badge rounded-pill px-2 py-1" style="background-color: #eff6ff; color: #3b82f6; font-size: 10px;">Live</span>
                </div>
                <h2 class="fw-bold m-0" style="color: #0f172a;">
                    <span class="text-muted small fw-normal" style="font-size: 16px;">PKR</span> 
                    <?php echo ($totalRevenue) ? number_format($totalRevenue, 2) : '0.00'; ?>
                </h2>
                <div class="mt-3 pt-3 border-top" style="border-color: #f1f5f9 !important;">
                    <span class="text-success small fw-semibold"><i class="fa-solid fa-arrow-trend-up me-1"></i> <?php echo isset($lang['revenue_desc']) ? $lang['revenue_desc'] : 'Sum of all paid invoices'; ?></span>
                </div>
            </div> 
        </div>
        
        <?php if(isset($_SESSION['userId']) && $_SESSION['userId'] == 1) { ?>
        <div class="col-md-8">
            <div class="card content-box h-100">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold m-0" style="color: #1e293b; font-size: 16px;">
                        <i class="fa-solid fa-user-check me-2 text-primary"></i><?php echo isset($lang['user_orders']) ? $lang['user_orders'] : 'User Wise Orders'; ?>
                    </h5>
                </div>
                
                <div class="table-responsive">
                    <table class="table align-middle" id="userOrderTable">
                        <thead>
                            <tr>                                
                                <th><?php echo isset($lang['th_username']) ? $lang['th_username'] : 'System Username'; ?></th>
                                <th class="text-end"><?php echo isset($lang['th_total_orders']) ? $lang['th_total_orders'] : 'Total Orders (Rupees)'; ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if($userwieseOrder > 0) {
                                while ($orderResult = $userwiseQuery->fetch_assoc()) { ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px; color: #64748b;">
                                                    <i class="fa-solid fa-user small"></i>
                                                </div>
                                                <span class="fw-semibold text-dark"><?php echo $orderResult['username']?></span>
                                            </div>
                                        </td>
                                        <td class="text-end text-success fw-bold" style="font-size: 15px;">
                                            <?php echo number_format($orderResult['totalorder'], 2)?> <span style="font-size: 11px; font-weight: normal; color: #64748b;">PKR</span>
                                        </td>
                                    </tr>
                                <?php } 
                            } else { ?>
                                <tr>
                                    <td colspan="2" class="text-center text-muted py-4">
                                        <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" style="width: 48px; opacity: 0.3;" class="mb-2 d-block mx-auto">
                                        <?php echo isset($lang['no_data']) ? $lang['no_data'] : 'No data available in this period'; ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div> 
        <?php } ?>
        
    </div>
</div>

<script src="assests/plugins/moment/moment.min.js"></script>
<script src="assests/plugins/fullcalendar/fullcalendar.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script type="text/javascript">
    $(function () {
        // 🛑 លុបបន្ទាត់ $('#navDashboard').addClass('active'); ចេញដើម្បីកុំឱ្យវាជាន់ជាមួយលក្ខខណ្ឌ PHP Navbar 

        // មុខងារកូដ DARK MODE
        if (localStorage.getItem('theme') === 'dark') {
            $('body').addClass('dark-mode');
            $('#darkModeToggle').html('<i class="fa-solid fa-sun me-2"></i> របៀបពេលថ្ងៃ (Light Mode)');
        }

        $('#darkModeToggle').click(function() {
            $('body').toggleClass('dark-mode');
            if ($('body').hasClass('dark-mode')) {
                localStorage.setItem('theme', 'dark');
                $(this).html('<i class="fa-solid fa-sun me-2"></i> (Light Mode)');
            } else {
                localStorage.setItem('theme', 'light');
                $(this).html('<i class="fa-solid fa-moon me-2"></i> (Dark Mode)');
            }
        });
        
        // មុខងារគូរក្រាហ្វិកដងឈរ (Bar Chart)
        var ctx = document.getElementById('stockLevelChart').getContext('2d');
        var stockChart = new Chart(ctx, {
            type: 'bar', 
            data: {
                labels: <?php echo json_encode($chartLabels); ?>, 
                datasets: [{
                    label: 'ចំនួនស្តុកដែលមានស្រាប់ (Quantity)',
                    data: <?php echo json_encode($chartData); ?>, 
                    backgroundColor: 'rgba(59, 130, 246, 0.4)', 
                    borderColor: 'rgba(59, 130, 246, 1)',     
                    borderWidth: 1.5,
                    borderRadius: 6 
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9' }
                    },
                    x: {
                        grid: { display: false }
                    }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    });
</script>

<?php require_once 'includes/footer.php'; ?>
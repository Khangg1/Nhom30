<?php
session_start();
require_once 'db.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['maadmin'])) {
    header('Location: login.php');
    exit;
}

// Lấy thống kê
$stmt = $conn->query("SELECT COUNT(*) FROM donhang WHERE isdelete = 0");
$totalOrders = $stmt->fetchColumn();

$stmt = $conn->query("SELECT COUNT(*) FROM dongho WHERE isdelete = 0");
$totalProducts = $stmt->fetchColumn();

$stmt = $conn->query("SELECT COUNT(*) FROM nguoidung WHERE isdelete = 0");
$totalCustomers = $stmt->fetchColumn();

$stmt = $conn->query("SELECT SUM(tongtien) FROM donhang WHERE isdelete = 0");
$totalRevenue = $stmt->fetchColumn();

// Thống kê doanh thu theo tháng (6 tháng gần nhất)
$stmt = $conn->query("
    SELECT 
        FORMAT(ngaydat, 'MM/yyyy') as thang,
        SUM(tongtien) as doanhthu
    FROM donhang 
    WHERE isdelete = 0 
        AND ngaydat >= DATEADD(MONTH, -6, GETDATE())
    GROUP BY FORMAT(ngaydat, 'MM/yyyy')
    ORDER BY MIN(ngaydat)
");
$revenueData = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Thống kê đơn hàng theo trạng thái
$stmt = $conn->query("
    SELECT 
        tgh.tentrangthai,
        COUNT(d.madonhang) as soluong
    FROM trangthai_giaohang tgh
    LEFT JOIN donhang d ON tgh.matrangthai = d.matrangthai_giaohang
        AND d.isdelete = 0
    GROUP BY tgh.matrangthai, tgh.tentrangthai
");
$orderStatusData = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Thống kê top 5 sản phẩm bán chạy
$stmt = $conn->query("
    SELECT TOP 5
        dh.tendongho,
        COUNT(ct.madh) as soluongban
    FROM dongho dh
    LEFT JOIN chitietdonhang ct ON dh.madh = ct.madh
    LEFT JOIN donhang d ON ct.madonhang = d.madonhang
    WHERE dh.isdelete = 0 AND d.isdelete = 0
    GROUP BY dh.madh, dh.tendongho
    ORDER BY COUNT(ct.madh) DESC
");
$topProductsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

ob_start();
?>

<div class="dashboard">
    <div class="dashboard-header">
        <h1>Bảng điều khiển</h1>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon orders">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="stat-details">
                <h3>Đơn hàng</h3>
                <p><?php echo number_format($totalOrders); ?></p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon products">
                <i class="fas fa-box"></i>
            </div>
            <div class="stat-details">
                <h3>Sản phẩm</h3>
                <p><?php echo number_format($totalProducts); ?></p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon customers">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-details">
                <h3>Khách hàng</h3>
                <p><?php echo number_format($totalCustomers); ?></p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon revenue">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="stat-details">
                <h3>Doanh thu</h3>
                <p><?php echo number_format($totalRevenue, 0, ',', '.'); ?> VNĐ</p>
            </div>
        </div>
    </div>

    <!-- Add chart containers -->
    <div class="charts-grid">
        <div class="chart-card">
            <h3>Doanh thu 6 tháng gần nhất</h3>
            <canvas id="revenueChart"></canvas>
        </div>

        <div class="chart-card">
            <h3>Trạng thái đơn hàng</h3>
            <canvas id="orderStatusChart"></canvas>
        </div>

        <div class="chart-card">
            <h3>Top 5 sản phẩm bán chạy</h3>
            <canvas id="topProductsChart"></canvas>
        </div>
    </div>
</div>

<style>
    .dashboard {
        padding: 2rem;
        font-family: 'Arial', sans-serif;
    }

    .dashboard-header h1 {
        color: #34495e;
        font-size: 2.5rem;
        margin: 0;
        text-align: center;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
        margin-top: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
    }

    .stat-icon i {
        font-size: 1.5rem;
        color: white;
    }

    .stat-icon.orders {
        background: linear-gradient(135deg, #3498db, #2980b9);
    }

    .stat-icon.products {
        background: linear-gradient(135deg, #2ecc71, #27ae60);
    }

    .stat-icon.customers {
        background: linear-gradient(135deg, #9b59b6, #8e44ad);
    }

    .stat-icon.revenue {
        background: linear-gradient(135deg, #f1c40f, #f39c12);
    }

    .stat-details h3 {
        color: #7f8c8d;
        font-size: 0.9rem;
        margin: 0 0 0.5rem 0;
    }

    .stat-details p {
        color: #2c3e50;
        font-size: 1.5rem;
        font-weight: bold;
        margin: 0;
    }

    .charts-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 2rem;
        margin-top: 2rem;
    }

    .chart-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .chart-card h3 {
        color: #2c3e50;
        margin-bottom: 1rem;
        text-align: center;
    }

    canvas {
        width: 100% !important;
        height: 300px !important;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const revenueLabels = <?php echo json_encode(array_column($revenueData, 'thang')); ?>;
        const revenueValues = <?php echo json_encode(array_column($revenueData, 'doanhthu')); ?>;
        const statusLabels = <?php echo json_encode(array_column($orderStatusData, 'tentrangthai')); ?>;
        const statusValues = <?php echo json_encode(array_column($orderStatusData, 'soluong')); ?>;
        const productLabels = <?php echo json_encode(array_column($topProductsData, 'tendongho')); ?>;
        const productValues = <?php echo json_encode(array_column($topProductsData, 'soluongban')); ?>;

        const revenueChart = new Chart(document.getElementById('revenueChart'), {
            type: 'line',
            data: {
                labels: revenueLabels,
                datasets: [{
                    label: 'Doanh thu',
                    data: revenueValues,
                    borderColor: '#3498db',
                    backgroundColor: 'rgba(52, 152, 219, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: value => new Intl.NumberFormat('vi-VN', {
                                style: 'currency',
                                currency: 'VND'
                            }).format(value)
                        }
                    }
                }
            }
        });

        const orderStatusChart = new Chart(document.getElementById('orderStatusChart'), {
            type: 'doughnut',
            data: {
                labels: statusLabels,
                datasets: [{
                    data: statusValues,
                    backgroundColor: [
                        '#3498db',
                        '#2ecc71',
                        '#e74c3c',
                        '#f1c40f',
                        '#9b59b6'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right'
                    }
                }
            }
        });

        const topProductsChart = new Chart(document.getElementById('topProductsChart'), {
            type: 'bar',
            data: {
                labels: productLabels,
                datasets: [{
                    label: 'Số lượng bán',
                    data: productValues,
                    backgroundColor: '#3498db'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    });
</script>

<?php
$content = ob_get_clean();
include('layoutadmin.php');
?>
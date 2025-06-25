<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin Panel</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: #f8f9fa;
            display: flex;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            color: white;
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 2rem 1.5rem;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-header h2 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .sidebar-header p {
            opacity: 0.8;
            font-size: 0.9rem;
        }

        .sidebar-menu {
            padding: 1rem 0;
        }

        .menu-item {
            padding: 0.8rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            color: white;
        }

        .menu-item:hover {
            background: rgba(255,255,255,0.1);
            transform: translateX(5px);
        }

        .menu-item.active {
            background: rgba(255,255,255,0.2);
            border-left: 4px solid #fff;
        }

        .menu-item i {
            width: 20px;
        }

        /* Main Content */
        .main-content {
            margin-left: 250px;
            flex: 1;
            min-height: 100vh;
        }

        .top-header {
            background: white;
            padding: 1rem 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-title {
            font-size: 1.5rem;
            color: #333;
            font-weight: 600;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(45deg, #667eea, #764ba2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }

        .logout-btn {
            background: #dc3545;
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.9rem;
            transition: background 0.3s ease;
        }

        .logout-btn:hover {
            background: #c82333;
        }

        /* Dashboard Content */
        .dashboard-content {
            padding: 2rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }

        .stat-info h3 {
            font-size: 2rem;
            color: #333;
            margin-bottom: 0.2rem;
        }

        .stat-info p {
            color: #666;
            font-size: 0.9rem;
        }

        .bg-primary { background: linear-gradient(45deg, #667eea, #764ba2); }
        .bg-success { background: linear-gradient(45deg, #28a745, #20c997); }
        .bg-warning { background: linear-gradient(45deg, #ffc107, #fd7e14); }
        .bg-danger { background: linear-gradient(45deg, #dc3545, #e83e8c); }

        /* Content Cards */
        .content-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 1.5rem;
        }

        .content-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .card-header {
            padding: 1.5rem;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-header h3 {
            color: #333;
            font-size: 1.2rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        .recent-activity {
            list-style: none;
        }

        .activity-item {
            padding: 1rem 0;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.9rem;
        }

        .activity-info h4 {
            color: #333;
            font-size: 0.9rem;
            margin-bottom: 0.2rem;
        }

        .activity-info p {
            color: #666;
            font-size: 0.8rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .content-grid {
                grid-template-columns: 1fr;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }
        }

        .dark-mode {
            background: #181a1b !important;
            color: #e8e6e3 !important;
        }
        .dark-mode .sidebar {
            background: linear-gradient(135deg, #232526 0%, #414345 100%) !important;
            color: #e8e6e3 !important;
        }
        .dark-mode .main-content, .dark-mode .top-header, .dark-mode .stat-card, .dark-mode .content-card {
            background: #232526 !important;
            color: #e8e6e3 !important;
        }
        .dark-mode .sidebar-menu .menu-item:hover, .dark-mode .sidebar-menu .menu-item.active {
            background: rgba(255,255,255,0.08) !important;
            color: #fff !important;
        }
        .dark-mode .logout-btn {
            background: #b52a37 !important;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h2><i class="fas fa-tachometer-alt"></i></h2>
            <h2>Admin Panel</h2>
            <p>Welcome back!</p>
        </div>
        <form action="users.php" method="get" style="padding: 1rem 1.5rem 0 1.5rem;">
            <input type="text" name="search" placeholder="Cari user..." style="width: 100%; padding: 0.5rem 1rem; border-radius: 20px; border: none; outline: none; margin-bottom: 1rem;">
        </form>
        <div class="sidebar-menu">
            <a href="#" class="menu-item active">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <a href="users.php" class="menu-item">
                <i class="fas fa-users"></i>
                <span>Users</span>
            </a>
            <a href="settings.php" class="menu-item">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
            </a>
            <a href="reports.php" class="menu-item">
                <i class="fas fa-file-alt"></i>
                <span>Reports</span>
            </a>
            <a href="notifications.php" class="menu-item">
                <i class="fas fa-bell"></i>
                <span>Notifications</span>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Header -->
        <div class="top-header">
            <div class="page-title">Dashboard</div>
            <div class="user-info">
                <div class="user-avatar">
                    <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                </div>
                <span>
                    <a href="profile.php" style="color:inherit;text-decoration:underline;cursor:pointer;">
                        <?php echo htmlspecialchars($_SESSION['username']); ?>
                    </a>
                </span>
                <button id="darkModeToggle" style="background:none;border:none;cursor:pointer;font-size:1.3rem;" title="Toggle Dark Mode">🌙</button>
                <a href="logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>

        <!-- Dashboard Content -->
        <div class="dashboard-content">
            <!-- Statistics Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon bg-primary">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-info">
                        <h3>1,234</h3>
                        <p>Total Users</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon bg-success">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="stat-info">
                        <h3>89%</h3>
                        <p>Growth Rate</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon bg-warning">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="stat-info">
                        <h3>567</h3>
                        <p>Total Orders</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon bg-danger">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="stat-info">
                        <h3>$12,345</h3>
                        <p>Revenue</p>
                    </div>
                </div>
            </div>

            <!-- Content Grid -->
            <div class="content-grid">
                <!-- Main Content Card -->
                <div class="content-card">
                    <div class="card-header">
                        <h3>Welcome to Your Dashboard</h3>
                        <i class="fas fa-chart-area"></i>
                    </div>
                    <div class="card-body">
                        <p>Hello <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>! Welcome to your admin dashboard. Here you can manage your application, view statistics, and monitor your system's performance.</p>
                        <br>
                        <p>This dashboard provides you with:</p>
                        <ul style="margin-left: 1.5rem; margin-top: 0.5rem;">
                            <li>Real-time statistics and analytics</li>
                            <li>User management capabilities</li>
                            <li>System monitoring tools</li>
                            <li>Quick access to important features</li>
                        </ul>
                        <br>
                        <p>Use the sidebar menu to navigate through different sections of your admin panel.</p>
                    </div>
                </div>

                <!-- Recent Activity Card -->
                <div class="content-card">
                    <div class="card-header">
                        <h3>Recent Activity</h3>
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="card-body">
                        <ul class="recent-activity">
                            <li class="activity-item">
                                <div class="activity-icon bg-primary">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                                <div class="activity-info">
                                    <h4>New user registered</h4>
                                    <p>2 minutes ago</p>
                                </div>
                            </li>
                            <li class="activity-item">
                                <div class="activity-icon bg-success">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div class="activity-info">
                                    <h4>System backup completed</h4>
                                    <p>15 minutes ago</p>
                                </div>
                            </li>
                            <li class="activity-item">
                                <div class="activity-icon bg-warning">
                                    <i class="fas fa-exclamation"></i>
                                </div>
                                <div class="activity-info">
                                    <h4>Server maintenance scheduled</h4>
                                    <p>1 hour ago</p>
                                </div>
                            </li>
                            <li class="activity-item">
                                <div class="activity-icon bg-danger">
                                    <i class="fas fa-bug"></i>
                                </div>
                                <div class="activity-info">
                                    <h4>Bug report submitted</h4>
                                    <p>2 hours ago</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Add some interactivity
        document.addEventListener('DOMContentLoaded', function() {
            // Menu item click handling
            const menuItems = document.querySelectorAll('.menu-item');
            menuItems.forEach(item => {
                item.addEventListener('click', function() {
                    menuItems.forEach(i => i.classList.remove('active'));
                    this.classList.add('active');
                });
            });

            // Add some animation to stat cards
            const statCards = document.querySelectorAll('.stat-card');
            statCards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
                card.style.animation = 'fadeInUp 0.6s ease forwards';
            });
        });

        // Add CSS animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        `;
        document.head.appendChild(style);

        // Dark mode toggle
        const toggleBtn = document.getElementById('darkModeToggle');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const setDark = (on) => {
            document.body.classList.toggle('dark-mode', on);
            localStorage.setItem('darkMode', on ? '1' : '0');
            toggleBtn.textContent = on ? '☀️' : '🌙';
        };
        const initDark = () => {
            const saved = localStorage.getItem('darkMode');
            if (saved === null) setDark(prefersDark);
            else setDark(saved === '1');
        };
        toggleBtn.onclick = () => setDark(!document.body.classList.contains('dark-mode'));
        initDark();
    </script>
</body>
</html> 
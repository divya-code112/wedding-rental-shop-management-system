<!-- ADMIN SIDEBAR -->
<style>
    .sidebar {
        width: 250px;
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        background: #1e1e2f;
        color: #fff;
        padding-top: 20px;
        transition: 0.3s;
        z-index: 100;
    }
    .sidebar a {
        display: block;
        padding: 12px 20px;
        color: #ddd;
        text-decoration: none;
        font-size: 15px;
        font-weight: 500;
        transition: 0.2s;
    }
    .sidebar a:hover {
        background: #34344e;
        color: #fff;
    }
    .sidebar .active {
        background: #5755d9;
        color: #fff;
    }

    /* for content area */
    .content {
        margin-left: 260px;
        padding: 20px;
        transition: 0.3s;
    }

    /* TOGGLE BUTTON */
    #toggleBtn {
        position: absolute;
        left: 260px;
        top: 15px;
        font-size: 25px;
        cursor: pointer;
        color: #444;
    }

    .collapsed {
        width: 70px !important;
    }
    .collapsed a span {
        display: none;
    }
    .collapsed + #toggleBtn {
        left: 80px !important;
    }
    @media(max-width: 900px) {
        .sidebar { left: -260px; }
        .sidebar.open { left: 0; }
        .content { margin-left: 0; }
        #toggleBtn { left: 20px; }
    }
</style>

<div class="sidebar" id="sidebar">
    <h4 class="text-center mb-4">Admin Panel</h4>

    <a href="dashboard.php" class="active"><i class="bi bi-speedometer2"></i> <span>Dashboard</span></a>
    <a href="category.php"><i class="bi bi-list-task"></i> <span>Categories</span></a>
    <a href="subcategory.php"><i class="bi bi-diagram-2"></i> <span>Subcategories</span></a>
    <a href="type.php"><i class="bi bi-tag"></i> <span>Types</span></a>
    <a href="products.php"><i class="bi bi-bag"></i> <span>Products</span></a>
    <a href="orders.php"><i class="bi bi-cart-check"></i> <span>Orders</span></a>
    <a href="payments.php"><i class="bi bi-credit-card"></i> <span>Payments</span></a>
    <a href="returns.php"><i class="bi bi-arrow-return-left"></i> <span>Returns</span></a>
    <a href="feedback.php"><i class="bi bi-chat-dots"></i> <span>Feedback</span></a>
    <a href="users.php"><i class="bi bi-people"></i> <span>Users</span></a>
    <a href="reports.php"><i class="bi bi-file-earmark-bar-graph"></i> <span>Reports</span></a>
    <a href="logout.php"><i class="bi bi-box-arrow-left"></i> <span>Logout</span></a>
</div>

<i class="bi bi-list" id="toggleBtn"></i>

<script>
    let sidebar = document.getElementById("sidebar");
    let toggleBtn = document.getElementById("toggleBtn");

    toggleBtn.onclick = () => {
        if (window.innerWidth > 900) {
            sidebar.classList.toggle("collapsed");
        } else {
            sidebar.classList.toggle("open");
        }
    };
</script>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

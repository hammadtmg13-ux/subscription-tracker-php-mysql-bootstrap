<?php
require 'config.php';

// Fetch all subscriptions, soonest renewal first
$stmt = $pdo->query("SELECT * FROM subscriptions ORDER BY renewal_date ASC");
$subs = $stmt->fetchAll();

// Stats
$activeSubs = 0;
$monthlyTotal = 0;
foreach ($subs as $sub) {
    if ($sub['active']) {
        $activeSubs++;
        $monthlyTotal += ($sub['billing_cycle'] === 'Yearly') ? $sub['cost'] / 12 : $sub['cost'];
    }
}
$yearlyTotal = $monthlyTotal * 12;

// Next renewal among active subscriptions
$nextRenewal = null;
$daysLeft = null;
foreach ($subs as $sub) {
    if ($sub['active']) {
        $nextRenewal = $sub;
        $daysLeft = (int)((strtotime($sub['renewal_date']) - strtotime(date('Y-m-d'))) / 86400);
        break;
    }
}

// Category list for filters
$allCategories = $pdo->query("SELECT DISTINCT category FROM subscriptions ORDER BY category")->fetchAll(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SubTrack - Simple Subscription Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="index.php">&#128179; SubTrack</a>
        <div class="d-flex align-items-center gap-2">
            <span class="avatar-circle">HK</span>
            <span class="text-white fw-semibold">Hammad Khan</span>
        </div>
    </div>
</nav>

<div class="container py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">My Subscriptions</h2>
            <p class="text-muted mb-0">Every subscription in one place, no surprise renewals.</p>
        </div>
        <a href="add_subscription.php" class="btn btn-primary mt-2 mt-md-0">+ New Subscription</a>
    </div>

    <!-- Stats -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card stat-card p-3 text-center">
                <div class="stat-value text-primary"><?php echo $activeSubs; ?></div>
                <div class="stat-label">Active Subscriptions</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card p-3 text-center">
                <div class="stat-value text-success">$<?php echo number_format($monthlyTotal, 2); ?></div>
                <div class="stat-label">Cost Per Month</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card p-3 text-center">
                <div class="stat-value text-info">$<?php echo number_format($yearlyTotal, 2); ?></div>
                <div class="stat-label">Cost Per Year</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card p-3 text-center">
                <div class="stat-value text-warning"><?php echo $nextRenewal ? $daysLeft . ' days' : '-'; ?></div>
                <div class="stat-label">Next Renewal: <?php echo $nextRenewal ? htmlspecialchars($nextRenewal['name']) : 'None'; ?></div>
            </div>
        </div>
    </div>

    <!-- Search and filters -->
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <input type="text" id="searchInput" class="form-control" placeholder="Search subscriptions by name...">
        </div>
        <div class="col-md-6">
            <div class="btn-group flex-wrap" role="group">
                <button type="button" class="btn btn-outline-primary active" data-filter="All">All</button>
                <?php foreach ($allCategories as $category): ?>
                    <button type="button" class="btn btn-outline-primary" data-filter="<?php echo htmlspecialchars($category); ?>"><?php echo htmlspecialchars($category); ?></button>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Subscriptions grid -->
    <div class="row g-3" id="subsGrid">
        <?php if (empty($subs)): ?>
            <div class="col-12">
                <div class="alert alert-info text-center">No subscriptions yet. Click "New Subscription" to add your first one.</div>
            </div>
        <?php endif; ?>
        <?php foreach ($subs as $sub):
            $days = (int)((strtotime($sub['renewal_date']) - strtotime(date('Y-m-d'))) / 86400);
            $initials = strtoupper(substr($sub['name'], 0, 2));
        ?>
            <div class="col-md-6 col-lg-4 sub-item" data-category="<?php echo htmlspecialchars($sub['category']); ?>">
                <div class="card sub-card <?php echo $sub['active'] ? '' : 'paused'; ?>">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <span class="sub-logo"><?php echo $initials; ?></span>
                            <div class="flex-grow-1">
                                <h5 class="card-title mb-0"><?php echo htmlspecialchars($sub['name']); ?></h5>
                                <small class="text-muted"><?php echo htmlspecialchars($sub['category']); ?> &middot; <?php echo htmlspecialchars($sub['billing_cycle']); ?></small>
                            </div>
                            <?php if (!$sub['active']): ?>
                                <span class="badge bg-secondary">Paused</span>
                            <?php endif; ?>
                        </div>
                        <div class="d-flex justify-content-between align-items-end mb-2">
                            <div>
                                <div class="cost-value">$<?php echo number_format($sub['cost'], 2); ?><small class="text-muted fs-6">/mo</small></div>
                            </div>
                            <div class="text-end">
                                <small class="text-muted d-block">Renews <?php echo date('M d, Y', strtotime($sub['renewal_date'])); ?></small>
                                <small class="<?php echo ($sub['active'] && $days <= 14) ? 'renewal-soon' : 'text-muted'; ?>"><?php echo $days >= 0 ? 'in ' . $days . ' days' : 'overdue by ' . abs($days) . ' days'; ?></small>
                            </div>
                        </div>
                        <div class="d-flex gap-2 mt-2">
                            <a href="toggle_active.php?id=<?php echo $sub['id']; ?>" class="btn btn-sm btn-outline-warning"><?php echo $sub['active'] ? 'Pause' : 'Resume'; ?></a>
                            <a href="edit_subscription.php?id=<?php echo $sub['id']; ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                            <a href="delete_subscription.php?id=<?php echo $sub['id']; ?>" class="btn btn-sm btn-outline-danger delete-sub">Delete</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div id="emptyState" class="alert alert-info text-center mt-3" style="display: none;">No subscriptions match your search or filter.</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/app.js"></script>
</body>
</html>

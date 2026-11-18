<?php
require 'config.php';

$categories = ['Entertainment', 'Music', 'Design', 'Storage', 'Productivity', 'Gaming', 'Other'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $category = $_POST['category'];
    $cost = (float)$_POST['cost'];
    $billingCycle = $_POST['billing_cycle'];
    $renewalDate = $_POST['renewal_date'];

    $stmt = $pdo->prepare("INSERT INTO subscriptions (name, category, cost, billing_cycle, renewal_date) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$name, $category, $cost, $billingCycle, $renewalDate]);
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Subscription - SubTrack</title>
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
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card form-card p-4">
                <h3 class="fw-bold mb-3">Add a New Subscription</h3>
                <form method="POST" action="add_subscription.php">
                    <div class="mb-3">
                        <label for="name" class="form-label">Service Name</label>
                        <input type="text" class="form-control" id="name" name="name" required maxlength="100" placeholder="Netflix">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select class="form-select" id="category" name="category">
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo $category; ?>"><?php echo $category; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="billing_cycle" class="form-label">Billing Cycle</label>
                            <select class="form-select" id="billing_cycle" name="billing_cycle">
                                <option value="Monthly">Monthly</option>
                                <option value="Yearly">Yearly</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="cost" class="form-label">Cost ($)</label>
                            <input type="number" step="0.01" min="0" class="form-control" id="cost" name="cost" required placeholder="9.99">
                            <small id="monthlyPreview" class="text-muted">About $0.00 per month</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="renewal_date" class="form-label">Next Renewal Date</label>
                            <input type="date" class="form-control" id="renewal_date" name="renewal_date" required>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Save Subscription</button>
                        <a href="index.php" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/app.js"></script>
</body>
</html>

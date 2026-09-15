<?php
require_once 'includes/functions.php';
require_once 'config/db.php';
requireLogin();


$stmt = $pdo->query("
    SELECT
        COUNT(*) AS total,
        SUM(status = 'New') AS new_count,
        SUM(status = 'Contacted') AS contacted_count,
        SUM(status = 'Converted') AS converted_count,
        SUM(status = 'Lost') AS lost_count
    FROM leads
");
$counts = $stmt->fetch();


$recent = $pdo->query("SELECT id, name, mobile, status, created_at FROM leads ORDER BY created_at DESC LIMIT 5")->fetchAll();

$pageTitle = 'Dashboard';
require_once 'includes/header.php';
?>

<h1>Dashboard</h1>
<p style="color:#64748b;margin-top:-8px;">Overview of your leads pipeline.</p>

<div class="stats-grid">
    <div class="stat-card total">
        <div class="label">Total Leads</div>
        <div class="value"><?= (int)$counts['total'] ?></div>
    </div>
    <div class="stat-card new">
        <div class="label">New</div>
        <div class="value"><?= (int)$counts['new_count'] ?></div>
    </div>
    <div class="stat-card contacted">
        <div class="label">Contacted</div>
        <div class="value"><?= (int)$counts['contacted_count'] ?></div>
    </div>
    <div class="stat-card converted">
        <div class="label">Converted</div>
        <div class="value"><?= (int)$counts['converted_count'] ?></div>
    </div>
</div>

<div class="card">
    <div class="actions-row" style="justify-content:space-between;align-items:center;">
        <h2 style="margin:0;">Recent Leads</h2>
        <a href="leads_list.php" class="btn btn-secondary btn-sm">View all leads</a>
    </div>

    <?php if (empty($recent)): ?>
        <p style="color:#94a3b8;">No leads yet. <a href="leads_add.php">Add your first lead</a>.</p>
    <?php else: ?>
        <div class="table-wrapper" style="box-shadow:none;">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Mobile</th>
                        <th>Status</th>
                        <th>Added On</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($recent as $lead): ?>
                    <tr>
                        <td><?= h($lead['name']) ?></td>
                        <td><?= h($lead['mobile']) ?></td>
                        <td><span class="badge badge-<?= strtolower(h($lead['status'])) ?>"><?= h($lead['status']) ?></span></td>
                        <td><?= h(date('d M Y', strtotime($lead['created_at']))) ?></td>
                        <td><a href="leads_view.php?id=<?= (int)$lead['id'] ?>">View</a></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>

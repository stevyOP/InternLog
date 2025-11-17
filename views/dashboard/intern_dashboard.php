<?php
$page_title = 'Intern Dashboard';
include 'views/layouts/header.php';
?>

<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="h3 mb-0">
                <i class="fas fa-tachometer-alt me-2"></i>Intern Dashboard
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </nav>
        </div>
        <div class="col-auto">
            <span class="badge bg-info fs-6">
                <i class="fas fa-user-graduate me-1"></i>Intern
            </span>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-0"><?= $logs_this_week ?></h3>
                    <p class="mb-0">Logs This Week</p>
                </div>
                <div class="stats-icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-0"><?= $pending_logs ?></h3>
                    <p class="mb-0">Pending Logs</p>
                </div>
                <div class="stats-icon">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-0"><?= count($recent_evaluations) ?></h3>
                    <p class="mb-0">Evaluations</p>
                </div>
                <div class="stats-icon">
                    <i class="fas fa-star"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-0"><?= $profile ? getDepartmentName($profile['department']) : 'N/A' ?></h3>
                    <p class="mb-0">Department</p>
                </div>
                <div class="stats-icon">
                    <i class="fas fa-building"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Logs -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-clipboard-list me-2"></i>Recent Daily Logs
                </h5>
            </div>
            <div class="card-body">
                <?php if (empty($recent_logs)): ?>
                    <p class="text-muted text-center py-3">No logs submitted yet.</p>
                    <div class="text-center">
                        <a href="index.php?page=intern&action=addLog" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Add Your First Log
                        </a>
                    </div>
                <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($recent_logs as $log): ?>
                            <div class="list-group-item border-0 px-0">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1"><?= formatDate($log['date']) ?></h6>
                                        <p class="mb-1 text-muted small">
                                            <?= htmlspecialchars(substr($log['task_description'], 0, 80)) ?>...
                                        </p>
                                        <small class="text-muted">
                                            Skills: <?= htmlspecialchars($log['skills'] ?? 'N/A') ?> • 
                                            Status: <span class="badge bg-<?= $log['status'] === 'approved' ? 'success' : ($log['status'] === 'pending' ? 'warning' : 'danger') ?>">
                                                <?= ucfirst($log['status']) ?>
                                            </span>
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        <?php if ($log['status'] === 'pending' && strtotime($log['created_at']) > (time() - 86400)): ?>
                                            <a href="index.php?page=intern&action=editLog&id=<?= $log['id'] ?>" 
                                               class="btn btn-sm btn-outline-primary me-1">
                                                Edit
                                            </a>
                                        <?php endif; ?>
                                        <a href="index.php?page=intern&action=viewLog&id=<?= $log['id'] ?>" 
                                           class="btn btn-sm btn-outline-info">
                                            View
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Recent Evaluations -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-star me-2"></i>Recent Evaluations
                </h5>
            </div>
            <div class="card-body">
                <?php if (empty($recent_evaluations)): ?>
                    <p class="text-muted text-center py-3">No evaluations yet.</p>
                <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($recent_evaluations as $evaluation): ?>
                            <div class="list-group-item border-0 px-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">Week <?= $evaluation['week_no'] ?> Evaluation</h6>
                                        <p class="mb-1 text-muted small">
                                            Technical: <?= $evaluation['rating_technical'] ?>/5 • 
                                            Soft Skills: <?= $evaluation['rating_softskills'] ?>/5
                                        </p>
                                        <?php if ($evaluation['comments']): ?>
                                            <small class="text-muted">
                                                "<?= htmlspecialchars(substr($evaluation['comments'], 0, 60)) ?>..."
                                            </small>
                                        <?php endif; ?>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-<?= ($evaluation['rating_technical'] + $evaluation['rating_softskills']) / 2 >= 4 ? 'success' : (($evaluation['rating_technical'] + $evaluation['rating_softskills']) / 2 >= 3 ? 'warning' : 'danger') ?>">
                                            <?= number_format(($evaluation['rating_technical'] + $evaluation['rating_softskills']) / 2, 1) ?>/5
                                        </span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php if ($profile): ?>
<div class="row mt-4">
    <!-- Profile Information -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user me-2"></i>My Profile
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <td width="40%"><strong>Name:</strong></td>
                                <td><?= htmlspecialchars($_SESSION['name']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Email:</strong></td>
                                <td><?= htmlspecialchars($_SESSION['email']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Department:</strong></td>
                                <td><span class="badge bg-info"><?= getDepartmentName($profile['department']) ?></span></td>
                            </tr>
                            <tr>
                                <td><strong>Supervisor:</strong></td>
                                <td><?= htmlspecialchars($profile['supervisor_name']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Start Date:</strong></td>
                                <td><?= formatDate($profile['start_date']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>End Date:</strong></td>
                                <td><?= formatDate($profile['end_date']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>
                                    <span class="badge bg-<?= $profile['status'] === 'active' ? 'success' : 'secondary' ?>">
                                        <?= ucfirst($profile['status']) ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Duration:</strong></td>
                                <td>
                                    <?php
                                    $start = new DateTime($profile['start_date']);
                                    $end = new DateTime($profile['end_date']);
                                    $interval = $start->diff($end);
                                    echo $interval->format('%m months, %d days');
                                    ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Summary -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-line me-2"></i>Performance Summary
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($recent_evaluations)): ?>
                    <?php
                    $total_evals = count($recent_evaluations);
                    $avg_technical = array_sum(array_column($recent_evaluations, 'rating_technical')) / $total_evals;
                    $avg_softskills = array_sum(array_column($recent_evaluations, 'rating_softskills')) / $total_evals;
                    $avg_overall = ($avg_technical + $avg_softskills) / 2;
                    
                    $total_logs_count = count($recent_logs);
                    $approved_logs = count(array_filter($recent_logs, function($log) {
                        return $log['status'] === 'approved';
                    }));
                    $approval_rate = $total_logs_count > 0 ? ($approved_logs / $total_logs_count) * 100 : 0;
                    ?>
                    <div class="row text-center mb-3">
                        <div class="col-4">
                            <h4 class="text-primary mb-1"><?= number_format($avg_technical, 1) ?></h4>
                            <small class="text-muted">Technical</small>
                            <div class="progress mt-2" style="height: 8px;">
                                <div class="progress-bar bg-primary" style="width: <?= ($avg_technical/5)*100 ?>%"></div>
                            </div>
                        </div>
                        <div class="col-4">
                            <h4 class="text-success mb-1"><?= number_format($avg_softskills, 1) ?></h4>
                            <small class="text-muted">Soft Skills</small>
                            <div class="progress mt-2" style="height: 8px;">
                                <div class="progress-bar bg-success" style="width: <?= ($avg_softskills/5)*100 ?>%"></div>
                            </div>
                        </div>
                        <div class="col-4">
                            <h4 class="text-info mb-1"><?= number_format($avg_overall, 1) ?></h4>
                            <small class="text-muted">Overall</small>
                            <div class="progress mt-2" style="height: 8px;">
                                <div class="progress-bar bg-info" style="width: <?= ($avg_overall/5)*100 ?>%"></div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="fas fa-trophy text-warning me-2"></i>Performance Level</span>
                            <strong>
                                <span class="badge bg-<?= $avg_overall >= 4 ? 'success' : ($avg_overall >= 3 ? 'info' : 'warning') ?>">
                                    <?php
                                    if ($avg_overall >= 4.5) echo 'Excellent';
                                    elseif ($avg_overall >= 3.5) echo 'Very Good';
                                    elseif ($avg_overall >= 2.5) echo 'Good';
                                    else echo 'Needs Improvement';
                                    ?>
                                </span>
                            </strong>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="fas fa-check-circle text-success me-2"></i>Log Approval Rate</span>
                            <strong><?= round($approval_rate) ?>%</strong>
                        </div>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar bg-<?= $approval_rate >= 80 ? 'success' : ($approval_rate >= 60 ? 'warning' : 'danger') ?>" 
                                 style="width: <?= $approval_rate ?>%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span><i class="fas fa-clipboard-check text-info me-2"></i>Total Evaluations</span>
                            <strong><?= $total_evals ?></strong>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No performance data available yet.</p>
                        <small class="text-muted">Your supervisor will evaluate your performance weekly.</small>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="row mt-4">
    <!-- Quick Actions -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-bolt me-2"></i>Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <a href="index.php?page=intern&action=addLog" class="btn btn-outline-primary w-100 mb-3">
                            <i class="fas fa-plus me-2"></i>Add Daily Log
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="index.php?page=intern&action=logs" class="btn btn-outline-success w-100 mb-3">
                            <i class="fas fa-clipboard-list me-2"></i>View All Logs
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="index.php?page=intern&action=weeklyReport" class="btn btn-outline-info w-100 mb-3">
                            <i class="fas fa-file-pdf me-2"></i>Weekly Report
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="index.php?page=intern&action=evaluations" class="btn btn-outline-warning w-100 mb-3">
                            <i class="fas fa-star me-2"></i>My Evaluations
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>



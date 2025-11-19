<?php
$page_title = 'My Overview';
include 'views/layouts/header.php';

// Extract variables for use in the view and set defaults
extract($data ?? []);
$recent_logs = $recent_logs ?? [];
$recent_evaluations = $recent_evaluations ?? [];
$profile = $profile ?? null;
$skills_summary = $skills_summary ?? [];
?>

<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="h3 mb-0">
                <i class="fas fa-chart-line me-2"></i>My Overview
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php?page=dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active">Overview</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<!-- Skills Summary & Recent Evaluations -->
<div class="row mb-4">
    <!-- Skills Summary -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-tools me-2"></i>Skills Summary
                </h5>
            </div>
            <div class="card-body">
                <?php if (empty($skills_summary)): ?>
                    <p class="text-muted text-center py-3">No skills recorded yet.</p>
                    <small class="text-muted d-block text-center">Add skills to your daily logs to track your learning progress.</small>
                <?php else: ?>
                    <div class="row">
                        <?php foreach ($skills_summary as $skill => $count): ?>
                            <div class="col-md-6 mb-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-truncate" style="max-width: 150px;" title="<?= htmlspecialchars($skill) ?>">
                                        <i class="fas fa-check-circle text-success me-1"></i>
                                        <?= htmlspecialchars($skill) ?>
                                    </span>
                                    <span class="badge bg-primary"><?= $count ?></span>
                                </div>
                                <div class="progress mt-1" style="height: 5px;">
                                    <div class="progress-bar bg-primary" style="width: <?= min(100, ($count / max($skills_summary)) * 100) ?>%"></div>
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
                    <div class="text-center mt-3">
                        <a href="index.php?page=intern&action=evaluations" class="btn btn-sm btn-outline-primary">
                            View All Evaluations <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php if ($profile): ?>
<div class="row">
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
                <div class="text-center mt-3">
                    <a href="index.php?page=profile" class="btn btn-outline-primary">
                        <i class="fas fa-edit me-2"></i>Edit Profile
                    </a>
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

<?php include 'views/layouts/footer.php'; ?>

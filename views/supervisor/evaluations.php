<?php
$page_title = 'Evaluations';
include 'views/layouts/header.php';
?>

<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="h3 mb-0">
                <i class="fas fa-star me-2"></i>Weekly Evaluations
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php?page=dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active">Evaluations</li>
                </ol>
            </nav>
        </div>
        <div class="col-auto">
            <a href="index.php?page=supervisor&action=addEvaluation" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add Evaluation
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-list me-2"></i>All Evaluations
        </h5>
    </div>
    <div class="card-body">
        <?php if (empty($evaluations)): ?>
            <div class="text-center py-5">
                <i class="fas fa-star fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No evaluations submitted yet</h5>
                <p class="text-muted">Start evaluating your interns' weekly performance.</p>
                <a href="index.php?page=supervisor&action=addEvaluation" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add First Evaluation
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover data-table">
                    <thead>
                        <tr>
                            <th>Intern Name</th>
                            <th>Week</th>
                            <th>Technical Skills</th>
                            <th>Soft Skills</th>
                            <th>Overall Rating</th>
                            <th>Comments</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($evaluations as $evaluation): ?>
                            <?php 
                            $overall = ($evaluation['rating_technical'] + $evaluation['rating_softskills']) / 2;
                            ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($evaluation['intern_name']) ?></strong>
                                    <br>
                                    <small class="text-muted"><?= htmlspecialchars($evaluation['intern_email']) ?></small>
                                </td>
                                <td>
                                    <span class="badge bg-primary">Week <?= $evaluation['week_no'] ?></span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-<?= $evaluation['rating_technical'] >= 4 ? 'success' : ($evaluation['rating_technical'] >= 3 ? 'warning' : 'danger') ?> me-2">
                                            <?= $evaluation['rating_technical'] ?>/5
                                        </span>
                                        <div class="progress" style="width: 60px; height: 8px;">
                                            <div class="progress-bar bg-<?= $evaluation['rating_technical'] >= 4 ? 'success' : ($evaluation['rating_technical'] >= 3 ? 'warning' : 'danger') ?>" 
                                                 style="width: <?= ($evaluation['rating_technical'] / 5) * 100 ?>%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-<?= $evaluation['rating_softskills'] >= 4 ? 'success' : ($evaluation['rating_softskills'] >= 3 ? 'warning' : 'danger') ?> me-2">
                                            <?= $evaluation['rating_softskills'] ?>/5
                                        </span>
                                        <div class="progress" style="width: 60px; height: 8px;">
                                            <div class="progress-bar bg-<?= $evaluation['rating_softskills'] >= 4 ? 'success' : ($evaluation['rating_softskills'] >= 3 ? 'warning' : 'danger') ?>" 
                                                 style="width: <?= ($evaluation['rating_softskills'] / 5) * 100 ?>%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-<?= $overall >= 4 ? 'success' : ($overall >= 3 ? 'info' : 'warning') ?> fs-6">
                                        <?= number_format($overall, 1) ?>/5
                                    </span>
                                </td>
                                <td>
                                    <?php if ($evaluation['comments']): ?>
                                        <div style="max-width: 200px;">
                                            <?= htmlspecialchars(substr($evaluation['comments'], 0, 60)) ?>
                                            <?= strlen($evaluation['comments']) > 60 ? '...' : '' ?>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted">No comments</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <?= formatDate($evaluation['created_at'], 'M j, Y') ?>
                                    </small>
                                </td>
                                <td>
                                    <a href="index.php?page=supervisor&action=viewEvaluation&id=<?= $evaluation['id'] ?>" 
                                       class="btn btn-sm btn-outline-info" title="View">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Summary Statistics -->
<?php if (!empty($evaluations)): ?>
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-pie me-2"></i>Rating Distribution
                </h5>
            </div>
            <div class="card-body">
                <?php
                $total = count($evaluations);
                $excellent = count(array_filter($evaluations, function($e) {
                    return (($e['rating_technical'] + $e['rating_softskills']) / 2) >= 4.5;
                }));
                $good = count(array_filter($evaluations, function($e) {
                    $avg = ($e['rating_technical'] + $e['rating_softskills']) / 2;
                    return $avg >= 3.5 && $avg < 4.5;
                }));
                $satisfactory = count(array_filter($evaluations, function($e) {
                    $avg = ($e['rating_technical'] + $e['rating_softskills']) / 2;
                    return $avg >= 2.5 && $avg < 3.5;
                }));
                $needs_improvement = $total - $excellent - $good - $satisfactory;
                ?>
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Excellent (4.5-5.0)</span>
                        <strong><?= $excellent ?> (<?= round(($excellent/$total)*100) ?>%)</strong>
                    </div>
                    <div class="progress" style="height: 20px;">
                        <div class="progress-bar bg-success" style="width: <?= ($excellent/$total)*100 ?>%"></div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Good (3.5-4.4)</span>
                        <strong><?= $good ?> (<?= round(($good/$total)*100) ?>%)</strong>
                    </div>
                    <div class="progress" style="height: 20px;">
                        <div class="progress-bar bg-info" style="width: <?= ($good/$total)*100 ?>%"></div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Satisfactory (2.5-3.4)</span>
                        <strong><?= $satisfactory ?> (<?= round(($satisfactory/$total)*100) ?>%)</strong>
                    </div>
                    <div class="progress" style="height: 20px;">
                        <div class="progress-bar bg-warning" style="width: <?= ($satisfactory/$total)*100 ?>%"></div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Needs Improvement (&lt;2.5)</span>
                        <strong><?= $needs_improvement ?> (<?= round(($needs_improvement/$total)*100) ?>%)</strong>
                    </div>
                    <div class="progress" style="height: 20px;">
                        <div class="progress-bar bg-danger" style="width: <?= ($needs_improvement/$total)*100 ?>%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>Evaluation Summary
                </h5>
            </div>
            <div class="card-body">
                <?php
                $avg_technical = array_sum(array_column($evaluations, 'rating_technical')) / $total;
                $avg_softskills = array_sum(array_column($evaluations, 'rating_softskills')) / $total;
                $avg_overall = ($avg_technical + $avg_softskills) / 2;
                ?>
                <div class="row text-center mb-3">
                    <div class="col-4">
                        <h3 class="text-primary"><?= number_format($avg_technical, 1) ?></h3>
                        <small class="text-muted">Avg Technical</small>
                    </div>
                    <div class="col-4">
                        <h3 class="text-success"><?= number_format($avg_softskills, 1) ?></h3>
                        <small class="text-muted">Avg Soft Skills</small>
                    </div>
                    <div class="col-4">
                        <h3 class="text-info"><?= number_format($avg_overall, 1) ?></h3>
                        <small class="text-muted">Avg Overall</small>
                    </div>
                </div>
                <hr>
                <div class="mt-3">
                    <p><strong>Total Evaluations:</strong> <?= $total ?></p>
                    <p><strong>Unique Interns:</strong> <?= count(array_unique(array_column($evaluations, 'intern_id'))) ?></p>
                    <p><strong>Latest Evaluation:</strong> <?= formatDate($evaluations[0]['created_at'], 'M j, Y') ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php include 'views/layouts/footer.php'; ?>

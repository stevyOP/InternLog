<?php
$page_title = 'View Evaluation';
include 'views/layouts/header.php';

$overall = ($evaluation['rating_technical'] + $evaluation['rating_softskills']) / 2;
?>

<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="h3 mb-0">
                <i class="fas fa-star me-2"></i>Evaluation Details
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php?page=dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="index.php?page=supervisor&action=evaluations">Evaluations</a></li>
                    <li class="breadcrumb-item active">View</li>
                </ol>
            </nav>
        </div>
        <div class="col-auto">
            <a href="index.php?page=supervisor&action=evaluations" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Evaluations
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-file-alt me-2"></i>Evaluation Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label text-muted">Intern Name</label>
                        <p class="mb-0">
                            <strong><?= htmlspecialchars($evaluation['intern_name']) ?></strong><br>
                            <small class="text-muted"><?= htmlspecialchars($evaluation['intern_email']) ?></small>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Week Number</label>
                        <p class="mb-0">
                            <span class="badge bg-primary fs-6">Week <?= $evaluation['week_no'] ?></span>
                        </p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-4">
                        <label class="form-label text-muted">Technical Skills Rating</label>
                        <div class="d-flex align-items-center">
                            <h3 class="mb-0 me-3 text-primary"><?= $evaluation['rating_technical'] ?>/5</h3>
                            <div class="progress flex-grow-1" style="height: 20px;">
                                <div class="progress-bar bg-primary" 
                                     style="width: <?= ($evaluation['rating_technical'] / 5) * 100 ?>%"></div>
                            </div>
                        </div>
                        <div class="mt-2">
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star <?= $i <= $evaluation['rating_technical'] ? 'text-warning' : 'text-muted' ?>"></i>
                            <?php endfor; ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted">Soft Skills Rating</label>
                        <div class="d-flex align-items-center">
                            <h3 class="mb-0 me-3 text-success"><?= $evaluation['rating_softskills'] ?>/5</h3>
                            <div class="progress flex-grow-1" style="height: 20px;">
                                <div class="progress-bar bg-success" 
                                     style="width: <?= ($evaluation['rating_softskills'] / 5) * 100 ?>%"></div>
                            </div>
                        </div>
                        <div class="mt-2">
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star <?= $i <= $evaluation['rating_softskills'] ? 'text-warning' : 'text-muted' ?>"></i>
                            <?php endfor; ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted">Overall Rating</label>
                        <div class="d-flex align-items-center">
                            <h3 class="mb-0 me-3 text-info"><?= number_format($overall, 1) ?>/5</h3>
                            <div class="progress flex-grow-1" style="height: 20px;">
                                <div class="progress-bar bg-info" 
                                     style="width: <?= ($overall / 5) * 100 ?>%"></div>
                            </div>
                        </div>
                        <div class="mt-2">
                            <span class="badge bg-<?= $overall >= 4.5 ? 'success' : ($overall >= 3.5 ? 'info' : ($overall >= 2.5 ? 'warning' : 'danger')) ?> fs-6">
                                <?php
                                if ($overall >= 4.5) echo 'Excellent';
                                elseif ($overall >= 3.5) echo 'Good';
                                elseif ($overall >= 2.5) echo 'Satisfactory';
                                else echo 'Needs Improvement';
                                ?>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label text-muted">Comments & Feedback</label>
                    <div class="card bg-light">
                        <div class="card-body">
                            <?php if ($evaluation['comments']): ?>
                                <p class="mb-0"><?= nl2br(htmlspecialchars($evaluation['comments'])) ?></p>
                            <?php else: ?>
                                <p class="mb-0 text-muted"><em>No comments provided.</em></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label text-muted">Evaluation Date</label>
                        <p class="mb-0">
                            <i class="fas fa-calendar me-2"></i>
                            <?= formatDate($evaluation['created_at'], 'F j, Y g:i A') ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>Rating Guide
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge bg-success me-2">5</span>
                        <strong>Excellent</strong>
                    </div>
                    <small class="text-muted">Outstanding performance, exceeds expectations</small>
                </div>
                <div class="mb-3">
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge bg-info me-2">4</span>
                        <strong>Very Good</strong>
                    </div>
                    <small class="text-muted">Above average, consistently meets expectations</small>
                </div>
                <div class="mb-3">
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge bg-primary me-2">3</span>
                        <strong>Good</strong>
                    </div>
                    <small class="text-muted">Meets expectations satisfactorily</small>
                </div>
                <div class="mb-3">
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge bg-warning me-2">2</span>
                        <strong>Fair</strong>
                    </div>
                    <small class="text-muted">Below expectations, needs improvement</small>
                </div>
                <div class="mb-3">
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge bg-danger me-2">1</span>
                        <strong>Poor</strong>
                    </div>
                    <small class="text-muted">Significantly below expectations</small>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-line me-2"></i>Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <a href="index.php?page=supervisor&action=logs&intern_id=<?= $evaluation['intern_id'] ?>" 
                   class="btn btn-outline-primary w-100 mb-2">
                    <i class="fas fa-clipboard-list me-2"></i>View Intern's Logs
                </a>
                <a href="index.php?page=supervisor&action=addEvaluation&intern_id=<?= $evaluation['intern_id'] ?>" 
                   class="btn btn-outline-success w-100 mb-2">
                    <i class="fas fa-plus me-2"></i>Add New Evaluation
                </a>
            </div>
        </div>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>

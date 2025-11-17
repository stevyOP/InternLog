<?php
$page_title = 'Performance Reports';
include 'views/layouts/header.php';
?>

<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="h3 mb-0">
                <i class="fas fa-chart-bar me-2"></i>Performance Reports
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php?page=dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active">Reports</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<?php if (empty($interns)): ?>
    <div class="card">
        <div class="card-body">
            <div class="text-center py-5">
                <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No Interns Assigned</h5>
                <p class="text-muted">You don't have any interns to generate reports for.</p>
            </div>
        </div>
    </div>
<?php else: ?>

<!-- Overall Summary -->
<div class="row mb-4">
    <?php
    $total_interns = count($interns);
    $total_logs = array_sum(array_column($interns, 'total_logs'));
    $total_approved = array_sum(array_column($interns, 'approved_logs'));
    $total_pending = array_sum(array_column($interns, 'pending_logs'));
    $total_evaluations = array_sum(array_column($interns, 'total_evaluations'));
    ?>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <h3 class="text-primary"><?= $total_interns ?></h3>
                <p class="mb-0 text-muted">Total Interns</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <h3 class="text-success"><?= $total_logs ?></h3>
                <p class="mb-0 text-muted">Total Logs</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <h3 class="text-warning"><?= $total_pending ?></h3>
                <p class="mb-0 text-muted">Pending Reviews</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <h3 class="text-info"><?= $total_evaluations ?></h3>
                <p class="mb-0 text-muted">Total Evaluations</p>
            </div>
        </div>
    </div>
</div>

<!-- Intern Performance Table -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-users me-2"></i>Intern Performance Overview
        </h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover data-table">
                <thead>
                    <tr>
                        <th>Intern Name</th>
                        <th>Department</th>
                        <th>Status</th>
                        <th>Total Logs</th>
                        <th>Approval Rate</th>
                        <th>Evaluations</th>
                        <th>Avg Technical</th>
                        <th>Avg Soft Skills</th>
                        <th>Overall Rating</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($interns as $intern): ?>
                        <?php
                        $approval_rate = $intern['total_logs'] > 0 
                            ? round(($intern['approved_logs'] / $intern['total_logs']) * 100) 
                            : 0;
                        $avg_overall = $intern['total_evaluations'] > 0 
                            ? ($intern['avg_technical'] + $intern['avg_softskills']) / 2 
                            : 0;
                        ?>
                        <tr>
                            <td>
                                <strong><?= htmlspecialchars($intern['intern_name']) ?></strong>
                                <br>
                                <small class="text-muted"><?= htmlspecialchars($intern['intern_email']) ?></small>
                            </td>
                            <td>
                                <span class="badge bg-info"><?= getDepartmentName($intern['department']) ?></span>
                            </td>
                            <td>
                                <span class="badge bg-<?= $intern['status'] === 'active' ? 'success' : 'secondary' ?>">
                                    <?= ucfirst($intern['status']) ?>
                                </span>
                            </td>
                            <td>
                                <strong><?= $intern['total_logs'] ?></strong>
                                <br>
                                <small class="text-muted">
                                    <?= $intern['approved_logs'] ?> approved, 
                                    <?= $intern['pending_logs'] ?> pending
                                </small>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="me-2"><?= $approval_rate ?>%</span>
                                    <div class="progress" style="width: 60px; height: 8px;">
                                        <div class="progress-bar bg-<?= $approval_rate >= 80 ? 'success' : ($approval_rate >= 60 ? 'warning' : 'danger') ?>" 
                                             style="width: <?= $approval_rate ?>%"></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-primary"><?= $intern['total_evaluations'] ?></span>
                            </td>
                            <td>
                                <?php if ($intern['total_evaluations'] > 0): ?>
                                    <span class="badge bg-<?= $intern['avg_technical'] >= 4 ? 'success' : ($intern['avg_technical'] >= 3 ? 'info' : 'warning') ?>">
                                        <?= number_format($intern['avg_technical'], 1) ?>/5
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted">N/A</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($intern['total_evaluations'] > 0): ?>
                                    <span class="badge bg-<?= $intern['avg_softskills'] >= 4 ? 'success' : ($intern['avg_softskills'] >= 3 ? 'info' : 'warning') ?>">
                                        <?= number_format($intern['avg_softskills'], 1) ?>/5
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted">N/A</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($intern['total_evaluations'] > 0): ?>
                                    <span class="badge bg-<?= $avg_overall >= 4 ? 'success' : ($avg_overall >= 3 ? 'info' : 'warning') ?> fs-6">
                                        <?= number_format($avg_overall, 1) ?>/5
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted">N/A</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="index.php?page=supervisor&action=logs&intern_id=<?= $intern['user_id'] ?>" 
                                       class="btn btn-sm btn-outline-primary" title="View Logs">
                                        <i class="fas fa-clipboard-list"></i>
                                    </a>
                                    <a href="index.php?page=supervisor&action=addEvaluation&intern_id=<?= $intern['user_id'] ?>" 
                                       class="btn btn-sm btn-outline-success" title="Add Evaluation">
                                        <i class="fas fa-star"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Performance Charts -->
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i>Log Activity by Intern
                </h5>
            </div>
            <div class="card-body">
                <canvas id="logsChart" height="250"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-line me-2"></i>Average Performance Ratings
                </h5>
            </div>
            <div class="card-body">
                <canvas id="performanceChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-download me-2"></i>Export Reports
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted">Download comprehensive reports for your records and analysis.</p>
                <div class="row">
                    <div class="col-md-4">
                        <button class="btn btn-outline-success w-100 mb-2" onclick="exportToCSV()">
                            <i class="fas fa-file-excel me-2"></i>Export to Excel (CSV)
                        </button>
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-outline-danger w-100 mb-2" onclick="exportToPDF()">
                            <i class="fas fa-file-pdf me-2"></i>Export to PDF
                        </button>
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-outline-primary w-100 mb-2" onclick="window.print()">
                            <i class="fas fa-print me-2"></i>Print Report
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Logs Chart
const logsCtx = document.getElementById('logsChart').getContext('2d');
const internNames = <?= json_encode(array_column($interns, 'intern_name')) ?>;
const totalLogs = <?= json_encode(array_column($interns, 'total_logs')) ?>;
const approvedLogs = <?= json_encode(array_column($interns, 'approved_logs')) ?>;

new Chart(logsCtx, {
    type: 'bar',
    data: {
        labels: internNames,
        datasets: [{
            label: 'Total Logs',
            data: totalLogs,
            backgroundColor: '#840100',
            borderColor: '#840100',
            borderWidth: 1
        }, {
            label: 'Approved Logs',
            data: approvedLogs,
            backgroundColor: '#28a745',
            borderColor: '#28a745',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Performance Chart
const performanceCtx = document.getElementById('performanceChart').getContext('2d');
const avgTechnical = <?= json_encode(array_map(function($i) { 
    return $i['total_evaluations'] > 0 ? round($i['avg_technical'], 1) : 0; 
}, $interns)) ?>;
const avgSoftSkills = <?= json_encode(array_map(function($i) { 
    return $i['total_evaluations'] > 0 ? round($i['avg_softskills'], 1) : 0; 
}, $interns)) ?>;

new Chart(performanceCtx, {
    type: 'line',
    data: {
        labels: internNames,
        datasets: [{
            label: 'Technical Skills',
            data: avgTechnical,
            borderColor: '#840100',
            backgroundColor: 'rgba(132, 1, 0, 0.1)',
            tension: 0.4,
            fill: true
        }, {
            label: 'Soft Skills',
            data: avgSoftSkills,
            borderColor: '#5c0100',
            backgroundColor: 'rgba(92, 1, 0, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                max: 5,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});

// Export functions
function exportToCSV() {
    alert('CSV export functionality would be implemented here. This would generate a downloadable CSV file with all intern performance data.');
}

function exportToPDF() {
    alert('PDF export functionality would be implemented here. This would generate a professional PDF report with charts and statistics.');
}
</script>

<?php endif; ?>

<?php include 'views/layouts/footer.php'; ?>

<?php
$page_title = 'Intern Dashboard';
include 'views/layouts/header.php';

// Extract variables for use in the view and set defaults
extract($data ?? []);
$logs_this_week = $logs_this_week ?? 0;
$pending_logs = $pending_logs ?? 0;
$approved_logs = $approved_logs ?? 0;
$total_logs = $total_logs ?? 0;
$recent_logs = $recent_logs ?? [];
$recent_evaluations = $recent_evaluations ?? [];
$profile = $profile ?? null;
$recent_announcements = $recent_announcements ?? [];
$skills_summary = $skills_summary ?? [];
$calendar_logs = $calendar_logs ?? [];
?>

<style>
/* Additional Dashboard Specific Styles */
.metric-card {
    position: relative;
    overflow: hidden;
    height: 100%;
}

.metric-card .metric-value {
    font-size: 3rem;
    font-weight: 800;
    line-height: 1;
    background: linear-gradient(135deg, #fff 0%, rgba(255,255,255,0.8) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.metric-card .metric-label {
    font-size: 0.95rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    opacity: 0.95;
}

.metric-card .metric-icon {
    position: absolute;
    right: 1.5rem;
    top: 50%;
    transform: translateY(-50%);
    font-size: 4rem;
    opacity: 0.2;
}

.content-card {
    height: 100%;
    display: flex;
    flex-direction: column;
}

.content-card .card-body {
    flex: 1;
    overflow: hidden;
}

.calendar-day {
    aspect-ratio: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.875rem;
    transition: all 0.3s ease;
    cursor: pointer;
}

.calendar-day:hover {
    transform: scale(1.1);
}

.skill-item {
    transition: all 0.3s ease;
}

.skill-item:hover {
    transform: translateX(8px);
}

.announcement-item {
    transition: all 0.3s ease;
    border-left: 3px solid transparent;
}

.announcement-item:hover {
    border-left-color: var(--primary-color);
    background: rgba(99, 102, 241, 0.05);
}

.action-btn {
    position: relative;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 2rem 1rem;
    text-decoration: none;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.action-btn::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(99, 102, 241, 0.1);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
}

.action-btn:hover::before {
    width: 300px;
    height: 300px;
}

.action-btn:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(99, 102, 241, 0.3);
}

.action-btn i {
    font-size: 2.5rem;
    margin-bottom: 0.75rem;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.action-btn span {
    font-weight: 600;
    color: var(--dark-color);
}
</style>

<div class="page-header scroll-animate">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="h2 mb-2" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                <i class="fas fa-tachometer-alt me-2"></i>Intern Dashboard
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </nav>
        </div>
        <div class="col-auto">
            <span class="badge" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); font-size: 1rem; padding: 0.75rem 1.5rem;">
                <i class="fas fa-user-graduate me-2"></i>Intern
            </span>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stats-card metric-card scroll-animate" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="metric-value"><?= $logs_this_week ?></div>
            <div class="metric-label">Logs This Week</div>
            <div class="metric-icon">
                <i class="fas fa-clipboard-list"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stats-card metric-card scroll-animate" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); animation-delay: 0.1s;">
            <div class="metric-value"><?= $pending_logs ?></div>
            <div class="metric-label">Pending Logs</div>
            <div class="metric-icon">
                <i class="fas fa-clock"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stats-card metric-card scroll-animate" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); animation-delay: 0.2s;">
            <div class="metric-value"><?= $approved_logs ?></div>
            <div class="metric-label">Approved Logs</div>
            <div class="metric-icon">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stats-card metric-card scroll-animate" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); animation-delay: 0.3s;">
            <div class="metric-value"><?= count($recent_evaluations) ?></div>
            <div class="metric-label">Evaluations</div>
            <div class="metric-icon">
                <i class="fas fa-star"></i>
            </div>
        </div>
    </div>
</div>

<!-- Internship Progress -->
<?php if ($profile): ?>
<div class="row mb-4">
    <div class="col-12">
        <div class="card scroll-animate">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0" style="font-weight: 700;">
                        <i class="fas fa-calendar-alt me-2" style="color: var(--primary-color);"></i>
                        Internship Progress
                    </h5>
                    <span class="badge" style="background: linear-gradient(135deg, var(--info-color), var(--secondary-color)); padding: 0.5rem 1rem;">
                        <?= $profile ? getDepartmentName($profile['department']) : 'N/A' ?>
                    </span>
                </div>
                <?php
                $start = new DateTime($profile['start_date']);
                $end = new DateTime($profile['end_date']);
                $now = new DateTime();
                $total_days = $start->diff($end)->days;
                $elapsed_days = $start->diff($now)->days;
                $progress = min(100, max(0, ($elapsed_days / $total_days) * 100));
                $remaining_days = max(0, $end->diff($now)->days);
                ?>
                <div class="progress mb-3" style="height: 20px;">
                    <div class="progress-bar" 
                         role="progressbar" 
                         style="width: <?= round($progress) ?>%"
                         aria-valuenow="<?= round($progress) ?>" 
                         aria-valuemin="0" 
                         aria-valuemax="100">
                        <span style="font-weight: 600;"><?= round($progress) ?>%</span>
                    </div>
                </div>
                <div class="row text-center">
                    <div class="col-md-4">
                        <div class="p-3">
                            <i class="fas fa-calendar-check mb-2" style="font-size: 2rem; color: var(--success-color);"></i>
                            <p class="mb-0 small text-muted">Started</p>
                            <p class="mb-0 fw-bold"><?= formatDate($profile['start_date']) ?></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3" style="border-left: 2px solid rgba(99, 102, 241, 0.1); border-right: 2px solid rgba(99, 102, 241, 0.1);">
                            <i class="fas fa-hourglass-half mb-2" style="font-size: 2rem; color: var(--warning-color);"></i>
                            <p class="mb-0 small text-muted">Remaining</p>
                            <p class="mb-0 fw-bold"><?= $remaining_days ?> days</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3">
                            <i class="fas fa-calendar-times mb-2" style="font-size: 2rem; color: var(--danger-color);"></i>
                            <p class="mb-0 small text-muted">Ends</p>
                            <p class="mb-0 fw-bold"><?= formatDate($profile['end_date']) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Main Content: 3 Columns -->
<div class="row mb-4">
    <!-- Recent Logs -->
    <div class="col-lg-4 mb-4">
        <div class="card content-card scroll-animate">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-clipboard-list me-2"></i>Recent Daily Logs
                </h5>
            </div>
            <div class="card-body" style="max-height: 450px; overflow-y: auto;">
                <?php if (empty($recent_logs)): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-clipboard-list mb-3" style="font-size: 3rem; color: var(--primary-color); opacity: 0.3;"></i>
                        <p class="text-muted mb-3">No logs submitted yet.</p>
                        <a href="index.php?page=intern&action=addLog" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Add Your First Log
                        </a>
                    </div>
                <?php else: ?>
                    <?php foreach (array_slice($recent_logs, 0, 5) as $log): ?>
                        <div class="mb-3 p-3" style="border-radius: 12px; background: rgba(99, 102, 241, 0.03); border-left: 3px solid var(--primary-color); transition: all 0.3s ease;">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <p class="mb-1 fw-bold" style="color: var(--dark-color);"><?= formatDate($log['date']) ?></p>
                                    <p class="mb-2 text-muted small" style="line-height: 1.5;">
                                        <?= htmlspecialchars(substr($log['task_description'], 0, 80)) ?>...
                                    </p>
                                    <span class="badge bg-<?= $log['status'] === 'approved' ? 'success' : ($log['status'] === 'pending' ? 'warning' : 'danger') ?>">
                                        <?= ucfirst($log['status']) ?>
                                    </span>
                                </div>
                                <a href="index.php?page=intern&action=viewLog&id=<?= $log['id'] ?>" 
                                   class="btn btn-sm btn-outline-primary ms-2">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <div class="text-center mt-3">
                        <a href="index.php?page=intern&action=logs" class="btn btn-outline-primary w-100">
                            View All Logs <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Announcements -->
    <div class="col-lg-4 mb-4">
        <div class="card content-card scroll-animate" style="animation-delay: 0.1s;">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-bullhorn me-2"></i>Announcements
                </h5>
            </div>
            <div class="card-body" style="max-height: 450px; overflow-y: auto;">
                <?php if (empty($recent_announcements)): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-bullhorn mb-3" style="font-size: 3rem; color: var(--secondary-color); opacity: 0.3;"></i>
                        <p class="text-muted">No announcements yet.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($recent_announcements as $announcement): ?>
                        <div class="announcement-item mb-3 p-3" style="border-radius: 12px; background: rgba(139, 92, 246, 0.03);">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width: 48px; height: 48px; background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));">
                                        <i class="fas fa-bullhorn" style="color: white;"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-bold"><?= htmlspecialchars($announcement['title']) ?></h6>
                                    <p class="mb-2 text-muted small">
                                        <?= htmlspecialchars(substr($announcement['message'], 0, 100)) ?>
                                        <?= strlen($announcement['message']) > 100 ? '...' : '' ?>
                                    </p>
                                    <small class="text-muted">
                                        <i class="fas fa-user me-1"></i><?= htmlspecialchars($announcement['created_by_name']) ?> • 
                                        <i class="fas fa-clock me-1"></i><?= formatDate($announcement['date_created'], 'M j, Y') ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Monthly Calendar -->
    <div class="col-lg-4 mb-4">
        <div class="card content-card scroll-animate" style="animation-delay: 0.2s;">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-alt me-2"></i><?= date('F Y') ?> Activity
                </h5>
            </div>
            <div class="card-body">
                <?php
                $month = date('m');
                $year = date('Y');
                $first_day = mktime(0, 0, 0, $month, 1, $year);
                $days_in_month = date('t', $first_day);
                $day_of_week = date('w', $first_day);
                ?>
                <div class="calendar-grid">
                    <div class="row text-center mb-2 g-1">
                        <?php foreach (['S', 'M', 'T', 'W', 'T', 'F', 'S'] as $day): ?>
                            <div class="col">
                                <small class="fw-bold text-muted"><?= $day ?></small>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php
                    $day = 1;
                    for ($week = 0; $week < 6 && $day <= $days_in_month; $week++) {
                        echo '<div class="row g-1 mb-1">';
                        for ($dow = 0; $dow < 7; $dow++) {
                            echo '<div class="col">';
                            if (($week == 0 && $dow < $day_of_week) || $day > $days_in_month) {
                                echo '<div style="aspect-ratio: 1;"></div>';
                            } else {
                                $current_date = sprintf('%04d-%02d-%02d', $year, $month, $day);
                                $status = isset($calendar_logs[$current_date]) ? $calendar_logs[$current_date] : null;
                                $bg_class = $status === 'approved' ? 'bg-success' : ($status === 'pending' ? 'bg-warning' : ($status === 'rejected' ? 'bg-danger' : 'bg-light'));
                                $text_class = $status ? 'text-white' : 'text-dark';
                                $is_today = $day == date('d') ? 'border border-primary border-3' : '';
                                echo '<div class="calendar-day ' . $bg_class . ' ' . $text_class . ' ' . $is_today . '">' . $day . '</div>';
                                $day++;
                            }
                            echo '</div>';
                        }
                        echo '</div>';
                    }
                    ?>
                </div>
                <hr class="my-3">
                <div class="row g-2 small text-center">
                    <div class="col-3">
                        <div class="p-2 rounded" style="background: rgba(16, 185, 129, 0.1);">
                            <div class="rounded-circle bg-success mx-auto mb-1" style="width: 12px; height: 12px;"></div>
                            <small class="d-block">Approved</small>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="p-2 rounded" style="background: rgba(245, 158, 11, 0.1);">
                            <div class="rounded-circle bg-warning mx-auto mb-1" style="width: 12px; height: 12px;"></div>
                            <small class="d-block">Pending</small>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="p-2 rounded" style="background: rgba(239, 68, 68, 0.1);">
                            <div class="rounded-circle bg-danger mx-auto mb-1" style="width: 12px; height: 12px;"></div>
                            <small class="d-block">Rejected</small>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="p-2 rounded" style="background: rgba(226, 232, 240, 1);">
                            <div class="rounded-circle bg-light border mx-auto mb-1" style="width: 12px; height: 12px;"></div>
                            <small class="d-block">No Log</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Skills & Performance -->
<div class="row mb-4">
    <!-- Skills Summary -->
    <div class="col-lg-6 mb-4">
        <div class="card scroll-animate">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-tools me-2"></i>Skills Summary
                </h5>
            </div>
            <div class="card-body">
                <?php if (empty($skills_summary)): ?>
                    <div class="text-center py-4">
                        <i class="fas fa-tools mb-3" style="font-size: 3rem; color: var(--info-color); opacity: 0.3;"></i>
                        <p class="text-muted mb-0">No skills recorded yet.</p>
                        <small class="text-muted">Add skills to your daily logs to track progress.</small>
                    </div>
                <?php else: ?>
                    <div class="row g-3">
                        <?php foreach (array_slice($skills_summary, 0, 6, true) as $skill => $count): ?>
                            <div class="col-md-6">
                                <div class="skill-item p-3" style="border-radius: 12px; background: linear-gradient(135deg, rgba(99, 102, 241, 0.05), rgba(139, 92, 246, 0.05));">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="fw-bold text-truncate" style="max-width: 150px;" title="<?= htmlspecialchars($skill) ?>">
                                            <i class="fas fa-check-circle me-2" style="color: var(--success-color);"></i>
                                            <?= htmlspecialchars($skill) ?>
                                        </span>
                                        <span class="badge" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));"><?= $count ?></span>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar" style="width: <?= min(100, ($count / max($skills_summary)) * 100) ?>%"></div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Performance Summary -->
    <div class="col-lg-6 mb-4">
        <div class="card scroll-animate" style="animation-delay: 0.1s;">
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
                    ?>
                    <div class="row text-center mb-4">
                        <div class="col-4">
                            <div class="p-3" style="border-radius: 12px; background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(99, 102, 241, 0.05));">
                                <h3 class="mb-1" style="color: var(--primary-color); font-weight: 800;"><?= number_format($avg_technical, 1) ?></h3>
                                <small class="text-muted d-block mb-2">Technical</small>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar" style="width: <?= ($avg_technical/5)*100 ?>%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-3" style="border-radius: 12px; background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(16, 185, 129, 0.05));">
                                <h3 class="mb-1" style="color: var(--success-color); font-weight: 800;"><?= number_format($avg_softskills, 1) ?></h3>
                                <small class="text-muted d-block mb-2">Soft Skills</small>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-success" style="width: <?= ($avg_softskills/5)*100 ?>%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-3" style="border-radius: 12px; background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(59, 130, 246, 0.05));">
                                <h3 class="mb-1" style="color: var(--info-color); font-weight: 800;"><?= number_format($avg_overall, 1) ?></h3>
                                <small class="text-muted d-block mb-2">Overall</small>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-info" style="width: <?= ($avg_overall/5)*100 %>%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="my-3">
                    <div class="d-flex justify-content-between align-items-center p-3" style="border-radius: 12px; background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(245, 158, 11, 0.05));">
                        <span class="fw-bold">
                            <i class="fas fa-trophy me-2" style="color: var(--warning-color);"></i>
                            Performance Level
                        </span>
                        <span class="badge bg-<?= $avg_overall >= 4 ? 'success' : ($avg_overall >= 3 ? 'info' : 'warning') ?>" style="padding: 0.5rem 1rem;">
                            <?php
                            if ($avg_overall >= 4.5) echo 'Excellent';
                            elseif ($avg_overall >= 3.5) echo 'Very Good';
                            elseif ($avg_overall >= 2.5) echo 'Good';
                            else echo 'Needs Improvement';
                            ?>
                        </span>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-chart-line mb-3" style="font-size: 3rem; color: var(--primary-color); opacity: 0.3;"></i>
                        <p class="text-muted mb-0">No performance data available yet.</p>
                        <small class="text-muted">Your supervisor will evaluate your performance weekly.</small>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-12">
        <div class="card scroll-animate">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-bolt me-2"></i>Quick Actions
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <a href="index.php?page=intern&action=addLog" class="card action-btn border-0">
                            <i class="fas fa-plus"></i>
                            <span>Add Daily Log</span>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <a href="index.php?page=intern&action=logs" class="card action-btn border-0">
                            <i class="fas fa-clipboard-list"></i>
                            <span>View All Logs</span>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <a href="index.php?page=intern&action=evaluations" class="card action-btn border-0">
                            <i class="fas fa-star"></i>
                            <span>My Evaluations</span>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <a href="index.php?page=intern&action=attendance" class="card action-btn border-0">
                            <i class="fas fa-calendar-check"></i>
                            <span>Attendance</span>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <a href="index.php?page=intern&action=statistics" class="card action-btn border-0">
                            <i class="fas fa-chart-bar"></i>
                            <span>My Statistics</span>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <a href="index.php?page=intern&action=weeklyReport" class="card action-btn border-0">
                            <i class="fas fa-file-pdf"></i>
                            <span>Weekly Report</span>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <a href="index.php?page=profile" class="card action-btn border-0">
                            <i class="fas fa-user"></i>
                            <span>My Profile</span>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <button onclick="window.print()" class="card action-btn border-0 w-100" style="background: transparent;">
                            <i class="fas fa-print"></i>
                            <span>Print Dashboard</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Scroll animations
document.addEventListener('DOMContentLoaded', function() {
    const animateElements = document.querySelectorAll('.scroll-animate');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('active');
            }
        });
    }, {
        threshold: 0.1
    });
    
    animateElements.forEach(el => observer.observe(el));
    
    // Auto-dismiss alerts after 5 seconds
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});
</script>

<?php include 'views/layouts/footer.php'; ?>

<?php
$page_title = 'Administration';
include 'views/layouts/header.php';
?>

<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="h3 mb-0">
                <i class="fas fa-cogs me-2"></i>Administration
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php?page=dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active">Administration</li>
                </ol>
            </nav>
        </div>
        <div class="col-auto">
            <span class="badge bg-danger fs-6">
                <i class="fas fa-user-shield me-1"></i>Administrator Panel
            </span>
        </div>
    </div>
</div>

<!-- Section 1 - User Management -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-users-cog me-2"></i>User Management
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">Manage system users, roles, and permissions</p>
                <div class="row g-3">
                    <div class="col-md-3">
                        <a href="index.php?page=admin&action=users" class="btn btn-outline-primary w-100 py-3">
                            <i class="fas fa-users fa-2x d-block mb-2"></i>
                            <span>Manage Users</span>
                            <br>
                            <small class="text-muted"><?= $total_users ?> users</small>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="index.php?page=admin&action=addUser" class="btn btn-outline-success w-100 py-3">
                            <i class="fas fa-user-plus fa-2x d-block mb-2"></i>
                            <span>Add New User</span>
                            <br>
                            <small class="text-muted">Create account</small>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="index.php?page=admin&action=loginActivity" class="btn btn-outline-info w-100 py-3">
                            <i class="fas fa-history fa-2x d-block mb-2"></i>
                            <span>Login Activity</span>
                            <br>
                            <small class="text-muted">View sessions</small>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="index.php?page=admin&action=roles" class="btn btn-outline-warning w-100 py-3">
                            <i class="fas fa-user-tag fa-2x d-block mb-2"></i>
                            <span>Role Settings</span>
                            <br>
                            <small class="text-muted">Permissions</small>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Section 2 - Department Management -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="fas fa-building me-2"></i>Department Management
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">Manage departments and assign supervisors</p>
                <div class="row g-3">
                    <div class="col-md-6">
                        <a href="index.php?page=admin&action=departments" class="btn btn-outline-success w-100 py-3">
                            <i class="fas fa-sitemap fa-2x d-block mb-2"></i>
                            <span>Manage Departments</span>
                            <br>
                            <small class="text-muted">6 departments</small>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="index.php?page=admin&action=addDepartment" class="btn btn-outline-primary w-100 py-3">
                            <i class="fas fa-plus-circle fa-2x d-block mb-2"></i>
                            <span>Add Department</span>
                            <br>
                            <small class="text-muted">Create new</small>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Section 3 - Internship Program Settings -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">
                    <i class="fas fa-graduation-cap me-2"></i>Internship Program Settings
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">Configure internship cycles and submission rules</p>
                <div class="row g-3">
                    <div class="col-md-4">
                        <a href="index.php?page=admin&action=programSettings" class="btn btn-outline-info w-100 py-3">
                            <i class="fas fa-calendar-alt fa-2x d-block mb-2"></i>
                            <span>Program Dates</span>
                            <br>
                            <small class="text-muted">Start/End dates</small>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="index.php?page=admin&action=submissionRules" class="btn btn-outline-warning w-100 py-3">
                            <i class="fas fa-clock fa-2x d-block mb-2"></i>
                            <span>Submission Rules</span>
                            <br>
                            <small class="text-muted">Time limits</small>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="index.php?page=admin&action=evaluationSettings" class="btn btn-outline-success w-100 py-3">
                            <i class="fas fa-star fa-2x d-block mb-2"></i>
                            <span>Evaluation Form</span>
                            <br>
                            <small class="text-muted">Form settings</small>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Section 4 - System Configuration -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-warning text-white">
                <h5 class="mb-0">
                    <i class="fas fa-sliders-h me-2"></i>System Configuration
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">Configure system-wide settings and preferences</p>
                <div class="row g-3">
                    <div class="col-md-3">
                        <a href="index.php?page=admin&action=emailSettings" class="btn btn-outline-danger w-100 py-3">
                            <i class="fas fa-envelope fa-2x d-block mb-2"></i>
                            <span>Email Settings</span>
                            <br>
                            <small class="text-muted">SMTP config</small>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="index.php?page=admin&action=notifications" class="btn btn-outline-primary w-100 py-3">
                            <i class="fas fa-bell fa-2x d-block mb-2"></i>
                            <span>Notifications</span>
                            <br>
                            <small class="text-muted">Enable/Disable</small>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="index.php?page=admin&action=systemSettings" class="btn btn-outline-info w-100 py-3">
                            <i class="fas fa-cog fa-2x d-block mb-2"></i>
                            <span>System Settings</span>
                            <br>
                            <small class="text-muted">General config</small>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="index.php?page=admin&action=permissions" class="btn btn-outline-success w-100 py-3">
                            <i class="fas fa-shield-alt fa-2x d-block mb-2"></i>
                            <span>Permissions</span>
                            <br>
                            <small class="text-muted">Access control</small>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Section 5 - Audit & Activity Logs -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-clipboard-list me-2"></i>Audit & Activity Logs
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">Monitor system activity and user actions</p>
                <div class="row g-3">
                    <div class="col-md-6">
                        <a href="index.php?page=admin&action=auditLogs" class="btn btn-outline-secondary w-100 py-3">
                            <i class="fas fa-list-alt fa-2x d-block mb-2"></i>
                            <span>Audit Logs</span>
                            <br>
                            <small class="text-muted">User actions</small>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="index.php?page=admin&action=loginActivity" class="btn btn-outline-info w-100 py-3">
                            <i class="fas fa-sign-in-alt fa-2x d-block mb-2"></i>
                            <span>Login History</span>
                            <br>
                            <small class="text-muted">Access logs</small>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Section 6 - Export & Backup Tools -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">
                    <i class="fas fa-download me-2"></i>Export & Backup Tools
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">Export data and backup system</p>
                <div class="row g-3">
                    <div class="col-md-3">
                        <a href="index.php?page=admin&action=exportInterns" class="btn btn-outline-primary w-100 py-3">
                            <i class="fas fa-file-export fa-2x d-block mb-2"></i>
                            <span>Export Interns</span>
                            <br>
                            <small class="text-muted">CSV/Excel</small>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="index.php?page=admin&action=exportLogs" class="btn btn-outline-success w-100 py-3">
                            <i class="fas fa-file-csv fa-2x d-block mb-2"></i>
                            <span>Export Logs</span>
                            <br>
                            <small class="text-muted">All logs</small>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="index.php?page=admin&action=exportEvaluations" class="btn btn-outline-info w-100 py-3">
                            <i class="fas fa-file-download fa-2x d-block mb-2"></i>
                            <span>Export Evaluations</span>
                            <br>
                            <small class="text-muted">All data</small>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="index.php?page=admin&action=backup" class="btn btn-outline-danger w-100 py-3">
                            <i class="fas fa-database fa-2x d-block mb-2"></i>
                            <span>Backup Database</span>
                            <br>
                            <small class="text-muted">Manual backup</small>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Statistics -->
<div class="row">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h4 class="mb-0"><?= $total_users ?></h4>
                <p class="mb-0">Total Users</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h4 class="mb-0"><?= $active_interns ?></h4>
                <p class="mb-0">Active Interns</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h4 class="mb-0"><?= $total_logs_today ?></h4>
                <p class="mb-0">Logs Today</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h4 class="mb-0"><?= $pending_approvals ?></h4>
                <p class="mb-0">Pending Approvals</p>
            </div>
        </div>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>

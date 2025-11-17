<?php
$page_title = 'My Profile';
include 'views/layouts/header.php';
?>

<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="h3 mb-0">
                <i class="fas fa-user me-2"></i>My Profile
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php?page=dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active">Profile</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="row">
    <!-- Profile Information -->
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user-edit me-2"></i>Profile Information
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="index.php?page=profile&action=update">
                    <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">
                            <i class="fas fa-user me-2"></i>Full Name *
                        </label>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="<?= htmlspecialchars($user['name']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope me-2"></i>Email Address *
                        </label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="<?= htmlspecialchars($user['email']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-user-tag me-2"></i>Role
                        </label>
                        <input type="text" class="form-control" 
                               value="<?= ucfirst($user['role']) ?>" readonly disabled>
                        <div class="form-text">Your role cannot be changed.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-calendar me-2"></i>Member Since
                        </label>
                        <input type="text" class="form-control" 
                               value="<?= formatDate($user['created_at'], 'F j, Y') ?>" readonly disabled>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <?php if ($user['role'] === 'intern' && $profile): ?>
        <!-- Intern Profile Details -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user-graduate me-2"></i>Internship Details
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Department</label>
                        <p class="mb-0"><strong><?= getDepartmentName($profile['department']) ?></strong></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Status</label>
                        <p class="mb-0">
                            <span class="badge bg-<?= $profile['status'] === 'active' ? 'success' : ($profile['status'] === 'completed' ? 'primary' : 'danger') ?>">
                                <?= ucfirst($profile['status']) ?>
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Start Date</label>
                        <p class="mb-0"><strong><?= formatDate($profile['start_date'], 'F j, Y') ?></strong></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">End Date</label>
                        <p class="mb-0"><strong><?= formatDate($profile['end_date'], 'F j, Y') ?></strong></p>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label text-muted">Supervisor</label>
                        <p class="mb-0">
                            <strong><?= htmlspecialchars($profile['supervisor_name']) ?></strong><br>
                            <small class="text-muted"><?= htmlspecialchars($profile['supervisor_email']) ?></small>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Sidebar -->
    <div class="col-md-4">
        <!-- Profile Picture -->
        <div class="card mb-4 text-center">
            <div class="card-body">
                <div class="mb-3">
                    <i class="fas fa-user-circle fa-5x text-muted"></i>
                </div>
                <h5><?= htmlspecialchars($user['name']) ?></h5>
                <p class="text-muted mb-2"><?= htmlspecialchars($user['email']) ?></p>
                <span class="badge bg-primary"><?= ucfirst($user['role']) ?></span>
            </div>
        </div>

        <!-- Change Password -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-key me-2"></i>Change Password
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="index.php?page=profile&action=changePassword">
                    <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                    
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password</label>
                        <input type="password" class="form-control" id="current_password" 
                               name="current_password" required>
                    </div>

                    <div class="mb-3">
                        <label for="new_password" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="new_password" 
                               name="new_password" required>
                        <div class="form-text">Minimum 6 characters</div>
                    </div>

                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="confirm_password" 
                               name="confirm_password" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-lock me-2"></i>Change Password
                    </button>
                </form>
            </div>
        </div>

        <!-- Quick Stats (for interns) -->
        <?php if ($user['role'] === 'intern'): ?>
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i>Quick Stats
                </h5>
            </div>
            <div class="card-body">
                <?php
                try {
                    global $db;
                    // Get logs count
                    $stmt = $db->prepare("SELECT COUNT(*) as count FROM daily_logs WHERE intern_id = ?");
                    $stmt->execute([$user['id']]);
                    $logs_count = $stmt->fetch()['count'];

                    // Get evaluations count
                    $stmt = $db->prepare("SELECT COUNT(*) as count FROM evaluations WHERE intern_id = ?");
                    $stmt->execute([$user['id']]);
                    $eval_count = $stmt->fetch()['count'];
                } catch (Exception $e) {
                    $logs_count = 0;
                    $eval_count = 0;
                }
                ?>
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>Total Logs</span>
                        <strong><?= $logs_count ?></strong>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>Evaluations</span>
                        <strong><?= $eval_count ?></strong>
                    </div>
                </div>
                <hr>
                <a href="index.php?page=intern&action=logs" class="btn btn-outline-primary btn-sm w-100">
                    <i class="fas fa-clipboard-list me-2"></i>View All Logs
                </a>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>

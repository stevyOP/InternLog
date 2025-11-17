<?php
/**
 * Profile Controller
 * Parliament Intern Logbook System
 */

class ProfileController {
    private $db;

    public function __construct() {
        global $db;
        $this->db = $db;
    }

    /**
     * Display user profile
     */
    public function index() {
        requireAuth();
        
        $user_id = $_SESSION['user_id'];
        
        try {
            // Get user data
            $stmt = $this->db->prepare("
                SELECT id, name, email, role, profile_picture, created_at 
                FROM users 
                WHERE id = ?
            ");
            $stmt->execute([$user_id]);
            $user = $stmt->fetch();

            if (!$user) {
                setFlashMessage('error', 'User not found.');
                header('Location: index.php?page=dashboard');
                exit;
            }

            // Get intern profile if user is an intern
            $profile = null;
            if ($user['role'] === 'intern') {
                $stmt = $this->db->prepare("
                    SELECT ip.*, 
                           s.name as supervisor_name,
                           s.email as supervisor_email
                    FROM intern_profiles ip
                    LEFT JOIN users s ON ip.supervisor_id = s.id
                    WHERE ip.user_id = ?
                ");
                $stmt->execute([$user_id]);
                $profile = $stmt->fetch();
            }

            include 'views/profile/index.php';
        } catch (Exception $e) {
            setFlashMessage('error', 'Failed to load profile.');
            header('Location: index.php?page=dashboard');
            exit;
        }
    }

    /**
     * Update profile
     */
    public function update() {
        requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_SESSION['user_id'];
            $name = sanitize($_POST['name'] ?? '');
            $csrf_token = $_POST['csrf_token'] ?? '';

            if (!verifyCSRFToken($csrf_token)) {
                setFlashMessage('error', 'Invalid request. Please try again.');
                header('Location: index.php?page=profile');
                exit;
            }

            if (empty($name)) {
                setFlashMessage('error', 'Name is required.');
                header('Location: index.php?page=profile');
                exit;
            }

            try {
                // Check if user is admin and can update email
                $isAdmin = $_SESSION['role'] === 'admin';
                
                if ($isAdmin) {
                    $email = sanitize($_POST['email'] ?? '');
                    
                    if (empty($email)) {
                        setFlashMessage('error', 'Email is required.');
                        header('Location: index.php?page=profile');
                        exit;
                    }

                    if (!isValidEmail($email)) {
                        setFlashMessage('error', 'Invalid email address.');
                        header('Location: index.php?page=profile');
                        exit;
                    }

                    // Check if email is already taken by another user
                    $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
                    $stmt->execute([$email, $user_id]);
                    if ($stmt->fetch()) {
                        setFlashMessage('error', 'Email address is already in use.');
                        header('Location: index.php?page=profile');
                        exit;
                    }

                    // Update user profile with email
                    $stmt = $this->db->prepare("
                        UPDATE users 
                        SET name = ?, email = ?, updated_at = NOW() 
                        WHERE id = ?
                    ");
                    $stmt->execute([$name, $email, $user_id]);

                    // Update session
                    $_SESSION['name'] = $name;
                    $_SESSION['email'] = $email;
                } else {
                    // Update only name for non-admin users
                    $stmt = $this->db->prepare("
                        UPDATE users 
                        SET name = ?, updated_at = NOW() 
                        WHERE id = ?
                    ");
                    $stmt->execute([$name, $user_id]);

                    // Update session
                    $_SESSION['name'] = $name;
                }

                logActivity($user_id, 'profile_updated', 'Profile information updated');
                setFlashMessage('success', 'Profile updated successfully.');
            } catch (Exception $e) {
                setFlashMessage('error', 'Failed to update profile. Please try again.');
            }
        }

        header('Location: index.php?page=profile');
        exit;
    }

    /**
     * Upload profile picture
     */
    public function uploadPicture() {
        requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $csrf_token = $_POST['csrf_token'] ?? '';

            if (!verifyCSRFToken($csrf_token)) {
                setFlashMessage('error', 'Invalid request. Please try again.');
                header('Location: index.php?page=profile');
                exit;
            }

            if (!isset($_FILES['profile_picture']) || $_FILES['profile_picture']['error'] === UPLOAD_ERR_NO_FILE) {
                setFlashMessage('error', 'Please select a file to upload.');
                header('Location: index.php?page=profile');
                exit;
            }

            $file = $_FILES['profile_picture'];

            // Check for upload errors
            if ($file['error'] !== UPLOAD_ERR_OK) {
                setFlashMessage('error', 'File upload failed. Please try again.');
                header('Location: index.php?page=profile');
                exit;
            }

            // Validate file type
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime_type = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);

            if (!in_array($mime_type, $allowed_types)) {
                setFlashMessage('error', 'Invalid file type. Only JPG, PNG, and GIF are allowed.');
                header('Location: index.php?page=profile');
                exit;
            }

            // Validate file size (max 2MB)
            if ($file['size'] > 2 * 1024 * 1024) {
                setFlashMessage('error', 'File size must be less than 2MB.');
                header('Location: index.php?page=profile');
                exit;
            }

            try {
                $user_id = $_SESSION['user_id'];

                // Get old profile picture
                $stmt = $this->db->prepare("SELECT profile_picture FROM users WHERE id = ?");
                $stmt->execute([$user_id]);
                $old_picture = $stmt->fetch()['profile_picture'];

                // Generate unique filename
                $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $filename = 'profile_' . $user_id . '_' . time() . '.' . $extension;
                $upload_path = __DIR__ . '/../uploads/profile_pictures/' . $filename;

                // Move uploaded file
                if (!move_uploaded_file($file['tmp_name'], $upload_path)) {
                    setFlashMessage('error', 'Failed to save uploaded file.');
                    header('Location: index.php?page=profile');
                    exit;
                }

                // Delete old profile picture if exists
                if ($old_picture && file_exists(__DIR__ . '/../' . $old_picture)) {
                    unlink(__DIR__ . '/../' . $old_picture);
                }

                // Update database
                $db_path = 'uploads/profile_pictures/' . $filename;
                $stmt = $this->db->prepare("UPDATE users SET profile_picture = ? WHERE id = ?");
                $stmt->execute([$db_path, $user_id]);

                logActivity($user_id, 'profile_picture_updated', 'Profile picture uploaded');
                setFlashMessage('success', 'Profile picture updated successfully.');
            } catch (Exception $e) {
                setFlashMessage('error', 'Failed to update profile picture. Please try again.');
            }
        }

        header('Location: index.php?page=profile');
        exit;
    }

    /**
     * Change password
     */
    public function changePassword() {
        requireAuth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $current_password = $_POST['current_password'] ?? '';
            $new_password = $_POST['new_password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            $csrf_token = $_POST['csrf_token'] ?? '';

            if (!verifyCSRFToken($csrf_token)) {
                setFlashMessage('error', 'Invalid request. Please try again.');
                header('Location: index.php?page=profile');
                exit;
            }

            if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
                setFlashMessage('error', 'Please fill in all fields.');
                header('Location: index.php?page=profile');
                exit;
            }

            if ($new_password !== $confirm_password) {
                setFlashMessage('error', 'New passwords do not match.');
                header('Location: index.php?page=profile');
                exit;
            }

            if (strlen($new_password) < 6) {
                setFlashMessage('error', 'Password must be at least 6 characters long.');
                header('Location: index.php?page=profile');
                exit;
            }

            try {
                // Verify current password
                $stmt = $this->db->prepare("SELECT password FROM users WHERE id = ?");
                $stmt->execute([$_SESSION['user_id']]);
                $user = $stmt->fetch();

                if (!password_verify($current_password, $user['password'])) {
                    setFlashMessage('error', 'Current password is incorrect.');
                    header('Location: index.php?page=profile');
                    exit;
                }

                // Update password
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt = $this->db->prepare("UPDATE users SET password = ? WHERE id = ?");
                $stmt->execute([$hashed_password, $_SESSION['user_id']]);

                logActivity($_SESSION['user_id'], 'password_change', 'Password changed from profile');
                setFlashMessage('success', 'Password changed successfully.');
            } catch (Exception $e) {
                setFlashMessage('error', 'Failed to change password. Please try again.');
            }
        }

        header('Location: index.php?page=profile');
        exit;
    }
}
?>

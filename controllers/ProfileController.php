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
                SELECT id, name, email, role, created_at 
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
            $email = sanitize($_POST['email'] ?? '');
            $csrf_token = $_POST['csrf_token'] ?? '';

            if (!verifyCSRFToken($csrf_token)) {
                setFlashMessage('error', 'Invalid request. Please try again.');
                header('Location: index.php?page=profile');
                exit;
            }

            if (empty($name) || empty($email)) {
                setFlashMessage('error', 'Name and email are required.');
                header('Location: index.php?page=profile');
                exit;
            }

            if (!isValidEmail($email)) {
                setFlashMessage('error', 'Invalid email address.');
                header('Location: index.php?page=profile');
                exit;
            }

            try {
                // Check if email is already taken by another user
                $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
                $stmt->execute([$email, $user_id]);
                if ($stmt->fetch()) {
                    setFlashMessage('error', 'Email address is already in use.');
                    header('Location: index.php?page=profile');
                    exit;
                }

                // Update user profile
                $stmt = $this->db->prepare("
                    UPDATE users 
                    SET name = ?, email = ?, updated_at = NOW() 
                    WHERE id = ?
                ");
                $stmt->execute([$name, $email, $user_id]);

                // Update session
                $_SESSION['name'] = $name;
                $_SESSION['email'] = $email;

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

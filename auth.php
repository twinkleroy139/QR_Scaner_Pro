<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login / Register - QR Scanner Pro</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background: #f4f7f6; color: #333; padding: 20px; }
        
        .navbar { display: flex; justify-content: space-between; align-items: center; background: #2c3e50; color: #fff; padding: 15px 30px; border-radius: 8px; margin-bottom: 30px; }
        .navbar a { color: #fff; text-decoration: none; margin-left: 15px; font-weight: bold; }
        .nav-link { padding: 6px 12px; border-radius: 4px; }
        
        .auth-container { max-width: 450px; margin: 40px auto; background: #fff; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); overflow: hidden; }
        
        /* Tab Navigation */
        .auth-tabs { display: flex; background: #f1f5f9; border-bottom: 1px solid #e2e8f0; }
        .tab-btn { flex: 1; padding: 15px; border: none; background: none; font-size: 16px; font-weight: bold; color: #64748b; cursor: pointer; transition: all 0.2s; }
        .tab-btn.active { background: #fff; color: #2563eb; border-bottom: 3px solid #2563eb; }
        
        /* Tab Content */
        .tab-content { padding: 30px; display: none; }
        .tab-content.active { display: block; }
        
        .form-group { margin-bottom: 20px; }
        label { display: block; font-weight: bold; margin-bottom: 8px; font-size: 0.9em; color: #475569; }
        input[type="text"], input[type="email"], input[type="password"] { width: 100%; padding: 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 15px; }
        input:focus { outline: none; border-color: #2563eb; }
        
        .btn-submit { width: 100%; background: #2563eb; color: #fff; padding: 12px; border: none; border-radius: 6px; font-weight: bold; font-size: 16px; cursor: pointer; }
        .btn-submit:hover { background: #1d4ed8; }
        
        .alert { padding: 12px; border-radius: 6px; margin-bottom: 20px; font-size: 0.9em; display: none; }
        .alert-error { background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; }
        .alert-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; }
    </style>
</head>
<body>

    <div class="navbar">
        <h2>QR Scanner Pro</h2>
        <div>
            <a href="index.php" class="nav-link">Scanner</a>
            <a href="generator.php" class="nav-link">Generator</a>
            <a href="dashboard.php" class="nav-link">Dashboard</a>
            <a href="auth.php" class="nav-link" style="background: #3b82f6;">Login / Register</a>
        </div>
    </div>

    <div class="auth-container">
        <!-- Tab Headers -->
        <div class="auth-tabs">
            <button class="tab-btn active" onclick="switchTab('login')">Login</button>
            <button class="tab-btn" onclick="switchTab('register')">Register</button>
        </div>

        <!-- Feedback Alert Box -->
        <div style="padding: 0 30px; margin-top: 20px;">
            <div id="alert-box" class="alert"></div>
        </div>

        <!-- Login Form -->
        <div id="login-tab" class="tab-content active">
            <form id="login-form">
                <div class="form-group">
                    <label for="login-email">Email Address</label>
                    <input type="email" id="login-email" required placeholder="yourname@example.com">
                </div>
                <div class="form-group">
                    <label for="login-password">Password</label>
                    <input type="password" id="login-password" required placeholder="••••••••">
                </div>
                <button type="submit" class="btn-submit">Sign In</button>
            </form>
        </div>

        <!-- Register Form -->
        <div id="register-tab" class="tab-content">
            <form id="register-form">
                <div class="form-group">
                    <label for="reg-username">Username</label>
                    <input type="text" id="reg-username" required placeholder="johndoe">
                </div>
                <div class="form-group">
                    <label for="reg-email">Email Address</label>
                    <input type="email" id="reg-email" required placeholder="yourname@example.com">
                </div>
                <div class="form-group">
                    <label for="reg-password">Password</label>
                    <input type="password" id="reg-password" required placeholder="••••••••">
                </div>
                <button type="submit" class="btn-submit">Create Account</button>
            </form>
        </div>
    </div>

    <script>
        // Tab Switcher Logic
        function switchTab(tabName) {
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
            hideAlert();

            if (tabName === 'login') {
                document.querySelectorAll('.tab-btn')[0].classList.add('active');
                document.getElementById('login-tab').classList.add('active');
            } else {
                document.querySelectorAll('.tab-btn')[1].classList.add('active');
                document.getElementById('register-tab').classList.add('active');
            }
        }

        const alertBox = document.getElementById('alert-box');

        function showAlert(message, isSuccess = false) {
            alertBox.className = `alert ${isSuccess ? 'alert-success' : 'alert-error'}`;
            alertBox.innerText = message;
            alertBox.style.display = 'block';
        }

        function hideAlert() {
            alertBox.style.display = 'none';
        }

        // AJAX Login Submission
        document.getElementById('login-form').addEventListener('submit', (e) => {
            e.preventDefault();
            hideAlert();

            const formData = new FormData();
            formData.append('email', document.getElementById('login-email').value);
            formData.append('password', document.getElementById('login-password').value);

            fetch('api/auth/login.php', { method: 'POST', body: formData })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showAlert('Login successful! Redirecting to dashboard...', true);
                        setTimeout(() => window.location.href = 'dashboard.php', 1000);
                    } else {
                        showAlert(data.message, false);
                    }
                })
                .catch(err => showAlert('Network error occurred.', false));
        });

        // AJAX Register Submission
        document.getElementById('register-form').addEventListener('submit', (e) => {
            e.preventDefault();
            hideAlert();

            const formData = new FormData();
            formData.append('username', document.getElementById('reg-username').value);
            formData.append('email', document.getElementById('reg-email').value);
            formData.append('password', document.getElementById('reg-password').value);

            fetch('api/auth/register.php', { method: 'POST', body: formData })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showAlert('Registration successful! You can now log in.', true);
                        setTimeout(() => switchTab('login'), 1500);
                    } else {
                        showAlert(data.message, false);
                    }
                })
                .catch(err => showAlert('Network error occurred.', false));
        });
    </script>
</body>
</html>
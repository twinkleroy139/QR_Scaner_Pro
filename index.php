<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart QR Scanner & Generator</title>
    <!-- Modern Styling -->
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background: #f4f7f6; color: #333; padding: 20px; }
        .navbar { display: flex; justify-content: space-between; align-items: center; background: #2c3e50; color: #fff; padding: 15px 30px; border-radius: 8px; margin-bottom: 30px; }
        .navbar a { color: #fff; text-decoration: none; margin-left: 15px; font-weight: bold; }
        .container { max-width: 700px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); text-align: center; }
        .scanner-box { margin: 20px 0; border: 2px dashed #cbd5e1; border-radius: 10px; padding: 10px; background: #fafafa; }
        #reader { width: 100%; max-width: 500px; margin: 0 auto; border-radius: 8px; overflow: hidden; }
        .btn { background: #3b82f6; color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-size: 16px; margin: 5px; }
        .btn-stop { background: #ef4444; display: none; }
        .file-upload-box { margin-top: 20px; padding: 15px; border-top: 1px solid #e2e8f0; }
        .result-box { display: none; margin-top: 25px; padding: 20px; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; }
        .result-box h3 { color: #166534; margin-bottom: 10px; }
        .result-text { word-break: break-all; font-family: monospace; background: #fff; padding: 10px; border-radius: 4px; border: 1px solid #e2e8f0; margin-bottom: 15px; }
    </style>
    <!-- Html5-Qrcode CDN -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
</head>
<body>

    <div class="navbar">
    <h2>QR Scanner Pro</h2>
    <div>
        <a href="index.php" style="background: #3b82f6; padding: 6px 12px; border-radius: 4px;">Scanner</a>
        <a href="generator.php" style="padding: 6px 12px;">Generator</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="dashboard.php" style="padding: 6px 12px;">Dashboard</a>
            <a href="#" id="logout-link" style="padding: 6px 12px; color: #ef4444;">Logout</a>
        <?php else: ?>
            <a href="test_auth.php" style="padding: 6px 12px;">Login / Register</a>
        <?php endif; ?>
    </div>
</div>

    <div class="container">
        <h2>Scan QR Code</h2>
        <p style="color: #64748b; margin-top: 5px;">Scan using your live camera or upload an image file</p>

        <!-- Camera Scanner Section -->
        <div class="scanner-box">
            <div id="reader"></div>
            <div style="margin-top: 15px;">
                <button id="start-cam-btn" class="btn">Start Camera Scanner</button>
                <button id="stop-cam-btn" class="btn btn-stop">Stop Camera</button>
            </div>
        </div>

        <!-- File Upload Section -->
        <div class="file-upload-box">
            <label for="qr-file-input" style="font-weight: bold; display: block; margin-bottom: 8px;">Or Upload Scanner Image:</label>
            <input type="file" id="qr-file-input" accept="image/*">
        </div>

        <!-- Decoded Result Box -->
        <div id="qr-result" class="result-box">
            <h3>Scan Result Detected!</h3>
            <div id="result-text" class="result-text"></div>
            <a id="action-btn" class="btn" href="#" target="_blank" style="display:none; text-decoration:none;">Open Link</a>
        </div>
    </div>

    <script src="assets/js/scanner.js"></script>
    <script>
        // Quick Logout handler
        const logoutLink = document.getElementById('logout-link');
        if(logoutLink) {
            logoutLink.addEventListener('click', (e) => {
                e.preventDefault();
                fetch('api/auth/logout.php')
                    .then(res => res.json())
                    .then(() => window.location.reload());
            });
        }
    </script>
</body>
</html>
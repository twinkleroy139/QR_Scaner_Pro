<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Generator</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background: #f4f7f6; color: #333; padding: 20px; }
        .navbar { display: flex; justify-content: space-between; align-items: center; background: #2c3e50; color: #fff; padding: 15px 30px; border-radius: 8px; margin-bottom: 30px; }
        .navbar a { color: #fff; text-decoration: none; margin-left: 15px; font-weight: bold; }
        .container { max-width: 650px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .form-group { margin-bottom: 20px; text-align: left; }
        label { display: block; font-weight: bold; margin-bottom: 8px; }
        input[type="text"], select, input[type="file"] { width: 100%; padding: 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 15px; }
        .btn { background: #10b981; color: white; padding: 12px 24px; border: none; border-radius: 6px; cursor: pointer; font-size: 16px; width: 100%; font-weight: bold; }
        .btn:disabled { background: #9ca3af; cursor: not-allowed; }
        .output-box { display: none; margin-top: 30px; text-align: center; padding: 20px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; }
        #qrcode-display { display: flex; justify-content: center; margin: 20px 0; }
        .btn-download { background: #3b82f6; display: inline-block; width: auto; text-decoration: none; padding: 10px 20px; }
    </style>
    <!-- QRCode.js CDN Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
</head>
<body>

    <div class="navbar">
    <h2>QR Scanner Pro</h2>
    <div>
        <a href="index.php" style="padding: 6px 12px;">Scanner</a>
        <a href="generator.php" style="background: #3b82f6; padding: 6px 12px; border-radius: 4px;">Generator</a>
        <a href="dashboard.php" style="padding: 6px 12px;">Dashboard</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="#" id="logout-link" style="padding: 6px 12px; color: #ef4444;">Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a>
        <?php else: ?>
            <a href="auth.php" style="padding: 6px 12px;">Login / Register</a>
        <?php endif; ?>
    </div>
</div>

    <div class="container">
        <h2 style="text-align: center; margin-bottom: 20px;">Create QR Code</h2>

        <div class="form-group">
            <label for="qr-type">Select Type:</label>
            <select id="qr-type">
                <option value="link">URL / Web Link</option>
                <option value="text">Plain Text</option>
                <option value="image">Photo / Image Upload</option>
            </select>
        </div>

        <div class="form-group" id="text-input-group">
            <label for="qr-text-input">Content / Link:</label>
            <input type="text" id="qr-text-input" placeholder="https://example.com or Enter text here...">
        </div>

        <div class="form-group" id="image-input-group" style="display: none;">
            <label for="qr-image-input">Select Photo / Image:</label>
            <input type="file" id="qr-image-input" accept="image/*">
        </div>

        <button id="generate-btn" class="btn">Generate QR Code</button>

        <div id="qr-output-box" class="output-box">
            <h3>Your Generated QR Code</h3>
            <div id="qrcode-display"></div>
            <a id="download-btn" class="btn btn-download" download="qrcode.png">Download QR Code</a>
        </div>
    </div>

    <script src="assets/js/generator.js"></script>
</body>
</html>
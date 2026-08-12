<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
$username = $isLoggedIn ? $_SESSION['username'] : 'Guest';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - QR Scanner Pro</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background: #f4f7f6; color: #333; padding: 20px; }
        .navbar { display: flex; justify-content: space-between; align-items: center; background: #2c3e50; color: #fff; padding: 15px 30px; border-radius: 8px; margin-bottom: 30px; }
        .navbar a { color: #fff; text-decoration: none; margin-left: 15px; font-weight: bold; }
        .container { max-width: 1000px; margin: 0 auto; }
        
        /* Guest Banner */
        .guest-banner { background: #fff3cd; border: 1px solid #ffe8a1; color: #856404; padding: 20px; border-radius: 10px; text-align: center; margin-bottom: 30px; }
        .btn-auth { background: #2563eb; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block; margin-top: 12px; }

        /* Stats Cards */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .card { background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.03); text-align: center; border-left: 5px solid #3b82f6; }
        .card h3 { font-size: 2em; color: #1e293b; margin-top: 5px; }
        .card p { color: #64748b; font-size: 0.9em; text-transform: uppercase; font-weight: 600; }

        /* History Table */
        .table-container { background: #fff; border-radius: 10px; padding: 25px; box-shadow: 0 4px 10px rgba(0,0,0,0.03); }
        .table-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { text-align: left; padding: 12px 15px; border-bottom: 1px solid #e2e8f0; }
        th { background: #f8fafc; color: #475569; font-size: 0.85em; text-transform: uppercase; }
        
        .badge { display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 0.75em; font-weight: bold; text-transform: uppercase; }
        .badge-scan { background: #e0f2fe; color: #0369a1; }
        .badge-generate { background: #dcfce7; color: #15803d; }
        
        .btn-action { padding: 6px 12px; border: none; border-radius: 4px; font-size: 0.85em; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn-copy { background: #cbd5e1; color: #1e293b; }
        .btn-visit { background: #3b82f6; color: white; margin-left: 5px; }
    </style>
</head>
<body>

    <div class="navbar">
    <h2>QR Scanner Pro</h2>
    <div>
        <a href="index.php" style="padding: 6px 12px;">Scanner</a>
        <a href="generator.php" style="padding: 6px 12px;">Generator</a>
        <a href="dashboard.php" style="background: #3b82f6; padding: 6px 12px; border-radius: 4px;">Dashboard</a>
        <?php if ($isLoggedIn): ?>
            <a href="#" id="logout-btn" style="padding: 6px 12px; color: #ef4444;">Logout</a>
        <?php else: ?>
            <a href="auth.php" style="padding: 6px 12px;">Login / Register</a>
        <?php endif; ?>
    </div>
</div>

    <div class="container">
        <h2 style="margin-bottom: 20px;">Welcome, <?php echo htmlspecialchars($username); ?>!</h2>

        <?php if (!$isLoggedIn): ?>
            <!-- Shown only for Guest users -->
            <div class="guest-banner">
                <h3>History Backup Disabled for Guests</h3>
                <p style="margin-top: 5px;">Scans and generated QR codes are not saved in guest mode. Log in or create an account to back up your scan history automatically across sessions.</p>
                <a href="test_auth.php" class="btn-auth">Login or Create Account</a>
            </div>
        <?php else: ?>
            <!-- Shown only for Logged-in users -->
            <div class="stats-grid">
                <div class="card" style="border-color: #3b82f6;">
                    <p>Total Scans Saved</p>
                    <h3 id="stat-scans">0</h3>
                </div>
                <div class="card" style="border-color: #10b981;">
                    <p>Total QR Generated</p>
                    <h3 id="stat-generated">0</h3>
                </div>
                <div class="card" style="border-color: #6366f1;">
                    <p>Total Activity</p>
                    <h3 id="stat-total">0</h3>
                </div>
            </div>

            <div class="table-container">
                <div class="table-header">
                    <h3>Your Saved QR History</h3>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Type</th>
                            <th>Content / Payload</th>
                            <th>Date & Time</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="history-tbody">
                        <tr><td colspan="5" style="text-align: center;">Loading history...</td></tr>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <?php if ($isLoggedIn): ?>
    <script>
        document.addEventListener('DOMContentLoaded', fetchHistory);

        function fetchHistory() {
            fetch('api/qr/get-history.php')
                .then(res => res.json())
                .then(result => {
                    if (!result.success) return;

                    const history = result.data;
                    const tbody = document.getElementById('history-tbody');
                    tbody.innerHTML = '';

                    let scanCount = 0;
                    let genCount = 0;

                    if (history.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="5" style="text-align: center;">No history recorded yet. Start scanning or generating!</td></tr>';
                        return;
                    }

                    history.forEach(item => {
                        if (item.scan_type === 'scan') scanCount++;
                        if (item.scan_type === 'generate') genCount++;

                        const isUrl = /^https?:\/\//i.test(item.qr_data);
                        const row = document.createElement('tr');

                        row.innerHTML = `
                            <td><span class="badge ${item.scan_type === 'scan' ? 'badge-scan' : 'badge-generate'}">${item.scan_type}</span></td>
                            <td>${item.content_type}</td>
                            <td style="max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="${item.qr_data}">${item.qr_data}</td>
                            <td>${new Date(item.created_at).toLocaleString()}</td>
                            <td>
                                <button class="btn-action btn-copy" onclick="copyText('${escapeHtml(item.qr_data)}')">Copy</button>
                                ${isUrl ? `<a href="${item.qr_data}" target="_blank" class="btn-action btn-visit">Open</a>` : ''}
                            </td>
                        `;
                        tbody.appendChild(row);
                    });

                    document.getElementById('stat-scans').innerText = scanCount;
                    document.getElementById('stat-generated').innerText = genCount;
                    document.getElementById('stat-total').innerText = history.length;
                })
                .catch(err => console.error('Error fetching history:', err));
        }

        function copyText(text) {
            navigator.clipboard.writeText(text).then(() => alert('Copied to clipboard!'));
        }

        function escapeHtml(text) {
            return text.replace(/'/g, "\\'").replace(/"/g, '&quot;');
        }

        const logoutBtn = document.getElementById('logout-btn');
        if (logoutBtn) {
            logoutBtn.addEventListener('click', (e) => {
                e.preventDefault();
                fetch('api/auth/logout.php')
                    .then(res => res.json())
                    .then(() => window.location.href = 'dashboard.php');
            });
        }
    </script>
    <?php endif; ?>
</body>
</html>
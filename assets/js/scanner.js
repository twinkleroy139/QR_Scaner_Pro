// assets/js/scanner.js
document.addEventListener('DOMContentLoaded', () => {
    const html5QrCode = new Html5Qrcode("reader");
    const resultContainer = document.getElementById("qr-result");
    const resultText = document.getElementById("result-text");
    const actionBtn = document.getElementById("action-btn");
    let isScanning = false;

    // Start Live Camera Scanner
    document.getElementById("start-cam-btn").addEventListener("click", () => {
        if (isScanning) return;

        Html5Qrcode.getCameras().then(devices => {
            if (devices && devices.length) {
                const cameraId = devices[0].id; // Primary camera
                html5QrCode.start(
                    cameraId,
                    { fps: 10, qrbox: { width: 250, height: 250 } },
                    (decodedText) => handleQrSuccess(decodedText),
                    (errorMessage) => { /* Ignore frame scan noise */ }
                ).then(() => {
                    isScanning = true;
                    document.getElementById("start-cam-btn").style.display = "none";
                    document.getElementById("stop-cam-btn").style.display = "inline-block";
                });
            } else {
                alert("No camera device found.");
            }
        }).catch(err => alert("Camera access error: " + err));
    });

    // Stop Live Camera
    document.getElementById("stop-cam-btn").addEventListener("click", () => {
        if (!isScanning) return;
        html5QrCode.stop().then(() => {
            isScanning = false;
            document.getElementById("start-cam-btn").style.display = "inline-block";
            document.getElementById("stop-cam-btn").style.display = "none";
        });
    });

    // File Upload Scanner
    document.getElementById("qr-file-input").addEventListener("change", (e) => {
        if (e.target.files.length === 0) return;
        const imageFile = e.target.files[0];

        html5QrCode.scanFile(imageFile, true)
            .then(decodedText => handleQrSuccess(decodedText))
            .catch(err => alert("No valid QR code detected in this image."));
    });

    // Handle Decoded QR Data
    function handleQrSuccess(decodedText) {
        resultContainer.style.display = "block";
        resultText.innerText = decodedText;

        // Detect URL vs Text
        const isUrl = /^https?:\/\//i.test(decodedText);
        if (isUrl) {
            actionBtn.href = decodedText;
            actionBtn.innerText = "Open Link";
            actionBtn.style.display = "inline-block";
        } else {
            actionBtn.style.display = "none";
        }

        // Save scan to database asynchronously if user is logged in
        const formData = new FormData();
        formData.append("qr_data", decodedText);
        formData.append("content_type", isUrl ? "url" : "text");
        formData.append("scan_type", "scan");

        fetch("api/qr/save-history.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(data => console.log("History Save Status:", data.message))
        .catch(err => console.error("Save error:", err));
    }
});
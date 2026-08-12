// assets/js/generator.js
document.addEventListener('DOMContentLoaded', () => {
    const qrTypeSelect = document.getElementById('qr-type');
    const textInputGroup = document.getElementById('text-input-group');
    const imageInputGroup = document.getElementById('image-input-group');
    const generateBtn = document.getElementById('generate-btn');
    const qrDisplay = document.getElementById('qrcode-display');
    const downloadBtn = document.getElementById('download-btn');
    
    let qrCodeObj = null;

    // Toggle Input Fields Based on Selection
    qrTypeSelect.addEventListener('change', () => {
        if (qrTypeSelect.value === 'image') {
            textInputGroup.style.display = 'none';
            imageInputGroup.style.display = 'block';
        } else {
            textInputGroup.style.display = 'block';
            imageInputGroup.style.display = 'none';
        }
    });

    // Handle Generation
    generateBtn.addEventListener('click', async () => {
        const type = qrTypeSelect.value;
        let contentToEncode = '';

        if (type === 'link' || type === 'text') {
            contentToEncode = document.getElementById('qr-text-input').value.trim();
            if (!contentToEncode) {
                alert('Please enter text or a URL.');
                return;
            }
            renderQrCode(contentToEncode, type === 'link' ? 'url' : 'text');
        } else if (type === 'image') {
            const fileInput = document.getElementById('qr-image-input');
            if (fileInput.files.length === 0) {
                alert('Please select an image file to upload.');
                return;
            }

            const formData = new FormData();
            formData.append('qr_image', fileInput.files[0]);

            generateBtn.innerText = 'Uploading Image...';
            generateBtn.disabled = true;

            try {
                const res = await fetch('api/qr/upload-image.php', { method: 'POST', body: formData });
                const data = await res.json();

                if (data.success) {
                    contentToEncode = data.image_url;
                    renderQrCode(contentToEncode, 'image');
                } else {
                    alert('Upload Error: ' + data.message);
                }
            } catch (err) {
                alert('Failed to upload image: ' + err);
            } finally {
                generateBtn.innerText = 'Generate QR Code';
                generateBtn.disabled = false;
            }
        }
    });

    // Render Canvas & Save to History
    function renderQrCode(dataText, contentType) {
        qrDisplay.innerHTML = ''; // Clear existing QR
        
        qrCodeObj = new QRCode(qrDisplay, {
            text: dataText,
            width: 200,
            height: 200,
            colorDark: "#000000",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });

        document.getElementById('qr-output-box').style.display = 'block';

        // Set Download Handler
        setTimeout(() => {
            const img = qrDisplay.querySelector('img');
            if (img) downloadBtn.href = img.src;
        }, 300);

        // Save generated QR to database asynchronously if user is logged in
        const formData = new FormData();
        formData.append("qr_data", dataText);
        formData.append("content_type", contentType);
        formData.append("scan_type", "generate");

        fetch("api/qr/save-history.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(data => console.log("History Save Status:", data.message))
        .catch(err => console.error("Save error:", err));
    }
});
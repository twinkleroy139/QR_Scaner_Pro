# 📷 QR Scanner Pro

A modern, responsive, full-stack QR Code Scanner and Generator web application built with PHP, Vanilla JavaScript, and MySQL.

🔗 **Live Demo:** [https://qr-scaner-pro.onrender.com](https://qr-scaner-pro.onrender.com)  
📂 **Repository:** [https://github.com/twinkleroy139/QR_Scaner_Pro](https://github.com/twinkleroy139/QR_Scaner_Pro)

---

## ✨ Features

- 🔍 **Live Camera Scanner:** Real-time QR code detection using device webcam/mobile camera.
- 🖼️ **Image File Scanner:** Scan QR codes directly from local image uploads.
- ⚡ **Dynamic QR Generator:**
  - Generate QR codes for plain text and web URLs.
  - Upload photos/images to generate instant shareable image QR codes.
  - High-resolution client-side QR downloads.
- 🔐 **User Authentication:** Secure user registration and login with bcrypt password hashing.
- 📊 **Activity Dashboard:** Logged-in users can track, manage, copy, and visit their scan and generator history across sessions.
- 🌐 **Guest Friendly:** Scanner and generator work seamlessly without requiring an account.

---

## 🛠️ Tech Stack

- **Backend:** PHP 8.2 (Apache, PDO MySQL)
- **Frontend:** HTML5, Modern CSS3, Vanilla JavaScript (ES6+)
- **Libraries:** [html5-qrcode](https://github.com/mebjas/html5-qrcode), [QRCode.js](https://davidshimjs.github.io/qrcodejs/)
- **Database:** MySQL (Cloud-hosted on Aiven)
- **Deployment & Hosting:** Docker on Render (HTTPS Enabled)

---

## 🚀 Local Development Setup

### 1. Clone the Repository
```bash
git clone [https://github.com/twinkleroy139/QR_Scaner_Pro.git](https://github.com/twinkleroy139/QR_Scaner_Pro.git)
cd QR_Scaner_Pro
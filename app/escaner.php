<!DOCTYPE html>
<html>
<head>
    <title>Escáner QR</title>
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
    <style>
        #scanner-container { width: 100%; max-width: 500px; margin: 0 auto; }
        #scan-result { margin-top: 20px; font-weight: bold; }
    </style>
</head>
<body>
    <div id="scanner-container">
        <video id="video" width="100%" autoplay playsinline></video>
    </div>
    <div id="scan-result"></div>

    <script>
        const video = document.getElementById('video');
        const resultDiv = document.getElementById('scan-result');

        // Acceder a la cámara
        navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
            .then(stream => {
                video.srcObject = stream;
                video.play();
                requestAnimationFrame(scanQR);
            })
            .catch(err => {
                resultDiv.innerText = "Error al acceder a la cámara: " + err.message;
            });

        // Escanear continuamente
        function scanQR() {
            const canvas = document.createElement('canvas');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            
            const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            const code = jsQR(imageData.data, imageData.width, imageData.height);
            
            if (code) {
                resultDiv.innerText = `QR detectado: ${code.data}`;
                // Verificar automáticamente con tu backend
                fetch(`verificar.php?codigo=${code.data}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.valid) {
                            alert(`Acceso permitido para: ${data.nombre}`);
                        } else {
                            alert("QR inválido o ya usado");
                        }
                    });
            } else {
                requestAnimationFrame(scanQR);
            }
        }
    </script>
</body>
</html>
document.addEventListener('DOMContentLoaded', () => {
  const registroForm = document.getElementById('registroForm');
  const downloadBtn = document.getElementById('downloadQR');

  registroForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    const formData = new FormData(registroForm);
    const response = await procesarRegistro(formData);

    if (response.success) {
      generarQR(response.codigo_qr);
      setupDownloadButton(response.codigo_qr); // Configurar botón

      alert("Registro exitoso. Presente este QR para ingresar.");
    } else {
      alert("Error: " + response.message);
    }
  });
});

// Procesar registro via Fetch API
async function procesarRegistro(formData) {
  try {
    const response = await fetch('registro.php', {
      method: 'POST',
      body: formData
    });
    return await response.json();
  } catch (error) {
    console.error("Error:", error);
    return { success: false, message: "Fallo en la conexión" };
  }
}

// Generar QR en el DOM
function generarQR(codigo) {
  const qrContainer = document.getElementById('qrCode');
  qrContainer.innerHTML = ''; // Limpiar QR previo
  new QRCode(qrContainer, {
    text: codigo,
    width: 200,
    height: 200
  });
}

// Configurar botón de descarga
function setupDownloadButton(codigo) {
  const downloadBtn = document.getElementById('downloadQR');
  const qrContainer = document.getElementById('qrCode');

  downloadBtn.disabled = false;

  downloadBtn.addEventListener('click', () => {
    const canvas = qrContainer.querySelector('canvas');
    if (!canvas) {
      alert("Primero genere un código QR");
      return;
    }

    // Crear enlace temporal
    const link = document.createElement('a');
    link.download = `QR_${codigo}.png`; // Nombre del archivo
    link.href = canvas.toDataURL('image/png');
    link.click();

    // Limpiar
    setTimeout(() => URL.revokeObjectURL(link.href), 100);
  });
}
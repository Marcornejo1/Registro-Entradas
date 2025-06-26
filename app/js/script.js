document.addEventListener('DOMContentLoaded', () => {
    const registroForm = document.getElementById('registroForm');
    
    registroForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const formData = new FormData(registroForm);
        const response = await procesarRegistro(formData);
        
        if (response.success) {
            generarQR(response.codigo_qr);
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
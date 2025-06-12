document.getElementById('registroForm').addEventListener('submit', async (e) => {
    e.preventDefault();

    const nombre = document.getElementById('nombre').value;
    const email = document.getElementById('email').value;

    const response = await fetch('procesarRegistro.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ nombre, email })
    });

    const data=await response.json();

    if(data.success){
        const qrContainer = document.getElementById('qrContainer');
        qrContainer.classList.remove('d-none');
    }
});
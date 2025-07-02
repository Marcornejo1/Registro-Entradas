// Función para mostrar notificaciones (añadir esto al inicio)
const mostrarNotificacion = (mensaje, tipo = 'success') => {
  const notificacion = document.createElement('div');
  notificacion.className = `notificacion ${tipo}`;
  notificacion.textContent = mensaje;
  document.body.appendChild(notificacion);

  setTimeout(() => {
    notificacion.remove();
  }, 3000);
};

// Renderizar la tabla
const renderTable = (data) => {
  const tbody = document.querySelector('tbody');
  if (!tbody) return;

  tbody.innerHTML = data.map(item => `
        <tr>
            <td>${item.id}</td>
            <td>${escapeHTML(item.nombre)}</td>
            <td class="${item.usado == 1 ? 'used' : 'pending'}">
                ${item.usado == 1 ? '✅ Usado' : '⏳ Pendiente'}
            </td>
        </tr>
    `).join('');
};

// Helper para seguridad XSS
const escapeHTML = (str) => {
  if (!str) return '';
  return str.toString()
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;');
};



// Actualizar datos
const updateTable = async () => {
  const loadingIndicator = document.getElementById('loadingIndicator');
// DEBUG: Verifica que el elemento existe
console.log('Elemento existe:', document.getElementById('loadingIndicator'));
  try {
    // Mostrar indicador
    loadingIndicator.classList.add('active');

    const response = await fetch('../api/registros.php');

    if (!response.ok) {
      throw new Error(`Error HTTP: ${response.status}`);
    }

    const data = await response.json();

    if (!data.success) {
      throw new Error(data.error || 'Error en los datos');
    }

    if (!Array.isArray(data.data)) {
      throw new Error('Formato de datos inválido');
    }

    renderTable(data.data);

  } catch (error) {
    console.error('Error al actualizar:', error);
    mostrarNotificacion(`⚠️ ${error.message}`, 'error');
  } finally {
    // Ocultar indicador (siempre se ejecuta)
    setTimeout(() => {
      loadingIndicator.classList.remove('active');
    }, 500); // Retraso para mejor UX
  }
};

// Iniciar
document.addEventListener('DOMContentLoaded', () => {
  // Cargar datos al inicio
  updateTable();

  // Actualizar cada 5 segundos
  setInterval(updateTable, 5000);
});
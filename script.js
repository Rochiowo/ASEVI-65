function cargarMedicos() {
    console.log('Iniciando cargarMedicos()');
    
    axios.get('get_medicos.php')
        .then(function (response) {
            console.log('Respuesta recibida:', response);
            let medicos = response.data;
            
            // Si la respuesta es un string, intenta parsearla como JSON
            if (typeof medicos === 'string') {
                try {
                    medicos = JSON.parse(medicos);
                    console.log('Datos parseados:', medicos);
                } catch (e) {
                    console.error('Error al parsear la respuesta:', e);
                    return;
                }
            }

            const contenedor = document.querySelector('.list-container');
            if (!contenedor) {
                console.error('No se encontró el contenedor .list-container');
                return;
            }

            let html = '';

            if (Array.isArray(medicos)) {
                console.log('Procesando array de médicos:', medicos);
                medicos.forEach(medico => {
                    if (!medico.nombre || !medico.matricula || !medico.especialidad) {
                        console.warn('Medico incompleto:', medico);
                        return;
                    }
                    html += `
                        <div class="doctor-card">
                            <img src="asetts/user.png" alt="Foto del médico" class="doctor-image">
                            <div class="doctor-info">
                                <p class="doctor-name">${medico.nombre}</p>
                                <p class="doctor-details">Matricula: ${medico.matricula}</p>
                                <p class="doctor-specialty">Especialidad: ${medico.especialidad}</p>
                            </div>
                            <img src="asetts/go.png" alt="Botón de flecha" class="arrow-button">
                        </div>
                    `;
                });
            } else {
                html = '<p>No se encontraron médicos.</p>';
            }

            console.log('HTML generado:', html);
            contenedor.innerHTML = html;
        })
        .catch(function (error) {
            console.error('Error al cargar los datos de los médicos:', error);
            document.querySelector('.list-container').innerHTML = '<p>Error al cargar los datos de los médicos.</p>';
        });
}

// Ejecuta la función cuando el DOM esté cargado
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM cargado, iniciando cargarMedicos()');
    cargarMedicos();
});
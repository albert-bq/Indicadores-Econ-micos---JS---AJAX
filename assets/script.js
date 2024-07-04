$(document).ready(function(){
    // URL base de la API que proporciona los indicadores económicos
    const api_url = "https://mindicador.cl/api";

    /**
     * Función para obtener el valor de un indicador específico desde la API
     * @param {string} indicador - El nombre del indicador que queremos obtener (ejemplo: 'dolar')
     * @param {function} callback - Función de callback que recibe el valor y la fecha del indicador
     */
    function getIndicador(indicador, callback) {
        // Realiza una solicitud GET a la API
        $.get(api_url, function(data){
            // Verifica si el indicador solicitado existe en la respuesta
            if (data[indicador]) {
                // Llama al callback con el valor y la fecha del indicador
                callback(data[indicador].valor, data[indicador].fecha);
            } else {
                // Si el indicador no se encuentra, llama al callback con valores nulos
                callback(null, null);
            }
        });
    }

    /**
     * Función para formatear números en formato de moneda chilena
     * @param {number} valor - El valor a formatear
     * @returns {string} - El valor formateado como una cadena de texto en formato moneda
     */
    function formatCurrency(valor) {
        // Convierte el valor a un número flotante y formatea con separador de miles y decimal
        return valor.toLocaleString('es-CL', { style: 'currency', currency: 'CLP' });
    }

    /**
     * Función para formatear el IPC con 3 decimales
     * @param {number} valor - El valor a formatear
     * @returns {string} - El valor formateado con 3 decimales
     */
    function formatPercentage(valor) {
        // Formatea el valor con 3 decimales
        return valor.toFixed(3) + '%';
    }

    /**
     * Función para formatear la fecha en formato dd-mm-YYYY
     * @param {string} fecha - La fecha en formato ISO 8601 (YYYY-MM-DD)
     * @returns {string} - La fecha formateada en formato dd-mm-YYYY
     */
    function formatDate(fecha) {
        const [year, month, day] = fecha.split('-');
        return `${day}-${month}-${year}`;
    }

    // Manejo del formulario de consulta dinámica
    $('#indicator-form').submit(function(event) {
        event.preventDefault(); // Previene el comportamiento por defecto del formulario

        const indicadorSeleccionado = $('#indicator-select').val(); // Obtiene el indicador seleccionado

        getIndicador(indicadorSeleccionado, function(valor, fecha) {
            let resultado;
            if (valor !== null && fecha !== null) {
                // Formatea el valor y la fecha
                let valorFormateado;
                if (indicadorSeleccionado === 'ipc') {
                    valorFormateado = formatPercentage(valor);
                } else {
                    valorFormateado = formatCurrency(valor);
                }
                const fechaFormateada = formatDate(fecha);

                // Prepara el texto del resultado
                resultado = `<p>Valor del ${indicadorSeleccionado.charAt(0).toUpperCase() + indicadorSeleccionado.slice(1)}: ${valorFormateado} (Fecha: ${fechaFormateada})</p>`;
            } else {
                // Mensaje si el indicador no se encuentra
                resultado = `<p>Indicador ${indicadorSeleccionado.charAt(0).toUpperCase() + indicadorSeleccionado.slice(1)} no encontrado</p>`;
            }

            // Muestra el resultado en el div correspondiente
            $('#indicator-result').html(resultado);
        });
    });

    // Ejemplo de uso para el indicador 'dolar'
    getIndicador('dolar', function(valor, fecha) {
        if (valor !== null && fecha !== null) {
            // Formatea el valor y la fecha
            const valorFormateado = formatCurrency(valor);
            const fechaFormateada = formatDate(fecha);

            // Muestra el valor del dólar y la fecha formateada en el elemento con id "indicador-valor"
            $('#indicador-valor').text(`Valor del Dólar: ${valorFormateado} (Fecha: ${fechaFormateada})`);
        } else {
            // Muestra un mensaje si el indicador no se encuentra
            $('#indicador-valor').text('Indicador no encontrado');
        }
    });

    // Ejemplo de uso para el indicador 'ipc'
    getIndicador('ipc', function(valor, fecha) {
        if (valor !== null && fecha !== null) {
            // Formatea el valor y la fecha
            const valorFormateado = formatPercentage(valor);
            const fechaFormateada = formatDate(fecha);

            // Muestra el valor del IPC y la fecha formateada en el elemento con id "indicador-valor"
            $('#indicador-valor').append(`<p>Valor del IPC: ${valorFormateado} (Fecha: ${fechaFormateada})</p>`);
        } else {
            // Muestra un mensaje si el indicador no se encuentra
            $('#indicador-valor').append('<p>Indicador IPC no encontrado</p>');
        }
    });
});

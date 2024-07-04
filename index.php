<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Indicadores Económicos - Guía de Uso</title>
    <link rel="stylesheet" href="assets/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/script.js"></script>
</head>
<body>
    <div class="container">
        <header>
            <h1>Indicadores Económicos - Guía de Uso</h1>
        </header>

        <section class="dynamic-content">
            <h2>Consulta Dinámica de Indicadores</h2>
            <form id="indicator-form">
                <label for="indicator-select">Selecciona un indicador:</label>
                <select id="indicator-select" name="indicator">
                    <option value="dolar">Dólar</option>
                    <option value="euro">Euro</option>
                    <option value="uf">UF</option>
                    <option value="ivp">IVP</option>
                    <option value="utm">UTM</option>
                    <option value="libra_cobre">Libra de Cobre</option>
                    <option value="tasa_cambio">Tasa de Cambio</option>
                    <option value="ipc">IPC</option>
                </select>
                <button type="submit">Obtener Valor</button>
            </form>
            <div id="indicator-result">
                <!-- El resultado del indicador seleccionado se mostrará aquí -->
            </div>
        </section>

        <section class="instructions">
            <h2>Introducción</h2>
            <p>Este documento proporciona instrucciones para integrar el script que obtiene indicadores económicos de Chile en tu sitio web. El script utiliza jQuery y AJAX para recuperar los datos.</p>

            <h2>Lista de Indicadores</h2>
            <p>A continuación se muestra la lista de indicadores disponibles y sus nombres en código que puedes utilizar:</p>
            <ul>
                <li><code>dolar</code> - Dólar observado</li>
                <li><code>euro</code> - Euro observado</li>
                <li><code>uf</code> - Unidad de Fomento (UF)</li>
                <li><code>ivp</code> - Índice de Valor Promedio (IVP)</li>
                <li><code>utm</code> - Unidad Tributaria Mensual (UTM)</li>
                <li><code>libra_cobre</code> - Libra de cobre</li>
                <li><code>tasa_cambio</code> - Tasa de cambio</li>
                <li><code>ipc</code> - Índice de Precios al Consumidor (IPC)</li>
            </ul>

            <h2>Instrucciones de Uso</h2>
            <p>Sigue estos pasos para utilizar el script:</p>
            <ol>
                <li>Asegúrate de que jQuery esté incluido en tu página. Puedes añadirlo mediante el siguiente enlace CDN:</li>
                <pre><code class="language-html">&lt;script src="https://code.jquery.com/jquery-3.6.0.min.js"&gt;&lt;/script&gt;</code></pre>
                <li>Agrega el siguiente contenedor <code>&lt;div&gt;</code> en tu HTML donde desees mostrar el valor del indicador:</li>
                <pre><code class="language-html">&lt;div id="indicador-valor"&gt;&lt;/div&gt;</code></pre>
                <li>Incorpora el siguiente código JavaScript para obtener y mostrar el indicador:</li>
                <pre><code class="language-javascript">
// Espera a que el documento esté completamente cargado
$(document).ready(function(){
    // URL de la API que proporciona los indicadores económicos
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
     * Función para formatear la fecha en formato dd-mm-YYYY
     * @param {string} fecha - La fecha en formato ISO 8601 (YYYY-MM-DD)
     * @returns {string} - La fecha formateada en formato dd-mm-YYYY
     */
    function formatDate(fecha) {
        const [year, month, day] = fecha.split('-');
        return `${day}-${month}-${year}`;
    }

    // --- Ejemplo de uso de las funciones ---

    // 1. Obtén el valor del indicador 'dolar'
    getIndicador('dolar', function(valor, fecha) {
        if (valor) {
            // 2. Formatea el valor y la fecha
            const valorFormateado = formatCurrency(valor);
            const fechaFormateada = formatDate(fecha);

            // 3. Muestra el valor del dólar y la fecha formateada en el elemento con id "indicador-valor"
            $('#indicador-valor').text(`Valor del Dólar: ${valorFormateado} (Fecha: ${fechaFormateada})`);
        } else {
            // Muestra un mensaje si el indicador no se encuentra
            $('#indicador-valor').text('Indicador no encontrado');
        }
    });

    // 4. Obtén el valor del indicador 'ipc' (Índice de Precios al Consumidor)
    getIndicador('ipc', function(valor, fecha) {
        if (valor) {
            // Formatea el valor y la fecha
            const valorFormateado = formatCurrency(valor);
            const fechaFormateada = formatDate(fecha);

            // Muestra el valor del IPC y la fecha formateada en el elemento con id "indicador-valor"
            $('#indicador-valor').append(`<p>Valor del IPC: ${valorFormateado} (Fecha: ${fechaFormateada})</p>`);
        } else {
            // Muestra un mensaje si el indicador no se encuentra
            $('#indicador-valor').append('<p>Indicador IPC no encontrado</p>');
        }
    });

    // Manejo del formulario de consulta dinámica
    $('#indicator-form').submit(function(event) {
        event.preventDefault(); // Previene el comportamiento por defecto del formulario

        const indicadorSeleccionado = $('#indicator-select').val(); // Obtiene el indicador seleccionado

        getIndicador(indicadorSeleccionado, function(valor, fecha) {
            let resultado;
            if (valor) {
                // Formatea el valor y la fecha
                const valorFormateado = formatCurrency(valor);
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

    // Puedes llamar a getIndicador con diferentes indicadores cambiando el primer parámetro
    // Por ejemplo, para obtener el valor de la UF:
    /*
    getIndicador('uf', function(valor, fecha) {
        if (valor) {
            const valorFormateado = formatCurrency(valor);
            const fechaFormateada = formatDate(fecha);
            $('#indicador-valor').text(`Valor de la UF: ${valorFormateado} (Fecha: ${fechaFormateada})`);
        } else {
            $('#indicador-valor').text('Indicador no encontrado');
        }
    });
    */
});
                </code></pre>

            <h2>Conclusión</h2>
            <p>Este script es una herramienta útil para mostrar indicadores económicos de Chile en tu sitio web. Puedes personalizarlo según tus necesidades y agregar más indicadores si es necesario.</p>
        </section>
    </div>
</body>
</html>

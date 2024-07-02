$(document).ready(function(){
    const api_url = "https://mindicador.cl/api";

    // Función para obtener el valor de un indicador
    function getIndicador(indicador, callback) {
        $.get(api_url, function(data){
            if (data[indicador]) {
                callback(data[indicador].valor, data[indicador].fecha);
            } else {
                callback(null, null);
            }
        });
    }

    // Ejemplo de uso de la función
    getIndicador('dolar', function(valor, fecha) {
        if (valor) {
            $('#indicador-valor').text(`Valor del Dólar: ${valor} (Fecha: ${fecha})`);
        } else {
            $('#indicador-valor').text('Indicador no encontrado');
        }
    });
});

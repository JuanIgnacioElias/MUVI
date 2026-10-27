<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selección de Butacas - MUVI</title>
    <!-- Vinculación al archivo CSS externo -->
    <link rel="stylesheet" href="/css/estilos_sala.css">

</head>
<body>

    <h1>Elige tus asientos</h1>
    <p>Sala del Cine</p>

    <!-- Simulación de la Pantalla del Cine -->
    <div class="pantalla-contenedor">
        <div class="pantalla"></div>
        <div class="pantalla-texto">Pantalla</div>
    </div>

    <!-- Mapa de la Sala -->
    <div class="sala-contenedor" id="salaCinema">
        <!-- Las filas y butacas reales se dibujan aquí mediante JS -->
    </div>

    <!-- Referencia de colores -->
    <div class="leyenda">
        <div class="leyenda-item">
            <div class="leyenda-color disponible"></div>
            <span>Disponible</span>
        </div>
        <div class="leyenda-item">
            <div class="leyenda-color seleccionada"></div>
            <span>Tu Selección</span>
        </div>
        <div class="leyenda-item">
            <div class="leyenda-color ocupada"></div>
            <span>Ocupado</span>
        </div>
    </div>

    <script>
        // Tu URL real obtenida de php spark serve
        const API_URL = 'http://localhost:8080/index.php/butacacontroller';


        async function cargarSala() {
            // Sala de prueba que se va a mostrar siempre por defecto
            const salaPorDefecto = [
                { posicionX: 'A', posicionY: 1, disponibilidad: 1 },
                { posicionX: 'A', posicionY: 2, disponibilidad: 0 },
                { posicionX: 'A', posicionY: 3, disponibilidad: 1 },
                { posicionX: 'A', posicionY: 4, disponibilidad: 1 },
                { posicionX: 'B', posicionY: 1, disponibilidad: 1 },
                { posicionX: 'B', posicionY: 2, disponibilidad: 1 },
                { posicionX: 'B', posicionY: 3, disponibilidad: 0 },
                { posicionX: 'B', posicionY: 4, disponibilidad: 1 },
            ];
            
            try {
                const respuesta = await fetch(API_URL);
                
                if (!respuesta.ok) {
                    throw new Error();
                }
                
                const butacas = await respuesta.json();
                
                if (butacas.length === 0) {
                    renderizarSala(salaPorDefecto);
                    return;
                }
                
                renderizarSala(butacas);
                
            } catch (error) {
                // Si la conexión falla, carga la sala de prueba en silencio sin mostrar carteles molestos
                renderizarSala(salaPorDefecto);
            }
        }
                renderizarSala(butacas);
                
            } catch (error) {
                console.error("Error al obtener las butacas:", error);
                // Si tu servidor está apagado o hay un problema de red
                sala.innerHTML = '<p style="color: #FFFFFF; background: rgba(255,0,0,0.2); padding: 15px; border-radius: 8px;">' +
                                 'No se pudo conectar con el servidor de MUVI. Verifique su conexión.</p>';
            }
        }

        function renderizarSala(butacas) {
            const sala = document.getElementById('salaCinema');
            sala.innerHTML = ''; 

            const filas = {};
            butacas.forEach(b => {
                if (!filas[b.posicionX]) filas[b.posicionX] = [];
                filas[b.posicionX].push(b);
            });

            for (const letraFila in filas) {
                const divFila = document.createElement('div');
                divFila.className = 'fila';

                const spanLetra = document.createElement('span');
                spanLetra.className = 'letra-fila';
                spanLetra.textContent = letraFila;
                divFila.appendChild(spanLetra);

                filas[letraFila].sort((a, b) => a.posicionY - b.posicionY);

                filas[letraFila].forEach(butaca => {
                    const botonAsiento = document.createElement('button');
                    botonAsiento.className = 'butaca';
                    botonAsiento.textContent = butaca.posicionY;

                    if (parseInt(butaca.disponibilidad) === 0) {
                        botonAsiento.classList.add('ocupada');
                        botonAsiento.disabled = true;
                    } else {
                        botonAsiento.addEventListener('click', () => {
                            botonAsiento.classList.toggle('seleccionada');
                            console.log(`Butaca seleccionada: Fila ${butaca.posicionX} - Asiento ${butaca.posicionY}`);
                        });
                    }

                    divFila.appendChild(botonAsiento);
                });

                sala.appendChild(divFila);
            }
        }

        cargarSala();
    </script>
</body>
</html>

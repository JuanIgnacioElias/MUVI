<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selección de Butacas - MUVI</title>
    <!-- Tu archivo CSS externo -->
    <link rel="stylesheet" href="/css/estilos_sala.css">
    
    <style>
        /* Estilo para crear el pasillo del medio en la sala de 250 personas */
        .butaca:nth-child(13) {
            margin-right: 30px; /* Crea una separación simulando el pasillo central */
        }
        /* Asegura que el contenedor de la sala sea lo suficientemente ancho para las 25 butacas */
        .sala-contenedor {
            max-width: 100%;
            overflow-x: auto; /* Agrega scroll horizontal si la pantalla es muy chica */
            padding: 30px 20px;
        }

        /* Estilos específicos para el nuevo botón Siguiente */
        .contenedor-boton-siguiente {
            margin-top: 40px;
            text-align: center;
        }
        .btn-siguiente-flujo {
            background-color: #e50914; /* Rojo corporativo MUVI */
            color: #ffffff;
            border: none;
            padding: 14px 45px;
            font-size: 18px;
            font-weight: bold;
            border-radius: 30px;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(229, 9, 20, 0.4);
            transition: background-color 0.2s, transform 0.1s;
        }
        .btn-siguiente-flujo:hover:not(:disabled) {
            background-color: #b91c1c;
            transform: scale(1.05);
        }
        .btn-siguiente-flujo:disabled {
            background-color: #555555;
            color: #aaaaaa;
            cursor: not-allowed;
            box-shadow: none;
            transform: none;
        }
    </style>
</head>
<body>
<?= view('templates/header') ?>
    
    <div class="contenedor-vista-butacas" style="max-width: 1100px; margin: 40px auto; padding: 20px; text-align: center; color: #ffffff; font-family: Arial, sans-serif;">
        <h1 style="color: #ffffff !important; font-size: 2.5rem; margin-bottom: 5px;">Elige tus asientos</h1>
        <p style="color: #cccccc !important; font-size: 1.1rem; margin-bottom: 30px;">Sala del Cine (Capacidad: 250 personas)</p>

        <!-- Simulación de la Pantalla del Cine -->
        <div class="pantalla-contenedor" style="width: 90%; margin: 0 auto 40px auto;">
            <div class="pantalla"></div>
            <div class="pantalla-texto">Pantalla</div>
        </div>

        <!-- Mapa de la Sala -->
        <div class="sala-contenedor" id="salaCinema">
            <!-- Las 250 butacas se dibujarán aquí automáticamente -->
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

        <!-- AQUÍ SE AGREGÓ EL BOTÓN SIGUIENTE -->
        <div class="contenedor-boton-siguiente">
            <button id="btnSiguiente" class="btn-siguiente-flujo" disabled>
                Siguiente
            </button>
        </div>
    </div>

    <script>
        const API_URL = 'http://localhost:8080/index.php/butacacontroller';

        // Arreglo global para acumular las butacas que el usuario vaya seleccionando
        let asientosSeleccionados = [];

        // Función auxiliar para generar las 250 butacas de prueba (10 filas x 25 asientos)
        function generarSalaDeRespaldo() {
            const sala = [];
            const filas = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];
            
            filas.forEach(letra => {
                for (let asiento = 1; asiento <= 25; asiento++) {
                    const randomDisponibilidad = Math.random() > 0.15 ? 1 : 0; 
                    
                    sala.push({
                        posicionX: letra,
                        posicionY: asiento,
                        disponibilidad: randomDisponibilidad
                    });
                }
            });
            return sala;
        }

        async function cargarSala() {
            try {
                const respuesta = await fetch(API_URL);
                
                if (!respuesta.ok) {
                    throw new Error("Fallo en la petición");
                }
                
                const butacas = await respuesta.json();
                
                if (!butacas || butacas.length === 0) {
                    renderizarSala(generarSalaDeRespaldo());
                    return;
                }
                
                renderizarSala(butacas);
                
            } catch (error) {
                console.log("Cargando el mapa de simulación para 250 personas...");
                renderizarSala(generarSalaDeRespaldo());
            }
        }

        function renderizarSala(butacas) {
            const sala = document.getElementById('salaCinema');
            if (!sala) return;
            
            sala.innerHTML = ''; 

            const filas = {};
            butacas.forEach(b => {
                if (!filas[b.posicionX]) filas[b.posicionX] = [];
                filas[b.posicionX].push(b);
            });

            const letrasOrdenadas = Object.keys(filas).sort();

            letrasOrdenadas.forEach(letraFila => {
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
                            // Cambia el color visual del botón (.seleccionada)
                            botonAsiento.classList.toggle('seleccionada');
                            
                            const identificador = `${butaca.posicionX}-${butaca.posicionY}`;
                            const index = asientosSeleccionados.findIndex(a => a.id === identificador);

                            if (index > -1) {
                                // Si ya estaba seleccionado, lo removemos de la lista (Deselección)
                                asientosSeleccionados.splice(index, 1);
                            } else {
                                // Si es nuevo, lo guardamos en el arreglo estructurado
                                asientosSeleccionados.push({
                                    id: identificador,
                                    fila: butaca.posicionX,
                                    asiento: butaca.posicionY
                                });
                            }

                            // Habilitar o deshabilitar el botón Siguiente dependiendo si hay al menos un asiento elegido
                            const botonSiguiente = document.getElementById('btnSiguiente');
                            if (asientosSeleccionados.length > 0) {
                                botonSiguiente.disabled = false;
                            } else {
                                botonSiguiente.disabled = true;
                            }

                            console.log("Asientos elegidos actualmente:", asientosSeleccionados);
                        });
                    }

                    divFila.appendChild(botonAsiento);
                });

                sala.appendChild(divFila);
            });
        }

        // CONTROL DEL EVENTO CLICK EN EL BOTÓN SIGUIENTE
        document.getElementById('btnSiguiente').addEventListener('click', () => {
            // 1. Guardamos el array de asientos convirtiéndolo en un string JSON dentro del LocalStorage
            localStorage.setItem('asientos_muvi', JSON.stringify(asientosSeleccionados));
            
            // 2. Redireccionamos directamente a la URL de la pantalla de combos de comida
            window.location.href = 'http://localhost:8080/index.php/compra/combos';
        });

        document.addEventListener("DOMContentLoaded", cargarSala);
    </script>
    <?= view('templates/footer') ?>
</body>
</html>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selección de Butacas - MUVI</title>
    <link rel="stylesheet" href="/css/estilos_sala.css">
</head>
<body>
<?= view('templates/header') ?>
    
    <div class="contenedor-vista-butacas" style="max-width: 1100px; margin: 40px auto; padding: 20px; text-align: center; color: #ffffff; font-family: Arial, sans-serif;">
        <h1 style="color: #ffffff !important; font-size: 2.5rem; margin-bottom: 5px;">Elige tus asientos</h1>
        <p id="textoCapacidad" style="color: #cccccc !important; font-size: 1.1rem; margin-bottom: 20px;">Sala del Cine (Cargando...)</p>

        <!-- PANEL VISUAL SOLO PARA EMPLEADO Y ADMINISTRADOR -->
        <?php if (!empty($puedeEditar) && $puedeEditar): ?>
        <div class="panel-edicion-sala">
            <button type="button" id="btnToggleEditar" class="btn-editar-sala">✏️ Editar Sala</button>
            
            <div id="herramientasEdicion" class="herramientas-edicion" style="display: none;">
                <div class="grupo-controles">
                    <span>Filas:</span>
                    <button type="button" onclick="modificarFilas(1)">+ Fila</button>
                    <button type="button" onclick="modificarFilas(-1)">- Fila</button>
                </div>
                <div class="grupo-controles">
                    <span>Columnas:</span>
                    <button type="button" onclick="modificarColumnas(1)">+ Col</button>
                    <button type="button" onclick="modificarColumnas(-1)">- Col</button>
                </div>
                <button type="button" id="btnGuardarSala" class="btn-guardar-sala">💾 Guardar Cambios</button>
            </div>
            <p id="ayudaEdicion" class="ayuda-edicion" style="display: none;">
                Hacé clic sobre cualquier cuadro para agregar o quitar una butaca (crear pasillos).
            </p>
        </div>
        <?php endif; ?>

        <!-- Simulación de la Pantalla del Cine -->
        <div class="pantalla-contenedor" style="width: 90%; margin: 20px auto 40px auto;">
            <div class="pantalla"></div>
            <div class="pantalla-texto">Pantalla</div>
        </div>

        <!-- Mapa de la Sala -->
        <div class="sala-contenedor" id="salaCinema"></div>

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

        <div class="contenedor-boton-siguiente" id="contenedorBtnSiguiente">
            <button id="btnSiguiente" class="btn-siguiente-flujo" disabled>
                Siguiente
            </button>
        </div>
    </div>

    <script>
        const ID_SALA = <?= (int) ($id_sala ?? 1) ?>;
        const BASE_URL = '<?= base_url('index.php') ?>';
        const API_URL = `${BASE_URL}/butacacontroller?id_sala=${ID_SALA}`;
        const GUARDAR_URL = `${BASE_URL}/admin/salas/guardar/${ID_SALA}`;
        const ABECEDARIO = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        let asientosSeleccionados = [];
        let modoEdicion = false;
        
        // Matriz visual: filas de objetos { esButaca: bool, disponibilidad: 1|0 }
        let grillaSala = []; 

        function generarSalaDeRespaldo() {
            const sala = [];
            const filas = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];
            filas.forEach(letra => {
                for (let asiento = 1; asiento <= 20; asiento++) {
                    sala.push({
                        posicionX: letra,
                        posicionY: asiento,
                        disponibilidad: 1
                    });
                }
            });
            return sala;
        }

        // Convierte la lista plana de la BD en una grilla editable con pasillos detectados
        function construirGrillaDesdeBD(butacas) {
            const filasMap = {};
            let maxColumna = 0;

            butacas.forEach(b => {
                if (!filasMap[b.posicionX]) filasMap[b.posicionX] = [];
                const col = parseInt(b.posicionY);
                if (col > maxColumna) maxColumna = col;
                filasMap[b.posicionX].push(b);
            });

            const letrasOrdenadas = Object.keys(filasMap).sort();
            if (maxColumna < 10) maxColumna = 15;

            grillaSala = letrasOrdenadas.map(letra => {
                const filaCeldas = [];
                const asientosFila = filasMap[letra];

                for (let c = 1; c <= maxColumna; c++) {
                    const encontrado = asientosFila.find(a => parseInt(a.posicionY) === c);
                    if (encontrado) {
                        filaCeldas.push({
                            esButaca: true,
                            disponibilidad: parseInt(encontrado.disponibilidad)
                        });
                    } else {
                        filaCeldas.push({
                            esButaca: false,
                            disponibilidad: 1
                        });
                    }
                }
                return filaCeldas;
            });
        }

        async function cargarSala() {
            try {
                const respuesta = await fetch(API_URL);
                if (!respuesta.ok) throw new Error("Fallo en la petición");
                const butacas = await respuesta.json();
                
                if (!butacas || butacas.length === 0) {
                    construirGrillaDesdeBD(generarSalaDeRespaldo());
                } else {
                    construirGrillaDesdeBD(butacas);
                }
            } catch (error) {
                construirGrillaDesdeBD(generarSalaDeRespaldo());
            }
            renderizarSala();
        }

        function renderizarSala() {
            const sala = document.getElementById('salaCinema');
            if (!sala) return;
            
            sala.innerHTML = '';
            sala.className = modoEdicion ? 'sala-contenedor modo-edicion' : 'sala-contenedor';

            let totalButacasActivas = 0;

            grillaSala.forEach((fila, indexFila) => {
                const letraFila = ABECEDARIO[indexFila] || `F${indexFila + 1}`;
                const divFila = document.createElement('div');
                divFila.className = 'fila';

                const spanLetra = document.createElement('span');
                spanLetra.className = 'letra-fila';
                spanLetra.textContent = letraFila;
                divFila.appendChild(spanLetra);

                let numeroVisible = 1;

                fila.forEach((celda, indexCol) => {
                    const botonAsiento = document.createElement('button');
                    botonAsiento.type = 'button';

                    if (celda.esButaca) {
                        totalButacasActivas++;
                        const numActual = numeroVisible++;
                        botonAsiento.className = 'butaca';
                        botonAsiento.textContent = numActual;

                        if (!modoEdicion && celda.disponibilidad === 0) {
                            botonAsiento.classList.add('ocupada');
                            botonAsiento.disabled = true;
                        }

                        botonAsiento.addEventListener('click', () => {
                            if (modoEdicion) {
                                // En modo edición: convierte butaca en pasillo
                                grillaSala[indexFila][indexCol].esButaca = false;
                                renderizarSala();
                            } else {
                                // En modo compra normal: selecciona asiento
                                botonAsiento.classList.toggle('seleccionada');
                                const identificador = `${letraFila}-${numActual}`;
                                const idx = asientosSeleccionados.findIndex(a => a.id === identificador);

                                if (idx > -1) {
                                    asientosSeleccionados.splice(idx, 1);
                                } else {
                                    asientosSeleccionados.push({
                                        id: identificador,
                                        fila: letraFila,
                                        asiento: numActual
                                    });
                                }
                                document.getElementById('btnSiguiente').disabled = asientosSeleccionados.length === 0;
                            }
                        });
                    } else {
                        // Es un pasillo / espacio vacío
                        botonAsiento.className = 'butaca pasillo';
                        botonAsiento.textContent = modoEdicion ? '+' : '';
                        botonAsiento.disabled = !modoEdicion;

                        if (modoEdicion) {
                            botonAsiento.addEventListener('click', () => {
                                // En modo edición: agrega una butaca en este espacio
                                grillaSala[indexFila][indexCol] = { esButaca: true, disponibilidad: 1 };
                                renderizarSala();
                            });
                        }
                    }

                    divFila.appendChild(botonAsiento);
                });

                sala.appendChild(divFila);
            });

Usa el código con precaución.
document.getElementById('textoCapacidad').textContent =
Sala ${ID_SALA} (Capacidad actual: ${totalButacasActivas} butacas);
}
// --- CONTROLES DEL MODO EDICIÓN ---
const btnToggleEditar = document.getElementById('btnToggleEditar');
if (btnToggleEditar) {
btnToggleEditar.addEventListener('click', () => {
modoEdicion = !modoEdicion;
asientosSeleccionados = [];
document.getElementById('btnSiguiente').disabled = true;
document.getElementById('herramientasEdicion').style.display = modoEdicion ? 'inline-flex' : 'none';
document.getElementById('ayudaEdicion').style.display = modoEdicion ? 'block' : 'none';
document.getElementById('contenedorBtnSiguiente').style.display = modoEdicion ? 'none' : 'block';
btnToggleEditar.textContent = modoEdicion ? '❌ Cancelar Edición' : '✏️ Editar Sala';
btnToggleEditar.classList.toggle('activo', modoEdicion);
if (!modoEdicion) {
cargarSala(); // Si cancela, recarga el estado original
} else {
renderizarSala();
}
});
document.getElementById('btnGuardarSala').addEventListener('click', async () => {
const butacasParaGuardar = [];
grillaSala.forEach((fila, indexFila) => {
const letraFila = ABECEDARIO[indexFila] || F${indexFila + 1};
fila.forEach((celda, indexCol) => {
if (celda.esButaca) {
// Guardamos posicionY como el índice de columna (indexCol + 1) para mantener los pasillos exactos
butacasParaGuardar.push({
posicionX: letraFila,
posicionY: indexCol + 1,
disponibilidad: celda.disponibilidad
});
}
});
});
try {
const resp = await fetch(GUARDAR_URL, {
method: 'POST',
headers: { 'Content-Type': 'application/json' },
body: JSON.stringify({ butacas: butacasParaGuardar })
});
const resultado = await resp.json();
if (resp.ok) {
alert('✅ ¡Diagrama de la sala guardado correctamente!');
modoEdicion = false;
document.getElementById('herramientasEdicion').style.display = 'none';
document.getElementById('ayudaEdicion').style.display = 'none';
document.getElementById('contenedorBtnSiguiente').style.display = 'block';
btnToggleEditar.textContent = '✏️ Editar Sala';
btnToggleEditar.classList.remove('activo');
cargarSala();
} else {
alert('Error: ' + (resultado.messages?.error || 'No se pudo guardar'));
}
} catch (err) {
alert('Error de conexión al guardar la sala.');
}
});
}
function modificarFilas(delta) {
if (delta > 0 && grillaSala.length < 26) {
const cols = grillaSala.length > 0 ? grillaSala[0].length : 15;
const nuevaFila = Array.from({ length: cols }, () => ({ esButaca: true, disponibilidad: 1 }));
grillaSala.push(nuevaFila);
} else if (delta < 0 && grillaSala.length > 1) {
grillaSala.pop();
}
renderizarSala();
}
function modificarColumnas(delta) {
if (grillaSala.length === 0) return;
const colsActuales = grillaSala[0].length;
if (delta > 0 && colsActuales < 30) {
grillaSala.forEach(fila => fila.push({ esButaca: true, disponibilidad: 1 }));
} else if (delta < 0 && colsActuales > 1) {
grillaSala.forEach(fila => fila.pop());
}
renderizarSala();
}
// CONTROL DEL EVENTO CLICK EN EL BOTÓN SIGUIENTE
document.getElementById('btnSiguiente').addEventListener('click', () => {
localStorage.setItem('asientos_muvi', JSON.stringify(asientosSeleccionados));
window.location.href = ${BASE_URL}/combos;
});
document.addEventListener("DOMContentLoaded", cargarSala);
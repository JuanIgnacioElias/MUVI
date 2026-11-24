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

        <div class="pantalla-contenedor" style="width: 90%; margin: 20px auto 40px auto;">
            <div class="pantalla"></div>
            <div class="pantalla-texto">Pantalla</div>
        </div>

        <div class="sala-contenedor" id="salaCinema"></div>

        <div class="leyenda">
            <div class="leyenda-item"><div class="leyenda-color disponible"></div><span>Disponible</span></div>
            <div class="leyenda-item"><div class="leyenda-color seleccionada"></div><span>Tu Selección</span></div>
            <div class="leyenda-item"><div class="leyenda-color ocupada"></div><span>Ocupado</span></div>
        </div>
               <div class="contenedor-boton-siguiente" id="contenedorBtnSiguiente">
            <form action="<?= base_url('butacas/seleccionar') ?>" method="POST" id="formSeleccionButacas">
                <?= csrf_field() ?>
                <input type="hidden" name="asientos" id="inputAsientosSeleccionados">
                <button type="submit" id="btnSiguiente" class="btn-siguiente-flujo" disabled>Siguiente</button>
            </form>
        </div>

    </div>
           <script>
        
        const ID_SALA = <?= (int) ($id_sala ?? 1) ?>;
        const BASE_URL = '<?= base_url('index.php') ?>';
        const API_URL = `${BASE_URL}/butacacontroller?id_sala=${ID_SALA}`;
        const GUARDAR_URL = `${BASE_URL}/admin/salas/guardar/${ID_SALA}`;
        const ABECEDARIO = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        let asientosSeleccionados = new Array(), modoEdicion = false, grillaSala = new Array();

        function generarSalaDeRespaldo() {
            const sala = new Array();
            new Array('A','B','C','D','E','F','G','H','I','J').forEach(letra => {
                for (let asiento = 1; asiento <= 20; asiento++) sala.push({ posicionX: letra, posicionY: asiento, disponibilidad: 1 });
            });
            return sala;
        }

        function construirGrillaDesdeBD(butacas) {
            const filasMap = {};
            let maxCol = 0;
            butacas.forEach(b => {
                if (!filasMap[b.posicionX]) filasMap[b.posicionX] = new Array();
                const col = parseInt(b.posicionY);
                if (col > maxCol) maxCol = col;
                filasMap[b.posicionX].push(b);
            });
            const letras = Object.keys(filasMap).sort();
            if (maxCol < 10) maxCol = 15;
            grillaSala = letras.map(letra => {
                const filaCeldas = new Array(), asientosFila = filasMap[letra];
                for (let c = 1; c <= maxCol; c++) {
                    const enc = asientosFila.find(a => parseInt(a.posicionY) === c);
                    filaCeldas.push({ esButaca: Boolean(enc), disponibilidad: enc ? parseInt(enc.disponibilidad) : 1 });
                }
                return filaCeldas;
            });
        }

        async function cargarSala() {
            try {
                const resp = await fetch(API_URL);
                if (!resp.ok) throw new Error();
                const butacas = await resp.json();
                construirGrillaDesdeBD((!butacas || butacas.length === 0) ? generarSalaDeRespaldo() : butacas);
            } catch (e) {
                construirGrillaDesdeBD(generarSalaDeRespaldo());
            }
            renderizarSala();
        }
        function renderizarSala() {
            const sala = document.getElementById('salaCinema');
            if (!sala) return;
            sala.innerHTML = '';
            sala.className = modoEdicion ? 'sala-contenedor modo-edicion' : 'sala-contenedor';
            let total = 0;

            grillaSala.forEach((fila, iFila) => {
                const letra = ABECEDARIO.charAt(iFila) || `F${iFila + 1}`;
                const divFila = document.createElement('div');
                divFila.className = 'fila';
                const span = document.createElement('span');
                span.className = 'letra-fila';
                span.textContent = letra;
                divFila.appendChild(span);

                let numVisible = 1;
                fila.forEach((celda, iCol) => {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    if (celda.esButaca) {
                        total++;
                        const num = numVisible++;
                        btn.className = 'butaca';
                        btn.textContent = num;
                        if (!modoEdicion && celda.disponibilidad === 0) { btn.classList.add('ocupada'); btn.disabled = true; }
                        btn.onclick = () => {
                            if (modoEdicion) { fila[iCol].esButaca = false; renderizarSala(); }
                            else {
                                btn.classList.toggle('seleccionada');
                                const id = `${letra}-${num}`, idx = asientosSeleccionados.findIndex(a => a.id === id);
                                if (idx > -1) asientosSeleccionados.splice(idx, 1);
                                else asientosSeleccionados.push({ id: id, fila: letra, asiento: num });
                                document.getElementById('btnSiguiente').disabled = asientosSeleccionados.length === 0;
                            }
                        };
                    } else {
                        btn.className = 'butaca pasillo';
                        btn.textContent = modoEdicion ? '+' : '';
                        btn.disabled = !modoEdicion;
                        if (modoEdicion) btn.onclick = () => { fila[iCol] = { esButaca: true, disponibilidad: 1 }; renderizarSala(); };
                    }
                    divFila.appendChild(btn);
                });
                sala.appendChild(divFila);
            });
            document.getElementById('textoCapacidad').textContent = `Sala ${ID_SALA} (Capacidad actual: ${total} butacas)`;
        }

        function modificarFilas(d) {
            if (d > 0 && grillaSala.length < 26) {
                const cols = grillaSala.length > 0 ? grillaSala.at(0).length : 15;
                grillaSala.push(Array.from({ length: cols }, () => ({ esButaca: true, disponibilidad: 1 })));
            } else if (d < 0 && grillaSala.length > 1) grillaSala.pop();
            renderizarSala();
        }

        function modificarColumnas(d) {
            if (grillaSala.length === 0) return;
            const cols = grillaSala.at(0).length;
            if (d > 0 && cols < 30) grillaSala.forEach(f => f.push({ esButaca: true, disponibilidad: 1 }));
            else if (d < 0 && cols > 1) grillaSala.forEach(f => f.pop());
            renderizarSala();
        }

        document.addEventListener("DOMContentLoaded", () => {
            const btnEd = document.getElementById('btnToggleEditar');
            if (btnEd) {
                btnEd.onclick = () => {
                    modoEdicion = !modoEdicion;
                    asientosSeleccionados = new Array();
                    document.getElementById('btnSiguiente').disabled = true;
                    document.getElementById('herramientasEdicion').style.display = modoEdicion ? 'inline-flex' : 'none';
                    document.getElementById('ayudaEdicion').style.display = modoEdicion ? 'block' : 'none';
                    document.getElementById('contenedorBtnSiguiente').style.display = modoEdicion ? 'none' : 'block';
                    btnEd.textContent = modoEdicion ? '❌ Cancelar Edición' : '✏️ Editar Sala';
                    btnEd.classList.toggle('activo', modoEdicion);
                    if (!modoEdicion) cargarSala(); else renderizarSala();
                };
                document.getElementById('btnGuardarSala').onclick = async () => {
                    const butacas = [];
                    grillaSala.forEach((f, iF) => {
                        const letra = ABECEDARIO.charAt(iF) || `F${iF + 1}`;
                        f.forEach((c, iC) => { 
                            if (c.esButaca) butacas.push({ posicionX: letra, posicionY: iC + 1, disponibilidad: c.disponibilidad }); 
                        });
                    });

                    try {
                        const resp = await fetch(GUARDAR_URL, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
                            },
                            body: JSON.stringify({
                                '<?= csrf_token() ?>': '<?= csrf_hash() ?>',
                                butacas: butacas
                            })
                        });
                        
                        const res = await resp.json();
                        if (resp.ok) {
                            alert('✅ ¡Diagrama de la sala guardado correctamente!');
                            btnEd.click();
                        } else {
                            const detalle = (res.messages && res.messages.error) ? res.messages.error : (res.message || JSON.stringify(res));
                            alert('Error: ' + detalle);
                        }
                    } catch (e) { 
                        alert('Error de conexión al guardar la sala.'); 
                    }
                };

            }
            document.getElementById('btnSiguiente').onclick = () => {
                localStorage.setItem('asientos_muvi', JSON.stringify(asientosSeleccionados));
                window.location.href = `${BASE_URL}/combos`;
            };
            cargarSala();
        });
        document.getElementById('formSeleccionButacas')?.addEventListener('submit', function(e) {
            if (asientosSeleccionados.length === 0) {
                e.preventDefault();
                alert('Por favor seleccioná al menos una butaca.');
                return;
            }
            document.getElementById('inputAsientosSeleccionados').value = JSON.stringify(asientosSeleccionados);
        });
    </script>
    <?= view('templates/footer') ?>
</body>
</html>

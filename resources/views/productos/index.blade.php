<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario | Gestión de Productos</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; color: #1e293b; }
        .card-custom { border: none; border-radius: 1rem; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); background-color: white; }
        .table-custom thead th { border: none; color: #475569; font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.1em; }
        .table-custom tbody tr { transition: transform 0.2s; border-bottom: 1px solid #f1f5f9; }
        .table-custom tbody tr:hover { background-color: #f8fafc; }
        .btn-custom { border-radius: 0.75rem; font-weight: 600; }
        .badge-critical { background-color: #ef4444; color: white; }
        .modal-content { border: none; border-radius: 1.5rem; }
    </style>
</head>
<body>
    
    <nav class="navbar navbar-dark bg-white shadow-sm py-3 mb-5">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="#"><i class="bi bi-box-seam me-2"></i>Inventario</a>
        </div>
    </nav>

    <main class="container">
        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm mb-4 alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger border-0 shadow-sm mb-4 alert-dismissible fade show" role="alert">
                <div class="fw-bold mb-2"><i class="bi bi-exclamation-octagon-fill me-2"></i> Errores de validación:</div>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h1 class="fw-bold">Gestión de Productos</h1>
                <p class="text-muted">Mantenimiento de existencias y precios</p>
            </div>
            <button class="btn btn-primary btn-custom shadow-sm px-4 py-2" data-bs-toggle="modal" data-bs-target="#modalNuevo">
                <i class="bi bi-plus-lg me-2"></i>Nuevo Producto
            </button>
        </div>

        <div class="card card-custom p-4 shadow-sm">
            <div class="table-responsive">
                <table class="table table-custom align-middle">
                    <thead>
                        <tr>
                            <th>PRODUCTO / MARCA</th>
                            <th class="text-center">EXISTENCIAS</th>
                            <th class="text-center">PRECIO VENTA</th>
                            <th class="text-end">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($productos as $p)
                        <tr>
                            <td>
                                <div class="fw-bold">{{ $p->nombre }}</div>
                                <div class="text-muted small">{{ $p->marca }} | {{ $p->categoria->nombre ?? 'Sin categoría' }}</div>
                            </td>
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-center">
                                    <span class="badge rounded-pill px-3 py-2 me-2 {{ $p->existencias < 10 ? 'badge-critical' : 'bg-success' }}">
                                        {{ $p->existencias }} unidades
                                    </span>
                                    <form action="{{ route('productos.surtir', $p->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-warning fw-bold shadow-sm">
                                            <i class="bi bi-arrow-repeat me-1"></i>Surtir +50
                                        </button>
                                    </form>
                                </div>
                            </td>
                            <td class="text-center fw-bold text-success">${{ number_format($p->precio_venta, 2) }}</td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-outline-info me-1" onclick="verProducto({{ $p }}, '{{ $p->categoria->nombre ?? 'N/A' }}')"><i class="bi bi-eye"></i></button>
                                    <button class="btn btn-sm btn-outline-success me-1" onclick="editarProducto({{ $p }})"><i class="bi bi-pencil"></i></button>
                                    <form action="{{ route('productos.destroy', $p->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar producto?')"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <div class="modal fade" id="modalNuevo" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg">
                <div class="modal-header">
                    <h5 class="fw-bold m-0 text-primary">Nuevo Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('productos.store') }}" method="POST" class="needs-validation" novalidate onsubmit="return validarPrecios(this)">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Nombre del Producto</label>
                            <input type="text" name="nombre" class="form-control bg-light border-0" 
                                   required minlength="2" maxlength="30" 
                                   pattern="^[a-zA-Z\sñÑáéíóúÁÉÍÓÚ]+$" title="Solo letras y espacios">
                            <div class="invalid-feedback">El nombre es obligatorio (solo letras).</div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold">Marca</label>
                                <input type="text" name="marca" class="form-control bg-light border-0" 
                                       required minlength="2" maxlength="30" 
                                       pattern="^[a-zA-Z\sñÑáéíóúÁÉÍÓÚ]+$" title="Solo letras y espacios">
                                <div class="invalid-feedback">La marca es obligatoria (solo letras).</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold">Categoría</label>
                                <select name="categoria_id" class="form-select bg-light border-0" required>
                                    <option value="" selected disabled>Elegir...</option>
                                    @foreach($categorias as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">Selecciona una categoría.</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label small fw-bold">Stock Inicial</label>
                                <input type="number" name="existencias" class="form-control bg-light border-0" required min="0">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label small fw-bold">P. Compra</label>
                                <input type="number" step="0.01" name="precio_compra" class="form-control bg-light border-0" required min="0.01">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label small fw-bold">P. Venta</label>
                                <input type="number" step="0.01" name="precio_venta" class="form-control bg-light border-0 fw-bold text-success" required min="0.01">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="submit" class="btn btn-primary btn-custom w-100 py-2 shadow-sm">Guardar Producto</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg">
                <div class="modal-header border-0">
                    <h5 class="fw-bold m-0 text-success">Editar Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="formEditar" method="POST" class="needs-validation" novalidate onsubmit="return validarPrecios(this)">
                    @csrf @method('PUT')
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Nombre</label>
                            <input type="text" name="nombre" id="edit_nombre" class="form-control bg-light border-0" 
                                   required minlength="2" maxlength="30" 
                                   pattern="^[a-zA-Z\sñÑáéíóúÁÉÍÓÚ]+$" title="Solo letras y espacios">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold">Marca</label>
                                <input type="text" name="marca" id="edit_marca" class="form-control bg-light border-0" 
                                       required minlength="2" maxlength="30" 
                                       pattern="^[a-zA-Z\sñÑáéíóúÁÉÍÓÚ]+$" title="Solo letras y espacios">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold">Existencias</label>
                                <input type="number" name="existencias" id="edit_existencias" class="form-control bg-light border-0" required min="0">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold">P. Compra</label>
                                <input type="number" step="0.01" name="precio_compra" id="edit_compra" class="form-control bg-light border-0" required min="0.01">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold">P. Venta</label>
                                <input type="number" step="0.01" name="precio_venta" id="edit_venta" class="form-control bg-light border-0 fw-bold text-success" required min="0.01">
                            </div>
                        </div>
                        <input type="hidden" name="categoria_id" id="edit_cat_id">
                    </div>
                    <div class="modal-footer border-0">
                        <button type="submit" class="btn btn-success btn-custom w-100 py-2 shadow-sm">Actualizar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalVer" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg">
                <div class="modal-header border-0">
                    <h5 class="fw-bold m-0 text-info">Detalles del Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 text-center">
                    <i class="bi bi-box-seam text-info" style="font-size: 3rem;"></i>
                    <h3 id="ver_nombre" class="fw-bold mt-2 mb-0"></h3>
                    <span id="ver_categoria" class="badge bg-light text-dark border mb-4"></span>
                    <hr>
                    <div class="row g-3 text-start">
                        <div class="col-6">
                            <label class="small text-muted d-block">Marca</label>
                            <span id="ver_marca" class="fw-medium"></span>
                        </div>
                        <div class="col-6 text-end">
                            <label class="small text-muted d-block">Stock Actual</label>
                            <span id="ver_existencias" class="badge rounded-pill"></span>
                        </div>
                        <div class="col-6">
                            <label class="small text-muted d-block">Precio Compra</label>
                            <span id="ver_compra" class="fw-bold text-muted"></span>
                        </div>
                        <div class="col-6 text-end">
                            <label class="small text-muted d-block">Precio Venta</label>
                            <span id="ver_venta" class="fw-bold text-success fs-5"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary btn-custom w-100" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Función de validación lógica de precios
        function validarPrecios(form) {
            const compra = parseFloat(form.querySelector('[name="precio_compra"]').value);
            const venta = parseFloat(form.querySelector('[name="precio_venta"]').value);

            if (venta <= compra) {
                alert('¡Error Lógico! El precio de venta ($' + venta + ') debe ser estrictamente mayor al precio de compra ($' + compra + ').');
                return false;
            }
            return true;
        }

        // Validación visual de Bootstrap
        (function () {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms).forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()

        function verProducto(p, catNombre) {
            const modal = new bootstrap.Modal(document.getElementById('modalVer'));
            document.getElementById('ver_nombre').innerText = p.nombre;
            document.getElementById('ver_marca').innerText = p.marca;
            document.getElementById('ver_categoria').innerText = catNombre;
            document.getElementById('ver_compra').innerText = '$' + parseFloat(p.precio_compra).toFixed(2);
            document.getElementById('ver_venta').innerText = '$' + parseFloat(p.precio_venta).toFixed(2);
            
            const badge = document.getElementById('ver_existencias');
            badge.innerText = p.existencias + ' unidades';
            badge.className = 'badge rounded-pill ' + (p.existencias < 10 ? 'bg-danger' : 'bg-success');
            
            modal.show();
        }

        function editarProducto(p) {
            const modal = new bootstrap.Modal(document.getElementById('modalEditar'));
            document.getElementById('formEditar').action = "{{ url('productos') }}/" + p.id;
            document.getElementById('edit_nombre').value = p.nombre;
            document.getElementById('edit_marca').value = p.marca;
            document.getElementById('edit_existencias').value = p.existencias;
            document.getElementById('edit_compra').value = p.precio_compra;
            document.getElementById('edit_venta').value = p.precio_venta;
            document.getElementById('edit_cat_id').value = p.categoria_id;
            modal.show();
        }
    </script>
</body>
</html>
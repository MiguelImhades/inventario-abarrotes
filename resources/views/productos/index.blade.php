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
        .text-limit { font-size: 0.7rem; color: #94a3b8; margin-top: 2px; display: block; }
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
            <div class="alert alert-success border-0 shadow-sm mb-4">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
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
                                    <button class="btn btn-sm btn-outline-info me-1" onclick="verProducto({{ $p }}, '{{ $p->categoria->nombre ?? 'N/A' }}')" title="Ver detalles"><i class="bi bi-eye"></i></button>
                                    <button class="btn btn-sm btn-outline-success me-1" onclick="editarProducto({{ $p }})" title="Editar"><i class="bi bi-pencil"></i></button>
                                    <form action="{{ route('productos.destroy', $p->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Seguro que deseas eliminar este producto?')" title="Eliminar"><i class="bi bi-trash"></i></button>
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
                <form action="{{ route('productos.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Nombre del Producto</label>
                            <input type="text" name="nombre" class="form-control bg-light border-0" required maxlength="30">
                            <span class="text-limit">Máximo 30 caracteres</span>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold">Marca</label>
                                <input type="text" name="marca" class="form-control bg-light border-0" required maxlength="30">
                                <span class="text-limit">Máximo 30 caracteres</span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold">Categoría</label>
                                <select name="categoria_id" class="form-select bg-light border-0" required>
                                    @foreach($categorias as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label small fw-bold">Stock Inicial</label>
                                <input type="number" name="existencias" class="form-control bg-light border-0" required min="0">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label small fw-bold">P. Compra</label>
                                <input type="number" step="0.01" name="precio_compra" class="form-control bg-light border-0" required min="0">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label small fw-bold">P. Venta</label>
                                <input type="number" step="0.01" name="precio_venta" class="form-control bg-light border-0 fw-bold text-success" required min="0">
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
                <form id="formEditar" method="POST">
                    @csrf @method('PUT')
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Nombre</label>
                            <input type="text" name="nombre" id="edit_nombre" class="form-control bg-light border-0" required maxlength="30">
                            <span class="text-limit">Máximo 30 caracteres</span>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold">Marca</label>
                                <input type="text" name="marca" id="edit_marca" class="form-control bg-light border-0" required maxlength="30">
                                <span class="text-limit">Máximo 30 caracteres</span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold">Existencias</label>
                                <input type="number" name="existencias" id="edit_existencias" class="form-control bg-light border-0" required min="0">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold">P. Compra</label>
                                <input type="number" step="0.01" name="precio_compra" id="edit_compra" class="form-control bg-light border-0" required min="0">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold">P. Venta</label>
                                <input type="number" step="0.01" name="precio_venta" id="edit_venta" class="form-control bg-light border-0 fw-bold text-success" required min="0">
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
                <div class="modal-header border-0"><h5 class="fw-bold m-0 text-info">Detalles del Producto</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body p-4" id="contenidoVer"></div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function verProducto(p, catNombre) {
            const modal = new bootstrap.Modal(document.getElementById('modalVer'));
            document.getElementById('contenidoVer').innerHTML = `
                <div class="text-center mb-4">
                    <h4 class="fw-bold">${p.nombre}</h4>
                    <span class="badge bg-primary px-3">${catNombre}</span>
                </div>
                <ul class="list-group list-group-flush border-top">
                    <li class="list-group-item d-flex justify-content-between py-3"><span>Marca:</span> <strong>${p.marca}</strong></li>
                    <li class="list-group-item d-flex justify-content-between py-3"><span>Existencias:</span> <strong>${p.existencias} unidades</strong></li>
                    <li class="list-group-item d-flex justify-content-between py-3"><span>Precio Venta:</span> <strong class="text-success">$${parseFloat(p.precio_venta).toFixed(2)}</strong></li>
                    <li class="list-group-item d-flex justify-content-between py-3 small text-muted"><span>Última actualización:</span> <span>${new Date(p.updated_at).toLocaleDateString()}</span></li>
                </ul>`;
            modal.show();
        }

        function editarProducto(p) {
            const modal = new bootstrap.Modal(document.getElementById('modalEditar'));
            
            // Ajuste de la URL de acción para el controlador
            document.getElementById('formEditar').action = "{{ url('productos') }}/" + p.id;
            
            // Llenado de campos
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
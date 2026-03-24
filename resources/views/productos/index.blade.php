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
        .card-custom { border: none; border-radius: 1rem; shadow: 0 10px 15px -3px rgba(0,0,0,0.1); background-color: white; }
        .table-custom thead th { border: none; color: #475569; font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.1em; }
        .table-custom tbody tr { transition: transform 0.2s; border-radius: 0.75rem; }
        .table-custom tbody tr:hover { transform: translateY(-3px); background-color: #f1f5f9; }
        .btn-custom { border-radius: 0.75rem; font-weight: 600; }
        .existencias-bajas { background-color: #fefce8 !important; }
        /* Estilo para los modales */
        .modal-content { border: none; border-radius: 1.5rem; }
        .modal-header { border-bottom: none; padding: 2rem 2rem 1rem; }
        .modal-footer { border-top: none; padding: 1rem 2rem 2rem; }
    </style>
</head>
<body>
    
    <nav class="navbar navbar-dark bg-white shadow-sm py-3 mb-5">
        <div class="container"><a class="navbar-brand fw-bold text-primary" href="#"><i class="bi bi-box-seam me-2"></i>Inventario</a></div>
    </nav>

    <main class="container">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h1>Gestión de Productos</h1>
                <p class="text-muted">Mantenimiento de existencias y precios</p>
            </div>
            <button class="btn btn-primary btn-custom shadow-sm px-4 py-2"><i class="bi bi-plus-lg me-2"></i>Nuevo Producto</button>
        </div>

        <div class="card card-custom p-4 shadow-sm">
            <div class="table-responsive">
                <table class="table table-custom table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Producto / Marca</th>
                            <th class="text-center">Existencias</th>
                            <th class="text-center">Precio Venta</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="existencias-bajas">
                            <td>
                                <div class="fw-bold">Leche Entera</div>
                                <div class="text-muted small">Lala | Lácteos</div>
                            </td>
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-center">
                                    <span class="badge bg-danger-subtle text-danger rounded-pill px-3 me-2">3 unidades</span>
                                    <button class="btn btn-sm btn-warning btn-custom text-dark"><i class="bi bi-arrow-repeat me-1"></i>Surtir</button>
                                </div>
                            </td>
                            <td class="text-center fw-bold">$26.00</td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-outline-info btn-custom me-1" data-bs-toggle="modal" data-bs-target="#modalVer"><i class="bi bi-eye"></i></button>
                                    <button class="btn btn-sm btn-outline-success btn-custom me-1" data-bs-toggle="modal" data-bs-target="#modalEditar"><i class="bi bi-pencil"></i></button>
                                    <button class="btn btn-sm btn-outline-danger btn-custom"><i class="bi bi-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <div class="modal fade" id="modalVer" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg">
                <div class="modal-header">
                    <h5 class="fw-bold m-0">Detalles del Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="text-center mb-4">
                        <div class="display-6 fw-bold text-primary">Leche Entera</div>
                        <span class="badge bg-primary-subtle text-primary rounded-pill">Categoría: Lácteos</span>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between"><span>Marca:</span> <strong>Lala</strong></li>
                        <li class="list-group-item d-flex justify-content-between"><span>Existencias:</span> <strong class="text-danger">3 unidades</strong></li>
                        <li class="list-group-item d-flex justify-content-between"><span>Precio Compra:</span> <strong>$21.00</strong></li>
                        <li class="list-group-item d-flex justify-content-between"><span>Precio Venta:</span> <strong class="text-success">$26.00</strong></li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-custom w-100" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg">
                <div class="modal-header">
                    <h5 class="fw-bold m-0">Editar Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Nombre del Producto</label>
                            <input type="text" class="form-control form-control-lg bg-light border-0" value="Leche Entera" style="font-size: 1rem;">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-muted">Marca</label>
                                <input type="text" class="form-control bg-light border-0" value="Lala">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-muted">Existencias</label>
                                <input type="number" class="form-control bg-light border-0" value="3">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-muted">Precio Compra</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0">$</span>
                                    <input type="text" class="form-control bg-light border-0" value="21.00">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-muted">Precio Venta</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0 text-success">$</span>
                                    <input type="text" class="form-control bg-light border-0 text-success fw-bold" value="26.00">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light btn-custom me-2" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success btn-custom px-4">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
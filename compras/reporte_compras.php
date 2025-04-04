<?php
include ('../app/config.php');
include ('../layout/sesion.php');
include ('../layout/parte1.php');
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1 class="m-0">Generar Reporte de Compras</h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Seleccionar Rango de Fechas</h3>
                        </div>
                        <form action="generar_reporte_pdf.php" method="post" target="_blank">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Fecha de Inicio</label>
                                    <input type="date" class="form-control" name="fecha_inicio" required>
                                </div>
                                <div class="form-group">
                                    <label>Fecha de Fin</label>
                                    <input type="date" class="form-control" name="fecha_fin" required>
                                </div>
                                <div class="form-group">
                                    <label>Tipo de Reporte</label>
                                    <select class="form-control" name="tipo_reporte">
                                        <option value="detallado">Detallado (con productos)</option>
                                        <option value="resumido">Resumido (solo totales)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-file-pdf"></i> Generar Reporte PDF
                                </button>
                                <a href="index.php" class="btn btn-default float-right">Cancelar</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include ('../layout/mensajes.php'); ?>
<?php include ('../layout/parte2.php'); ?>
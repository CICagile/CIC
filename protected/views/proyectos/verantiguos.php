<?php
/* @var $this ProyectosController */
/* @var $model Proyectos */
$this->breadcrumbs = array(
    'Proyectos' => array('admin'),
    $model->codigo,
);

$this->menu = array(
    array('label' => 'Corregir información del proyecto', 'url' => array('actualizar', 'id' => $model->idtbl_Proyectos)),
    array('label' => 'Ver proyectos activos', 'url' => array('admin')),
    array('label' => 'Ver proyectos antiguos', 'url' => array('adminantiguos')),
);
?>

<h3>Detalle del proyecto.</h3>

<?php
$sectores_beneficiados = Proyectos::listFormatBenefitedSectors($model->idtbl_sectorbeneficiado);
$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'codigo',
        'nombre',
        'estado',
        array(
            'label' => 'Fecha Inicio',
            'value' => $model->inicio,
        ),
        array(
            'label' => 'Fecha finalización',
            'value' => $model->fin,
        ),
        '_tipoproyecto.nombre',
        '_objetivoproyecto.nombre',
        '_adscrito.nombre',
        array(
            'label' => 'Sector(es) beneficiado(s)',
            'value' => $sectores_beneficiados,
            'type' => 'html',
        ),
    ),
));
?>
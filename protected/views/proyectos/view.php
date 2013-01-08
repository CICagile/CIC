<?php
/* @var $this ProyectosController */
/* @var $model Proyectos */

$this->breadcrumbs=array(
	'Proyectos'=>array('index'),
	$model->codigo,
);

$this->menu=array(
	array('label'=>'Ver Proyectos', 'url'=>array('index')),
	array('label'=>'Nuevo Proyecto', 'url'=>array('create')),
	array('label'=>'Editar Proyecto', 'url'=>array('update', 'id'=>$model->idtbl_Proyectos)),
	/*array('label'=>'Delete Proyectos', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->idtbl_Proyectos),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Proyectos', 'url'=>array('admin')),*/
);
?>

<h3>Detalle del proyecto.</h3>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		//'idtbl_Proyectos',
                'codigo',
		'nombre',		
		array(
                        'label' => $model->periodos->getAttributeLabel('inicio'),
                        'value' => FechaMysqltoPhp($model->periodos->inicio),
                ),
                array(
                        'label' => $model->periodos->getAttributeLabel('fin'),
                        'value' => FechaMysqltoPhp($model->periodos->fin),
                )
                
	),
)); 

        function FechaMysqltoPhp($pfechamysql)
        {
            try{
                $fecha = substr($pfechamysql, 0, 10);
                list($y, $m, $d) = explode('-', $fecha);               
                $fecha = $d.'-'.$m.'-'.$y;                 
            }
            catch (Exception $excepcion)
            {               
                throw new CHttpException(500,$excepcion,500);
            }    
            return $fecha;
        }
?>




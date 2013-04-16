<?php

/**
 * Esta clase crea un widget que permite agregar campos de formulario de forma
 * dinamica a partir de una plantilla.
 */
class DynamicForm extends CWidget
{
    /**
     * Estas son las opciones disponibles:
     *  - int       limit               Número máximo de clones permitidos.
     *  - string    formPrefix          Prefijo usado para identificar un form (si no es definido se usará un selector de fuente normalizado).
     *  - string    afterClone          Una función que será llamada despues de crear un duplicado.
     *  - boolean   normalizedFullForm  normaliza todos los campos del formulario (incluso los que están fuera del script).
     *  - string    createColor         Efecto de color cuando se hace un duplicado (requiere el módulo jQuery UI Effects).
     *  - string    removeColor         Efecto de color cuando se remueve un duplicado (requiere el módulo jQuery UI Effects).
     *  - int       duration            Duración del efecto de color (requiere el módulo jQuery UI Effects).
     *  - type      data                datos JSON que van a llenar previamente el formulario.
     * @var array $options Opciones del script.
     */
    public $options = array();
    
    /**
     * @var string $form ID del formulario que se quiere clonar.
     */
    public $form;
    
    /**
     * @var string $plus id Del objeto para el signo de +
     */
    public $plus;
    
    /**
     * @var string $minus id Del objeto para el signo de -
     */
    public $minus;
    
    public function init()
    {
        $file = dirname(__FILE__).DIRECTORY_SEPARATOR.'jquery-dynamic-form.js';
        $jsfile = Yii::app()->getAssetManager()->publish($file);
        Yii::app()->clientScript->registerScriptFile($jsfile);
        $options = CJavaScript::encode($this->options);
        Yii::app()->getClientScript()->registerScript(__CLASS__.'#'.$this->id,'$("#'.$this->form.'").dynamicForm("#'.$this->plus.'", "#'.$this->minus.'", '.$options.');');
        parent::init();
    }
}//fin clase Dynamic form
?>

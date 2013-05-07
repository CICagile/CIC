<?php
/*
 * option transfer extraido de /* http://www.yiiframework.com/extension/optiontransferselect/
 */
class Optiontransferselect extends CWidget{
	/**
	 * The label for the first mutiple select
	 * option
	 * @var string
	 */
	public $leftTitle;
	/**
	 * The label for the sencond mutiple select
	 * option
	 * @var string
	 */
	public $rightTitle;
	/**
	 * The name for the first mutiple select
	 * require
	 * @var string
	 */
	public $name;
	/**
	 * The name for the sencond mutiple select
	 * require
	 * @var string
	 */
	public $doubleName;
	/**
	 * data for generating the first list options .suport  (value=>display) and string.
	 * require
	 * @var array
	 */
	public $list;
	/**
	 * data for generating the first list options .suport  (value=>display) and string.
	 * require
	 * @var array
	 */
	public $doubleList;
	//***************************************************************************
	// register clientside widget files
	//***************************************************************************
	protected function registerClientScript(){
            //modificado para no requerir el archivo de jquery, pues esto causa conflictos y no es necesario
		//$file=dirname(__FILE__).DIRECTORY_SEPARATOR.'jquery.js';
		$file2=dirname(__FILE__).DIRECTORY_SEPARATOR.'jquery.multiselects.js';
		//$jsFile=Yii::app()->getAssetManager()->publish($file);
		$jsFile2=Yii::app()->getAssetManager()->publish($file2);
		$cs=Yii::app()->clientScript;
		//$cs->registerScriptFile($jsFile);
		$cs->registerScriptFile($jsFile2);
	}
	//***************************************************************************
	// Initializes the widget
	//***************************************************************************
	public function init(){
		if(!isset($this->name)){
			throw new CHttpException(500,'"name" have to be set!');
		}
		if(!isset($this->doubleName)){
			throw new CHttpException(500,'"doubleName" have to be set!');
		}
		if(!isset($this->list)){
			throw new CHttpException(500,'"list" have to be set!');
		}
		if(!isset($this->doubleList)){
			throw new CHttpException(500,'"doubleList" have to be set!');
		}
	}
	//***************************************************************************
	// Run the widget
	//***************************************************************************
	public function run(){
		echo "<table border=\"0\">\n";
		echo "<tr>\n";
		echo "<td>\n";
		if(isset($this->leftTitle)){
			echo "<label for=\"leftTitle\">{$this->leftTitle}</label><br />\n";
		}
		echo "<select name=\"{$this->name}\" id=\"select_left\" multiple=\"multiple\" size=\"15\">\n";
		foreach($this->list as $key=>$option){ //modificado de solamente $option
			if(is_array($option)){
				foreach($option as $value=>$label){
					echo "<option value=\"{$value}\">{$label}</option>\n";
				}
			}else{
				//echo "<option value=\"{$option}\">{$option}</option>\n";
                            echo "<option value=\"{$key}\">{$option}</option>\n"; //modificado de {$option} {$option}
			}
		}
		echo "</select></td>\n";

		echo "<td valign=\"middle\" align=\"center\">\n";
		echo "<input type=\"button\" id=\"options_left\"	 value=\"&lt;-\" /><br /><br />\n";
		echo "<input type=\"button\" id=\"options_right\"	 value=\"-&gt;\" /><br /><br />\n";
		echo "<input type=\"button\" id=\"options_left_all\"	 value=\"&lt;&lt;--\" /><br /><br />\n";
		echo "<input type=\"button\" id=\"options_right_all\"	 value=\"--&gt;&gt;\" /><br /><br /></td>\n";

		echo "<td>\n";
		if(isset($this->rightTitle)){
			echo "<label for=\"rightTitle\">{$this->rightTitle}</label><br />\n";
		}
		echo "<select name=\"{$this->doubleName}\" id=\"select_right\" multiple=\"multiple\" size=\"15\">\n";
                if(is_array($this->doubleList))
		foreach($this->doubleList as $key=>$doubleOption){
			if(is_array($doubleOption)){
				foreach($doubleOption as $value=>$label){
					echo "<option value=\"{$value}\">{$label}</option>\n";
				}
			}else{
				echo "<option value=\"{$key}\">{$doubleOption}</option>\n";
			}
		}
		echo "</select></td>\n";
		echo "</tr></table>\n";
		$this->registerClientScript();
		echo "<script type=\"text/javascript\"><!--\n";
		echo "\$(function() {\n";
		echo "\$(\"#select_left\").multiSelect(\"#select_right\", {trigger: \"#options_right\"});\n";
		echo "\$(\"#select_right\").multiSelect(\"#select_left\", {trigger: \"#options_left\"});\n";
		echo "\$(\"#select_left\").multiSelect(\"#select_right\", {allTrigger:\"#options_right_all\"});\n";
		echo "\$(\"#select_right\").multiSelect(\"#select_left\", {allTrigger:\"#options_left_all\"});\n";
		echo "});\n";
		echo "// --></script>\n";
		parent::init();
	}

	protected function renderContent(){
	}
}
?>
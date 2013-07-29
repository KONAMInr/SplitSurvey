<?php

/*
 * @author Nina Ranta
 * 1300381
 * Karelia-ammattikorkeakoulu
 * opinnäytetyö: Split Survey -kyselyjärjestelmä
 */



define('FTG_PHP_OPEN', '<?=');
define('FTG_PHP_CLOSE', '?>');
define('FTG_PHP_EXT', '.php');
define('FTG_FILE_OPEN', false);
define('FTG_PRI_KEY', 'id');

class FormTableGenerator
{
	var $allowID;
	var $types;
	var $columnField;
	var $formField;
	var $masterHtml;
	var $link;
	
	function FormTableGenerator($link, $allowID = false)
	{
		$this->link = $link;
		$this->allowID = $allowID;
		if(!$this->_getFTGXML())
		{
			die('<br /><b>Warning: </b>Failed to Process XML File: <b>ftg.config.xml</b> is required!<br />');
		}
	}
	
	function generatePage($tbl, $pageType, $action = '', $method = 'POST', $file = '')
	{
		$data = $this->generateTable($tbl, $pageType, $action, $method);
		return $this->saveToFile($file, $data, $pageType, $tbl);
	}
	
	function saveToFile($file, $data, $page = '', $tbl = '')
	{
		$fileName =(FTG_FILE_OPEN == false) ? 
				strtolower($page).'_'.$tbl.FTG_PHP_EXT : FTG_FILE_OPEN.$file.FTG_PHP_EXT;
		
		$result = fwrite(fopen($fileName, 'w'), $data);
		
		return ($result) ? $fileName.' was successfully written!' : $fileName.': failed writting!';
	}
	
	
	function generateTable($tbl, $page, $action = '', $method = 'POST')
	{
		$tableInfo = $this->getTableInfo($tbl);
		$tableName = ucwords($tbl);
		$table = $this->_tablePart('tableOpen', $page.'_'.$tbl, $page.' '.$tableName);
		
		$num = count($tableInfo);
		
		for($i = 0; $i < $num; $i++)
		{
			$label  = $this->_formField('label',$tableInfo[$i]->Field, $tableInfo[$i]->Label, $page);
			$field  = $this->_formField($this->columnField[$tableInfo[$i]->Type], $tableInfo[$i]->Field, $page);
			$table .= $this->_tablePart('tableSection', $label, $field);
		}
		
		$table .= $this->_tablePart('tableClose', $page.' '.$tableName);
		
		return $this->_formPart('formOpen', $page.'_'.$tbl, $action, $method).$table.$this->_formPart('formClose');
		//return $tableInfo;
	}
	
	function getTableInfo($tbl)
	{
		$sql = "DESCRIBE $tbl";
		
		$res = mysql_query($sql);
		
		$table = array();
		
		while ($row = mysql_fetch_object($res)) 
		{	
			$this->_cleanUp($row);	
			if(!$this->allowID)
			{
				if($row->Field != FTG_PRI_KEY)
				{
					$table[] = $row;		
				}
			}
			
			else 
			{
				$table[] = $row;
			}
			
		}
		
		return $table;
	}
	
	function _getFTGXML()
	{
		$xml = new XML_Unserializer(array('complexType' => 'object'));
		
		if (!($xmlFile = @file_get_contents('ftg.config.xml')))
		{
			return false;
			exit();
		}
		
		if($xml->unserialize($xmlFile))
		{
			$config = $xml->getUnserializedData();
				
			$num = count($config->typePlugin);
			
			for ($i = 0; $i < $num; $i++)
			{
				$this->types[$config->typePlugin[$i]->type] = $config->typePlugin[$i]->asType;
				$this->columnField[$config->typePlugin[$i]->asType] = $config->typePlugin[$i]->asFormField;
			}

			$num = count($config->formPlugin);
			
			for ($i = 0; $i < $num; $i++)
			{
				$this->formField[$config->formPlugin[$i]->formFieldID] = $config->formPlugin[$i]->formFieldHTML;
			}
			
			$num = count($config->htmlMasterPlugin);
			
			for($i = 0; $i < $num; $i++)
			{
				$this->masterHtml[$config->htmlMasterPlugin[$i]->masterSection] = $config->htmlMasterPlugin[$i]->sectionHtml;	
			}
			
			return true;
		}
		
		else 
		{
			return false;
		}
	}
	
	function _formField($part, $name, $edit = '')
	{
		if($edit == 'Edit')
		{
			$edit = FTG_PHP_OPEN.'$'.$name.'_value'.FTG_PHP_CLOSE;
		}
		elseif ($edit == 'Add')
		{
			$edit = '';
		}
		
		$html  = $this->formField[$part];
		$html  = str_replace('@name', $name, $html);
		$html  = str_replace('@edit', $edit, $html);
		$html  = str_replace('#phpopen#', FTG_PHP_OPEN, $html);
		$html  = str_replace('#phpclose#', FTG_PHP_CLOSE, $html);
		
		return $html;
	}
	
	function _formPart($part, $id = '', $action = '', $method = '')
	{
		$html = $this->masterHtml[$part];
		$html = str_replace('@id', $id, $html);
		$html = str_replace('@method', $method, $html);
		$html = str_replace('@action', $action, $html);
		return $html;
	}
	
	function _tablePart($part, $one = 'Submit', $two = 'Reset')
	{
		$html = $this->masterHtml[$part];
		$html = str_replace('@one', $one, $html);
		$html = str_replace('@two', $two, $html);
		return $html;
	}
	
	function _cleanUp(&$obj)
	{
		foreach ($this->types as $needle => $type)
		{
			 if(is_int(strpos($obj->Type, $needle)))
			 {
			 	$obj->Type = $type;
			 }
		}
		
		$obj->Label = ucwords(str_replace('_', ' ', $obj->Field));
		
		if (is_int(strpos($obj->Label, 'Id')))
		{
			$obj->Label = str_replace('Id', '', $obj->Label);
		}
	}
}

?>
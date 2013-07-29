<?php

	// This file has been sligtly modified to be compatible with the FTG xml namespace
	require('Unserializer.php');

	// Opening syntax for PHP
	define('FTG_PHP_OPEN', '<?=');

	// Closing syntax for php
	define('FTG_PHP_CLOSE', '?>');

	// Have to set to modify how output files are name, instead oh '.php.' they will end with FTG_PHP_EXT
	define('FTG_PHP_EXT', '.php');

	// Have to set this modify how output files are name
	define('FTG_FILE_OPEN', false);

	// Have to be named as primary key of the table
	define('FTG_PRI_KEY', 'ID');

	// Used to generate tables wrapped in a form, fields are included along side descriptive lables
	class FormTableGenerator
	{
		/* Allow primary key to be listed on html table
		@ var		bool
		*/
		var $allowID;
	
		// Private variables
		var $types;
		var $columnField;
		var $formField;
		var $masterHtml;
		// Private variables

		/* DB Link
		@ var 		resource
		*/
		var $link;

		/* Constructor, loads xml configuration file
		if you want the 'ID' of a field to show up, set to true
		*/
		function FormTableGenerator($link, $allowID = false)
		{
			$this->link = $link;

		$this->allowID = $allowID;

		if(!$this->_getFTGXML())
		{
			die('<br /><b>Warning: </b>Failed to Process XML File: <b>ftg.config.xml</b> is required!<br />');
		}
	}
	
	/* Generates table & saves it to a file, the file need not exists
	@ param		string		$tbl
	DB Table name
	@param		string		$pageType
	'Add' or 'Edit' (capitals necessary)
	@ param		string 		$action
	Form's submission page
	@param		string		$method ['GET']
	'GET' or 'POST'
	@ param		string		$file
	To be used if you would like a non-default file name
	Set FTG_FILE_OPEN to your opening filename value, then provide the main part of the filename via $file
	@ return 	string
	Success or error message
	*/
	function generatePage($tbl, $pageType, $action = '', $method = 'POST', $file = '')
	{
		$data = $this->generateTable($tbl, $pageType, $action, $method);
		return $this->saveToFile($file, $data, $pageType, $tbl);
	}
	
	/* Moved to private, private functions are not discussed
	@ see THIS::generatePage()
	*/
	function saveToFile($file, $data, $page = '', $tbl = '')
	{
		$fileName =(FTG_FILE_OPEN == false) ? 
				strtolower($page).'_'.$tbl.FTG_PHP_EXT : FTG_FILE_OPEN.$file.FTG_PHP_EXT;
		
		$result = fwrite(fopen($fileName, 'w'), $data);
		
		return ($result) ? $fileName.' was successfully written!' : $fileName.': failed writting!';
	}
	
	/* Generate a html table
	@deprecated THIS::generatePage() preferred
	@param		string		DB TBL		$tbl
	@param		string		'Add' or 'Edit'		$page(capitals required!)
	@param		string		form Action	$action
	@param		string		form Method	$method
	@ return 	string	
	*/
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
	
	/* Returns an array of objects describing a table
	@param 		string		$tbl
	DB Table
	@ return array
	*/
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
	
	
	/* Converts xml to arrays, not discussed further
	@ return	bool
	*/
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
	
	// Private function
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
	
	// Private function
	function _formPart($part, $id = '', $action = '', $method = '')
	{
		$html = $this->masterHtml[$part];
		$html = str_replace('@id', $id, $html);
		$html = str_replace('@method', $method, $html);
		$html = str_replace('@action', $action, $html);
		return $html;
	}
	
	// Private function
	function _tablePart($part, $one = 'Submit', $two = 'Reset')
	{
		$html = $this->masterHtml[$part];
		$html = str_replace('@one', $one, $html);
		$html = str_replace('@two', $two, $html);
		return $html;
	}
	
	// Private function
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
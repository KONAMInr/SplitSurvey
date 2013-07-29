<?php

	// Uses PEAR error management
	require_once 'PEAR.php';

	// Uses XML_Parser to unserialize document
	require_once 'XML/Parser.php';

	// Convert nested tags to array or object
	define('XML_UNSERIALIZER_OPTION_COMPLEXTYPE', 'complexType');

	// Name of the attribute that stores the orginal key
	define('XML_UNSERIALIZER_OPTION_ATTRIBUTE_KEY', 'keyAttribute');

	// Name of the attribute that stores the type
	define('XML_UNSERIALIZER_OPTION_ATTRIBUTE_TYPE', 'typeAttribute');

	// Name of the attrivute that stores the class name
	define('XML_UNSERIALIZER_OPTION_ATTRIBUTE_CLASS', 'classAttribute');

	// Whether to use the tag name as a class name
	define('XML_UNSERIALIZER_OPTION_TAG_AS_CLASSNAME', 'tagAsClass');

	// Name of the default class
	define('XML_UNSERIALIZER_OPTION_DEFAULT_CLASS', 'defaultClass');

	// Whether to parse attributes
	define('XML_UNSERIALIZER_OPTION_ATTRIBUTES_PARSE', 'parseAttributes');

	// Key of the array to store attributes
	define('XML_UNSERIALIZER_OPTION_ATTRIBUTES_ARRAYKEY', 'attributesArray');

	// String to prepand attribute name (if any)
	define('XML_UNSERIALIZER_OPTION_ATTRIBUTES_PREPEND', 'prependAttributes');

	// Key to store the content, if XML_UNSERIALIZER_OPTION_ATTRIBUTES_PARSE is used
	define('XML_UNSERIALIZER_OPTION_CONTENT_KEY', 'contentName');

	// Map tag names
	define('XML_UNSERIALIZER_OPTION_TAG_MAP', 'tagMap');

	// List of tags that will always be enumerated
	define('XML_UNSERIALIZER_OPTION_FORCE_ENUM', 'forceEnum');

	// Encoding of the XML document
	define('XML_UNSERIALIZER_OPTION_ENCODING_SOURCE', 'encoding');

	// Desired target encoding of the data
	define('XML_UNSERIALIZER_OPTION_ENCODING_TARGET', 'targetEncoding');

	// Callback that will be applied a textual data
	define('XML_UNSERIALIZER_OPTION_DECODE_FUNC', 'decodeFunction');

	// Whether to return the result of the unserialization from unserialize()
	define('XML_UNSERIALIZER_OPTION_RETURN_RESULT', 'returnResult');

	// Set the whitespace behavior
	define('XML_UNSERIALIZER_OPTION_WHITESPACE', 'whitespace');

	// Keep all whitespace
	define('XML_UNSERIALIZER_WHITESPACE_KEEP', 'keep');

	// Remove whitespace from start and end of the data 
	define('XML_UNSERIALIZER_WHITESPACE_TRIM', 'trim');

	// Normalize whitespace
	define('XML_UNSERIALIZER_WHITESPACE_NORMALIZE', 'normalize');

	// Whether to override all options that have been set before
	define('XML_UNSERIALIZER_OPTION_OVERRIDE_OPTIONS', 'overrideOptions');

	// List of tags, that will not be used as keys
	define('XML_UNSERIALIZER_OPTION_IGNORE_KEYS', 'ignoreKeys');

	// Whether to use type guessing for scalar values
	define('XML_UNSERIALIZER_OPTION_GUESS_TYPES', 'guessTypes');
	
	// Code for no serialization done
	define('XML_UNSERIALIZER_ERROR_NO_UNSERIALIZATION', 151);

	/* Class to unzerializes XML documents that have been created with XML_Seralize.
	To unserialize and XML document you have to add type hints to the XML_Serializer options.
	If no type hints are available, XML_Unserializer will guess how the tags should be treated, 
	that means complex structures will be arrays that tags with only CData in them will be Strings 
	*/
	class XML_Unserializer extends PEAR
	{
		/* List of all available options
		@ access	private
		@ var		array
		*/
    		var $_knownOptions = array(
                                XML_UNSERIALIZER_OPTION_COMPLEXTYPE,
                                XML_UNSERIALIZER_OPTION_ATTRIBUTE_KEY,
                                XML_UNSERIALIZER_OPTION_ATTRIBUTE_TYPE,
                                XML_UNSERIALIZER_OPTION_ATTRIBUTE_CLASS,
                                XML_UNSERIALIZER_OPTION_TAG_AS_CLASSNAME,
                                XML_UNSERIALIZER_OPTION_DEFAULT_CLASS,
                                XML_UNSERIALIZER_OPTION_ATTRIBUTES_PARSE,
                                XML_UNSERIALIZER_OPTION_ATTRIBUTES_ARRAYKEY,
                                XML_UNSERIALIZER_OPTION_ATTRIBUTES_PREPEND,
                                XML_UNSERIALIZER_OPTION_CONTENT_KEY,
                                XML_UNSERIALIZER_OPTION_TAG_MAP,
                                XML_UNSERIALIZER_OPTION_FORCE_ENUM,
                                XML_UNSERIALIZER_OPTION_ENCODING_SOURCE,
                                XML_UNSERIALIZER_OPTION_ENCODING_TARGET,
                                XML_UNSERIALIZER_OPTION_DECODE_FUNC,
                                XML_UNSERIALIZER_OPTION_RETURN_RESULT,
                                XML_UNSERIALIZER_OPTION_WHITESPACE,
                                XML_UNSERIALIZER_OPTION_IGNORE_KEYS,
                                XML_UNSERIALIZER_OPTION_GUESS_TYPES
				);

  		/* Default options for the serialization
		@ access	private
		@ var		array
		*/
    		var $_defaultOptions = array(
                         	XML_UNSERIALIZER_OPTION_COMPLEXTYPE         => 'array',                // complex types will be converted to arrays, if no type hint is given
                        	XML_UNSERIALIZER_OPTION_ATTRIBUTE_KEY       => '_originalKey',         // get array key/property name from this attribute
                         	XML_UNSERIALIZER_OPTION_ATTRIBUTE_TYPE      => '_type',                // get type from this attribute
                        	XML_UNSERIALIZER_OPTION_ATTRIBUTE_CLASS     => '_class',               // get class from this attribute (if not given, use tag name)
                        	XML_UNSERIALIZER_OPTION_TAG_AS_CLASSNAME    => true,                   // use the tagname as the classname
                        	XML_UNSERIALIZER_OPTION_DEFAULT_CLASS       => 'stdClass',             // name of the class that is used to create objects
                        	XML_UNSERIALIZER_OPTION_ATTRIBUTES_PARSE    => false,                  // parse the attributes of the tag into an array
                        	XML_UNSERIALIZER_OPTION_ATTRIBUTES_ARRAYKEY => false,                  // parse them into sperate array (specify name of array here)
                        	XML_UNSERIALIZER_OPTION_ATTRIBUTES_PREPEND  => '',                     // prepend attribute names with this string
                        	XML_UNSERIALIZER_OPTION_CONTENT_KEY         => '_content',             // put cdata found in a tag that has been converted to a complex type in this key
                        	XML_UNSERIALIZER_OPTION_TAG_MAP             => array(),                // use this to map tagnames
                        	XML_UNSERIALIZER_OPTION_FORCE_ENUM          => array(),                // these tags will always be an indexed array
                        	XML_UNSERIALIZER_OPTION_ENCODING_SOURCE     => null,                   // specify the encoding character of the document to parse
                        	XML_UNSERIALIZER_OPTION_ENCODING_TARGET     => null,                   // specify the target encoding
                        	XML_UNSERIALIZER_OPTION_DECODE_FUNC         => null,                   // function used to decode data
                        	XML_UNSERIALIZER_OPTION_RETURN_RESULT       => false,                  // unserialize() returns the result of the unserialization instead of true
                        	XML_UNSERIALIZER_OPTION_WHITESPACE          => XML_UNSERIALIZER_WHITESPACE_TRIM, // remove whitespace around data
                        	XML_UNSERIALIZER_OPTION_IGNORE_KEYS         => array(),                // List of tags that will automatically be added to the parent, instead of adding a new key
                        	XML_UNSERIALIZER_OPTION_GUESS_TYPES         => false                   // Whether to use type guessing
                       		);

  		/* Current options for the serialization
		@ access	public
		@ var		array
		*/
    		var $options = array();

		/* Unserialized data
		@ access	private
		@ var		string
		*/
    		var $_unserializedData = null;

		/* Name of the root tag
		@ access	private
		@ var		string
		*/
   		var $_root = null;

		/* Stack for all values that js found
		@ access	private
		@ var		array
		*/
    		var $_dataStack  =   array();

		/* /* Stack for all values that are generated
		@ access 	private
		@ var		array
		*/
    		var $_valStack  =   array();

		/* Current tag depth
		@ access	private
		@ var		int
		*/
    		var $_depth = 0;

		/* XML_Parser instance
		@ access	private
		@ var		object XML_Parser
		*/
    		var $_parser = null;
    
   		/* Constructor
		@ access	public
		@ param		mixed 		$options 	array containing options for the unserialization
		*/
   		function XML_Unserializer($options = null)
    		{
        		if (is_array($options)) {
            		$this->options = array_merge($this->_defaultOptions, $options);
        	} 

		else 
		{
            		$this->options = $this->_defaultOptions;
        	}
	}

  	/* Return API version
	@ access	public
	@ static
	@ return	string $version API version
	*/
    	functin apiVersion()
    	{
        	return '@package_version@';
    	}

   	*/ Reset all options to default options
	@ access	public
	@ see		setOptions(), XML_Unserialization(), setOptions()
	*/
    	function resetOptions()
    	{
        	$this->options = $this->_defaultOptions;
    	}

 	/* Set an options
	@ access 	public
	@ see		resetOptions(), XML_Unserizalition(), setOptions()
	*/
    	function setOption($name, $value)
    	{
        	$this->options[$name] = $value;
    	}

	/* Sets several options at once
	@ accexs	public
	@ see		resetOptions(), XML_Unserializer(), setOptions()
	*/   
    	function setOptions($options)
    	{
        	$this->options = array_merge($this->options, $options);
    	}

   	/* Unzerialize data
	@ access	public
	@ param		mixes 		$data 		data to unserialize (string, filename or resource)
	@ param		boolean 	$isFile 	data should be treated as a file
	@ param		array		$options	options that will override the global options for this call
	@ return	boolean		$success
	*/
    	function unserialize($data, $isFile = false, $options = null)
    	{
       		 $this->_unserializedData = null;
        	$this->_root = null;

        // if options have been specified, use them instead of the previously defined ones
        if (is_array($options)) 
	{
            	$optionsBak = $this->options;
            	if (isset($options[XML_UNSERIALIZER_OPTION_OVERRIDE_OPTIONS]) && $options[XML_UNSERIALIZER_OPTION_OVERRIDE_OPTIONS] == true) 
		{
                	$this->options = array_merge($this->_defaultOptions, $options);
            	} 
	
		else 
		{
                	$this->options = array_merge($this->options, $options);
            	}
        } 

	else 
	{
            	$optionsBak = null;
        }

       	$this->_valStack = array();
        $this->_dataStack = array();
        $this->_depth = 0;

        $this->_createParser();
       
        if (is_string($data)) 
	{
            	if ($isFile) 
		{
                	$result = $this->_parser->setInputFile($data);
                	if (PEAR::isError($result)) 
			{
                    		return $result;
                	}
                		
			$result = $this->_parser->parse();
            	} 

		else 
		{
                	$result = $this->_parser->parseString($data,true);
            	}

        } 

	else 
	{
           	$this->_parser->setInput($data);
           	$result = $this->_parser->parse();
        }

        if ($this->options[XML_UNSERIALIZER_OPTION_RETURN_RESULT] === true) 
	{
        	$return = $this->_unserializedData;
        }
 
	else 
	{
            	$return = true;
        }

        if ($optionsBak !== null) 
	{
            	$this->options = $optionsBak;
        }

        if (PEAR::isError($result)) 
	{
            	return $result;
        }

        return $return;
    	}

   	/* Get the result of the serialization
	@ access 	public
	@ return	string		$serializedData
	*/
    	function getUnserializedData()
    	{
        	if ($this->_root === null) 
		{
            		return $this->raiseError('No unserialized data available. Use XML_Unserializer::unserialize() first.', XML_UNSERIALIZER_ERROR_NO_UNSERIALIZATION);
        	}
        
		return $this->_unserializedData;
    	}

   	/* Get the name of the root tag
	@ access 	public
	@ return	string		$rootName
	*/
    	function getRootName()
    	{
        	if ($this->_root === null) 
		{
            		return $this->raiseError('No unserialized data available. Use XML_Unserializer::unserialize() first.', XML_UNSERIALIZER_ERROR_NO_UNSERIALIZATION);
       		}

        	return $this->_root;
    	}

   	*/ Start element handler for XML parser
	@ access 	private
	@ param		object		$parser		XML parser object
	@ param		string		$element	XML element
	@ param		array		$attribs	attributes of XML tag
	@ return void
	*/
   	function startHandler($parser, $element, $attribs)
    	{
    		$element = str_replace('FTG:', '', $element);
        	if (isset($attribs[$this->options[XML_UNSERIALIZER_OPTION_ATTRIBUTE_TYPE]])) 
		{
            		$type = $attribs[$this->options[XML_UNSERIALIZER_OPTION_ATTRIBUTE_TYPE]];
            		$guessType = false;
        	} 

		else 
		{
            		$type = 'string';

            		if ($this->options[XML_UNSERIALIZER_OPTION_GUESS_TYPES] === true) 
			{
                		$guessType = true;
            		} 

			else 
			{
                		$guessType = false;
            		}	

        	}

        	if ($this->options[XML_UNSERIALIZER_OPTION_DECODE_FUNC] !== null) 
		{
            		$attribs = array_map($this->options[XML_UNSERIALIZER_OPTION_DECODE_FUNC], $attribs);
        	}
        
        	$this->_depth++;
        	$this->_dataStack[$this->_depth] = null;
	
        	if (is_array($this->options[XML_UNSERIALIZER_OPTION_TAG_MAP]) && isset($this->options[XML_UNSERIALIZER_OPTION_TAG_MAP][$element])) 
		{
            		$element = $this->options[XML_UNSERIALIZER_OPTION_TAG_MAP][$element];
        	}

        	$val = array(
                 	'name'         => $element,
                     	'value'        => null,
                     	'type'         => $type,
                     	'guessType'    => $guessType,
                     	'childrenKeys' => array(),
                     	'aggregKeys'   => array()
                    	);

       	 	if ($this->options[XML_UNSERIALIZER_OPTION_ATTRIBUTES_PARSE] == true && (count($attribs) > 0)) 
		{
            		$val['children'] = array();
            		$val['type']  = $this->_getComplexType($element);
            		$val['class'] = $element;

            	if ($this->options[XML_UNSERIALIZER_OPTION_GUESS_TYPES] === true) 
		{
            		$attribs = $this->_guessAndSetTypes($attribs);
            	}  
          
            	if ($this->options[XML_UNSERIALIZER_OPTION_ATTRIBUTES_ARRAYKEY] != false) 
		{
                	$val['children'][$this->options[XML_UNSERIALIZER_OPTION_ATTRIBUTES_ARRAYKEY]] = $attribs;
            	} 

		else 
		{
                	foreach ($attribs as $attrib => $value) 
			{
                    		$val['children'][$this->options[XML_UNSERIALIZER_OPTION_ATTRIBUTES_PREPEND].$attrib] = $value;
                	}
            	}
        }

       	 $keyAttr = false;
        
        if (is_string($this->options[XML_UNSERIALIZER_OPTION_ATTRIBUTE_KEY])) 
	{
            	$keyAttr = $this->options[XML_UNSERIALIZER_OPTION_ATTRIBUTE_KEY];
        } 

	elseif (is_array($this->options[XML_UNSERIALIZER_OPTION_ATTRIBUTE_KEY])) 
	{
            	if (isset($this->options[XML_UNSERIALIZER_OPTION_ATTRIBUTE_KEY][$element])) 
		{
                	$keyAttr = $this->options[XML_UNSERIALIZER_OPTION_ATTRIBUTE_KEY][$element];
            	} 

		elseif (isset($this->options[XML_UNSERIALIZER_OPTION_ATTRIBUTE_KEY]['#default'])) 
		{
                	$keyAttr = $this->options[XML_UNSERIALIZER_OPTION_ATTRIBUTE_KEY]['#default'];
            	} 

		elseif (isset($this->options[XML_UNSERIALIZER_OPTION_ATTRIBUTE_KEY]['__default'])) 
		{
                	// keep this for BC
                	$keyAttr = $this->options[XML_UNSERIALIZER_OPTION_ATTRIBUTE_KEY]['__default'];
            	}
        }
        
        if ($keyAttr !== false && isset($attribs[$keyAttr])) 
	{
            	$val['name'] = $attribs[$keyAttr];
        }

        if (isset($attribs[$this->options[XML_UNSERIALIZER_OPTION_ATTRIBUTE_CLASS]])) 
	{
            	$val['class'] = $attribs[$this->options[XML_UNSERIALIZER_OPTION_ATTRIBUTE_CLASS]];
        }

        array_push($this->_valStack, $val);
    	}

   	/* Try to guess the type of several value and set them accordingly
	@ access	private
	@ param		array		array, containing the values
	@ param		array		array, containing the values with their correct types
	*/
    	function _guessAndSetTypes($array)
    	{
        	foreach ($array as $key => $value) 
		{
        		$array[$key] = $this->_guessAndSetType($value);
        	}

        	return $array;
   	 }
    
   	/* Try to guess the type of a value and set it accordingly
	@ access	private
	@ param		string
	@ return	mixed		value with the best maching type	
	*/
   	function _guessAndSetType($value)
    	{
        	if ($value === 'true') 
		{
         		return true;
        	}

       		if ($value === 'false') 
		{
            		return false;
        	}

        	if ($value === 'NULL') 
		{
            		return null;
        	}

       	 	if (preg_match('/^[-+]?[0-9]{1,}$/', $value)) 
		{
        		return intval($value);
        	}

        	if (preg_match('/^[-+]?[0-9]{1,}\.[0-9]{1,}$/', $value)) 
		{
        		return doubleval($value);
        	}

       	 	return (string)$value;
    	}
    
   	/* End element handler for XML parser
	@ access 	private
	@ param		object 		XML parser ovbject
	@ param		string
	@ return void
	*/
    	function endHandler($parser, $element)
    	{
       	 	$value = array_pop($this->_valStack);
        	switch ($this->options[XML_UNSERIALIZER_OPTION_WHITESPACE]) 
		{
            		case XML_UNSERIALIZER_WHITESPACE_KEEP:
                	$data = $this->_dataStack[$this->_depth];
                	break;

            		case XML_UNSERIALIZER_WHITESPACE_NORMALIZE:
                	$data = trim(preg_replace('/\s\s+/m', ' ', $this->_dataStack[$this->_depth]));
                	break;

            		case XML_UNSERIALIZER_WHITESPACE_TRIM:
            		default:
                	$data  = trim($this->_dataStack[$this->_depth]);
                	break;
        	}

        	// adjust type of the value
        	switch(strtolower($value['type'])) 
		{

            		// unserialize an object
            		case 'object':

                	if (isset($value['class'])) 
			{
                    		$classname  = $value['class'];
                	} 

			else 
			{
                    		$classname = '';
                	}

                	// instantiate the class
                	if ($this->options[XML_UNSERIALIZER_OPTION_TAG_AS_CLASSNAME] === true && class_exists($classname)) 
			{
                    		$value['value'] = &new $classname;
                	} 

			else 
			{
                    		$value['value'] = &new $this->options[XML_UNSERIALIZER_OPTION_DEFAULT_CLASS];
                	}

                	if (trim($data) !== '') 
			{
                    		if ($value['guessType'] === true) 
				{
                    			$data = $this->_guessAndSetType($data);
                    		}

                   	 	$value['children'][$this->options[XML_UNSERIALIZER_OPTION_CONTENT_KEY]] = $data;
                	}

                	// set properties
                	foreach ($value['children'] as $prop => $propVal) 
			{
                 		// check whether there is a special method to set this property
                    		$setMethod = 'set'.$prop;

                    		if (method_exists($value['value'], $setMethod)) 
				{
                        		call_user_func(array(&$value['value'], $setMethod), $propVal);
                    		}

				else 
				{
                        		$value['value']->$prop = $propVal;
                    		}
                	}

               		 //  check for magic function
                	if (method_exists($value['value'], '__wakeup')) 
			{
                		$value['value']->__wakeup();
                	}

               	 	break;

            		// unserialize an array
			case 'array':
            		if (trim($data) !== '') 
			{
                		if ($value['guessType'] === true) 
				{
                    			$data = $this->_guessAndSetType($data);
                    		}

                    		$value['children'][$this->options[XML_UNSERIALIZER_OPTION_CONTENT_KEY]] = $data;
                	}

                	if (isset($value['children'])) 
			{
                    		$value['value'] = $value['children'];
                	} 

			else 
			{
                    		$value['value'] = array();
                	}

               		break;

           	 	// unserialize a null value
            		case 'null':
                	$data = null;
               		break;

            		// unserialize a resource => this is not possible :-(
            		case 'resource':
                	$value['value'] = $data;
                	break;

            		// unserialize any scalar value
            		default:

                	if ($value['guessType'] === true) 
			{
                    		$data = $this->_guessAndSetType($data);
                	} 

			else 
			{
                    		settype($data, $value['type']);
                	}
            
               		$value['value'] = $data;
                	break;

        	}

        	$parent = array_pop($this->_valStack);
        	if ($parent === null) 
		{
            		$this->_unserializedData = &$value['value'];
            		$this->_root = &$value['name'];
            		return true;
        	} 

		else 
		{
            		// parent has to be an array
            		if (!isset($parent['children']) || !is_array($parent['children'])) 
			{
                		$parent['children'] = array();

                		if (!in_array($parent['type'], array('array', 'object'))) 
				{
                    			$parent['type'] = $this->_getComplexType($parent['name']);

                    			if ($parent['type'] == 'object') 
					{
                        			$parent['class'] = $parent['name'];
                    			}
                		}
            		}

            		if (in_array($element, $this->options[XML_UNSERIALIZER_OPTION_IGNORE_KEYS])) 
			{
                		$ignoreKey = true;
            		} 

			else 
			{
            			$ignoreKey = false;
            		}
            
            		if (!empty($value['name']) && $ignoreKey === false) 
			{
                		// there already has been a tag with this name
                		if (in_array($value['name'], $parent['childrenKeys']) || in_array($value['name'], $this->options[XML_UNSERIALIZER_OPTION_FORCE_ENUM])) 
				{
                    			// no aggregate has been created for this tag
                    			if (!in_array($value['name'], $parent['aggregKeys'])) 
					{
                        			if (isset($parent['children'][$value['name']])) 
						{
                            				$parent['children'][$value['name']] = array($parent['children'][$value['name']]);
                        			} 

						else 
						{
                            				$parent['children'][$value['name']] = array();
                        			}

                        			array_push($parent['aggregKeys'], $value['name']);
                    			}

                    			array_push($parent['children'][$value['name']], $value['value']);
                		} 

				else 
				{
                   			$parent['children'][$value['name']] = &$value['value'];
                    			array_push($parent['childrenKeys'], $value['name']);
                		}
            		} 

			else 
			{
               		 	array_push($parent['children'], $value['value']);
            		}

            		array_push($this->_valStack, $parent);
        		}

        	$this->_depth--;
    	}

   	/* Handler for character data
	@ access	private
	@ param 	object		XML parser object
	@ param		string		CDATA	
	@ return void
	*/
   	function cdataHandler($parser, $cdata)
    	{
        	if ($this->options[XML_UNSERIALIZER_OPTION_DECODE_FUNC] !== null) 
		{
            		$cdata = call_user_func($this->options[XML_UNSERIALIZER_OPTION_DECODE_FUNC], $cdata);
        	}

        	$this->_dataStack[$this->_depth] .= $cdata;
    	}

   	/* Get the complex type, that should be used for a specified tag
	@ access	private		
	@ param		string		name of the tag
	@ return 	string		complex type ('array' or 'object')
	*/
    	function _getComplexType($tagname)
    	{
        	if (is_string($this->options[XML_UNSERIALIZER_OPTION_COMPLEXTYPE]))
		{
        		return $this->options[XML_UNSERIALIZER_OPTION_COMPLEXTYPE];
        	}

        	if (isset($this->options[XML_UNSERIALIZER_OPTION_COMPLEXTYPE][$tagname])) 
		{
        		return $this->options[XML_UNSERIALIZER_OPTION_COMPLEXTYPE][$tagname];
        	}

        	if (isset($this->options[XML_UNSERIALIZER_OPTION_COMPLEXTYPE]['#default'])) 
		{
        		return $this->options[XML_UNSERIALIZER_OPTION_COMPLEXTYPE]['#default'];
        	}

        	return 'array';
    	}
    
   	/* Create the XML_Parser intance
	@ access	private
	@ return	boolean
	*/
    	function _createParser()
    	{
        	if (is_object($this->_parser)) 
		{
            		$this->_parser->free();
            		unset($this->_parser);
        	}

        	$this->_parser = &new XML_Parser($this->options[XML_UNSERIALIZER_OPTION_ENCODING_SOURCE], 'event', $this->options[XML_UNSERIALIZER_OPTION_ENCODING_TARGET]);
        	$this->_parser->folding = false;
        	$this->_parser->setHandlerObj($this);
        	return true;
    	}
}

?>
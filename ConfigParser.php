<?php
/*
ConfigParser - Parse Config Files
Copyright (C) 2014  Colby Cox

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software Foundation,
Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301  USA
 
 */

class ConfigParser{
	
	//Properties	
	private $configFileString;	
	private $configsArray;	
	
	//Function that parses the config file and stores
	//associative array in $configsArray
	public function parseConfigFile($filePath){
		
		//Reset $configsArray	
		$this->configsArray = array();	
		
		//Store config file as string	
		if(!$this->configFileString = file_get_contents($filePath)){
			throw new Exception("Config file not found.", 1);			
		}
		
		//Split file into lines with newline characters
		$lines = explode(PHP_EOL, $this->configFileString);
		
		//Loop through lines
		foreach ($lines as $line) {
			
			//Check if the line has an equal character
			if(strpos($line, '=') !== false){
				
				//Check for the comment character	
				if (strpos($line, '#') !== FALSE) {
					//Remove all comments if at the end of the line
					$line = substr($line, 0, strpos($line, '#'));
				}
				
				//Make sure there is still an equal sign after removal of comment
				if (strpos($line, '=') !== FALSE) {
					
					//Explode the line with equal sign
					$keyValuePair = explode('=', $line);
					
					//Make sure the array is limited to one key and one value
					if(count($keyValuePair) == 2){
						
						//Remove white spaces before or after the key and value	
						$key = trim($keyValuePair[0]);
						$value = trim($keyValuePair[1]);
						
						//Make sure there are no spaces between the values
						if(strpos($key.$value, ' ') === false){
							
							//Test and convert values
							switch ($value) {
								case filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) === true:
									$value = true;
									break;
								case filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) === false:
									$value = false;
									break;
								case filter_var($value, FILTER_VALIDATE_INT):
									$value = (int) $value;
									break;	
								case filter_var($value, FILTER_VALIDATE_FLOAT):
									$value = (float) $value;
									break;	
								default:									
									break;
							}
							//Set key and value pair in $configsArray
							$this->configsArray[$key] = $value;
						}
					}
				}
				
					
			}
			
		}
	}
	
	//Get individual configurations
	public function getConfig($config){
			
		//Check if a file has been parsed
		if($this->configFileString == ''){
			throw new Exception("No config file parsed ", 1);
		}
		//Return the configuration if it exists	
		if(!isset($this->configsArray[$config])){
			throw new Exception("No configuration by the name of '".htmlspecialchars($config, ENT_QUOTES)."' found", 1);			
		}
		return $this->configsArray[$config];
	}
	
	//Return all the stored configurations
	public function getAllConfigs(){
					
		return $this->configsArray;
		
	}
	
}



?>
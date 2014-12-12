<?php
//USING THE CONFIGPARSER

//USAGE

//Class declaration
require_once('ConfigParser.php');

//Instantiation
$configParser = new ConfigParser();

//Parse
$configParser->parseConfigFile('test-config.ini');

//Get specific config
var_dump($configParser->getConfig('verbose'));

//Get all configs
var_dump($configParser->getAllConfigs());

?>
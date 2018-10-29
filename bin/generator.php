<?php

/**
 * Written by Travis Kent Beste
 * Tue Oct 23 23:16:36 CDT 2018
 */

$installDirectory = __DIR__;

require_once $installDirectory . '/../../../../vendor/autoload.php';
$config = include($installDirectory . '/../config.php');
$variables = array();
$verbose = 0;

// command line options
$longopts = array(
	"help",
	"verbose",
	"module:",
	"name:",
	"tablename:",
	"createAll",
	"displayConfig",
	"createViews",
	"createController",
	"createModule",
	"createForm",
	"createManager",
	"createModuleConfig",
);
$options = getopt('', $longopts);
if (isset($options['help']) && ($options['help'] == '') ) { usage(); }
if (!isset($options['module']) || ($options['module'] == '') ) { usage(); }
if (!isset($options['tablename']) || ($options['tablename'] == '') ) { usage(); }
if (isset($options['verbose']) && ($options['verbose'] == '') ) { $verbose = 1; }

// sanitize the input from the user
sanitize($options['module'], 'module', $variables, $verbose);
sanitize($options['name'], 'name', $variables, $verbose);
sanitize($options['tablename'], 'tablename', $variables, $verbose);

// get the database columns
getMysqlColumns($config['database']['hostname'], $config['database']['username'], $config['database']['password'], $config['database']['database'], $variables);

if ($verbose)
{
    print_r($variables);
}

// set the templates directory
$loader = new Twig_Loader_Filesystem($installDirectory . '/../templates');

// set the twig environment
$twig = new Twig_Environment($loader, array());

//--------------------//
// module
//--------------------//
if (isset($options['createModule']) && ($options['createModule'] == '') ) {

    // module
    $module = $twig->render('Module.php', $variables);
    $viewDirectory = $config['baseDirectory'] . '/module/' . $variables['moduleCamelized'] . '/src';
    $viewFilename = $viewDirectory . '/Module.php';
    if (!file_exists($viewDirectory)) {
        mkdir($viewDirectory, 0755, true);
    }
		// only update if it doesn't exist, because if we overwrite you'll be super annoyed
		if (! file_exists($viewFilename)) {
    	file_put_contents($viewFilename, $module);
		}

}

//--------------------//
// config
//--------------------//
if (isset($options['displayConfig']) && ($options['displayConfig'] == '') ) {

    // module config single
    $module_config_single = $twig->render('config/module.config.php.single', $variables);
    echo $module_config_single;

		// module config
    $module_config = $twig->render('config/module.config.php', $variables);
    $viewDirectory = $config['baseDirectory'] . '/module/' . $variables['moduleCamelized'] . '/config';
    $viewFilename = $viewDirectory . '/module.config.php';
    if (!file_exists($viewDirectory)) {
        mkdir($viewDirectory, 0755, true);
    }
		// only update if it doesn't exist, because if we overwrite you'll be super annoyed
		if (! file_exists($viewFilename)) {
    	file_put_contents($viewFilename, $module_config);
		}

}

//--------------------//
// views
//--------------------//
if (isset($options['createViews']) && ($options['createViews'] == '') )
{

    // index
    $index = $twig->render('view/index.phtml', $variables);
    $viewDirectory = $config['baseDirectory'] . '/module/' . $variables['moduleCamelized'] . '/view/' . $variables['moduleDashed'] . '/' . $variables['nameDashed'];
    $viewFilename = $viewDirectory . '/index.phtml';
    if (!file_exists($viewDirectory)) {
        mkdir($viewDirectory, 0755, true);
    }
    file_put_contents($viewFilename, $index);

    // add
    $index = $twig->render('view/add.phtml', $variables);
    $viewDirectory = $config['baseDirectory'] . '/module/' . $variables['moduleCamelized'] . '/view/' . $variables['moduleDashed'] . '/' . $variables['nameDashed'];
    $viewFilename = $viewDirectory . '/add.phtml';
    if (!file_exists($viewDirectory)) {
        mkdir($viewDirectory, 0755, true);
    }
    file_put_contents($viewFilename, $index);

    // edit
    $index = $twig->render('view/edit.phtml', $variables);
    $viewDirectory = $config['baseDirectory'] . '/module/' . $variables['moduleCamelized'] . '/view/' . $variables['moduleDashed'] . '/' . $variables['nameDashed'];
    $viewFilename = $viewDirectory . '/edit.phtml';
    if (!file_exists($viewDirectory)) {
        mkdir($viewDirectory, 0755, true);
    }
    file_put_contents($viewFilename, $index);

}

// manager
if (isset($options['createManager']) && ($options['createManager'] == '') )
{

    // Manager
    $manager = $twig->render('src/Manager.php', $variables);
    $managerDirectory = $config['baseDirectory'] . '/module/' . $variables['moduleCamelized'] . '/src/Service';
    $managerFilename = $managerDirectory . '/' . $variables['nameCamelized'] . 'Manager.php';
    if (!file_exists($managerDirectory)) {
        mkdir($managerDirectory, 0755, true);
    }
    file_put_contents($managerFilename, $manager);

    // ManagerFactory
    $managerFactory = $twig->render('src/ManagerFactory.php', $variables);
    $managerFactoryDirectory = $config['baseDirectory'] . '/module/' . $variables['moduleCamelized'] . '/src/Service/Factory';
    $managerFactoryFilename = $managerFactoryDirectory . '/' . $variables['nameCamelized'] . 'ManagerFactory.php';
    if (!file_exists($managerFactoryDirectory)) {
        mkdir($managerFactoryDirectory, 0755, true);
    }
    file_put_contents($managerFactoryFilename, $managerFactory);
}

// form
if (isset($options['createForm']) && ($options['createForm'] == '') )
{

    // Form
    $form = $twig->render('src/Form.php', $variables);
    $formDirectory = $config['baseDirectory'] . '/module/' . $variables['moduleCamelized'] . '/src/Form';
    $formFilename = $formDirectory . '/' . $variables['nameCamelized'] . 'Form.php';
    if (!file_exists($formDirectory)) {
        mkdir($formDirectory, 0755, true);
    }
    file_put_contents($formFilename, $form);

}

// controller
if (isset($options['createController']) && ($options['createController'] == '') )
{

    // Controller
    $controller = $twig->render('src/Controller.php', $variables);
    $controllerDirectory = $config['baseDirectory'] . '/module/' . $variables['moduleCamelized'] . '/src/Controller';
    $controllerFilename = $controllerDirectory . '/' . $variables['nameCamelized'] . 'Controller.php';
    if (! file_exists($controllerDirectory)) {
        mkdir($controllerDirectory, 0755, true);
    }
    file_put_contents($controllerFilename, $controller);

	// ControllerFactory
	$controllerFactory = $twig->render('src/ControllerFactory.php', $variables);
	$controllerFactoryDirectory = $config['baseDirectory'] . '/module/' . $variables['moduleCamelized'] . '/src/Controller/Factory';
	$controllerFactoryFilename = $controllerFactoryDirectory . '/' . $variables['nameCamelized'] . 'ControllerFactory.php';
    if (! file_exists($controllerFactoryDirectory)) {
        mkdir($controllerFactoryDirectory, 0755, true);
    }
    file_put_contents($controllerFactoryFilename, $controllerFactory);

}

/**
 * usage
 */
function usage()
{
	print "usage: ./generator.php\n";
	print "\t--help             : help\n";
	print "\t--verbose          : verbose\n";
	print "\t----------------------------------------\n";
	print "\t--module           : module\n";
	print "\t--name             : name\n";
	print "\t--tablename        : tablename\n";
	print "\t----------------------------------------\n";
	print "\t--createAll        : createAll\n";
	print "\t--createController : createController\n";
	print "\t--createModule     : createModule\n";
	print "\t--createManager    : createManager\n";
	print "\t--createViews      : createViews\n";
	print "\t--createForm       : createForm\n";
	print "\t--displayConfig    : displayConfig\n";
	exit(0);
}

/**
 * @param $word
 * @param $delimiter
 * @return string
 */
function camelize($word, $delimiter)
{
    $elements = explode($delimiter, ucfirst($word));

    for ($i = 0; $i < count($elements); $i++)
    {
        if (0 == $i)
        {
            //$elements[$i] = strtolower($elements[$i]);
        }
        else
        {
            //$elements[$i] = strtolower($elements[$i]);
            $elements[$i] = ucwords($elements[$i]);
        }
    }

    return implode('', $elements);
}

/**
 * @param $word
 * @return string
 */
function camelize2dashed($word)
{
    return strtolower(preg_replace('/([a-zA-Z0-9])(?=[A-Z])/', '$1-', $word));
}

/**
 * @param $variable
 * @param $type
 * @param $variables
 * @param $verbose
 */
function sanitize($variable, $type, &$variables, $verbose)
{

    $variableCamelized = camelize($variable, '_');
    $variableCamelized = camelize($variableCamelized, '-');
    $variableCamelizedLcfirst = lcfirst($variableCamelized);
    $variableCamelizedDashed = camelize2dashed($variableCamelized);
    $variableCamelizedUnderscored = preg_replace('/-/', '_', $variableCamelizedDashed);
    $variableCamelizedWithSpaces = $variableCamelizedDashed;
    $variableCamelizedWithSpaces = preg_replace('/-/', ' ', $variableCamelizedWithSpaces);

    $variables[$type . 'Camelized'] = $variableCamelized;
    $variables[$type . 'CamelizedLcFirst'] = $variableCamelizedLcfirst;
    $variables[$type . 'Dashed'] = $variableCamelizedDashed;
    $variables[$type . 'Underscored'] = $variableCamelizedUnderscored;
    $variables[$type . 'CamelizedWithSpaces'] = $variableCamelizedDashed;

    if ($verbose)
    {
        print "variable                      : $variable\n";
        print "variableCamelized             : $variableCamelized\n";
        print "variableCamelizedLcfirst      : $variableCamelizedLcfirst\n";
        print "variableCamelizedDashed       : $variableCamelizedDashed\n";
        print "variableCamelizedUnderscored  : $variableCamelizedUnderscored\n";
    }

}

/**
 * @param $hostname
 * @param $username
 * @param $password
 * @param $database
 * @param $variables
 */
function getMysqlColumns($hostname, $username, $password, $database, &$variables)
{

    $mysqli = new mysqli($hostname, $username, $password);
    if ($mysqli->connect_error)
    {
        die("Connection failed: " . $conn->connect_error);
    }
    $mysqli->select_db('information_schema');
    $query = "select * from columns where table_schema = '$database' and table_name='" . $variables['tablenameUnderscored'] . "'";
    $result = $mysqli->query($query);
    $columns = array();
    while($row = $result->fetch_row())
    {
        // keeping this here for debugging reasons
        //print_r($row);

        $obj = new \stdClass;
        $obj->name = $row[3];
        $obj->type = $row[7];

        // we can expect the results from mysql database to be underscore separated
        $obj->nameCamelized = camelize($obj->name, '_');

        $obj->nameCamelizedLcfirst = lcfirst($obj->nameCamelized);
        $obj->nameCamelizedWithSpaces = $obj->name;
        $obj->nameCamelizedWithSpaces = preg_replace('/_/', ' ', $obj->nameCamelizedWithSpaces);
        $obj->nameCamelizedWithSpaces = ucwords($obj->nameCamelizedWithSpaces);

        $obj->nameWithoutId = preg_replace('/_id/', '', $obj->name);

        $obj->nameCamelizedWithoutId = preg_replace('/Id$/', '', $obj->nameCamelized);

        $columns[] = $obj;
    }

    //$obj = new \stdClass;
    //$obj->name = 'test_id';
    //$obj->nameCamelized = camelize($obj->name, '_');
    //$obj->nameCamelizedLcfirst = lcfirst($obj->nameCamelized);
    //$obj->nameCamelizedWithSpaces = $obj->name;
    //$obj->nameCamelizedWithSpaces = preg_replace('/_/', ' ', $obj->nameCamelizedWithSpaces);
    //$obj->nameCamelizedWithSpaces = ucwords($obj->nameCamelizedWithSpaces);
    //$obj->nameWithoutId = preg_replace('/_id/', '', $obj->name);
    //$obj->nameCamelizedWithoutId = preg_replace('/Id$/', '', $obj->nameCamelized);
    //$columns[] = $obj;

    $variables['columns'] = $columns;

}

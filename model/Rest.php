<?php

class Rest {

    private $_request = array();
    private $_response;

    public function __construct() {
        $this->processRequest();
    }

    public function process() {
        $controllerName = $this->_request['controller'] . 'Controller'; /* AddressesController */
        if (!class_exists($controllerName)) {
            $this->_response = array("ResponseStatus" => 202, "Response" => "Requested Controller does not exists.");
            return;
        }
        $controllerObj = new $controllerName($this->_request); /* AddressesController */
        $controllerMethod = $this->_request['method']; /* GET|PUT|POST|DELETE */
        $controllerObj->$controllerMethod();
        $this->_response = $controllerObj->getResponse();
    }

    private function processRequest() {   //filling in $_request['controller'], $_request['method'] and $_request['params']
        if ($_SERVER['REQUEST_URI'] == '/') {
            echo "Hello World!";
            exit;
        }
        $requestUri = urldecode($_SERVER['REQUEST_URI']);
        $requestUri = trim($requestUri);
        $requestUri = array_filter(explode('/', $requestUri));
        $this->_request['controller'] = ucfirst(array_shift($requestUri));
        if (stristr($this->_request['controller'], "?") !== FALSE) {
            $pos = strpos($this->_request['controller'], "?");
            $this->_request['controller'] = substr($this->_request['controller'], 0, $pos);
        }
        if (!empty($requestUri)) {
            $_GET['id'] = array_shift($requestUri);
        }

        $this->_request['method'] = strtolower($_SERVER['REQUEST_METHOD']);

        switch ($this->_request['method']) {
            case 'get':
                $this->_request['params'] = $_GET; /* get whole collection or get one particular memeber. */
                break;
            case 'post':
                $this->_request['params'] = array_merge($_POST, $_GET); /* create a new entry in the collection. */
                break;
            case 'put':
                parse_str(file_get_contents('php://input'), $this->_request['params']); /* replace the entire collection with another collection or replace the addressed member of the collection. */
                $this->_request['params']['id'] = $_GET['id'];
                break;
            case 'delete':
                $this->_request['params'] = $_GET; /* delete the entire collection or delete the addressed member of the collection. */
                break;
            default:
                break;
        }
    }

    public function getResponse() {
        echo json_encode($this->_response);
    }

}

/*
    private static $codes = array(
        200 => 'Success',
        201 => 'Action not allowed',
        202 => 'Error: Requested Controller does not exists',
        203 => 'Error: Input data empty',
        204 => 'Error: Input data is not in JSON format',
        205 => 'Error: Input entry contains undefined fields',
        206 => 'Error: Input entry is not full. Each memeber of collection should contain all fields',
        207 => 'Error: The operation failed while working with database',
        208 => 'Error: Requested ID is incorrect',
        209 => 'Error: Requested ID not found',
        210 => 'Error: Only one object is expected in input',
        211 => 'Error: Invalid format of input data ',
        300 => 'Fatal Error: Unknown reason'
    );
 */
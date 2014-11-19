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
            $this->_response = array("ResponseStatus" => Codes::$CONTROLLER_NOT_EXISTS, "Response" => Codes::getMessage(Codes::$CONTROLLER_NOT_EXISTS));
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
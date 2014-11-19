<?php

class AddressesController {

    private $_request;
    private $_response = '';
    private $_responseStatus = '';

    public function __construct($request) {
        $this->_request = $request;
    }

    /**
     * GET 
     */

    public function get() {
        $addressObj = new Address();
        $id = $this->_request['params']['id'];
        $gets = $addressObj->getAddress($id);
        if ($gets == Codes::$ID_INCORRECT) {
            $this->_responseStatus = Codes::$ID_INCORRECT;
            $this->_response = Codes::getMessage($this->_responseStatus);
            return;
        }
        if ($gets == Codes::$ID_NOT_FOUND) {
            $this->_responseStatus = Codes::$ID_NOT_FOUND;
            $this->_response = Codes::getMessage($this->_responseStatus);
            return;
        }
        $this->_responseStatus = Codes::$SUCCES_CODE;
        $this->_response = $gets;
    }

    /**
     * POST
     */

    public function post() {
        $addressObj = new Address();
        $id = $this->_request['params']['id'];
        if (!empty($id)) {
            $this->_responseStatus = Codes::$ACTION_NOT_ALLOWED;
            $this->_response = Codes::getMessage($this->_responseStatus);
            return;
        }
        $data_json = $this->_request['params']['address'];
        if (empty($data_json)) {    // cheking input data for emptiness
            $this->_responseStatus = Codes::$INPUT_EMPTY;
            $this->_response = Codes::getMessage($this->_responseStatus);
            return;
        }
        $data = json_decode($data_json, true);  // cheking input data for JSON format
        if ($data == null) {
            $this->_responseStatus = Codes::$INPUT_NOT_JSON;
            $this->_response = Codes::getMessage($this->_responseStatus);
            return;
        }
        $this->_responseStatus = $addressObj->insertAddress($data);   //inserting new entry
        $this->_response = Codes::getMessage($this->_responseStatus);
    }

    /**
     * PUT
     */

    public function put() {
        $addressObj = new Address();
        $id = $this->_request['params']['id'];
        $data_json = $this->_request['params']['address'];
        if (empty($data_json)) {     // cheking input data for emptiness
            $this->_responseStatus = Codes::$INPUT_EMPTY;
            $this->_response = Codes::getMessage($this->_responseStatus);
            return;
        }
        $data = json_decode($data_json, true);  // cheking input data for JSON format
        if ($data == null) {
            $this->_responseStatus = Codes::$INPUT_NOT_JSON;
            $this->_response = Codes::getMessage($this->_responseStatus);
            return;
        }
        $this->_responseStatus = $addressObj->updateAddress($id, $data);      //update itself
        $this->_response = Codes::getMessage($this->_responseStatus);
    }

    /**
     * DELETE
     */

    public function delete() {
        $addressObj = new Address();
        $id = $this->_request['params']['id'];
        $this->_responseStatus = $addressObj->deleteAddress($id);
        $this->_response = Codes::getMessage($this->_responseStatus);
    }

    public function getResponse() {
        return array("ResponseStatus" => $this->_responseStatus, "Response" => $this->_response);
    }

}

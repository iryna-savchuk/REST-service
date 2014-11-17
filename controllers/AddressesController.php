<?php

class AddressesController {

    private $_request;
    private $_response = '';
    private $_responseStatus = '';

    public function __construct($request) {
        $this->_request = $request;
    }

    public function get() {
        $addressObj = new Address();
        $id = $this->_request['params']['id'];
        $this->_response = $addressObj->getAddress($id);
        if ($this->_response == 208) {
            $this->_responseStatus = 208;
            $this->_response = 'Error: The requested ID is incorrect!';
            return;
        }
        if ($this->_response == 209) {
            $this->_responseStatus = 209;
            $this->_response = 'Error: No record with the required ID found!';
            return;
        }
        $this->_responseStatus = 200; //success
    }

    public function post() {
        $addressObj = new Address();
        $id = $this->_request['params']['id'];
        if (!empty($id)) {
            $this->_response = 'Error: Method not allowed!';
            $this->_responseStatus = 201;
            return;
        }
        $data_json = $this->_request['params']['address'];
        if (empty($data_json)) {    // cheking input data for emptiness
            $this->_response = 'Error: The address parameter in POST query is empty!';
            $this->_responseStatus = 203;
            return;
        }
        $data = json_decode($data_json, true);  // cheking input data for JSON format
        if ($data == null) {
            $this->_response = 'Error: Input data is not in JSON format!';
            $this->_responseStatus = 204;
            return;
        }
        $this->_responseStatus = $addressObj->insertAddress($data);   //inserting new entry
        switch ($this->_responseStatus) {
            case 200:
                $this->_response = 'The new address has been added successfully!';
                break;
            case 205:
                $this->_response = 'Address adding failed: JSON data contain some undefined fields!';
                break;
            case 206:
                $this->_response = 'Address adding failed: JSON data is not full!';
                break;
            case 207:
                $this->_response = 'Address adding failed: Error occurred while inserting new data to the database!';
                break;
            case 210:
                $this->_response = 'Address adding failed: One address is expected to be insert at once!';
                break;
            case 211:
                $this->_response = 'Error: Invalid format of input data';
                break;
            default: {
                    $this->_response = 'Fatal Error: Unknown reason!';
                    $this->_responseStatus = 300;
                }
        }
    }

    public function put() {
        $addressObj = new Address();
        $id = $this->_request['params']['id'];
        $data_json = $this->_request['params']['address'];
        if (empty($data_json)) {     // cheking input data for emptiness
            $this->_response = 'Error: The address parameter in PUT query is empty. Update impossible!';
            $this->_responseStatus = 203;
            return;
        }
        $data = json_decode($data_json, true);  // cheking input data for JSON format
        if ($data == null) {
            $this->_response = 'Error: Input data is not in JSON format!';
            $this->_responseStatus = 300;
            return;
        }
        $this->_responseStatus = $addressObj->updateAddress($id, $data);      //update itself
        switch ($this->_responseStatus) {
            case 200:
                $this->_response = 'The data has been updated successfully!';
                break;
            case 205:
                $this->_response = 'Update failed: JSON data contain some undefined fields!';
                break;
            case 206:
                $this->_response = 'Update failed: JSON data is not full!';
                break;
            case 207:
                $this->_response = 'Update failed: Error occurred while working with database!';
                break;
            case 208:
                $this->_response = 'Update failed: Requested ID should be numeric!';
                break;
            case 209:
                $this->_response = 'Update failed: No record with the required ID found!';
                break;
            case 210:
                $this->_response = 'Update failed: One address entry is expected!';
                break;
            case 211:
                $this->_response = 'Error: Invalid format of input data';
                break;           
            default: {
                    $this->_response = 'Fatal Error: Unknown reason!';
                    $this->_responseStatus = 300;
                }
        }
    }

    public function delete() {
        $addressObj = new Address();
        $id = $this->_request['params']['id'];
        $this->_responseStatus = $addressObj->deleteAddress($id);
        switch ($this->_responseStatus) {
            case 200:
                $this->_response = 'The data has been deleted successfully!';
                break;
            case 207:
                $this->_response = 'Deletion failed: Error occurred while removing data from the database!';
                break;
            case 208:
                $this->_response = 'Data deletion failed: Requested ID is incorrect!';
                break;
            case 209:
                $this->_response = 'No record found by the requested ID!';
                break;
            default: {
                    $this->_response = 'Fatal Error: Unknown reason!';
                    $this->_responseStatus = 300;
                }
        }
    }

    public function getResponse() {
        return array("ResponseStatus" => $this->_responseStatus, "Response" => $this->_response);
    }

}

<?php

class Address {

    private $_tableName;
    private $_db;

    public function __construct() {
        $this->_tableName = 'address';
        $this->_db = DB::getInstance();
    }

    public function getAddress($id = null) {
        if ($id == null) {  //getting whole collection
            return $this->_db->getAll("SELECT * FROM ?n", $this->_tableName);
        } elseif (is_numeric($id)) {    //getting member of the collection
            $result = $this->_db->getRow("SELECT * FROM ?n WHERE ADDRESSID = ?i LIMIT 1", $this->_tableName, $id);
            if (empty($result)) {
                return 209; //No record found by ID
            }
            return $result;
        } else {
            return 208; //the requested ID is incorrect
        }
    }

    public function insertAddress($data = array()) {    //create a new entry in the collection
        if ($this->_isAssoc($data)) {
            return 211; // invalid format of input data 
        }
        if (count($data) > 1) {  
            return 210;  // one address is expected only
        }
        $columns = $this->_getColumns();
        foreach ($data as $address) {
            $datacheck = $this->_isCorrectEntry($address, $columns);
            if ($datacheck == 205) {
                return 205; //JSON data contains some undefined fields
            }
            if ($datacheck == 206) {
                return 206; //Entry is not full
            }
        }
        $sql = "INSERT INTO ?n SET ?u";
        $result = $this->_db->query($sql, $this->_tableName, $data[0]);
        if (!$result) {
            return 207;  //error while inserting new data to the database
        }
        return 200; //success
    }

    public function updateAddress($id = null, $data = array()) {
        if ($this->_isAssoc($data)) {
            return 211; // invalid format of input data 
        }
        $columns = $this->_getColumns();
        foreach ($data as $address) {   //input checks
            $datacheck = $this->_isCorrectEntry($address, $columns);
            if ($datacheck == 205) {
                return 205; //JSON data contains some undefined fields
            }
            if ($datacheck == 206) {
                return 206; //Entry is not full
            }
        }
        if ($id == null) {  // CASE 1: replace the entire collection with another collection
            $delete = $this->_db->query("DELETE FROM ?n", $this->_tableName);
            if (!$delete) {
                return 207;  //DB error
            }
            foreach ($data as $address) {
                $result = $this->_db->query("INSERT INTO ?n SET ?u", $this->_tableName, $address);
                if (!$result) {
                    return 207; //DB error
                }
            }
            return 200; //success
        } elseif (is_numeric($id)) {    // CASE 2: replace the addressed member of the collection
            if (count($data) > 1) {
                return 210; // one address is expected only when updating single record
            }
            $idcheck = $this->_db->getRow("SELECT * FROM ?n WHERE ADDRESSID = ?i LIMIT 1", $this->_tableName, $id);
            if (empty($idcheck)) {  // if the record doesn't exist, it should be created
                $sql = "INSERT INTO ?n SET ?u";
                $data[0]['ADDRESSID']=$id;
                $result = $this->_db->query($sql, $this->_tableName, $data[0]);
                if (!$result) {
                    return 207;  //error while inserting new data to the database
                }
                return 200; //success
            }
            $sql = "UPDATE ?n SET ?u WHERE ADDRESSID = ?i";
            $result = $this->_db->query($sql, $this->_tableName, $data[0], $id);
            if (!$result) {
                return 207; //DB error
            }
            return 200; //success
        } else {
            return 208; //incorrect ID
        }
    }

    public function deleteAddress($id = null) {
        if ($id == null) { //CASE 1: delete whole collection
            $result = $this->_db->query("DELETE FROM ?n", $this->_tableName);
            if (!result) {
                return 207; //DB error
            }
            return 200; //success
        } elseif (is_numeric($id)) { //CASE 2: delete a particular memeber
            $idcheck = $this->_db->getRow("SELECT * FROM ?n WHERE ADDRESSID = ?i LIMIT 1", $this->_tableName, $id);
            if (empty($idcheck)) {
                return 209; //No record found by ID
            }
            $result = $this->_db->query("DELETE FROM ?n WHERE ADDRESSID=?i", $this->_tableName, $id);
            if (!$result) {
                return 207; //DB error
            }
            return 200; //success
        } else {
            return 208; //ID is incorrect
        }
    }

    /*
     * Function to get fields names from the table. Returns array of fiels names excluding ID
     */

    private function _getColumns() {
        $columns = array();
        $sql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '$this->_tableName'";
        $result = $this->_db->getAll($sql);
        if (!empty($result)) {
            foreach ($result as $item) {
                $columns[] = $item["COLUMN_NAME"];
            }
        }
        array_shift($columns); //shift ID field off
        return $columns;
    }

    /*
     * Function to check whether entry is full and contains only defined fields
     */

    private function _isCorrectEntry($entry = array(), $columns = array()) {

        foreach ($entry as $key => $value) {
            if (!in_array($key, $columns)) {
                return 205;    //JSON entry contain some undefined fields
            }
        }
        if (count($entry) < count($columns)) {
            return 206;  //JSON entry is not full
        }
        return 'success';
    }

    private function _isAssoc($entry = array()) {
        return (bool) count(array_filter(array_keys($entry), 'is_string'));
    }

}

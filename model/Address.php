<?php

class Address {

    private $_tableName;
    private $_db;

    public function __construct() {
        $this->_tableName = 'address';
        $this->_db = DB::getInstance();
    }

    /**
     * Function to get all addresses or particular address by its ID
     */
    public function getAddress($id = null) {
        if ($id == null) {  //getting whole collection
            return $this->_db->getAll("SELECT * FROM ?n", $this->_tableName);
        } elseif (is_numeric($id)) {    //getting member of the collection
            $result = $this->_db->getRow("SELECT * FROM ?n WHERE ADDRESSID = ?i LIMIT 1", $this->_tableName, $id);
            if (empty($result)) {
                return Codes::$ID_NOT_FOUND;
            }
            return $result;
        } else {
            return Codes::$ID_INCORRECT;
        }
    }

    /**
     * Function to create a new address in the collection
     */
    public function insertAddress($data = array()) {
        if ($this->_isAssoc($data)) {
            return Codes::$INVALID_INPUT;
        }
        if (count($data) > 1) {
            return Codes::$ONE_OBJECT_EXPECTED;
        }
        $columns = $this->_db->getColumns($this->_tableName);
        array_shift($columns); //shift ID field off
        foreach ($data as $address) {
            $datacheck = $this->_isCorrectEntry($address, $columns);
            if ($datacheck == Codes::$UNDEFINED_FIELDS_DETECTED) {
                return Codes::$UNDEFINED_FIELDS_DETECTED;
            }
            if ($datacheck == Codes::$INPUT_NOT_FULL) {
                return Codes::$INPUT_NOT_FULL;
            }
        }
        $sql = "INSERT INTO ?n SET ?u";
        $result = $this->_db->query($sql, $this->_tableName, $data[0]);
        if (!$result) {
            return Codes::$DB_ERROR;
        }
        return Codes::$SUCCES_CODE;
    }

    /**
     * Function to update entire collection of addresses or particular address
     */
    public function updateAddress($id = null, $data = array()) {
        if ($this->_isAssoc($data)) {
            return Codes::$INVALID_INPUT;
        }
        $columns = $this->_db->getColumns($this->_tableName);
        array_shift($columns); //shift ID field off
        foreach ($data as $address) {   //input checks
            $datacheck = $this->_isCorrectEntry($address, $columns);
            if ($datacheck == Codes::$UNDEFINED_FIELDS_DETECTED) {
                return Codes::$UNDEFINED_FIELDS_DETECTED;
            }
            if ($datacheck == Codes::$INPUT_NOT_FULL) {
                return Codes::$INPUT_NOT_FULL;
            }
        }
        if ($id == null) {  // CASE 1: replace the entire collection with another collection
            $delete = $this->_db->query("DELETE FROM ?n", $this->_tableName);
            if (!$delete) {
                return Codes::$DB_ERROR;
            }
            foreach ($data as $address) {
                $result = $this->_db->query("INSERT INTO ?n SET ?u", $this->_tableName, $address);
                if (!$result) {
                    return Codes::$DB_ERROR;
                }
            }
            return Codes::$SUCCES_CODE;
        } elseif (is_numeric($id)) {    // CASE 2: replace the addressed member of the collection
            if (count($data) > 1) {
                return Codes::$ONE_OBJECT_EXPECTED;
            }
            $idcheck = $this->_db->getRow("SELECT * FROM ?n WHERE ADDRESSID = ?i LIMIT 1", $this->_tableName, $id);
            if (empty($idcheck)) {  // if the record doesn't exist, it should be created
                $sql = "INSERT INTO ?n SET ?u";
                $data[0]['ADDRESSID'] = $id;
                $result = $this->_db->query($sql, $this->_tableName, $data[0]);
                if (!$result) {
                    return Codes::$DB_ERROR;
                }
                return Codes::$SUCCES_CODE;
            }
            $sql = "UPDATE ?n SET ?u WHERE ADDRESSID = ?i";
            $result = $this->_db->query($sql, $this->_tableName, $data[0], $id);
            if (!$result) {
                return Codes::$DB_ERROR;
            }
            return Codes::$SUCCES_CODE;
        } else {
            return Codes::$ID_INCORRECT;
        }
    }

    /**
     * Function to delete entire collection of addresses or particular address
     */
    public function deleteAddress($id = null) {
        if ($id == null) { //CASE 1: delete whole collection
            $result = $this->_db->query("DELETE FROM ?n", $this->_tableName);
            if (!result) {
                return Codes::$DB_ERROR;
            }
            return Codes::$SUCCES_CODE;
        } elseif (is_numeric($id)) { //CASE 2: delete a particular memeber
            $idcheck = $this->_db->getRow("SELECT * FROM ?n WHERE ADDRESSID = ?i LIMIT 1", $this->_tableName, $id);
            if (empty($idcheck)) {
                return Codes::$ID_NOT_FOUND;
            }
            $result = $this->_db->query("DELETE FROM ?n WHERE ADDRESSID=?i", $this->_tableName, $id);
            if (!$result) {
                return Codes::$DB_ERROR;
            }
            return Codes::$SUCCES_CODE;
        } else {
            return Codes::$ID_INCORRECT;
        }
    }

    /**
     * Function to check whether entry is full and contains only defined fields
     */
    private function _isCorrectEntry($entry = array(), $columns = array()) {

        foreach ($entry as $key => $value) {
            if (!in_array($key, $columns)) {
                return Codes::$UNDEFINED_FIELDS_DETECTED;
            }
        }
        if (count($entry) < count($columns)) {
            return Codes::$INPUT_NOT_FULL;
        }
        return 'correct';
    }

    private function _isAssoc($entry = array()) {
        return (bool) count(array_filter(array_keys($entry), 'is_string'));
    }

}

<?php

class Codes {

    public static $SUCCES_CODE = 200;
    public static $ACTION_NOT_ALLOWED = 201;
    public static $CONTROLLER_NOT_EXISTS = 202;
    public static $INPUT_EMPTY = 203;
    public static $INPUT_NOT_JSON = 204;
    public static $UNDEFINED_FIELDS_DETECTED = 205;
    public static $INPUT_NOT_FULL = 206;
    public static $DB_ERROR = 207;
    public static $ID_INCORRECT = 208;
    public static $ID_NOT_FOUND = 209;
    public static $ONE_OBJECT_EXPECTED = 210;
    public static $INVALID_INPUT = 211;
    public static $FATAL_ERROR = 300;

    /**
     * function to return error message by the code 
     */
    public static function getMessage($code) {
        switch ($code) {
            case self::$SUCCES_CODE:
                $message = 'Success';
                break;
            case self::$ACTION_NOT_ALLOWED:
                $message = 'Action not allowed';
                break;
            case self::$CONTROLLER_NOT_EXISTS:
                $message = 'Error: Requested Controller does not exists';
                break;
            case self::$INPUT_EMPTY:
                $message = 'Error: Expected input data is empty';
                break;
            case self::$INPUT_NOT_JSON:
                $message = 'Error: Input data is not in JSON format';
                break;
            case self::$UNDEFINED_FIELDS_DETECTED:
                $message = 'Error: Input entry contains undefined fields';
                break;
            case self::$INPUT_NOT_FULL:
                $message = 'Error: Input entry is not full. Each memeber of collection should contain all fields';
                break;
            case self::$DB_ERROR:
                $message = 'Error: The operation failed while working with database';
                break;
            case self::$ID_INCORRECT:
                $message = 'Error: Requested ID is incorrect';
                break;
            case self::$ID_NOT_FOUND:
                $message = 'Error: Requested ID not found';
                break;
            case self::$ONE_OBJECT_EXPECTED:
                $message = 'Error: Only one object is expected in input';
                break;
            case self::$INVALID_INPUT:
                $message = 'Error: Invalid format of input data';
                break;
            default:
                $message = 'Fatal Error: Unknown reason';
                break;
        }
        return $message;
    }

}

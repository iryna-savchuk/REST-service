<?php

require_once 'model/DB.php';
require_once 'model/Rest.php';
require_once 'model/Address.php';
require_once 'model/Codes.php';
require_once 'controllers/AddressesController.php';

$restObj = new Rest();
$restObj->process();
echo $restObj->getResponse();

/**
 * The samples of input data:
  --------------------------------------------------
  1 address, not full:
  address=[{"LABEL":"Hospital 17","STREET":"Shevchenko","HOUSENUMBER":"33 b","CITY":"Cherkassy"}]
  --------------------------------------------------
  1 address not JSON:
  address=[{"LABEL":"Hospital 20","STREET":"Shevchenko","HOUSENUMBER":"122","CITY":"Zhitomir":}]
  --------------------------------------------------
  1 address, contains unknown fields:
  address=[{"LABEL":"Hospital 17","STREET":"Shevchenko","HOUSENUMBER":"33 b","NAME":"IRENE"}]
  --------------------------------------------------
  1 address, correct:
  address=[{"LABEL":"Hospital 15","STREET":"Sovetskaya","HOUSENUMBER":"15","CITY":"Kharkiv","POSTALCODE":"12129","COUNTRY":"Ukraine"}]
  --------------------------------------------------
  several addresses, the second not full:
  address=[{"LABEL":"School","STREET":"Lesi Ukrainky","HOUSENUMBER":"112","CITY":"Kyiv","POSTALCODE":"91222","COUNTRY":"Ukraine"},
  {"LABEL":"National University","STREET":"Svobody","HOUSENUMBER":"4","CITY":"Kharkiv","POSTALCODE":"93000"}]
  --------------------------------------------------
  several addresses, the second contains unknown fields:
  address=[{"LABEL":"School","STREET":"Lesi Ukrainky","HOUSENUMBER":"112","CITY":"Kyiv","POSTALCODE":"91222","COUNTRY":"Ukraine"},
  {"LABEL":"National University","NAME":"IRENE","STREET":"Svobody","HOUSENUMBER":"4","CITY":"Kharkiv","POSTALCODE":"93000"}]
  --------------------------------------------------
  several arrdesses, correct:
  address=[{"LABEL":"School 122","STREET":"Lenina","HOUSENUMBER":"12a","CITY":"Kyiv","POSTALCODE":"91222","COUNTRY":"Ukraine"},
  {"LABEL":"Hospital 20","STREET":"Darvina","HOUSENUMBER":"10","CITY":"Kharkiv","POSTALCODE":"745233","COUNTRY":"Ukraine"},
  {"LABEL":"Chiildrengarden","STREET":"Oboronnaya","HOUSENUMBER":"44","CITY":"Lugansk","POSTALCODE":"75709","COUNTRY":"Ukraine"}]
  --------------------------------------------------
 */
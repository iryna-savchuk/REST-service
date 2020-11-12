REST-service
============
This REST service was created to store and modify addresses.

It is supposed to work with the following URL requests:

http://www.rest.dev/addresses/ - working with collection of all addresses; 

http://www.rest.dev/addresses/addressId/ - working with a specific instance from the collection.

The incoming data is expected to be in JSON format. The response is also a JSON object, consisting of two fields - "ResponseStatus" and "Response", for example: 
```{"ResponseStatus":200,"Response":"The data has been updated successfully!"} ```

The table of the "ResponseStatus" codes:

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

The logics of operations is based on the Wikipedia article about REST service:
http://en.wikipedia.org/wiki/Representational_state_transfer

1. Method GET (operation read):

   a) http://www.rest.dev/addresses/ - in Response, returns a list all existing addresses from table.
   
   b) http://www.rest.dev/addresses/addressId/ - returns an exact instance from the address collection by the corresponding "addressId".

2. Method POST (operation create):

   a) http://www.rest.dev/addresses/ - creates a new record in the table of addresses. The variable "address" is expected in the input parameters that needs to be an array of one element(addess), for example:
   ```
   address=[{"LABEL":"Hospital 15","STREET":"Naukova","HOUSENUMBER":"15","CITY":"Kharkiv","POSTALCODE":"12129","COUNTRY":"Ukraine"}]
   ```
   
   b) http://www.rest.dev/addresses/addressId/ - returns and error, because when a new record is created, the corresponding ID is defined automatically and cannot be pre-set in request.

3. Method PUT (operation update):

   а) http://www.rest.dev/addresses/ - allows to update the collection of addresses.
   It expects the variable "address" in the input parameters that needs to be an array of one or multiple addresses, for example:
   ```
   address=[{"LABEL":"School 22","STREET":"Naukova","HOUSENUMBER":"12a","CITY":"Kyiv","POSTALCODE":"91222","COUNTRY":"Ukraine"},
   {"LABEL":"Hospital 20","STREET":"Darvina","HOUSENUMBER":"10","CITY":"Kharkiv","POSTALCODE":"745233","COUNTRY":"Ukraine"},
   {"LABEL":"Chiildrengarden","STREET":"Oboronnaya","HOUSENUMBER":"44","CITY":"Lugansk","POSTALCODE":"75709","COUNTRY":"Ukraine"}]
   ```
   While this, the previous data will be deleted, and the new data will recorded into the table.
   
   b) http://www.rest.dev/addresses/addressId/ - allows to upldate one specific address.
   It expects the variable "address" in the input parameters that needs to be an array of one element(address), for example:
   ```
   address=[{"LABEL":"Petrova Katya","STREET":"Bogdana Khmelnitskogo","HOUSENUMBER":"1b","CITY":"Lutsk","POSTALCODE":"34534","COUNTRY":"Ukraine"}]
   ```
   If there is no existing address which corresponds to the specified ID in the table, a new record will be created.

4. Method DELETE (operation delete):

   а) http://www.rest.dev/addresses/ - deletes the whole collection of addresses from the table.

   b) http://www.rest.dev/addresses/addressId/ - deletes one specific address from the data table by the corresponding "addressId".

General notes regarding the service functioning:

= Update/insert will be performed only if the input data in "address" parameter contains full objects-addresses (meaning all the fields are required in the address) and if it doesn't contain any unexpacted keys (meaning no additinal fields that differ from the fiells in the table are allowed). Thus, every incoming object(address) must have the following keys: "LABEL", "STREET", "HOUSENUMBER", "CITY", "POSTALCODE", "COUNTRY".

= A user authorization is not considered while processing the requests.

= To take security measures while working with MySQL requests, while solving the task, the following article has been taken into account: http://habrahabr.ru/post/165069/ 
It is devoted to preventing MySQL injections and contains a useful class for working with databases.

= To test the service, the Firefor addon named Poster has been used: https://addons.mozilla.org/en-US/firefox/addon/poster/  

------------------------------
Author: Iryna (Ivko) Savchuk. Date: 13-Nov-2014. 
------------------------------

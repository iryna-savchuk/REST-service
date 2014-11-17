REST-service
============
Данный REST сервис предназначен для хранения и модификации адресов. 

Предполагается работа с URL запросами: 
http://www.rest.dev/addresses/ - работа с коллекцией всех адресов;
http://www.rest.dev/addresses/addressId/ - работа с конкретным представителем коллекции.

Входящие данные ожидаются в формате JSON. Ответ также представляет собой JSON объект, состоящий из двух полей, а именно ResponseStatus и Response, например: 
{"ResponseStatus":200,"Response":"The data has been updated successfully!"} 

Таблица кодов ResponseStatus в ответе:
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

За основу логики операций была взята статься о REST сервисе из Wikipedia: 
http://en.wikipedia.org/wiki/Representational_state_transfer

1. Метод GET (операция read):
   
   а) http://www.rest.dev/addresses/  - возвращает в Response список всех существующих адресов из таблицы.
   
   б) http://www.rest.dev/addresses/addressId/ - возвращает один конкретный представитель коллекции адресов, соответствующий указанному в ссылке addressId. 

2. Метод POST (операция create):
   
   а) http://www.rest.dev/addresses/  - создает новую запись в таблице адресов. 
   Ожидает во входящих параметрах переменную address, которая будет представлять собой массив из одного элемента-адреса, например:
   address=[{"LABEL":"Hospital 15","STREET":"Sovetskaya","HOUSENUMBER":"15","CITY":"Kharkiv","POSTALCODE":"12129","COUNTRY":"Ukraine"}]
   
   б) http://www.rest.dev/addresses/addressId/ - выдает ошибку, так как создание новой записи в таблице осуществляется с автоматическим определением ID и не может быть доступно по заданному идентификатору. 

3. Метод PUT (операция update):
   
   а) http://www.rest.dev/addresses/  - позволяет обновить всю коллекцию адресов. 
   Ожидает во входящих параметрах переменную address, которая будет представлять собой массив новых адресов (одного или многих), например:
   address=[{"LABEL":"School 22","STREET":"Lenina","HOUSENUMBER":"12a","CITY":"Kyiv","POSTALCODE":"91222","COUNTRY":"Ukraine"},{"LABEL":"Hospital 20","STREET":"Darvina","HOUSENUMBER":"10","CITY":"Kharkiv","POSTALCODE":"745233","COUNTRY":"Ukraine"},{"LABEL":"Chiildrengarden","STREET":"Oboronnaya","HOUSENUMBER":"44","CITY":"Lugansk","POSTALCODE":"75709","COUNTRY":"Ukraine"}]
   При этом, предыдущие данные будут удалены, а новые данные записаны в таблицу.

   б) http://www.rest.dev/addresses/addressId/ - позволяет обновить один конкретный адрес.
   Ожидает во входящих параметрах переменную address, которая будет представлять собой массив из одного элемента-адреса, например:
   address=[{"LABEL":"Petrova Katya","STREET":"Bogdana Khmelnitskogo","HOUSENUMBER":"12 b","CITY":"Lutsk","POSTALCODE":"34534","COUNTRY":"Ukraine"}]
   В случае, если в таблице не существует адреса с указанным ID, создает новую запись.

4. Метод DELETE (операция delete):
   а) http://www.rest.dev/addresses/  - удаляет всю коллекцию адресов в таблице.
   б) http://www.rest.dev/addresses/addressId/ - удаляет из таблицы данных один конкретный адрес, соответствующий заданному идентификатору.

Общее замечание по работе сервиса: обновление/вставка данных будет осуществляться только при условии, что входящие данные в параметре address содержат полные объекты-адреса (т.е. указание значений всех полей адреса является обязательным) и не содержат посторонних ключей (т.е таких, которые отличаются от полей таблицы адресов). Таким образом, каждый входящий объект-адрес, должен иметь следующие ключи: "LABEL", "STREET", "HOUSENUMBER", "CITY", "POSTALCODE", "COUNTRY".

При выполнении задачи, мною был изучен вопрос безопасности работы с MySQL-запросами, в частности способы избежания MySQL-инъекций. В этом смысле, оказалась полезной следующая статья и класс для работы с базами данных: http://habrahabr.ru/post/165069/

ДЛЯ ТЕСТА:
Для тестирования данного REST сервиса, как и было предложено в условии задания, использовалось аддон Poster для Firefox: https://addons.mozilla.org/en-US/firefox/addon/poster/ 
Запустите этот аддон, введите строку запроса и вставьте необходимые Вам параметры по примеру строк, описанных выше. 

------------------------------
Автор: Ивко Ирина. 
Дата: 13 ноября, 2014. 
------------------------------

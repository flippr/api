api
===
This is an example class script proceeding secured API
To use this class you should keep same as query string and function name
Ex: If the query string value rquest=delete_user Access modifiers doesn't matter but function should be
     function delete_user(){
         You code goes here
     }
Class will execute the function dynamically;

usage :

    $object->response(output_data, status_code);
    $object->_request	- to get santinized input

    output_data : JSON (I am using)
    status_code : Send status message for headers

Add This extension for localhost checking :
    Chrome Extension : Advanced REST client Application
    URL : https://chrome.google.com/webstore/detail/hgmloofddffdnphfgcellkdfbfbjeloo

I used the below table for demo purpose:

```
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_fullname` varchar(25) NOT NULL,
  `user_email` varchar(50) NOT NULL,
  `user_password` varchar(50) NOT NULL,
  `user_status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
```
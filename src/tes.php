<?php
// require_once('./helper/request_data_validator.php');

// $validator = new RequestDataValidator();
// $dataValdate = [
//     'text'                => 'required|max:5|min:3'
// ];
// $data = [
//     'password'  => 1234,
//     'email'     => 'juniantowicaksono06@gmail.com', 
//     'konfirmasi_password'  => 1234,
//     'phoneNumber'=> "+6281354070748",
//     'text'  => "HAL"
// ];


// $result = $validator->validate($dataValdate, $data);
// $messages = $validator->getMessages();
// echo json_encode($messages);  

require_once('./utils/session.php');

$sess = new Session();

$sess->set('name', 'Anthony');
echo $sess->get('name');
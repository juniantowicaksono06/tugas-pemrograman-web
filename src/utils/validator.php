<?php 

class Validator {
    private $errorStatus;

    private $messages = [
        'validEmail'    => 'Email tidak valid',
        'required'      => 'Input %s wajib diisi',
        'matches'       => 'Input %s tidak sama dengan %s',
        'optional'      => 'Optional',
        'numeric'       => 'Input %s harus berupa angka',
        'phoneNumber'   => 'Nomor Handphone tidak valid',
        'max'           => 'Input %s maksimal berupa %s karakter',
        'min'           => 'Input %s minimal berupa %s karakter',
    ];

    private $inputNames = [];

    private $storedMessages = [];

    public function __construct() {
        $this->errorStatus = false;
    }

    private function phoneNumber($nomorHP) {
        $pattern = '/\b(?:\+?(\d{1,3}))?[-. (]*(\d{3})[-. )]*(\d{3})[-. ]*(\d{4})\b/';
        return preg_match_all($pattern, $nomorHP, $matches);
    }

    private function max($value, $length) {
        return strlen($value) <= $length;
    }

    private function min($value, $length) {
        return strlen($value) >= $length;
    }

    private function validEmail($email) {
        $regex = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
        return preg_match($regex, $email);
    }

    private function matches($input, $with) {
        return $input == $with;
    }

    private function required($input) {
        return !empty($input);
    }

    private function optional($input) {
        return true;
    }

    private function numeric($input) {
        return !is_numeric($input);
    }

    public function setMessages($messages) {
        foreach($messages as $key => $message) {
            $this->messages[$key] = $message;
        }
    }

    public function setInputName($input) {
        $this->inputNames = $input;
    }

    public function validate($input, $data) {
        $this->storedMessages = [];
        foreach($input as $key => $rules) {
            if (strpos(strtolower($rules), 'required') !== false) {
                if(!array_key_exists($key, $data)) {
                    $inputName = $key;
                    if(array_key_exists($key, $this->inputNames)) {
                        $inputName = $this->inputNames[$key];
                    }
                    $this->storedMessages = array_merge($this->storedMessages, [
                        $key    => [
                            'required'  => sprintf($this->messages['required'], $inputName)
                        ]
                    ]);
                    continue;
                }
            }
            $eachRule = explode('|', $rules);
            foreach($eachRule as $rule) {
                if(!array_key_exists($key, $data)) {
                    continue;
                }
                $fn = $rule;
                $fnParameters = [$data[$key]];
                $matchesPattern = "/\[(.*?)\]/";
                if(!array_key_exists($key, $this->inputNames)) {
                    $keys = [$key];
                }
                else {
                    $keys = [$this->inputNames[$key]];
                }
                if (preg_match($matchesPattern, $rule, $matches)) {
                    $fn = strstr($fn, '[', true);
                    if(!array_key_exists($matches[1], $this->inputNames)) {
                        array_push($keys, $matches[1]);
                    }
                    else {
                        array_push($keys, $this->inputNames[$matches[1]]);
                    }
                    array_push($fnParameters, array_key_exists($matches[1], $data) ? $data[$matches[1]] : null);
                }
                else if(strpos($rule, ":") !== false) {
                    $value = explode(":", $rule);
                    $fn = $value[0];
                    array_push($fnParameters, end($value));
                    array_push($keys, end($value));
                }
                if(method_exists($this, $fn)) {
                    $result = call_user_func_array(array($this, $fn), $fnParameters);  
                    if(!$result) {
                        $this->errorStatus = true;
                        $msg = vsprintf($this->messages[$fn], $keys);
                        $this->storedMessages = array_merge($this->storedMessages, [
                            $key    => [
                                $fn => $msg
                            ]
                        ]);
                    }
                }
            }
        }
        return empty($this->storedMessages);
    }
    public function getMessages() {
        return $this->storedMessages;
    }
}
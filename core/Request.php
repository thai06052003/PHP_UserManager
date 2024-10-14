<?php
class Request
{
    private $__rules = [], $__message = [], $__errors = [];
    public $db;
    public function __construct() {
        $this->db = new Database();
    }
    public function getMethod()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }
    public function isPost()
    {
        if ($this->getMethod() == 'post') {
            return true;
        }
        return false;
    }
    public function isGet()
    {
        if ($this->getMethod() == 'get') {
            return true;
        }
        return false;
    }
    public function getFields()
    {
        $dataFields = [];
        if ($this->isGet()) {
            // Lấy dữ liệu với phương thức get
            if (!empty($_GET)) {
                echo 'get';
                foreach ($_GET as $key => $value) {
                    if (is_array($value)) {
                        $dataFields[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                    } else {
                        $dataFields[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                    }
                }
            }
        } else if ($this->isPost()) {
            // Lấy dữ liệu với phương thức post
            if (!empty($_POST)) {
                foreach ($_POST as $key => $value) {
                    if (is_array($value)) {
                        $dataFields[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                    } else {
                        $dataFields[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                    }
                }
            }
        }
        return $dataFields;
    }
    // set rules
    public function rules($rules = [])
    {
        $this->__rules = $rules;
    }
    // set message
    public function message($message)
    {
        $this->__message = $message;
        //$validate = $this->validate();
    }
    // Run validate
    public function validate()
    {
        $this->__rules = array_filter($this->__rules);
        $checkValidate = true;
        if (!empty($this->__rules)) {
            $dataFields = $this->getFields();

            foreach ($this->__rules as $fieldName => $ruleItem) {
                $ruleItemArr = explode('|', $ruleItem);
                foreach ($ruleItemArr as $rule) {
                    $rulesArr = explode(':', $rule);
                    $ruleName = null;
                    $rulevalue = null;

                    $ruleName = reset($rulesArr);
                    if (count($rulesArr) > 1) {
                        $rulevalue = end($rulesArr);
                    }
                    if ($ruleName == 'required') {
                        if (empty(trim($dataFields[$fieldName]))) {
                            $this->setErrors($fieldName, $ruleName);
                        }
                    }
                    if ($ruleName == 'min') {
                        if (strlen(trim($dataFields[$fieldName])) < $rulevalue) {
                            $this->setErrors($fieldName, $ruleName);
                        }
                    }
                    if ($ruleName == 'max') {
                        if (strlen(trim($dataFields[$fieldName])) > $rulevalue) {
                            $this->setErrors($fieldName, $ruleName);
                        }
                    }
                    if ($ruleName == 'email') {
                        if (!filter_var($dataFields[$fieldName], FILTER_VALIDATE_EMAIL)) {
                            $this->setErrors($fieldName, $ruleName);
                        }
                    }
                    if ($ruleName == 'match') {
                        if (trim($dataFields[$fieldName]) != trim($dataFields[$rulevalue])) {
                            $this->setErrors($fieldName, $ruleName);
                        }
                    }
                    if ($ruleName == 'unique') {
                        $tableName = null;
                        $fieldCheck = null;

                        if (!empty($rulesArr[1])) {
                            $tableName = $rulesArr[1];
                        }
                        if (!empty($rulesArr[2])) {
                            $fieldCheck = $rulesArr[2];
                        }
                        if (!empty($tableName) && !empty($fieldCheck)) {
                            if (count($rulesArr)==3) {
                                $checkExist = $this->db->query("SELECT $fieldCheck FROM $tableName WHERE $fieldCheck = 'trim($dataFields[$fieldName])'")->rowCount();
                            }
                            else if (count($rulesArr)==4) {
                                if (!empty($rulesArr[3]) && preg_match('~.+?\=.+?~is',$rulesArr[3])) {
                                    $conditionWhere = $rulesArr[3];
                                    $conditionWhere = str_replace('=', '<>', $conditionWhere);
                                    //echo $conditionWhere;
                                    $checkExist = $this->db->query("SELECT $fieldCheck FROM $tableName WHERE $fieldCheck = 'trim($dataFields[$fieldName])' AND $conditionWhere")->rowCount();
                                }
                            }

                            if (!empty($checkExist)) {
                                $this->setErrors($fieldName, $ruleName);
                            }
                        }
                    }
                    // Validate age
                    if (preg_match('~^callback_(.+)~is', $ruleName, $callbackArr)){
                        if (!empty($callbackArr[1])) {
                            $callbackName = $callbackArr[1];
                            $controller = App::$app->getCurrentController();
                            if (method_exists($controller, $callbackName)) {
                                $checkCallback = call_user_func_array([$controller, $callbackName], [trim($dataFields[$fieldName])]);
                                if (!$checkCallback) {
                                    $this->setErrors($fieldName, $ruleName);
                                }
                            }

                        }
                    }
                }
            }
        }
        if (!empty($this->__errors)) {
            $checkValidate = false;
        }
        $sessionKey = Session::isInvalid();
        Session::flash($sessionKey.'_errors', $this->error());
        Session::flash($sessionKey.'_old', $this->getFields());

        return $checkValidate;
    }
    // Get errors
    public function error($fieldName = '')
    {
        if (!empty($this->__errors)) {
            if (empty($fieldName)) {
                foreach ($this->__errors as $key => $error) {
                    $errorsArr[$key] = reset($error);
                }
                return $errorsArr;
                //return $this->__errors;
            }
            return reset($this->__errors[$fieldName]);
        }
        return false;
    }
    // Set error
    public function setErrors($fieldName, $ruleName)
    {
        $this->__errors[$fieldName][$ruleName] = $this->__message[$fieldName . '.' . $ruleName];
    }
}

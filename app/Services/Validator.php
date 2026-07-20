<?php

class Validator {
    /**
     * Menyimpan kumpulan pesan error
     * @var array
     */
    private static $errors = [];

    /**
     * Menjalankan proses validasi
     * @param array $data  Data input (misal: $_POST atau $_GET)
     * @param array $rules Aturan validasi (misal: ['username' => 'required|min:5'])
     * @return bool        Mengembalikan true jika lolos validasi, false jika ada error
     */
    public static function validate($data, $rules) {
        self::$errors = []; // Reset errors on every validation call

        foreach ($rules as $field => $fieldRules) {
            $value = $data[$field] ?? null;
            $rulesArray = explode('|', $fieldRules);

            foreach ($rulesArray as $rule) {
                $ruleName = $rule;
                $ruleValue = null;

                if (strpos($rule, ':') !== false) {
                    if (strpos($rule, 'regex:') === 0) {
                        $ruleName = 'regex';
                        $ruleValue = substr($rule, 6); 
                    } else {
                        list($ruleName, $ruleValue) = explode(':', $rule, 2);
                    }
                }

                self::checkRule($field, $value, $ruleName, $ruleValue, $data);
            }
        }

        return empty(self::$errors);
    }

    /**
     * Memeriksa kecocokan data dengan aturan yang ditentukan
     */
    private static function checkRule($field, $value, $ruleName, $ruleValue, $allData = []) {
        $value = is_string($value) ? trim($value) : $value;
        $fieldName = self::formatFieldName($field);

        switch ($ruleName) {
            case 'required':
                if ($value === null || $value === '' || (is_array($value) && empty($value))) {
                    if (isset($_COOKIE['user_language']) && $_COOKIE['user_language'] == 'id') {
                        self::addError($field, "Kolom {$fieldName} wajib diisi.");
                    }else{
                        self::addError($field, "Column {$fieldName} is required.");
                    }
                }
                break;

            case 'email':
                if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    if (isset($_COOKIE['user_language']) && $_COOKIE['user_language'] == 'id') {
                        self::addError($field, "Format {$fieldName} tidak valid.");
                    }else{
                        self::addError($field, "Format {$fieldName} is not valid email address.");
                    }
                }
                break;

            case 'numeric':
                if (!empty($value) && !is_numeric($value)) {
                    if (isset($_COOKIE['user_language']) && $_COOKIE['user_language'] == 'id') {
                        self::addError($field, "Kolom {$fieldName} harus berupa angka.");
                    }else{
                        self::addError($field, "Column {$fieldName} must be numeric.");
                    }
                }
                break;

            case 'minlength':
                if (!empty($value)) {
                    // Gunakan mb_strlen agar akurat menghitung panjang string
                    if (mb_strlen((string)$value) < (int)$ruleValue) {
                        if (isset($_COOKIE['user_language']) && $_COOKIE['user_language'] == 'id') {
                            self::addError($field, "Kolom {$fieldName} minimal harus {$ruleValue} karakter.");
                        }else{
                            self::addError($field, "Column {$fieldName} must be at least {$ruleValue} characters.");
                        }
                    }
                }
                break;

            case 'min':
                if (!empty($value)) {
                    if (is_numeric($value)) {
                        if ($value < $ruleValue) {
                            if (isset($_COOKIE['user_language']) && $_COOKIE['user_language'] == 'id') {
                                self::addError($field, "Kolom {$fieldName} minimal harus {$ruleValue}.");
                            }else{
                                self::addError($field, "Column {$fieldName} must be at least value {$ruleValue}.");
                            }
                        }
                    } elseif (strlen($value) < $ruleValue) {
                        if (isset($_COOKIE['user_language']) && $_COOKIE['user_language'] == 'id') {
                            self::addError($field, "Kolom {$fieldName} minimal harus {$ruleValue} karakter.");
                        }else{
                            self::addError($field, "Column {$fieldName} must be at least {$ruleValue} characters.");
                        }
                    }
                }
                break;

            case 'max':
                if (!empty($value)) {
                    if (is_numeric($value)) {
                        if ($value > $ruleValue) {
                            if (isset($_COOKIE['user_language']) && $_COOKIE['user_language'] == 'id') {
                                self::addError($field, "Kolom {$fieldName} maksimal adalah {$ruleValue}.");
                            }else{
                                self::addError($field, "Column {$fieldName} must be at most value {$ruleValue}.");
                            }
                        }
                    } elseif (strlen($value) > $ruleValue) {
                        if (isset($_COOKIE['user_language']) && $_COOKIE['user_language'] == 'id') {
                            self::addError($field, "Kolom {$fieldName} maksimal adalah {$ruleValue} karakter.");
                        }else{
                            self::addError($field, "Column {$fieldName} must be at most {$ruleValue} characters.");
                        }
                    }
                }
                break;

            case 'matches':
                if ($value !== ($allData[$ruleValue] ?? null)) {
                    $matchFieldName = self::formatFieldName($ruleValue);
                    if (isset($_COOKIE['user_language']) && $_COOKIE['user_language'] == 'id') {
                        self::addError($field, "Kolom {$fieldName} harus sama dengan {$matchFieldName}.");
                    }else{
                        self::addError($field, "Column {$fieldName} must match {$matchFieldName}.");
                    }
                }
                break;

            case 'regex':
                if (!empty($value)) {
                    if (!preg_match($ruleValue, $value)) {  
                        if (isset($_COOKIE['user_language']) && $_COOKIE['user_language'] == 'id') {
                            self::addError($field, "Format {$fieldName} tidak sesuai aturan.");
                        }else{
                            self::addError($field, "Format {$fieldName} is no valid.");
                        }
                    }
                }
                break;
        }
    }

    /**
     * Menambahkan pesan error ke dalam array errors
     */
    private static function addError($field, $message) {
        self::$errors[$field][] = $message;
    }

    /**
     * Mengambil semua daftar error yang terjadi
     * @return array
     */
    public static function getErrors() {
        return self::$errors;
    }

    /**
     * Mengambil semua pesan error dalam format string (biasanya untuk alert)
     * @return string
     */
    public static function getErrorsString() {
        if (empty(self::$errors)) return '';
        
        $output = '- ';
        foreach (self::$errors as $fieldErrors) {
            foreach ($fieldErrors as $error) {
                $output .= "{$error}, ";
            }
        }
        $output .= '';
        return $output;
    }

    /**
     * Mengambil pesan error pertama dari field tertentu
     */
    public static function first($field) {
        return self::$errors[$field][0] ?? null;
    }

    /**
     * Merapikan nama field agar lebih manusiawi
     */
    private static function formatFieldName($field) {
        $formatted = str_replace(['_', '-'], ' ', $field);
        $formatted = preg_replace('/(?<!\ )[A-Z]/', ' $0', $formatted);
        return ucwords(trim($formatted));
    }
}

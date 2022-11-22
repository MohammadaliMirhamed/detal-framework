<?php

namespace App\Configs;

use App\Foundations\Contracts\ValidationInterface;
use App\Configs\Database;

class Validation implements ValidationInterface
{

    /**
     * @var string $name
     */
    public string $name;

    /**
     * @var mixed $value
     */
    public mixed $value;

    /**
     * @var array $errors
     */
    public array $errors = array();

    /**
     * @var array $patterns
     */
    public array $patterns = array(
        'uri'           => '[A-Za-z0-9-\/_?&=]+',
        'url'           => '[A-Za-z0-9-:.\/_?&=#]+',
        'alpha'         => '[\p{L}]+',
        'words'         => '[\p{L}\s]+',
        'alphanum'      => '[\p{L}0-9]+',
        'int'           => '[0-9]+',
        'float'         => '[0-9\.,]+',
        'tel'           => '[0-9+\s()-]+',
        'text'          => '[\p{L}0-9\s-.,;:!"%&()?+\'°#\/@]+',
        'file'          => '[\p{L}\s0-9-_!%&()=\[\]#@,.;+]+\.[A-Za-z0-9]{2,4}',
        'folder'        => '[\p{L}\s0-9-_!%&()=\[\]#@,.;+]+',
        'address'       => '[\p{L}0-9\s.,()°-]+',
        'date_dmy'      => '[0-9]{1,2}\-[0-9]{1,2}\-[0-9]{4}',
        'date_ymd'      => '[0-9]{4}\-[0-9]{1,2}\-[0-9]{1,2}',
        'email'         => '[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+.[a-zA-Z0-9-.]+[.]+[a-z-A-Z]'
    );
        
    /**
     * Field name
     * 
     * @param string $name
     * @return this
     */
    public function name(string $name): ValidationInterface
    {
        $this->name = $name;
        return $this;
    }
    
    /**
     * Field value
     * 
     * @param mixed $value
     * @return this
     */
    public function value(mixed $value): ValidationInterface
    {
        $this->value = $value;
        return $this;
    }

    /**
     * File
     * 
     * @param mixed $value
     * @return this
     */
    public function file(mixed $value): ValidationInterface
    {
        $this->value = $value;
        return $this;
    }
    
    /**
     * Pattern to apply to regular expression recognition
     * 
     * @param string $name
     * @return this
     */
    public function pattern(string $name): ValidationInterface
    {
        if ($name == 'array') {
            if (!is_array($this->value)) {
                $this->errors[] = 'Format field '.$this->name.' is invalid.';
            }
                
        } else {
            $regex = '/^('.$this->patterns[$name].')$/u';
            if ($this->value != '' && !preg_match($regex, $this->value)) {
                $this->errors[] = 'Format field '.$this->name.' is invalid.';
            }       
        }

        return $this;
    }
    
    /**
     * Custom pattern
     * 
     * @param string $pattern
     * @return this
     */
    public function customPattern(string $pattern): ValidationInterface
    {
        $regex = '/^('.$pattern.')$/u';
        if ($this->value != '' && !preg_match($regex, $this->value)) {
            $this->errors[] = 'Format field '.$this->name.' is invalid.';
        }

        return $this;
    }

    /**
     * Required field
     * 
     * @return this
     */
    public function required(): ValidationInterface
    {
        if ((is_array($this->value) && $this->value['error'] == 4) || ($this->value == '' || $this->value == null)) {
            $this->errors[] = 'Field '.$this->name.' is required.';
        }

        return $this;
    }
    
    /**
     * Minimum length of Field value
     * 
     * @param int $min
     * @return this
     */
    public function min(int $length): ValidationInterface
    {
        if (is_string($this->value)) {
            if (strlen($this->value) < $length) {
                $this->errors[] = 'Value of '.$this->name.' is less than the minimum length';
            }
        } else {
            if ($this->value < $length) {
                $this->errors[] = 'Value of '.$this->name.' is less than the minimum length';
            }
        }

        return $this;
    }
        
    /**
     * Maximum length of Field value
     * 
     * @param int $max
     * @return this
     */
    public function max(int $length): ValidationInterface
    {
        if (is_string($this->value)) {
            if (strlen($this->value) > $length) {
                $this->errors[] = 'Value of '.$this->name.' is higher than the maximum length';
            }  
        } else {
            if ($this->value > $length) {
                $this->errors[] = 'Value of '.$this->name.' is higher than the maximum length';
            }
        }

        return $this;
    }
    
    /**
     * Compare with the value of another field
     * 
     * @param mixed $value
     * @return this
     */
    public function equal(mixed $value): ValidationInterface
    {
        if ($this->value != $value) {
            $this->errors[] = 'Value of '. $this->name .' is not match';
        }

        return $this;
    }
    
    /**
     * Maximum file size in KB
     *
     * @param int $size
     * @return this 
     */
    public function maxSize(int $size): ValidationInterface
    {
        if (is_array($this->value) && $this->value['error'] != 4 && $this->value['size'] > ($size * 1000)) {
            $this->errors[] = 'The file '.$this->name.' exceeds the maximum size of ' . $size .' KB.';
        }

        return $this;
    }
    
    /**
     * Extension (format) of the file
     *
     * @param array $extensions
     * @return this 
     */
    public function ext(array $extensions): ValidationInterface
    {
        if (
            is_array($this->value) && $this->value['error'] != 4
            && !in_array(pathinfo($this->value['name'], PATHINFO_EXTENSION), $extensions)
            && !in_array(strtoupper(pathinfo($this->value['name'], PATHINFO_EXTENSION)), $extensions)
        ) {
            $this->errors[] = 'The file '.$this->name.' it is not a ' . implode(', ',$extensions) . '.';
        }
            
        return $this;
    }
    
    /**
     * Purifies to prevent XSS attacks
     *
     * @param string $string
     * @return $string
     */
    public function purify(string $string): string
    {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Validated fields
     * 
     * @return bool
     */
    public function isSuccess(): bool
    {
        return empty($this->errors) ?? false;
    }
    
    /**
     * Validation errors
     * 
     * @return array $this->errors
     */
    public function getErrors(): array
    {
        return !$this->isSuccess() ? $this->errors : [];
    }
    
    /**
     * View errors in Html format
     * 
     * @return string $html
     */
    public function displayErrors(): string
    {
        $html = '<ul>';
        foreach($this->getErrors() as $error) {
            $html .= '<li>'.$error.'</li>';
        }
        $html .= '</ul>';
    
        return $html;
    }
    
    /**
     * View validation result
     *
     * @return bool|string
     */
    public function result(): bool|string
    {
        if (!$this->isSuccess()) {
            foreach($this->getErrors() as $error) {
                echo "$error\n";
            }
            exit;
            
        } else {
            return true;
        }
    }
    
    /**
     * Check if the value is a integer number
     *
     * @return ValidationInterface
     */
    public function isInt(): ValidationInterface
    {
        if (!filter_var($this->value, FILTER_VALIDATE_INT)) {
            $this->errors[] = 'Field '.$this->name.' it is not a integer type.';
        }
        
        return $this;
    }
    
    /**
     * Check if the value is a float number
     *
     * @return ValidationInterface
     */
    public function isFloat(): ValidationInterface
    {
        if (!filter_var($this->value, FILTER_VALIDATE_FLOAT)) {
            $this->errors[] = 'Field '.$this->name.' it is not a float type.';
        }
        
        return $this;
    }
    
    /**
     * Check if the value is a letter of the alphabet
     *
     * @return ValidationInterface
     */
    public function isAlpha(): ValidationInterface
    {
        if (!filter_var($this->value, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => "/^[a-zA-Z]+$/")))) {
            $this->errors[] = 'Field '.$this->name.' it is not a alphabet type.';
        }
        
        return $this;
    }

    /**
     * Check if the value is a string
     *
     * @return ValidationInterface
     */
    public function isString(): ValidationInterface
    {
        if (!is_string($this->value)) {
            $this->errors[] = 'Field '.$this->name.' it is not a string type.';
        }
        
        return $this;
    }
    
    /**
     * Check if the value is a letter or a number
     *
     * @return ValidationInterface
     */
    public function isAlphanum(): ValidationInterface
    {
        if (!filter_var($this->value, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => "/^[a-zA-Z0-9]+$/")))) {
            $this->errors[] = 'Field '.$this->name.' it is not a alphabet/number type.';
        }
        
        return $this;
    }
    
    /**
     * Check if the value is a url
     *
     * @return ValidationInterface
     */
    public function isUrl(): ValidationInterface
    {
        if (!filter_var($this->value, FILTER_VALIDATE_URL)) {
            $this->errors[] = 'Field '.$this->name.' it is not a url type.';
        }
        
        return $this;
    }       

    /**
     * Check if the value is uri
     *
     * @return ValidationInterface
     */
    public function isUri(): ValidationInterface
    {
        if (!filter_var($this->value, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => "/^[A-Za-z0-9-\/_]+$/")))) {
            $this->errors[] = 'Field '.$this->name.' it is not a uri type.';
        }
        
        return $this;
    }
    
    /**
     * Check if the value is true or false
     *
     * @return ValidationInterface
     */
    public function isBool(): ValidationInterface
    {
        if (!is_bool(filter_var($this->value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE))) {
            $this->errors[] = 'Field '.$this->name.' it is not a bool type.';
        }
        
        return $this;
    }
    
    /**
     * Check if the value is e-mail
     *
     * @return ValidationInterface
     */
    public function isEmail(): ValidationInterface
    {
        if (!filter_var($this->value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = 'Field '.$this->name.' it is not a email type.';
        }
        
        return $this;
    }

    /**
     * Check if the value is json
     *
     * @return ValidationInterface
     */
    public function isJson(): ValidationInterface
    {
        if (!json_decode($this->value)) {
            $this->errors[] = 'Field '.$this->name.' it is not a json type.';
        }
        
        return $this;
    }

    /**
     * Check if the value is duplicate in database/model
     * 
     * @param string $pattern
     * @return ValidationInterface
     */
    public function isDuplicate(string $pattern): ValidationInterface
    {
        [$table, $column] = explode(':', $pattern);

        $result = (new Database())->raw(
            "SELECT COUNT(id) as `count` FROM $table WHERE $column = :$column",
            [$column => $this->value]
        );

        if ($result[0]['count'] > 0) {
            $this->errors[] = 'Value of '.$this->name.' already exist in the database';
        }
        
        return $this;
    }
    
    /**
     * Check if the value exist in database/model
     * 
     * @param string $pattern
     * @return ValidationInterface
     */
    public function exist(string $pattern): ValidationInterface
    {
        [$table, $column] = explode(':', $pattern);

        $result = (new Database())->raw(
            "SELECT COUNT(id) as `count` FROM $table WHERE $column = :$column",
            [$column => $this->value]
        );

        if ($result[0]['count'] == 0) {
            $this->errors[] = 'Value of '.$this->name.' does not exist in the database';
        }
        
        return $this;
    }
}
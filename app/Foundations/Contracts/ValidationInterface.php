<?php

namespace App\Foundations\Contracts;

interface ValidationInterface
{
    /**
     * Field name
     * 
     * @param string $name
     * @return this
     */
    public function name(string $name): ValidationInterface;
    
    /**
     * Field value
     * 
     * @param mixed $value
     * @return this
     */
    public function value(mixed $value): ValidationInterface;

    /**
     * File
     * 
     * @param mixed $value
     * @return this
     */
    public function file(mixed $value): ValidationInterface;
    
    /**
     * Pattern to apply to regular expression recognition
     * 
     * @param string $name
     * @return this
     */
    public function pattern(string $name): ValidationInterface;
    
    /**
     * Custom pattern
     * 
     * @param string $pattern
     * @return this
     */
    public function customPattern(string $pattern): ValidationInterface;

    /**
     * Required field
     * 
     * @return this
     */
    public function required(): ValidationInterface;
    
    /**
     * Minimum length of the field value
     * 
     * @param int $min
     * @return this
     */
    public function min(int $length): ValidationInterface;
        
    /**
     * Maximum length of the field value
     * 
     * @param int $max
     * @return this
     */
    public function max(int $length): ValidationInterface;
    
    /**
     * Compare with the value of another field
     * 
     * @param mixed $value
     * @return this
     */
    public function equal(mixed $value): ValidationInterface;
    
    /**
     * Maximum file size
     *
     * @param  $size
     * @return this 
     */
    public function maxSize(int $size): ValidationInterface;
    
    /**
     * Extension (format) of the file
     *
     * @param array $extensions
     * @return this 
     */
    public function ext(array $extensions): ValidationInterface;
    
    /**
     * Purifies to prevent XSS attacks
     *
     * @param string $string
     * @return $string
     */
    public function purify(string $string): string;
    
    /**
     * Validated fields
     * 
     * @return bool
     */
    public function isSuccess(): bool;
    
    /**
     * Validation errors
     * 
     * @return array $this->errors
     */
    public function getErrors(): array;
    
    /**
     * View errors in Html format
     * 
     * @return string $html
     */
    public function displayErrors(): string;
    
    /**
     * View validation result
     *
     * @return bool|string
     */
    public function result(): bool|string;
    
    /**
     * Check if the value is a integer number
     *
     * @return ValidationInterface
     */
    public function isInt(): ValidationInterface;
    
    /**
     * Check if the value is a float number
     *
     * @return ValidationInterface
     */
    public function isFloat(): ValidationInterface;
    
    /**
     * Check if the value is a letter of the alphabet
     *
     * @return ValidationInterface
     */
    public function isAlpha(): ValidationInterface;
    
    /**
     * Check if the value is a string
     *
     * @return ValidationInterface
     */
    public function isString(): ValidationInterface;

    /**
     * Check if the value is a letter or a number
     *
     * @return ValidationInterface
     */
    public function isAlphanum(): ValidationInterface;
    
    /**
     * Check if the value is a url
     *
     * @return ValidationInterface
     */
    public function isUrl(): ValidationInterface;
    
    /**
     * Check if the value is uri
     *
     * @return ValidationInterface
     */
    public function isUri(): ValidationInterface;
    
    /**
     * Check if the value is true or false
     *
     * @return ValidationInterface
     */
    public function isBool(): ValidationInterface;
    
    /**
     * Check if the value is e-mail
     *
     * @return ValidationInterface
     */
    public function isEmail(): ValidationInterface;

    /**
     * Check if the value is json
     *
     * @return ValidationInterface
     */
    public function isJson(): ValidationInterface;

    /**
    * Check if the value is duplicate in database/model
    * 
    * @param string $pattern
    * @return ValidationInterface
    */
    public function isDuplicate(string $pattern): ValidationInterface;

    /**
     * Check if the value exist in database/model
     * 
     * @param string $pattern
     * @return ValidationInterface
     */
    public function exist(string $pattern): ValidationInterface;
   
}
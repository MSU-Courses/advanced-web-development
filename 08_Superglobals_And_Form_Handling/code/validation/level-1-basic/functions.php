<?php

/**
 * Validates that a required field is not empty.
 *
 * @param string $field The name of the field being validated
 * @param string $value The value to validate
 * @return bool True if the value is not empty, false otherwise
 */
function validateRequired(string $field, string $value): bool
{
   if (empty($value)) {
      return false;
   }
   return true;
}

/**
 * Validates that a value is a valid email address.
 *
 * @param string $email The email address to validate
 * @return bool True if the email is valid, false otherwise
 */
function validateEmail(string $email): bool
{
   return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validates that a string value is within a specified length range.
 *
 * @param string $field The name of the field being validated
 * @param string $value The value to validate
 * @param int $min The minimum allowed length (inclusive)
 * @param int $max The maximum allowed length (inclusive)
 * @return bool True if the value length is within range, false otherwise
 */
function validateLength(string $field, string $value, int $min, int $max): bool
{
   $length = strlen($value);
   return $length >= $min && $length <= $max;
}

/**
 * Validates that a value exists in an array of allowed values.
 *
 * @param string $field The name of the field being validated
 * @param string $value The value to validate
 * @param array $allowedValues Array of permitted values
 * @return bool True if the value is in the allowed values array, false otherwise
 */
function validateEnum(string $field, string $value, array $allowedValues): bool
{
   return in_array($value, $allowedValues);
}

/**
 * Generates HTML markup for displaying field validation errors.
 *
 * @param array $errors Associative array of field errors, where keys are field names and values are error message arrays
 * @param string $field The field name to retrieve errors for
 * @return string HTML unordered list containing error messages, or empty string if no errors exist
 */
function getErrors(array $errors, string $field): string
{
   $html = '';

   if (!empty($errors[$field])) {
      $html .= '<ul>';
      foreach ($errors[$field] as $error) {
         $html .= "<li>$error</li>";
      }
      $html .= '</ul>';
   }

   return $html;
}

/**
 * Retrieves and sanitizes a previously submitted form field value from $_POST.
 *
 * @param string $field The name of the form field to retrieve
 * @return string The sanitized POST value, or empty string if the field was not submitted
 */
function getOldValue(string $field): string
{
   return htmlspecialchars($_POST[$field] ?? '', ENT_QUOTES, 'UTF-8');
}

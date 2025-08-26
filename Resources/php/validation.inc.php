<?php
function setup_input($input, $field_name = '') {
    // Trim whitespace
    $input = trim($input);
    // Remove backslashes
    $input = stripslashes($input);
    // Prevent XSS
    $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');

    // Check for empty input
    if (empty($input)) {
        // Store error in session or return it for handling
        return ['error' => "The $field_name field cannot be empty"];
    }

    return ['value' => $input];
}
?>

<?php

class Core_Validation
{
    public static function validateExists($table, $column, $value)
    {
        $db = Core_Connection::getInstance();
        $sql = "SELECT COUNT(*) FROM {$table} WHERE {$column} = :val";
        $stmt = $db->prepare($sql);
        $stmt->execute([':val' => $value]);
        return $stmt->fetchColumn() > 0;
    }

    public static function isValidEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Specific workflow check for add to cart / checkout
     */
    public static function checkEmailStatus($email)
    {
        if (self::validateExists('users', 'email', $email)) {
            return 'exists'; // Trigger password request
        }
        return 'new'; // Allow guest/registration flow
    }
}

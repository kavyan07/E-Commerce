<?php

class Model_User extends Model_Abstract
{
    public function __construct($data = [])
    {
        $this->_resource = new Model_User_Resource();
        parent::__construct($data);
    }

    /**
     * Delete user and handle session/cascading logic
     */
    public function delete()
    {
        $userId = $this->getData('id');
        $currentSessionUserId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

        // Perform deletion from DB
        // PostgreSQL ON DELETE CASCADE will handle child tables (orders, carts)
        $result = parent::delete();

        if ($result && $userId == $currentSessionUserId) {
            // Auto logout if deleted user is the one currently logged in
            session_destroy();
        }

        return $result;
    }
}

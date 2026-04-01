<?php

abstract class View_Abstract
{
    protected $_template = '';
    protected $_data = [];

    public function __construct($data = [])
    {
        $this->_data = $data;
    }

    public function setTemplate($template)
    {
        $this->_template = $template;
        return $this;
    }

    public function __set($key, $value)
    {
        $this->_data[$key] = $value;
    }
    public function __get($key)
    {
        return isset($this->_data[$key]) ? $this->_data[$key] : null;
    }

    public function getRequest($key = null)
    {
        if ($key === null) {
            return $_REQUEST;
        }
        return isset($_REQUEST[$key]) ? $_REQUEST[$key] : null;
    }

    public function render($template = null)
    {
        $template = $template ?: $this->_template;
        $file = ROOT_PATH . '/app/Design/view/' . $template . '.phtml';

        if (file_exists($file)) {
            ob_start();
            extract($this->_data);
            include $file;
            return ob_get_clean();
        }
        return "Template $template not found.";
    }

    public function toHtml()
    {
        $content = $this->render();

        // Capture header and footer manually for the "toHtml" call
        ob_start();
        $data = $this->_data; // Reference or extract if needed by header/footer
        extract($data);

        include ROOT_PATH . '/includes/header.php';
        echo $content;
        include ROOT_PATH . '/includes/footer.php';

        return ob_get_clean();
    }

    public function getImageUrl($imagePath, $type = 'products')
    {
        if (!$imagePath) {
            return '/E-commerce-website/public/images/placeholder.jpg';
        }

        if (strpos($imagePath, 'http') === 0) {
            return $imagePath;
        }

        // Clean redundant prefixes
        $imagePath = ltrim($imagePath, '/');
        $prefixes = ['E-commerce-website/', 'public/images/', 'public/', 'media/'];
        foreach ($prefixes as $prefix) {
            if (strpos($imagePath, $prefix) === 0) {
                $imagePath = substr($imagePath, strlen($prefix));
            }
        }

        return '/E-commerce-website/public/images/' . $type . '/' . $imagePath;
    }
}

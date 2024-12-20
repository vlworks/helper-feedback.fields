<?php

namespace Vlworks\Helper;

/**
 *  FeedbackFields
 *  Поля создаются из единого места и используются в компоненте, параметрах
 *  component vendor main.feedback
 *  Требуется после создания полей в /local/php_interface/include/functions.php воссоздать подсказки в Почтовом событии с префиксом AUTHOR_FIELD
 */
class FeedbackFields
{
    protected static FeedbackFields $instance;

    protected string $prefix_l10n = "BASIS_FIELDS_";
    protected array $fields = [];
    protected string $callback = '';

    private function __construct(){}
    private function __clone(){}
    private function __wakeup(){}

    public static function getInstance(): FeedbackFields
    {
        if (!isset(self::$instance))
        {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getForParameters(): array
    {
        $result['NONE'] = call_user_func($this->callback, $this->prefix_l10n . 'P_' . 'NONE');

        foreach ($this->fields as $field)
        {
            $result[strtoupper($field)] = call_user_func($this->callback, $this->prefix_l10n . 'P_' . strtoupper($field));
        }

        return $result;
    }

    public function getForComponentsReq(): array
    {
        $_result = [];

        foreach ($this->fields as $field)
        {
            $_result[] = [
                "NAME" => strtoupper($field),
                "POST" => 'user_' . strtolower($field),
            ];
        }

        return $_result;
    }

    public function addField(string $field): void
    {
        if (!in_array($field, $this->fields))
            $this->fields[] = $field;
    }
    public function setCallback(string $func): void
    {
        $this->callback = $func;
    }
}
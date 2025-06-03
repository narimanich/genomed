<?php

namespace app\components;

use app\models\Url;
use Yii;

class ShortLinkGenerator
{
    /**
     * Генерирует уникальный короткий код
     *
     * @param int $length Длина кода (по умолчанию 6)
     * @return string
     */
    public function generateUniqueCode(int $length = 6): string
    {
        do {
            $code = $this->generateRandomString($length);
        } while ($this->isCodeExists($code));

        return $code;
    }

    /**
     * Проверяет, существует ли такой код в БД
     *
     * @param string $code
     * @return bool
     */
    protected function isCodeExists(string $code): bool
    {
        return Url::find()->where(['short_code' => $code])->exists();
    }

    /**
     * Генерирует случайную строку из букв и цифр
     *
     * @param int $length
     * @return string
     */
    protected function generateRandomString(int $length): string
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $charLength = strlen($chars) - 1;
        $result = '';

        for ($i = 0; $i < $length; $i++) {
            $result .= $chars[random_int(0, $charLength)];
        }

        return $result;
    }
}
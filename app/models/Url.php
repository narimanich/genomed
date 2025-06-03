<?php
namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class Url extends ActiveRecord
{
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
                // значение временной метки
                'value' => function() {
                    return time();
                },
            ],
        ];
    }

    public static function tableName()
    {
        return 'url';
    }

    /**
     * Правила валидации
     */
    public function rules()
    {
        return [
            [['original_url'], 'required', 'message' => 'Поле обязательно к заполнению'],
            [['original_url'], 'string', 'max' => 1024],
            [['short_code'], 'string', 'max' => 10],
            [['clicks'], 'integer', 'min' => 0],
            [['created_at'], 'integer'],
            [['original_url'], 'url', 'message' => 'Введите корректный URL'],
            [['short_code'], 'unique', 'targetClass' => 'app\models\Url', 'message' => 'Такой код уже существует'],
        ];
    }

    /**
     * Названия полей (labels)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'original_url' => 'Оригинальный URL',
            'short_code' => 'Короткий код',
            'created_at' => 'Дата создания',
            'clicks' => 'Количество переходов',
        ];
    }

    public function getLogs()
    {
        return $this->hasMany(UrlLog::class, ['url_id' => 'id']);
    }

    public function validateUrl($attribute)
    {
        if (!filter_var($this->$attribute, FILTER_VALIDATE_URL)) {
            $this->addError($attribute, 'Неверный формат URL.');
        }
    }

    public function checkAvailability($attribute)
    {
        $ch = curl_init($this->$attribute);
        curl_setopt_array($ch, [
            CURLOPT_HEADER => true,
            CURLOPT_NOBODY => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if (!$response || $httpCode >= 400) {
            $this->addError($attribute, 'Ресурс недоступен.');
        }
    }
}
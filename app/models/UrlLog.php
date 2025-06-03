<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class UrlLog extends ActiveRecord
{
    public static function tableName()
    {
        return 'url_log';
    }

    /**
     * Правила валидации
     */
    public function rules()
    {
        return [
            [['url_id'], 'required'],
            [['url_id'], 'integer'],
            [['ip'], 'string', 'max' => 45],
            [['visited_at'], 'integer'],
            [['url_id'], 'exist', 'skipOnError' => true,
                'targetClass' => \app\models\Url::class,
                'targetAttribute' => ['url_id' => 'id']
            ],
        ];
    }

    /**
     * Названия полей (labels)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url_id' => 'Ссылка',
            'ip' => 'IP-адрес',
            'visited_at' => 'Дата посещения',
        ];
    }

    public static function logVisit($urlId)
    {
        $model = new self();
        $model->url_id = $urlId;
        $model->ip = Yii::$app->request->userIP;
        $model->visited_at = time();
        $model->save();

        // Обновляем счетчик
        $url = Url::findOne($urlId);
        $url->clicks += 1;
        $url->save();
    }

    public function getUrl()
    {
        return $this->hasOne(Url::class, ['id' => 'url_id']);
    }
}
<?php

namespace app\controllers;

use app\models\Url;
use app\models\UrlLog;
use app\components\QrCodeGenerator;
use app\components\ShortLinkGenerator;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $model = new Url();
        return $this->render('index', compact('model'));
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLog()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => UrlLog::find()->with('url'),
            'pagination' => ['pageSize' => 20],
            'sort' => ['defaultOrder' => ['visited_at' => SORT_DESC]],
        ]);

        return $this->render('log', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionGenerate()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $model = new Url();
        $model->original_url = Yii::$app->request->post('url');

        $model->validateUrl('original_url');
        $model->checkAvailability('original_url');

        if ($model->hasErrors()) {
            return ['error' => $model->getFirstError('original_url')];
        }
        // Генерируем уникальный код
        $code = (new ShortLinkGenerator())->generateUniqueCode();

        $model->short_code = $code;
        $model->save();

        $shortUrl = Yii::$app->request->hostInfo . '/' . $code;
        $qrImage = base64_encode((new QrCodeGenerator())->generate($shortUrl));

        return [
            'success' => true,
            'qr' => "data:image/png;base64," . $qrImage,
            'shortUrl' => $shortUrl
        ];
    }

    public function actionRedirect($code)
    {
        $url = Url::find()->where(['short_code' => $code])->one();
        if ($url) {
            UrlLog::logVisit($url->id);
            return $this->redirect($url->original_url);
        }
        throw new \yii\web\NotFoundHttpException("Ссылка не найдена");
    }
}

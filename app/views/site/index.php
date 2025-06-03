<?php
use kartik\widgets\ActiveForm;
use kartik\widgets\Alert;
use yii\helpers\Html;
?>

    <h2 class="text-center">Генератор короткой ссылки</h2>
    <br>

<?php $form = ActiveForm::begin([
    'id' => 'url-form',
    'type' => ActiveForm::TYPE_HORIZONTAL,
]); ?>

<?= $form->field($model, 'original_url')->textInput(['placeholder' => 'Введите URL...']) ?>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <?= Html::button('Генерировать', ['class' => 'btn btn-primary', 'id' => 'generate-btn']) ?>
        </div>
    </div>

<?php ActiveForm::end(); ?>


    <!-- Модальное окно -->
    <div class="modal fade" id="qrModal" tabindex="-1" aria-labelledby="qrModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered justify-content-center align-items-center" style="min-width: 300px;">
            <div class="modal-content text-center p-4">
                <div class="modal-header border-0 justify-content-center">
                    <h5 class="modal-title" id="qrModalLabel">Ваш QR-код и короткая ссылка</h5>
                </div>
                <div class="modal-body">
                    <img id="modal-qr-image" src="" alt="QR Code" style="max-width: 200px; margin-bottom: 15px;" />
                    <br>
                    <a id="modal-short-url" href="#" target="_blank"></a>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <?= Html::button('Закрыть', ['class' => 'btn btn-secondary', 'data-bs-dismiss' => 'modal']) ?>
                </div>
            </div>
        </div>
    </div>

<?php
$this->registerJsFile('@web/js/index.js', [
    'depends' => [\yii\web\JqueryAsset::class],
    'position' => \yii\web\View::POS_END
]);
?>
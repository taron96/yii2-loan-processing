<?php

namespace app\controllers;

use app\services\ProcessorService;
use Yii;
use yii\rest\Controller;

class ProcessorController extends Controller
{
    private ProcessorService $service;

    /**
     * Dependency Injection via constructor
     *
     * @param                                $id
     * @param                                $module
     * @param \app\services\ProcessorService $service
     * @param array                          $config
     */
    public function __construct($id, $module, ProcessorService $service, $config = [])
    {
        $this->service = $service;
        parent::__construct($id, $module, $config);
    }

    /**
     * Endpoint: GET /processor
     * Validates loan requests and approves or rejects them
     *
     * @return array
     */
    public function actionIndex(): array
    {
        $delay = (int)Yii::$app->request->get('delay', 1);

        $this->service->processLoans($delay);

        Yii::$app->response->statusCode = 200;

        return [
            'result' => true,
        ];
    }
}

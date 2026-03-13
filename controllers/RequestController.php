<?php

declare(strict_types=1);

namespace app\controllers;

use app\services\LoanService;
use Yii;
use yii\rest\Controller;

/**
 * RequestController handles the API endpoints for loan management
 */
class RequestController extends Controller
{
    private LoanService $loanService;

    /**
     * Dependency Injection via constructor
     *
     * @param                           $id
     * @param                           $module
     * @param \app\services\LoanService $loanService
     * @param array                     $config
     */
    public function __construct($id, $module, LoanService $loanService, $config = [])
    {
        $this->loanService = $loanService;
        parent::__construct($id, $module, $config);
    }

    /**
     * Endpoint: POST /requests
     * Creates a loan request for a user
     *
     * @return array
     */
    public function actionCreate(): array
    {
        $data = Yii::$app->request->post();

        $model = $this->loanService->createRequest($data);

        if ($model) {
            Yii::$app->response->statusCode = 201;
            return [
                'result' => true,
                'id'     => $model->id,
            ];
        }

        Yii::$app->response->statusCode = 400;

        return ['result' => false];
    }
}

<?php

declare(strict_types=1);

namespace app\services;

use app\models\LoanRequest;
use app\repositories\LoanRequestRepository;
use Throwable;
use Yii;

/**
 * LoanService handles business logic for loan applications
 */
class LoanService
{
    /**
     * @var \app\repositories\LoanRequestRepository
     */
    private LoanRequestRepository $repository;

    public function __construct(LoanRequestRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Creates a new loan request after checking for existing approvals
     *
     * @param array $data
     *
     * @return LoanRequest|null Returns model on success, null on failure
     */
    public function createRequest(array $data): ?LoanRequest
    {
        $model = new LoanRequest();
        $model->load($data, '');

        if (!$model->validate()) {
            Yii::error('Validation failed: '.json_encode($model->getErrors()));
            return null;
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $exists = $this->repository->hasApprovedLoanWithLock($data['user_id']);
            if ($exists) {
                $transaction->rollBack();
                return null;
            }

            if ($model->save(false)) {
                $transaction->commit();
                return $model;
            }

            $transaction->rollBack();
        } catch (Throwable $e) {
            $transaction->rollBack();
            Yii::error("Error creating loan: ".$e->getMessage());
        }

        return null;
    }
}

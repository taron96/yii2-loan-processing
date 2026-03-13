<?php

declare(strict_types=1);

namespace app\services;

use app\models\LoanRequest;
use app\repositories\LoanRequestRepository;
use Throwable;
use Yii;

use function sleep;

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

    /**
     * Validates loan requests and approves or rejects them
     *
     * @param int $delay
     *
     * @return LoanRequest|null Returns model on success, null on failure
     */
    public function processLoans(int $delay): ?LoanRequest
    {
        $query = $this->repository->getPendingLoansQuery();

        foreach ($query->batch(100) as $loans) {
            foreach ($loans as $loan) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $hasApprovedLoan = $this->repository->hasApprovedLoanWithLock($loan->user_id);
                    if ($hasApprovedLoan) {
                        $loan->status = LoanRequest::STATUS_DECLINED;
                    } else {
                        if ($delay > 0) {
                            sleep($delay);
                        }

                        $loan->status = (random_int(
                                1,
                                100
                            ) <= 10) ? LoanRequest::STATUS_APPROVED : LoanRequest::STATUS_DECLINED;
                    }

                    $loan->updated_at = Yii::$app->formatter->asDatetime('now', 'php:Y-m-d H:i:s');

                    $loan->save();
                    $transaction->commit();
                } catch (Throwable $e) {
                    $transaction->rollBack();
                    Yii::error("Error processing loan ".$loan->id.": ".$e->getMessage());
                }
            }
        }

        return null;
    }
}

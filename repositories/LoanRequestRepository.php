<?php

declare(strict_types=1);

namespace app\repositories;

use app\models\LoanRequest;
use Yii;
use yii\db\ActiveQuery;
use yii\db\Connection;

class LoanRequestRepository
{
    private Connection $db;

    public function __construct()
    {
        $this->db = Yii::$app->db;
    }

    /**
     * Check if user has approved loan (with lock)
     *
     * @throws \yii\db\Exception
     */
    public function hasApprovedLoanWithLock(int $userId): bool
    {
        $sql = "SELECT id FROM ".LoanRequest::tableName().
            " WHERE user_id = :uid AND status = :status LIMIT 1 FOR UPDATE";

        return (bool)$this->db->createCommand($sql)
            ->bindValues([
                ':uid'    => $userId,
                ':status' => LoanRequest::STATUS_APPROVED,
            ])
            ->queryScalar();
    }

    /**
     * Returns query for pending loans to use it for batching later.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPendingLoansQuery(): ActiveQuery
    {
        return LoanRequest::find()->where(['status' => LoanRequest::STATUS_PENDING]);
    }
}

<?php

declare(strict_types=1);

namespace app\models;

use yii\db\ActiveRecord;

/**
 * LoanRequest ActiveRecord model
 *
 * @property int    $id
 * @property int    $user_id
 * @property double $amount
 * @property int    $term
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 */
class LoanRequest extends ActiveRecord
{
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_DECLINED = 'declined';

    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName(): string
    {
        return '{{%loan_requests}}';
    }

    /**
     * @return array the validation rules.
     */
    public function rules(): array
    {
        return [
            [['user_id', 'amount', 'term'], 'required'],
            [['user_id', 'term'], 'integer'],
            [['amount'], 'number', 'min' => 1, 'max' => 9999999999.99],
        ];
    }
}

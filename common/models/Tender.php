<?php

namespace common\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "tenders".
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property int $budget
 * @property int $status
 * @property int $created_by
 * @property-read ActiveQuery $nomenclatures
 */
class Tender extends ActiveRecord
{
    public const STATUS_ACTIVE = 1;
    public const STATUS_DRAFT = 0;

    public const STATUS_MAP = [
        self::STATUS_DRAFT => 'Чернетка',
        self::STATUS_ACTIVE => 'Активний'
    ];

    public const SCENARIO_UPDATE = 'update';

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'tenders';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', 'budget', 'status'], 'required'],
            [['description'], 'string'],
            [['budget', 'status'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['created_by'], 'integer'],
            [['status'], 'validateStatus', 'on' => self::SCENARIO_UPDATE]
        ];
    }

    public function validateStatus($attribute): void
    {
        /** @var self $currentTender */
        $currentTender = self::find()->where(['id' => $this->id])->one();

        if ($currentTender->status === self::STATUS_ACTIVE) {
            $this->addError('status', 'Активний тендер не може бути чернеткою');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Назва закупівлі',
            'description' => 'Опис',
            'budget' => 'Бюджет',
            'status' => 'Статус',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function nomenclatures(): ActiveQuery
    {
        return $this->hasMany(Nomenclature::class, ['tender_id' => 'id']);
    }
}

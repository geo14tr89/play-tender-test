<?php

namespace common\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "nomenclatures".
 *
 * @property int $id
 * @property int $tender_id
 * @property string $description
 * @property int|null $count
 * @property int|null $measure
 */
class Nomenclature extends ActiveRecord
{
    public const KILOGRAM = 1;
    public const LITER = 2;
    public const METER = 3;

    public const MEASURE_MAP = [
        self::KILOGRAM => 'кілограми',
        self::LITER => 'літри',
        self::METER => 'метри',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'nomenclatures';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['description'], 'required'],
            [['tender_id', 'count', 'measure'], 'integer'],
            [['description'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'tender_id' => 'Tender ID',
            'description' => 'Опис',
            'count' => 'Кількість',
            'measure' => 'Одиниці виміру',
        ];
    }

    public function tender(): ActiveQuery
    {
        return $this->hasOne(Tender::class, ['id' => 'tender_id']);
    }
}

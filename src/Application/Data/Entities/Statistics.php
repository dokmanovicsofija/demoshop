<?php

namespace Application\Data\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Statistics
 *
 * This class represents the Statistics entity in the database.
 * It uses Eloquent ORM for interaction with the database.
 *
 * @method static Statistics first() Retrieve the first record in the database.
 */
class Statistics extends Model
{
    /**
     * @var string The table associated with the model.
     */
    protected $table = 'statistics';

    /**
     * @var array The attributes that are mass assignable.
     * This defines which fields can be mass assigned, i.e., when you create or update a record
     * using methods like `create` or `update`, only these fields will be considered.
     */
    protected $fillable = [
        'home_view_count',
    ];

    /**
     * @var bool Indicates if the model should be timestamped.
     * If set to true, Eloquent will automatically manage `created_at` and `updated_at` fields.
     * If your table doesn't have these fields, set this to false.
     */
    public $timestamps = true;
}
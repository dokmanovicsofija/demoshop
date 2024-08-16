<?php

namespace Data\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Admin
 *
 * This class represents the Admin entity in the database.
 * It uses Eloquent ORM for interaction with the database.
 */
class Admin extends Model
{
    /**
     * @var string The table associated with the model.
     */
    protected $table = 'admins';

    /**
     * @var array The attributes that are mass assignable.
     * This defines which fields can be mass assigned, i.e., when you create or update a record
     * using methods like `create` or `update`, only these fields will be considered.
     */
    protected $fillable = [
        'username', 'password', 'token',
    ];

    /**
     * @var bool Indicates if the model should be timestamped.
     * If set to true, Eloquent will automatically manage `created_at` and `updated_at` fields.
     * If your table doesn't have these fields, set this to false.
     */
    public $timestamps = true;
}
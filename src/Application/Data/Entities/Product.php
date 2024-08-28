<?php

namespace Application\Data\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Product
 *
 * This class represents the Product entity in the database.
 * It uses Eloquent ORM for interaction with the database.
 *
 * @method static Product where(string $column, mixed $value) Add a basic where clause to the query.
 * @method static Product whereIn(string $column, array $values) Add a "where in" clause to the query.*
 * @method static Product first(array $columns = ['*']) Execute the query and get the first result.
 * @method static Product orderBy(string $column, string $direction = 'asc') Add an order by clause to the query.
 * @method static Product with($relations) Eager load relations.
 * @method static Product all(array $columns = ['*']) Get all the models from the database.
 * @method static Product skip(int $value) Skip the given number of results.
 * @method static Product take(int $value) Limit the number of results returned.
 * @method static int count() Get the count of the total records.
 * @method static Product get(array $columns = ['*']) Execute the query as a "select" statement.
 * @method static Product find(int $id) Find a model by its primary key.
 */
class Product extends Model
{
    /**
     * @var string The table associated with the model.
     */
    protected $table = 'products';

    /**
     * @var array The attributes that are mass assignable.
     * This defines which fields can be mass assigned, i.e., when you create or update a record
     * using methods like `create` or `update`, only these fields will be considered.
     */
    protected $fillable = [
        'sku',
        'title',
        'brand',
        'price',
        'category_id',
        'short_description',
        'description',
        'image',
        'enabled',
        'featured',
        'view_count'
    ];

    /**
     * @var bool Indicates if the model should be timestamped.
     * If set to true, Eloquent will automatically manage `created_at` and `updated_at` fields.
     * If your table doesn't have these fields, set this to false.
     */
    public $timestamps = true;

    /**
     * Define the relationship with the Category model.
     * A product belongs to a category.
     *
     * @return BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}

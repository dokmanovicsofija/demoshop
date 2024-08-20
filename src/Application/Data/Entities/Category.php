<?php

namespace Application\Data\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Category
 *
 * This class represents the Category entity in the database.
 * It uses Eloquent ORM for interaction with the database.
 */
class Category extends Model
{
    /**
     * @var string The table associated with the model.
     */
    protected $table = 'categories';

    /**
     * @var array The attributes that are mass assignable.
     * This defines which fields can be mass assigned, i.e., when you create or update a record
     * using methods like `create` or `update`, only these fields will be considered.
     */
    protected $fillable = [
        'code', 'title', 'description', 'parent_id',
    ];

    /**
     * @var bool Indicates if the model should be timestamped.
     * If set to true, Eloquent will automatically manage `created_at` and `updated_at` fields.
     * If your table doesn't have these fields, set this to false.
     */
    public $timestamps = true;

    /**
     * Define the relationship with the Product model.
     * A category has many products.
     *
     * @return HasMany
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Define the relationship with itself (for subcategories).
     * A category can have many subcategories.
     *
     * @return HasMany
     */
    public function subcategories()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Define the relationship with itself (for parent category).
     * A category can belong to a parent category.
     *
     * @return BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Class Task
 *
 * @package App\Models\Task
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $status
 * @property string $building_id
 * @property string $assigned_user_id
 * @property string $creator_user_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Building $building
 * @property-read User $assigned
 * @property-read User $creator
 * @property-read Collection|Comments[] $comments
 */
class Task extends Model
{
    use HasFactory;

    protected $table = 'tasks';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'status',
        'building_id',
        'assigned_user_id',
        'creator_user_id',
    ];

    /**
     * Gets the task building
     *
     * @return BelongsTo
     */
    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class);
    }

    /**
     * Gets the task assigned user
     *
     * @return BelongsTo
     */
    public function assigned(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    /**
     * Gets the task creator user
     *
     * @return BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_user_id');
    }

    /**
     * Gets the task comments
     *
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}

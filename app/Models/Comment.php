<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Class Comment
 *
 * @package App\Models\Comment
 *
 * @property int $id
 * @property string $content
 * @property string $task_id
 * @property string $creator_user_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Task $task
 * @property-read User $creator
 */
class Comment extends Model
{
    use HasFactory;

    protected $table = 'comments';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'content',
        'task_id',
        'creator_user_id',
    ];

    /**
     * Gets the comment task
     *
     * @return BelongsTo
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Gets the comment creator user
     *
     * @return BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
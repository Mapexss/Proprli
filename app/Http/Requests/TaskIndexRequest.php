<?php

namespace App\Http\Requests;

use App\Enums\TaskStatusEnum;
use App\Models\Building;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaskIndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['nullable', 'string', 'min:1', 'max:255'],
            'status' => ['nullable', Rule::enum(TaskStatusEnum::class)],
            'assigned_user_id' => ['nullable', 'integer', Rule::exists(User::class, 'id')],
            'start_date' => ['nullable', 'date', 'before_or_equal:end_date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ];
    }
}
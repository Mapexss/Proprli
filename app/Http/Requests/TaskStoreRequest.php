<?php

namespace App\Http\Requests;

use App\Enums\TaskStatusEnum;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaskStoreRequest extends FormRequest
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
            'name' => ['required', 'string', 'min:1', 'max:255'],
            'description' => ['required', 'string', 'min:1', 'max:255'],
            'status' => ['required', Rule::enum(TaskStatusEnum::class)],
            'creator_user_id' => ['required', 'integer', Rule::exists(User::class, 'id')],
            'assigned_user_id' => ['required', 'integer', Rule::exists(User::class, 'id')],
        ];
    }
}

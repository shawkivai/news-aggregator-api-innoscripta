<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleSearchRequest extends FormRequest
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
            'keyword' => 'nullable|string|max:255|required_without_all:date,category_id,source_id',
            'date' => 'nullable|date|required_without_all:keyword,category_id,source_id',
            'category_id' => 'nullable|integer|exists:categories,id|required_without_all:keyword,date,source_id',
            'source_id' => 'nullable|integer|exists:news_sources,id|required_without_all:keyword,date,category_id',
        ];
    }
}

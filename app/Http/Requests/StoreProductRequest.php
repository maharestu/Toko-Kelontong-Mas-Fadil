<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'unit' => ['required', 'string', 'max:50'],
            'stock' => ['required', 'integer', 'min:0'],
            'current_purchase_price' => ['required', 'numeric', 'min:0'],
            'current_selling_price' => ['required', 'numeric', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama produk wajib diisi.',
            'name.max' => 'Nama produk maksimal 255 karakter.',
            'category_id.required' => 'Kategori produk wajib dipilih.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid.',
            'unit.required' => 'Satuan produk wajib diisi (misal: pcs, kg, botol).',
            'stock.required' => 'Jumlah stok awal wajib diisi.',
            'stock.integer' => 'Stok harus berupa bilangan bulat.',
            'stock.min' => 'Stok tidak boleh bernilai negatif.',
            'current_purchase_price.required' => 'Harga modal/beli wajib diisi.',
            'current_purchase_price.numeric' => 'Harga modal harus berupa angka valid.',
            'current_selling_price.required' => 'Harga jual wajib diisi.',
            'current_selling_price.numeric' => 'Harga jual harus berupa angka valid.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar harus JPEG, PNG, JPG, atau WEBP.',
            'image.max' => 'Ukuran gambar maksimal 2 MB.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $mergeData = [];

        if ($this->has('name')) {
            $mergeData['name'] = trim(strip_tags((string) $this->name));
        }

        if ($this->has('unit')) {
            $mergeData['unit'] = trim(strip_tags((string) $this->unit));
        }

        if ($this->has('is_active')) {
            $mergeData['is_active'] = filter_var($this->is_active, FILTER_VALIDATE_BOOLEAN);
        }

        if (! empty($mergeData)) {
            $this->merge($mergeData);
        }
    }
}

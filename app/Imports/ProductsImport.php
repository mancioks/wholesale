<?php

namespace App\Imports;

use App\Models\ImportQueue;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\HeadingRowImport;

class ProductsImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new ImportQueue([
            'user_id' => auth()->id(),
            'name' => $row['name'],
            'price' => $row['price'],
            'units' => $row['units'],
        ]);
    }

    public function rules(): array
    {
        return [
            '*.name' => 'required',
            '*.price' => 'required|numeric',
            '*.units' => 'required',
        ];
    }
}

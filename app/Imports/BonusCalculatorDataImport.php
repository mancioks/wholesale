<?php

namespace App\Imports;

use App\Models\BonusCalculationsData;
use Carbon\Carbon;
use DateTime;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class BonusCalculatorDataImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['sunaudojimo_data']);

        return new BonusCalculationsData([
            'calculation_id' => 0,
            'material' => $row['medziaga'],
            'price' => $row['vieneto_kaina'],
            'quantity' => $row['kiekis'] ?: 0,
            'used_at' => $date,
        ]);
    }

    public function rules(): array
    {
        return [
            '*.medziaga' => 'required',
            '*.vieneto_kaina' => 'required',
            '*.sunaudojimo_data' => 'required',
        ];
    }
}

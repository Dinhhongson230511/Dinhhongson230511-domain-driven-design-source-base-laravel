<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TestingUserImport implements WithHeadingRow
{
    use Importable;

    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }
}
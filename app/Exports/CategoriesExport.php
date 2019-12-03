<?php

namespace App\Exports;

use App\Category;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CategoriesExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    use Exportable;

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Description',
            'Articles No',
            'Crated from user',
            'Date created',
        ];
    }


    public function __construct(string $created_at_start, string $created_at_end)
    {
        $this->created_at_start = $created_at_start;
        $this->created_at_end = $created_at_end;
    }

    public function query()
    {
        $query = Category::query()
            ->select(['id', 'name', 'description',  'user_id', 'created_at', 'deleted_at',])
            ->with('user:id,name')
            ->withCount('articles');

        if (isset($this->created_at_start)) {

            $query = $query->where('created_at', '>=', $this->created_at_start);
        }

        if (isset($this->created_at_end)) {
            $query = $query->where('created_at', '<=', $this->created_at_end);
        }
        return $query;
    }
    public function map($category): array
    {
        return [
            $category->id,
            $category->name,
            $category->description,
            $category->articles_count,
            $category->user->name,
            $category->created_at,
        ];
    }
}
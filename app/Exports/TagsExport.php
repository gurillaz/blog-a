<?php

namespace App\Exports;

use App\Tag;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TagsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
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
        $query = Tag::query()
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
    public function map($tag): array
    {
        return [
            $tag->id,
            $tag->name,
            $tag->description,
            $tag->articles_count,
            $tag->user->name,
            $tag->created_at,
        ];
    }
}

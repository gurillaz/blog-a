<?php

namespace App\Exports;

use App\Article;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ArticlesExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    use Exportable;

    public function headings(): array
    {
        return [
            'ID',
            'Title',
            'Author',
            'Slug',
            'Summary',
            'Comments no',
            'Category Name',
            'Publishing date',
            'Featured',
            'Featured list place no',
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
        $query = Article::query()
            ->select(['id', 'title', 'slug', 'summary', 'body',  'user_id', 'category_id', 'publishing_date', 'meta_is-feature', 
            'meta_list_place', 'created_at'])
            ->with('user:id,name')
            ->with('category:id,name')
            ->withCount('comments');

        if (isset($this->created_at_start)) {

            $query = $query->where('created_at', '>=', $this->created_at_start);
        }

        if (isset($this->created_at_end)) {
            $query = $query->where('created_at', '<=', $this->created_at_end);
        }
        return $query;
    }
    public function map($article): array
    {

        return [
            $article->id,
            $article->title,
            $article->user->name,
            $article->slug,
            $article->summary,
            $article->comments_no,
            $article->category->name,
            $article->publishing_date,
            $article['meta_is-feature'],
            $article->meta_list_place,
            $article->created_at,
        ];
    }
}

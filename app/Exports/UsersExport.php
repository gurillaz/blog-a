<?php

namespace App\Exports;

use App\Article;
use App\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class UsersExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    use Exportable;

    public function headings(): array
    {
        return [
            'ID',
            'Email',
            'Email verified at',
            'Role',
            'Articles no',
            'Comments no',
            'Bookmarks no',
            'Created at',
        ];
    }


    public function __construct(string $created_at_start, string $created_at_end)
    {
        $this->created_at_start = $created_at_start;
        $this->created_at_end = $created_at_end;
    }

    public function query()
    {
        $query = User::query()
            ->select(['id', 'email', 'email_verified_at','role','created_at'])
            ->withCount('articles')
            ->withCount('comments')
            ->withCount('bookmarks');

        if (isset($this->created_at_start)) {

            $query = $query->where('created_at', '>=', $this->created_at_start);
        }

        if (isset($this->created_at_end)) {
            $query = $query->where('created_at', '<=', $this->created_at_end);
        }
        return $query;
    }
    public function map($user): array
    {

        return [
            $user->id,
            $user->email,
            $user->email_verified_at,
            $user->role,
            $user->articles_count,
            $user->comments_count,
            $user->bookmarks_count,
            $user->created_at,

        ];
    }
}

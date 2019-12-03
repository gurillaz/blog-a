<?php

namespace App\Exports;

use App\Article;
use App\Comment;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CommentsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    use Exportable;

    public function headings(): array
    {
        return [
            'ID',
            'Body',
            'Crated from user',
            'In article',
            'Comment type',
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
        $query = Comment::query()
            ->select(['id', 'body',  'user_id', 'commentable_id', 'commentable_type', 'created_at'])
            ->with('user:id,name');

        if (isset($this->created_at_start)) {

            $query = $query->where('created_at', '>=', $this->created_at_start);
        }

        if (isset($this->created_at_end)) {
            $query = $query->where('created_at', '<=', $this->created_at_end);
        }
        return $query;
    }
    public function map($comment): array
    {

        if ($comment->commentable_type == Comment::class) {
            // $comment['article'] = $comment->commentable()->first('commentable_id')['commentable_id'];
            $comment['article'] = Article::where('id', $comment->commentable()->first('commentable_id')['commentable_id'])->first(['id', 'title']);
        } else {
            $comment['article'] = $comment->commentable()->first(['id', 'title']);
        }




        if ($comment->commentable_type == Comment::class) {
            // $comment['article'] = $comment->commentable()->first('commentable_id')['commentable_id'];
            $comment['comment_type'] = "Reply";
        } else {
            $comment['comment_type'] = "Comment";
        }
        return [
            $comment->id,
            $comment->body,
            $comment->user->name,
            $comment->article->title,
            $comment->comment_type,
            $comment->created_at,
        ];
    }
}

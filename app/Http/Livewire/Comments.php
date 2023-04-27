<?php

namespace App\Http\Livewire;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class Comments extends Component
{

    // public $comments;

    public Post $post;

    protected $listeners = [
        'commentCreated' => '$refresh',
        'commentDeleted' => '$refresh'
    ];

    public function mount(Post $post)
    {
        $this->post = $post;

        
    }

    public function render()
    {
        $comments = $this->selectComments();
        return view('livewire.comments', compact('comments'));
    }

    // public function commentCreated(int $id)
    // {
    //     $comment = Comment::where('id', '=', $id)->first();
    //     if (!$comment->id_parent) {
    //         $this->comments = $this->comments->prepend($comment);
    //     }
    // }

    // public function commentDeleted(int $id)
    // {
    //     // $comment = Comment::where('id', '=', $id)->first();
    //     // if (!$comment->id_parent) {
    //     //     $this->comments = $this->comments->reject(function ($comment) use ($id) {
    //     //         return $comment->id == $id;
    //     //     });
    //     // }

    //     $this->comments = $this->selectComments();
    // }

    public function selectComments()
    {
        return Comment::where('id_post', '=', $this->post->id)
            ->with(['post', 'user', 'comments'])
            ->whereNull('id_parent')
            ->orderByDesc('created_at')
            ->get();
    }
}

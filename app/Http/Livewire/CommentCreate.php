<?php

namespace App\Http\Livewire;

use App\Models\Comment;
use App\Models\Post;
use Livewire\Component;

class CommentCreate extends Component
{
    public string $comment = '';

    public Post $post;

    public function mount(Post $post){
        $this->post = $post;
    }

    public function render()
    {
        return view('livewire.comment-create');
    }

    public function createComment(){
        $user = auth()->user();

        if(!$user){
            return redirect(route('login'));
        }


        $comment = Comment::create([
            'comment' => $this->comment,
            'id_post' => $this->post->id,
            'id_user' => $user->id
        ]);

        $this->emitUp('commentCreated', $comment->id);

    }

}

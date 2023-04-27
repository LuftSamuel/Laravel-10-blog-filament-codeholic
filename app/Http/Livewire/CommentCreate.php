<?php

namespace App\Http\Livewire;

use App\Models\Comment;
use App\Models\Post;
use Livewire\Component;

class CommentCreate extends Component
{
    public string $comment = '';

    public Post $post;

    public ?Comment $commentModel = null;

    public ?Comment $parentComment = null;

    public function mount(Post $post, $commentModel = null, $parentComment = null)
    {
        $this->post = $post;
        $this->commentModel = $commentModel;
        $this->comment = $commentModel ? $commentModel->comment : '';
        $this->parentComment = $parentComment;
    }

    public function render()
    {
        return view('livewire.comment-create');
    }

    public function createComment()
    {
        $user = auth()->user();
        if (!$user) {
             return redirect(route('login'));
         }

        //editando
        if ($this->commentModel) {
            if($this->commentModel->id_user != $user->id){
                return response('', 403);
            }

            $this->commentModel->comment = $this->comment;
            $this->commentModel->save();

            $this->comment = '';
            $this->emitUp('commentUpdated'); 
        //creando
        } else {

            

            $comment = Comment::create([
                'comment' => $this->comment,
                'id_post' => $this->post->id,
                'id_user' => $user->id,
                'id_parent' => $this->parentComment?->id
            ]);

            $this->emitUp('commentCreated', $comment->id);
            $this->comment = '';
        }
    }
}

<?php

namespace App\Http\Livewire;

use App\Models\Post;
use Livewire\Component;

class UpvoteDownvote extends Component
{
    public Post $post;

    public function montar(Post $post){
        $this->post = $post;
    }

    public function render()
    {
        $upvotes = \App\Models\UpvoteDownvote::where('id_post', '=', $this->post->id)->where('is_upvoted', '=', true)->count();
        $downvotes = \App\Models\UpvoteDownvote::where('id_post', '=', $this->post->id)->where('is_upvoted', '=', false)->count();

        $hasUpvote = null;

        $user = request()->user();

        if($user){
            $upvoteDownvote = UpvoteDownvote::where('id_post', '=', $this->post->id)
            ->where('user_id', '=', $user->id)
            ->first();

            if($upvoteDownvote){
                // !!convierte 1 o 0 en true o false
                $hasUpvote = !!$upvoteDownvote->is_upvoted;
            }
        }

        return view('livewire.upvote-downvote', compact('upvotes', 'downvotes', 'hasUpvote'));        
    }

    public function upvoteDownvote($upvote = true){
        /** @var \App\Models\User $user  */
        $user = request()->user();

        if(!$user){
            return $this->redirect('login');
        }

        if(!$user->hasVerifiedEmail()){
            return $this->redirect(route('verification.notice'));
        }

        $upvoteDownvote = \App\Models\UpvoteDownvote::where('id_post', '=', $this->post->id)
        ->where('id_user', '=', $user->id)
        ->first();

        if(!$upvoteDownvote){

            \App\Models\UpvoteDownvote::create([
                'is_upvoted' => $upvote,
                'id_post' => $this->post->id,
                'id_user' => $user->id
            ]);
            return;
        }

        if($upvote && $upvoteDownvote->is_upvoted || !$upvote && !$upvoteDownvote->is_upvoted){
            $upvoteDownvote->delete();
        }else{
            $upvoteDownvote->is_upvoted = $upvote;
            $upvoteDownvote->save();
        }
    }
}

<div>
    <div class="flex mb-4 gap-3">
        <div class="w-12 h-12 flex items-center justify-center bg-gray-100 rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
        </div>
        <div>
            <div>
                <a href="" class="font-semibold ring-indigo-600"> {{$comment->user->name}} </a>
                - {{ $comment->updated_at->diffForHumans() }}
            </div>
            <div class="text-gray-700">
                {{ $comment->comment }}
            </div>
            <div>
                <a href="" class="text-sm ring-indigo-600 mr-3">Reply</a>
                @if(\Illuminate\Support\Facades\Auth::id() == $comment->id_user)
                    <a href="" class="text-sm ring-blue-600 mr-3">Edit</a>
                    <a wire:click.prevent="deleteComment" href="" class="text-sm text-red-600">Delete</a>
                @endif
            </div>
        </div>
    </div>
</div>

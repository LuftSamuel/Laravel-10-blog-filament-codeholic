<?php

namespace App\Filament\Resources\PostResource\Widgets;

use App\Models\PostView;
use App\Models\UpvoteDownvote;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;

class PostOverview extends Widget
{
    protected static string $view = 'filament.resources.post-resource.widgets.post-overview';

    public ?Model $record = null;

    protected function getViewData(): array
    {
        return [
            'viewCount' => PostView::where('id_post', '=', $this->record->id)->count(),
            'upvotes' => UpvoteDownvote::where('id_post', '=', $this->record->id)->where('is_upvoted', '=', true)->count(),
            'downvotes' => UpvoteDownvote::where('id_post', '=', $this->record->id)->where('is_upvoted', '=', false)->count()
        ];
    }

    protected int | string | array $columnSpan = 1;

}

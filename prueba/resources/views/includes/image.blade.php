<div class="card pub_image">
    <div class="card-header">

        @if ($image->user->image)
            <div class="container-avatar">
                <img src="{{ route('user.avatar', ['filename' => $image->user->image]) }}" class="avatar" />
            </div>
        @endif
        <div class="data-user">
            <a href="{{ route('profile', ['id' => $image->user->id]) }}">
                {{ $image->user->name . ' ' . $image->user->surname }}
                <span class="nickname">
                    {{ ' @' . $image->user->nickname }}
                </span>
            </a>
        </div>

        <div class="card-body">
            <div class="image-container">
                <img src="{{ route('images.file', ['filename' => $image->image_path]) }}" />
            </div>
            <div class="description">
                <span class="nickname">{{ '@' . $image->user->nick }}</span>
                <span class="nickname date">{{ '|' . $image->created_at }}</span>
                {{ $image->description }}
            </div>

            <div class="likes">
                {{-- se compueba si el usuario dio like --}}
                <?php $user_like = false; ?>
                @foreach ($image->likes as $like)
                    @if ($like->user->id == Auth::user()->id)
                        <?php $user_like = true; ?>
                    @endif
                @endforeach

                @if ($user_like)
                    <img src="{{ asset('img/favorite-4-64.png') }}" data-id="{{ $image->id }}" class="btn-dislike" />
                @else
                    <img src="{{ asset('img/hearts-64.png') }}" data-id="{{ $image->id }}" class="btn-like" />
                @endif
                <span class="number_likes">{{ count($image->likes) }}</span>
            </div>

            <div class="comments">
                <a href="{{ route('images.detail', ['id' => $image->id]) }}" class ="btn btn-sm btn-warning btn-comments">
                    Comentarios ({{ count($image->comments) }})
                </a>
            </div>

        </div>

        {{-- <div class="clearfix">
            {{ $images->links() }}
        </div> --}}

    </div>

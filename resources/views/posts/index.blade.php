<a href="{{ route('posts.create') }}">create post</a>



@forelse ($posts as $post)
    {{ $post->title }}
    <br>
    {{ $post->body }}
    <div>
        comments ({{ $post->comments->count() }})
        @forelse ($post->comments as $comment)
            {{ $comment->body }}
        @empty
            No Comments Found
        @endforelse
    </div>
@empty
    No Posts Found
@endforelse

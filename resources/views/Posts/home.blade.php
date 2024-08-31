@include('errors')
@include('success')

@include('Posts.head')

<a class="btn btn-info btn-lg my-4 mx-3" href="{{ url('create') }}">Create Post</a>

<div class="d-flex flex-wrap">

@foreach ($posts as $post)
    <div class="card mb-3 mx-2" style="flex: 1 1 calc(33% - 20px);">

        <div class="card-body">

            <h3 class="card-title">{{ $post->title }}</h3>
            <p class="card-text">{{ $post->content }}</p>

            <div style="border-top: 1px solid #ccc; margin: 20px 0;"></div>

            <h4>Comments</h4>
            @foreach ($post->comments as $comment)
                <div style="border: 1px solid #ddd; padding: 10px; margin-bottom: 10px; position: relative;">
                    <strong>{{ $comment->user->name }}</strong>

                    <div class="w-25" style="display: flex; align-items: center; justify-content: space-between;">

                        <p style="margin-bottom: 0; flex: 1;">{{ $comment->content }}</p>
                        @if(auth()->id() === $comment->user_id)
                            <form action="{{ route('comment.delete', $comment->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')

                                <button type="submit" style="border: none; background: none; cursor: pointer;" onclick="return confirm('Are you sure you want to delete this comment?');">
                                    <i class="fas fa-trash-alt text-danger" style="font-size: 19px;"></i>
                                </button>
                            </form>
                        @endif
                    </div>

                </div>
            @endforeach

            @auth
                <div class="mb-3">
                <form action="{{ route('comment.store', $post->id) }}" method="POST" style="display: flex; align-items: center;">
                    @csrf
                    <input class="form-control me-2"  style="flex: 1" name="content" placeholder="Add Comment">
                    <button class="btn btn-outline-primary" style="--bs-btn-padding-y: .39rem; --bs-btn-padding-x: .35rem;" type="submit">Submit Comment</button>
                    </div>
                </form>
                </div>
            @endauth

            <div class="d-flex mb-3 mx-3">
                <a class="btn btn-info me-2" href="{{ url("show/$post->id") }}">View</a>
                <a class="btn btn-success me-2" href="{{ url("edit/$post->id") }}">Edit</a>
                <form action="{{ route('post.delete', $post->id) }}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>

        </div>
    </div>
@endforeach

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
</script>

</body>

</html>

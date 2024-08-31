@include('Posts.head')

<h1>New Comment on Your Post</h1>
<p>{{ $comment->user->name }} commented on your post:</p>
<p>"{{ $comment->content }}"</p>
<a href="{{ route('post.show', $comment->post->id) }}">View Post</a>

</body>
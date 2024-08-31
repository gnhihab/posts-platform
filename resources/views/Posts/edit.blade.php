@include('errors')

@include('Posts.head')

<form class='form-control' method="POST" action="{{ route('post.update',$post->id) }}" enctype="multipart/form-data" >
    @csrf
    @method('PUT')


  <div class="card" style="width: 28rem; margin-left:31%; margin-top:25px">

    <div class="card-header">
      Edit Your Post
    </div>

    <ul class="list-group list-group-flush">

      <li class="list-group-item">Title
        <input type="text" name="title" class="form-control text-dark" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{$post->title}}">
      </li>
      <li class="list-group-item">Content
        <textarea name="content" class="form-control text-dark" style="width: 100%;height: 200px;" id="exampleInputEmail1" aria-describedby="emailHelp">{{$post->content}}</textarea>
      </li>

    </ul>

    <button type="submit" class="btn btn-success">Update</button>

</form>

</body>
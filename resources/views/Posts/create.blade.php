@include('errors')

@include('Posts.head')

<div style="margin:auto;width:75%;margin-top:30px;padding:40px">
<form method="POST" class=" form-control" action="{{ route('post.store') }}" enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
      <label for="exampleInputPassword1" class="form-label">Post Title</label>
      <input type="text" name="title" class="form-control text-black" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Title">

    </div>

    <div class="mb-3">
        <label for="exampleInputPassword1" class="form-label">Post Content</label>
        <input type="text" name="content" class="form-control text-black" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Content">

    </div>

    <button type="submit" class="btn btn-success">Create</button>
  </form>
</div>
</body>
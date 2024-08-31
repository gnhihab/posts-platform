
@include('Posts.head')

<div style="margin:auto;width:75%;margin-top:30px;padding:40px">
<form class=" form-control" enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
      <label for="exampleInputPassword1" class="form-label" style="font-size: 25px">Post Title</label>
      <input class="form-control" style="font-size: 20px" type="text" value="{{$post->title}}" aria-label="Disabled input example" disabled readonly>
    </div>

    <div class="mb-3">
        <label for="exampleInputPassword1" class="form-label" style="font-size: 25px">Post Content</label>
        <input class="form-control" style="font-size: 20px" type="text" value="{{$post->content}}" aria-label="Disabled input example" disabled readonly>
    </div>

  </form>
</div>

</body>



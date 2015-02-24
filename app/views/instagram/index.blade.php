<ul class="grid entries">
@foreach ($photos as $photo)
    <li id="{{$photo->id}}"><a href="{{$photo->medialink}}"><img class="media" src="{{$photo->url}}" width="190px"></a><h3><a href="http://instagram.com/{{$photo->username}}">{{$photo->username}}</a></h3></li>
@endforeach
</ul>
<?php echo $photos->links(); ?>
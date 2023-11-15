@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @include('includes.message')
            @foreach($images as $image)
            <div class="card pub_image">
                <div class="card-header">

                    @if($image->user->image)
                    <div class="container-avatar">
                        <img src="{{ route('user.avatar',['filename'=>$image->user->image]) }}" class="avatar"/>
                    </div>
                    @endif
                    <div class="data-user">
                        {{$image->user->name.' '.$image->user->surname}}
                        <span class="nickname">
                            {{' @'.$image->user->nickname}}
                        </span>
                    </div>
                    
                <div class="card-body">
                    <div class="image-container">
                    <img src="{{ route('images.file',['filename' => $image->image_path])}}" />
                    </div>
                    <div class="likes">

                    </div>
                    <div class="description">
                        <span class="nickname">{{'@'.$image->user->nick}}</span>
                        {{$image->description}}
                    </div>

                    <a href="" class ="btn btn-warning btn-comments">
                        Comentarios
                    </a>

                </div>

                <div class="clearfix"></div>
                {{ $images->links() }}

            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

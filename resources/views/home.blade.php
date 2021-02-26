@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if(!auth()->user()->token)
                            <a href="oauth/redirect">server authorize now</a>
                    @endif

                    @foreach($posts as $post)
                        <div class="card">
                            <div class="card-header">
                                {{$post['title']}}
                            </div>
                            <div class="card-body">
                                {{$post['body']}}
                            </div>
                        </div>
                            <hr>
                    @endforeach

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

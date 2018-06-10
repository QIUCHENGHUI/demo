@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                        {{ $question->title }}
                        @foreach($question->topics as $topic)
                            <a class="topic float-right" href="/topic/{{ $topic->id }}">{{ $topic->name }}</a>
                        @endforeach
                    </div>
                    <div class="card-body">
                        {!! $question->body !!}
                    </div>
                    <div class="actions">
                        @if(Auth::check() && Auth::user()->owns($question))
                            <span class="edit"><a href="/questions/{{$question->id}}/edit">编辑</a></span>
                            <form action="/questions/{{$question->id}}" method="post" class="delete-form">
                                {{ method_field('DELETE') }}
                                {{ csrf_field() }}
                                <button class="button is-naked delete-button">删除</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header question-follow">
                        <h2>{{ $question->followers_count }}</h2>
                        <span>关注者</span>
                    </div>
                    <div class="card-body">
                        <question-follow-button question="{{$question->id}}" user="{{Auth::id()}}"></question-follow-button>
                        <a href="#editor" class="btn btn-primary btn-block">撰写答案</a>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                        {{ $question->answers_count }} 个答案
                    </div>
                    <div class="card-body">
                        @foreach($question->answers as $answer)
                            <div class="media">
                                <div class="media-left">
                                    <a href="">
                                        <img width="60" style="border-radius: 50%;border: 1px solid #ddd;padding: 3px;margin-right: 5px;" src="{{$answer->user->avatar}}" alt="{{ $answer->user->name }}">
                                    </a>
                                </div>
                                <div class="media-body">
                                    <h4 class="media-heading">
                                        <a href="/user/{{ $answer->user->name }}">
                                            {{ $answer->user->name }}
                                        </a>
                                    </h4>
                                    {!! $answer->body !!}
                                </div>
                            </div>
                        @endforeach
                        @if(Auth::check())
                            <form action="/questions/{{$question->id}}/answer" method="post">
                                {!! csrf_field() !!}
                                <div class="form-group">
                                    <div class="form-control{{ $errors->has('body') ? ' is-invalid' : '' }}">
                                        <script type="text/plain" {!! 'style="height:120px;"' !!} id="editor" name="body">
                                            {!! old('body') !!}
                                        </script>
                                    </div>
                                    @if ($errors->has('body'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('body') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <button class="btn btn-success float-right" type="submit">发布文章</button>
                            </form>
                        @else
                            <a href="{{ url('login') }}" class="btn btn-success btn-block">登录提交答案</a>
                        @endif
                    </div>

                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header question-follow">
                        <h5>关于作者</h5>
                    </div>
                    <div class="card-body">
                        <div class="justify-content-center row">
                            <a href="#">
                                <img width="92" style="border-radius: 50%;border: 1px solid #ddd;padding: 4px;" src="{{$question->user->avatar}}" alt="{{$question->user->name}}">
                            </a>
                        </div>
                        <div class="justify-content-center row">
                            <h4 class="media-heading">
                                <a href="">{{$question->user->name}}</a>
                            </h4>
                        </div>
                        <div class="user-statics">
                            <div class="statics-item text-center">
                                <div class="statics-text">问题</div>
                                <div class="static-count">{{ $question->user->questions_count }}</div>
                            </div>
                            <div class="statics-item text-center">
                                <div class="statics-text">回答</div>
                                <div class="static-count">{{ $question->user->answers_count }}</div>
                            </div>
                            <div class="statics-item text-center">
                                <div class="statics-text">关注者</div>
                                <div class="static-count">{{ $question->user->followers_count }}</div>
                            </div>
                        </div>
                        <user-follow-button user="{{$question->user_id}}" user="{{Auth::id()}}"></user-follow-button>
                        <a href="#editor" class="btn btn-primary btn-block">发送私信</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@section('js')
    @include('vendor.ueditor.assets')
    <!-- 实例化编辑器 -->
    <script type="text/javascript">
        var ue = UE.getEditor('editor', {
            toolbars: [
                ['bold', 'italic', 'underline', 'strikethrough', 'blockquote', 'insertunorderedlist', 'insertorderedlist', 'justifyleft','justifycenter', 'justifyright',  'link', 'insertimage', 'fullscreen']
            ],
            elementPathEnabled: false,
            enableContextMenu: false,
            autoClearEmptyNode:true,
            wordCount:false,
            imagePopup:false,
            autotypeset:{ indent: true,imageBlockLine: 'center' }
        });
        ue.ready(function() {
            ue.execCommand('serverparam', '_token', '{{ csrf_token() }}'); // 设置 CSRF token.
        });
    </script>
@endsection
@endsection

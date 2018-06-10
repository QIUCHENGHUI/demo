@extends('layouts.app')

@section('content')
    @include('vendor.ueditor.assets')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">发布问题</div>
                    <div class="card-body">
                        <form action="/questions" method="post">
                            {!! csrf_field() !!}
                            <div class="form-group">
                                <label for="title">标题</label>
                                <input type="text" name="title" value="{{ old('title') }}" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" placeholder="标题" id="title">
                                @if ($errors->has('title'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <select class="js-example-placeholder-multiple js-data-example-ajax form-control" style="width:100%;" name="topics[]" multiple="multiple">
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="title">内容</label>
                                <div class="form-control{{ $errors->has('body') ? ' is-invalid' : '' }}">
                                    <script type="text/plain" {!! 'style="height:200px;"' !!} id="container" name="body">
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
                    </div>
                </div>
            </div>
        </div>
    </div>
    @section('js')
    <!-- 实例化编辑器 -->
    <script type="text/javascript">
        var ue = UE.getEditor('container', {
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
        // $('.js-example-basic-multiple').select2();
        function formatTopic (topic) {
            return "<div class='select2-result-repository clearfix'>" +
            "<div class='select2-result-repository__meta'>" +
            "<div class='select2-result-repository__title'>" +
            topic.name ? topic.name : "Laravel" + "</div></div></div>";
        }

        function formatTopicSelection (topic) {
            return topic.name || topic.text;
        }

        $(".js-example-placeholder-multiple").select2({

            tags: true,
            placeholder: '选择相关话题',
            minimumInputLength: 2,
            ajax: {
                url: '/api/topics',
                dataType: 'json',
                delay: 500,
                data: function (params) {
                    return {
                        q: params.term
                    };
                },
                processResults: function (data, params) {
                    return {
                        results: data
                    };
                },
                cache: true
            },
            templateResult: formatTopic,
            templateSelection: formatTopicSelection,
            escapeMarkup: function (markup) { return markup; }
        });
    </script>
    @endsection
@endsection

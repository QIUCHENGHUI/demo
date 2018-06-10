@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <span class="topic">Lodash测试</span>
                    </div>
                    <div class="card-body">
                        欢迎使用Lodash！
                        <br>
                        <a href="https://colintoh.com/blog/lodash-10-javascript-utility-functions-stop-rewriting">
                            常用示例查看
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        _.times(5, function(){
            console.log(_.random(15, 20));
        });
    </script>
@endsection


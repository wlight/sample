@extends('layouts.default')
@section('title', '更新密码')

@section('content')
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
          <div class="panel-heading">更新密码</div>
          <div class="panel-body">
              @include('shared.errors')

              <form action="{{ route('password.update') }}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group">
                  <label class="col-md-4 control-label">邮箱地址：</label>
                  <div class="col-md-6">
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control">
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-md-4 contorl-label">密码：</label>
                  <div class="col-md-6">
                    <input type="password" name="password" class="form-control">
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-md-4 control-label">确认密码：</label>
                  <div class="col-md-6">
                    <input type="password" name="password_confirmation" class="form-control">
                  </div>
                </div>

                <div class="form-group">
                  <div class="col-md-6 col-md-offset-4">
                    <button type="submit" class="btn btn-primary">
                      更新密码
                    </button>
                  </div>
                </div>
              </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@stop

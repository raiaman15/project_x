@extends('layouts.app')

@section('head')
<style>
    html, body {
        height: 100%;
    }

    body {
        margin: 0;
        padding: 0;
        width: 100%;
        display: table;
        font-weight: 100;
        color: white;
        background-color: #0099CC;
    }

    .container {
        text-align: center;
        display: table-cell;
        vertical-align: middle;
    }

    .content {
        text-align: center;
        display: inline-block;
    }

    .title {
        font-size: 96px;
    }
</style>
@stop

@section('content')
<div class="container">
    <div class="content">
        <div class="title">PROJECT_X</div>
    </div>
</div>
@stop
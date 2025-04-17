@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Статистика сокращенного URL') }}</div>

                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-right">{{ __('Код') }}</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" value="{{ $code }}" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-right">{{ __('Сокращенный URL') }}</label>
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" class="form-control" value="{{ $short_url }}" id="shortUrl" readonly>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard()">{{ __('Копировать') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-right">{{ __('Оригинальный URL') }}</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" value="{{ $long_url }}" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-right">{{ __('Количество переходов') }}</label>
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" class="form-control" value="{{ $clicks }}" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <a href="{{ route('urls.index') }}" class="btn btn-primary">
                                {{ __('Сократить еще') }}
                            </a>
                            <a href="{{ $short_url }}" class="btn btn-secondary" target="_blank">
                                {{ __('Перейти по ссылке') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard() {
    var copyText = document.getElementById("shortUrl");
    copyText.select();
    document.execCommand("copy");
    alert("Скопировано: " + copyText.value);
}
</script>
@endsection 
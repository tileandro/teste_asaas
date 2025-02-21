@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @if (session()->has('success'))
                    <div class="col-md-12 alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}</div>
                @endif

                <div class="card">
                    <div class="card-header">{{ $page }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('produtos.store') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group row mb-2">
                                <label for="nome"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Nome do Produto') }}</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control @error('nome') is-invalid @enderror"
                                        name="nome" value="{{ old('nome') }}" autocomplete="nome" autofocus>

                                    @error('nome')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-2">
                                <label for="preco"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Preço do produto') }}</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control @error('preco') is-invalid @enderror"
                                        name="preco" value="{{ old('preco') }}" autocomplete="preco" autofocus>

                                    @error('preco')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-2">
                                <label for="imagem"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Imagem do produto') }}</label>
                                <div class="col-md-6">
                                    <input type="file"
                                        class="form-control-file {{ $errors->has('imagem') ? 'is-invalid' : '' }}"
                                        name="imagem" id="imagem" accept="image/jpg, image/jpeg, image/png, image/webp">

                                    @error('imagem')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror

                                    <output id="filesInfo"></output>

                                </div>
                            </div>

                            <div class="form-group row mb-0 row mb-2">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Cadastrar Produto') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function($) {
            $('input[name=preco]').mask('000.000,00', {
                reverse: true
            })
        })

        $("#imagem").on('change', function() {
            if (typeof(FileReader) != "undefined") {
                var image_holder = $("#filesInfo");
                image_holder.empty();
                var reader = new FileReader();

                reader.onload = function(e) {
                    $("<img />", {
                        "src": e.target.result,
                        "class": "w-100",
                        "onload": "$(this).fadeIn('slow');"
                    }).hide().appendTo(image_holder).fadeIn("slow");
                    // $("#pre-image").addClass('d-none');
                }

                image_holder.show();
                // console.log($(this)[0].files[0]);
                if (($(this)[0].files[0]) == undefined) {
                    $("#img").removeClass('d-none').fadeIn('slow');
                } else {
                    reader.readAsDataURL($(this)[0].files[0]);
                    $("#img").addClass('d-none').fadeOut('slow');
                }
            } else {
                alert("Este navegador não suporta FileReader.");
            }
        });
    </script>
@endsection

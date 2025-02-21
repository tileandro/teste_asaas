@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col">
                @if (session()->has('success'))
                    <div class="col-md-12 alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}</div>
                @endif
                <div class="card">
                    <div class="card-header">
                        <div class="pull-left">{{ $page }}</div>
                        <a href="{{ route('produtos.create') }}" class="btn btn-primary btn-sm pull-right"
                            data-toggle="tooltip" title="Cadastrar Produtos"><i class="fa fa-plus-square-o"> Cadastrar
                                Produtos</i></a>
                    </div>

                    <div class="card-body row">
                        @if (count($produtos) > 0)
                            @foreach ($produtos as $produto)
                                <div class="card col-md m-1 px-0 text-center">
                                    <img class="card-img-top" src="{{ asset($produto->imagem) }}" alt="{{ $produto->nome }}"
                                        data-toggle="tooltip" title="{{ $produto->nome }}">
                                    <div class="card-body">
                                        <h6 class="card-title">{{ $produto->nome }}</h6>
                                        <p class="card-text"><b>R$ {{ number_format($produto->preco, 2, ',', '.') }}</b></p>
                                        <a href="{{ route('finalizar-compra.show', $produto->id) }}"
                                            class="btn btn-primary"><i class="fa fa-shopping-cart"></i> Comprar</a>
                                    </div>
                                </div>
                            @endforeach

                            @if ($produtos->lastPage() > 1)
                                <nav aria-label="Navegação de página">
                                    <ul class="pagination pagination-sm justify-content-center">
                                        <li class="page-item" data-toggle="tooltip" title="Primeira página">
                                            <a class="page-link {{ !isset($request['page']) || $request['page'] == 1 ? 'disabled' : '' }}"
                                                href="{{ $produtos->url(1) }}">Primeira</a>
                                        </li>

                                        @for ($i = 2; $i < $produtos->lastPage(); $i++)
                                            <li class="page-item" data-toggle="tooltip"
                                                title="{{ $i }}ª página"><a
                                                    class="page-link {{ $request['page'] == $i ? 'disabled' : '' }}"
                                                    href="{{ $produtos->url($i) }}">{{ $i }}</a></li>
                                        @endfor

                                        <li class="page-item" data-toggle="tooltip" title="Última página">
                                            <a class="page-link {{ $request['page'] == $produtos->lastPage() ? 'disabled' : '' }}"
                                                href="{{ $produtos->url($produtos->lastPage()) }}">Última</a>
                                        </li>
                                    </ul>
                                </nav>
                            @endif
                        @else
                            <div class="col-md m-1 px-0 text-center">Nenhum produto cadastrado!</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script></script>
@endsection

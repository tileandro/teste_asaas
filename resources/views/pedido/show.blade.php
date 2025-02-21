@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                @if (session()->has('success'))
                    <div class="col-md-12 alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}</div>
                @endif

                @if (session()->has('error'))
                    <div class="col-md-12 alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}</div>
                @endif
                <div class="card">
                    <div class="card-header">
                        <div class="pull-left">{{ $page }} realizado com sucesso</div>
                        <a href="{{ route('produtos.index') }}" class="btn btn-primary btn-sm pull-right"
                            data-toggle="tooltip" title="Home"><i class="fa fa-home"> Página inicial</i></a>
                    </div>
                </div>
            </div>
            <div class="col-md-12 row my-2">
                <div class="col-md-7 p-0">
                    <div class="card">
                        <div class="card-body">
                            <div class="col-md-12 px-0">
                                <h4 class="col-md-12 m-2 px-0"><b>Dados do pedido {{ $pedido['id'] }}</b></h4>
                                <h5 class="col-md-12 m-2 px-0"><b>Status do pedido:</b>
                                    {{ $pedido['status_pedido_asaas'] == 'CONFIRMED' ? 'PAGO' : 'AGUARDANDO PAGAMENTO' }}
                                </h5>
                                <h5 class="col-md-12 m-2 px-0"><b>Data do vencimento:</b>
                                    {{ date('d/m/Y', strtotime($pedido['data_vencimento'])) }}</h5>

                                <h5 class="col-md-12 m-2 px-0"><b>Valor:</b> R$
                                    {{ number_format($pedido['valor_total'], 2, ',', '.') }}
                                    {{ $pedido['metodo_pagamento'] == 'CREDIT_CARD' ? '(Parcelado em ' . $pedido['numero_parcela_cartao'] . 'x de R$ ' . number_format($pedido['valor_parcela'], 2, ',', '.') . ')' : '' }}
                                </h5>
                                <h5 class="col-md-12 m-2 px-0"><b>Forma de pagamento:</b>
                                    {{ $pedido['metodo_pagamento'] == 'CREDIT_CARD' ? 'CARTÃO DE CRÉDITO' : $pedido['metodo_pagamento'] }}
                                </h5>
                                @if ($pedido['metodo_pagamento'] == 'BOLETO')
                                    <a href="{{ $pedido['link_boleto'] }}" target="_blank" class="btn btn-primary">
                                        Boleto
                                        Bancário</a>
                                @endif
                                @if ($pedido['metodo_pagamento'] == 'PIX')
                                    <div class="col-md m-2 px-0 mt-4 row">
                                        <h5 class="col-md-12 m-2 px-0"><b>Pix Qrcode</b></h5>
                                        <div class="col-md-3 px-0">
                                            <img src="data:image/png;base64, {{ $pedido['pix_qr_code'] }}"
                                                class="w-100" />
                                        </div>
                                        <div class="col-md-8">
                                            <h6 class="col-md-12 m-2 px-0">Acesse seu APP de pagamentos e faça a leitura do
                                                QR Code
                                                ao
                                                lado para efetuar o
                                                pagamento de forma rápida e segura.</h6>
                                        </div>
                                    </div>
                                    <div class="col-md m-2 mt-2 px-0 mt-4 row">
                                        <h5 class="col-md-12 m-2 px-0"><b>Código Pix copia e cola</b></h5>
                                        <div class="col-md-8 px-0">
                                            <input id="copia_cola" type="hidden"
                                                value="{{ $pedido['pix_copia_cola'] }}" />
                                            <h6 class="col-md-12 m-2 px-0">
                                                {{ $pedido['pix_copia_cola'] }}
                                            </h6>
                                        </div>
                                        <div class="col-md-3 text-right px-0">
                                            <button class="btn btn-md btn-primary btn_copy"><i class="fa fa-files-o"></i>
                                                Copiar</button>
                                        </div>
                                    </div>
                                    <div class="col-md-12 alert alert-success alert-dismissible fade hide alert_copy"
                                        role="alert">
                                        <i class="fa fa-check-square"></i> Código pix copia e cola copiado com sucesso
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-5 pr-0">
                    <div class="card">
                        <div class="card-body row">
                            <div class="col-md-12 m-2 px-0">
                                <h4 class="col-md-12 m-2 px-0">{{ $user['name'] }}</h4>
                                <h5 class="col-md-12 m-2 px-0"><span class="cpf">{{ $user['cpf'] }}</span><span
                                        class="cnpj">{{ $user['cnpj'] }}</span></h5>
                                <h6 class="col-md-12 m-2 px-0">{{ $user['email'] }}</h6>
                                <h6 class="col-md-12 m-2 px-0 phone">{{ $user['phone'] }}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="card mt-2">
                        <div class="card-body row">
                            <div class="col-md-4 m-2 px-0">
                                <img class="card-img-top" src="{{ asset($produto['imagem']) }}"
                                    alt="{{ $produto['nome'] }}" data-toggle="tooltip" title="{{ $produto['nome'] }}">
                            </div>
                            <div class="col-md m-2 px-0">
                                <h6 class="card-title">{{ $produto['nome'] }}</h6>
                                <p class="card-text"><b>R$ {{ number_format($produto['preco'], 2, ',', '.') }}</b></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function($) {
            $('.phone').mask('(00) 0000-0000', {})
            $('.cnpj').mask('00.000.000/0000-00', {})
            $('.cpf').mask('000.000.000-00', {})

            $('.btn_copy').click(function() {
                var textToCopy = $('#copia_cola').val();
                var tempTextarea = $('<input>');
                $('body').append(tempTextarea);
                tempTextarea.val(textToCopy).select();
                document.execCommand('copy');
                tempTextarea.remove();

                $('.alert_copy').addClass('show')
            })
        })
    </script>
@endsection

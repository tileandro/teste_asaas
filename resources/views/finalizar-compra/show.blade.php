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
                        <div class="pull-left">{{ $page }}</div>
                        <a href="{{ route('produtos.index') }}" class="btn btn-primary btn-sm pull-right"
                            data-toggle="tooltip" title="Voltar"><i class="fa fa-undo"> Voltar</i></a>
                    </div>
                </div>
            </div>
            <form method="POST" action="{{ route('finalizar-compra.store') }}" class="col-md-12 row my-2">
                @csrf
                <div class="card col-md-7">
                    <div class="card-body">
                        <div class="form-group row mb-3">
                            <div class="col-md">
                                <label class="col-md col-form-label px-0">
                                    {{ __('Nome do cliente') }}
                                    <text class="text-danger" data-toggle="tooltip" title="Preenchimento obrigatório"> *
                                    </text>
                                </label>
                                <input type="text" class="form-control @error('nome') is-invalid @enderror"
                                    name="nome" value="{{ old('nome') }}" autocomplete="nome" autofocus>

                                @error('nome')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <div class="col-md">
                                <label class="col-md col-form-label px-0">
                                    {{ __('E-mail do cliente') }}
                                    <text class="text-danger" data-toggle="tooltip" title="Preenchimento obrigatório"> *
                                    </text>
                                </label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email') }}" autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <div class="col-md">
                                <label class="col-md col-form-label px-0">
                                    {{ __('Telefone do cliente') }}
                                    <text class="text-danger" data-toggle="tooltip" title="Preenchimento obrigatório"> *
                                    </text>
                                </label>
                                <input type="text" class="form-control @error('telefone') is-invalid @enderror"
                                    name="telefone" value="{{ old('telefone') }}" autocomplete="telefone" autofocus>

                                @error('telefone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="radio_cpf" name="radio_cpf_cnpj" class="custom-control-input"
                                    value="cpf" {{ old('radio_cpf_cnpj') == 'cnpj' ? '' : 'checked' }}>
                                <label class="custom-control-label" for="radio_cpf">CPF</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="radio_cnpj" name="radio_cpf_cnpj" class="custom-control-input"
                                    value="cnpj" {{ old('radio_cpf_cnpj') == 'cnpj' ? 'checked' : '' }}>
                                <label class="custom-control-label" for="radio_cnpj">CNPJ</label>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <div class="col-md cpf">
                                <label class="col-md col-form-label px-0">
                                    {{ __('CPF do cliente') }}
                                    <text class="text-danger" data-toggle="tooltip" title="Preenchimento obrigatório"> *
                                    </text>
                                </label>
                                <input type="text" class="form-control @error('cpf') is-invalid @enderror" name="cpf"
                                    value="{{ old('cpf') }}" autofocus
                                    {{ old('radio_cpf_cnpj') == 'cnpj' ? 'disabled' : '' }}>

                                @error('cpf')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md cnpj">
                                <label class="col-md col-form-label px-0">
                                    {{ __('CNPJ do cliente') }}
                                    <text class="text-danger" data-toggle="tooltip" title="Preenchimento obrigatório"> *
                                    </text>
                                </label>
                                <input type="text" class="form-control @error('cnpj') is-invalid @enderror"
                                    name="cnpj" value="{{ old('cnpj') }}" autofocus
                                    {{ old('radio_cpf_cnpj') == 'cnpj' ? '' : 'disabled' }}>

                                @error('cnpj')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-5 pr-0">
                    <div class="card">
                        <input type="hidden" name="id_produto" value="{{ $produto['id'] }}" />
                        <input type="hidden" name="produto" value="{{ $produto['nome'] }}" />
                        <input type="hidden" name="preco" value="{{ $produto['preco'] }}" />
                        <input type="hidden" name="imagem" value="{{ $produto['imagem'] }}" />

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
                    <div class="card mt-2">
                        <div class="accordion" id="accordionExample">
                            <div class="card">
                                <div class="card-header p-0" id="headingOne">
                                    <h5 class="mb-0">
                                        <button class="btn py-0" type="button" data-toggle="collapse"
                                            data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            <input id="boleto" type="radio" class="input-radio btn"
                                                name="payment_method" value="BOLETO"
                                                {{ old('payment_method') != 'BOLETO' ? '' : 'checked' }}>
                                            <label for="boleto" class="btn my-0"><img
                                                    src="{{ asset('img/boleto.png') }}" /> BOLETO</label>
                                        </button>
                                    </h5>
                                </div>

                                <div id="collapseOne"
                                    class="collapse {{ old('payment_method') != 'BOLETO' ? '' : 'show' }}"
                                    aria-labelledby="headingOne" data-parent="#accordionExample">
                                    <div class="card-body">
                                        <div class="alert alert-info" role="alert">
                                            O boleto bancário será exibido após a confirmação e poderá ser impresso para
                                            pagamento em qualquer agência bancária, bankline (App) ou casas lotéricas.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header p-0" id="headingTwo">
                                    <h5 class="mb-0">
                                        <button class="btn collapsed py-0" type="button" data-toggle="collapse"
                                            data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                            <input id="pix" type="radio" class="input-radio btn"
                                                name="payment_method" value="PIX"
                                                {{ old('payment_method') == 'PIX' ? 'checked' : '' }}>
                                            <label for="pix" class="btn my-0"><img
                                                    src="{{ asset('img/pix.png') }}" /> PIX</label>
                                        </button>
                                    </h5>
                                </div>
                                <div id="collapseTwo" class="collapse {{ old('payment_method') == 'PIX' ? 'show' : '' }}"
                                    aria-labelledby="headingTwo" data-parent="#accordionExample">
                                    <div class="card-body">
                                        <div class="alert alert-info" role="alert">
                                            Pix é uma nova forma de pagamento instantâneo e seu pedido é aprovado na
                                            hora,
                                            após a confirmação será gerado o Pix Qrcode e o Pix copia e cola.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header p-0" id="headingThree">
                                    <h5 class="mb-0">
                                        <button class="btn collapsed py-0" type="button" data-toggle="collapse"
                                            data-target="#collapseThree" aria-expanded="false"
                                            aria-controls="collapseThree">
                                            <input id="cartao" type="radio" class="input-radio btn"
                                                name="payment_method" value="CREDIT_CARD"
                                                {{ old('payment_method') == 'CREDIT_CARD' ? 'checked' : '' }}>
                                            <label for="cartao" class="btn my-0"><img
                                                    src="{{ asset('img/cartaocredito.png') }}" /> CARTÃO DE
                                                CRÉDITO</label>
                                        </button>
                                    </h5>
                                </div>
                                <div id="collapseThree"
                                    class="collapse {{ old('payment_method') == 'CREDIT_CARD' ? 'show' : '' }}"
                                    aria-labelledby="headingThree" data-parent="#accordionExample">
                                    <div class="card-body">
                                        <div class="alert alert-info" role="alert">
                                            No cartão de crédito a provação do seu pedido é imediata, rápida e segura,
                                            insira os dados do cartão abaixo.
                                        </div>

                                        <div class="form-group row my-2">
                                            <div class="col-md">
                                                <label class="col-md col-form-label px-0">
                                                    {{ __('Número do cartão') }}
                                                    <text class="text-danger" data-toggle="tooltip"
                                                        title="Preenchimento obrigatório"> *
                                                    </text>
                                                </label>
                                                <input type="text"
                                                    class="form-control @error('numero_cartao') is-invalid @enderror"
                                                    name="numero_cartao" value="{{ old('numero_cartao') }}"
                                                    autocomplete="numero_cartao" autofocus>

                                                @error('numero_cartao')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row mb-2">
                                            <div class="col-md">
                                                <label class="col-md col-form-label px-0">
                                                    {{ __('Nome no cartão') }}
                                                    <text class="text-danger" data-toggle="tooltip"
                                                        title="Preenchimento obrigatório"> *
                                                    </text>
                                                </label>
                                                <input type="text"
                                                    class="form-control @error('nome_cartao') is-invalid @enderror"
                                                    name="nome_cartao" value="{{ old('nome_cartao') }}"
                                                    autocomplete="nome_cartao" autofocus>

                                                @error('nome_cartao')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row mb-2">
                                            <div class="col-md-6">
                                                <label class="col-md col-form-label px-0">
                                                    {{ __('Validade do cartão') }}
                                                    <text class="text-danger" data-toggle="tooltip"
                                                        title="Preenchimento obrigatório"> *
                                                    </text>
                                                </label>
                                                <input type="text"
                                                    class="form-control @error('validade_cartao') is-invalid @enderror"
                                                    name="validade_cartao" value="{{ old('validade_cartao') }}"
                                                    autocomplete="validade_cartao" autofocus>

                                                @error('validade_cartao')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label class="col-md col-form-label px-0">
                                                    {{ __('CVV do cartão') }}
                                                    <text class="text-danger" data-toggle="tooltip"
                                                        title="Preenchimento obrigatório"> *
                                                    </text>
                                                </label>
                                                <input type="text"
                                                    class="form-control @error('cvv_cartao') is-invalid @enderror"
                                                    name="cvv_cartao" value="{{ old('cvv_cartao') }}"
                                                    autocomplete="cvv_cartao" autofocus>

                                                @error('cvv_cartao')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md">
                                            <div class="form-group row">
                                                <label class="col-md col-form-label px-0">
                                                    {{ __('Selecione o número de parcelas') }}
                                                    <text class="text-danger" data-toggle="tooltip"
                                                        title="Preenchimento obrigatório"> *
                                                    </text>
                                                </label>
                                                <select class="custom-select" id="parcelas_cartao"
                                                    name="parcelas_cartao">
                                                    @for ($i = 1; $i <= 10; $i++)
                                                        {{ $parcela = $produto['preco'] / $i }}
                                                        @if ($parcela >= 20)
                                                            <option value="{{ $i }}">{{ $i }}x de
                                                                {{ substr(number_format($parcela, 3, ',', '.'), -0, -1) }}
                                                            </option>
                                                        @endif
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mb-0 row mt-3">
                        <div class="col">
                            <button type="submit" class="btn btn-primary">
                                {{ __('PAGAR') }}
                            </button>
                        </div>
                    </div>

                </div>
            </form>

        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function($) {
            $('input[name=telefone]').mask('(00) 0000-0000', {})
            $('input[name=cnpj]').mask('00.000.000/0000-00', {})
            $('input[name=cpf]').mask('000.000.000-00', {})
            $('input[name=numero_cartao]').mask('0000 0000 0000 0000', {})
            $('input[name=validade_cartao]').mask('00/0000', {})
            $('input[name=cvv_cartao]').mask('0000', {})

            $('input[name=radio_cpf_cnpj]').click(function() {
                if ($('input[name="radio_cpf_cnpj"]:checked').val() == 'cnpj') {
                    $('input[name=cnpj]').attr('disabled', false);
                    $('input[name=cpf]').attr('disabled', true);
                }
                if ($('input[name="radio_cpf_cnpj"]:checked').val() == 'cpf') {
                    $('input[name=cnpj]').attr('disabled', true);
                    $('input[name=cpf]').attr('disabled', false);
                }
            })
        })
    </script>
@endsection

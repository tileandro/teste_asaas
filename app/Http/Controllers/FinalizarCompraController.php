<?php

namespace App\Http\Controllers;

use App\Models\logPedidosAsaas;
use App\Models\logRegisterUsersAsaas;
use App\Models\Pedido;
use App\Models\Produtos;
use App\Models\User;
use Illuminate\Http\Request;
use Ramsey\Uuid\Type\Integer;

class FinalizarCompraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $radio_cpf_cnpj = $request->radio_cpf_cnpj;

        $request->validate(
            [
                'nome' => ['required', 'max:255'],
                'email' => ['required', 'email', 'max:100'],
                'telefone' => ['required', 'max:20'],
                $radio_cpf_cnpj => ['required', 'cpf_ou_cnpj'],
            ],
            [
                'required' => 'Obrigatório o preenchimento desse campo',
                'cpf_ou_cnpj' => ':attribute inválido',
                'max' => 'Tamanho máximo permitido de caracetes é de :max'
            ]
        );

        $request[$radio_cpf_cnpj] = str_replace('.', '', str_replace('-', '', str_replace('/', '', $request->$radio_cpf_cnpj)));
        $request['telefone'] = str_replace('(', '', str_replace(')', '', str_replace('-', '', str_replace(' ', '', $request->telefone))));

        $userRegister = $this->userRegister($request->nome, $request->email, $request->telefone, $request->$radio_cpf_cnpj, $request->server('HTTP_USER_AGENT'));

        if ($request->payment_method == 'BOLETO' || $request->payment_method == 'PIX') {
            $payment = $this->payment_boleto_pix($request->payment_method, $userRegister['idUserAsaas'], $request['preco'], $request->server('HTTP_USER_AGENT'), $userRegister['user_id'], $request->id_produto);
        }

        if ($request->payment_method == 'CREDIT_CARD') {
            $payment = $this->payment_credt_card($request->payment_method, $userRegister['idUserAsaas'], $request['preco'], $request->server('HTTP_USER_AGENT'), $userRegister['user_id'], $request->id_produto, $request->nome, $request->email, $request->$radio_cpf_cnpj, $request->telefone, $request->nome_cartao, $request->numero_cartao, $request->validade_cartao, $request->cvv_cartao, $request->parcelas_cartao);
        }

        if (($userRegister['status'] < 200 || $userRegister['status'] > 299) || ($payment['status'] < 200 || $payment['status'] > 299)) {
            return back()->with('error', $payment['msg']);
        } else {
            return redirect()->route('pedido.show', ['pedido' => $payment['pedido_id']]);
        };
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $produto = Produtos::find($id)->getAttributes();
        return view('finalizar-compra.show', ['page' => 'Finalizar Compra', 'produto' => $produto]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    private function userRegister(string $nome, string $email, int $telefone, string $radio_cpf_cnpj, string $agente)
    {
        $url = env('URL_ASAAS') . env('VERSION_ASAAS') . 'customers';
        $curl = curl_init($url);

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                "name": "' . $nome . '",
                "email": "' . $email . '",
                "mobilePhone": "' . $telefone . '",
                "cpfCnpj": "' . $radio_cpf_cnpj . '",
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Accept: application/json',
                'access_token: ' . env('TOKEN_ASAAS') . '',
                'User-Agent: ' . $agente . ''
            ),
        ));

        $response = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($httpcode < 200 || $httpcode > 299) {
            $error = json_decode($response);
            $msg = $error->errors[0]->description;
        } else {
            //Gerando log do request no banco
            $log = new logRegisterUsersAsaas();
            $log->json = $response;
            $log->save();

            //Gravando o usuário no banco
            $response = json_decode($response);
            $user = new User();
            $user->name = $response->name;
            $user->email = $response->email;
            $user->id_user_asaas = $response->id;
            $user->phone = $response->mobilePhone;
            if ($response->personType == 'JURIDICA') {
                $user->cnpj = $response->cpfCnpj;
            } else {
                $user->cpf = $response->cpfCnpj;
            }
            $user->save();

            $msg = 'Pagamento realizado com sucesso';
        }

        $json = array(
            'status' => $httpcode,
            'idUserAsaas' => $response->id,
            'user_id' => $user->id,
            'msg' => $msg
        );

        return $json;
    }

    private function payment_boleto_pix(string $method_payment, string $idUserAsass, float $preco, string $agente, string $user_id, int $produto_id)
    {
        $url = env('URL_ASAAS') . env('VERSION_ASAAS') . 'payments';
        $curl = curl_init($url);

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                "billingType": "' . $method_payment . '",
                "customer": "' . $idUserAsass . '",
                "value": ' . $preco . ',
                "dueDate": "' . date("Y-m-d", strtotime("+3 days")) . '",
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Accept: application/json',
                'access_token: ' . env('TOKEN_ASAAS') . '',
                'User-Agent: ' . $agente . ''
            ),
        ));

        $response = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($httpcode < 200 || $httpcode > 299) {
            $error = json_decode($response);
            $msg = $error->errors[0]->description;
        } else {
            //Gerando log do request no banco
            $log = new logPedidosAsaas();
            $log->json = $response;
            $log->save();

            //Gravando o pedido no banco
            $response = json_decode($response);
            $pedido = new Pedido();
            $pedido->produto_id = $produto_id;
            $pedido->user_id = $user_id;
            $pedido->valor_total = $response->value;
            $pedido->metodo_pagamento = $response->billingType;
            $pedido->data_vencimento = $response->dueDate;
            $pedido->status_pedido_asaas = $response->status;
            if ($response->billingType == 'BOLETO') {
                $pedido->link_boleto = $response->bankSlipUrl;
            }

            //gerar Qrcode e copia e cola pix
            if ($response->billingType == 'PIX') {
                $urlQrcode = env('URL_ASAAS') . env('VERSION_ASAAS') . 'payments/' . $response->id . '/pixQrCode';
                $curl = curl_init($urlQrcode);

                curl_setopt_array($curl, array(
                    CURLOPT_URL => $urlQrcode,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json',
                        'Accept: application/json',
                        'access_token: ' . env('TOKEN_ASAAS') . '',
                        'User-Agent: ' . $agente . ''
                    ),
                ));

                $responseQrCode = curl_exec($curl);
                $httpcodeQrCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                curl_close($curl);

                if ($httpcodeQrCode < 200 || $httpcodeQrCode > 299) {
                    $error = json_decode($responseQrCode);
                    $msg = $error->errors[0]->description;
                } else {
                    $responseQrCode = json_decode($responseQrCode);
                    $pedido->pix_copia_cola = $responseQrCode->payload;
                    $pedido->pix_qr_code = $responseQrCode->encodedImage;
                }
            }

            $pedido->save();

            $msg = 'Pedido realizado com sucesso';
        }

        $json = array(
            'status' => $httpcode,
            'pedido_id' => $pedido->id,
            'msg' => $msg
        );

        return $json;
    }

    private function payment_credt_card(string $method_payment, string $idUserAsass, float $preco, string $agente, string $user_id, int $produto_id, string $nome, string $email, string $cpf_cnpj, int $phone, string $card_name, string $card_number, string $card_valid, int $card_cvv, int $parcelas_cartao)
    {
        $card_number = str_replace(' ', '', $card_number);
        $card_valid = explode('/', $card_valid);
        $card_expiry_month = $card_valid[0];
        $card_expiry_year = $card_valid[1];

        $url = env('URL_ASAAS') . env('VERSION_ASAAS') . 'payments';
        $curl = curl_init($url);

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                'billingType' => "$method_payment",
                'customer' => "$idUserAsass",
                'value' =>  $preco,
                'dueDate' => '' . date("Y-m-d", strtotime("+3 days")) . '',
                'installmentCount' => $parcelas_cartao,
                'totalValue' => $preco,
                'creditCard' => [
                    'holderName' => "$card_name",
                    'number' => "$card_number",
                    'expiryMonth' => "$card_expiry_month",
                    'expiryYear' => "$card_expiry_year",
                    'ccv' => "$card_cvv"
                ],
                'creditCardHolderInfo' => [
                    'name' => "$nome",
                    'email' => "$email",
                    'cpfCnpj' => "$cpf_cnpj",
                    'postalCode' => '89223005',
                    'addressNumber' => '123',
                    'mobilePhone' => "$phone"
                ]
            ]),
            CURLOPT_HTTPHEADER => [
                "accept: application/json",
                "content-type: application/json",
                'access_token: ' . env('TOKEN_ASAAS') . '',
                'User-Agent: ' . $agente . ''
            ],
        ]);

        $response = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($httpcode < 200 || $httpcode > 299) {
            $error = json_decode($response);
            $msg = $error->errors[0]->description;
        } else {
            //Gerando log do request no banco
            $log = new logPedidosAsaas();
            $log->json = $response;
            $log->save();

            //Gravando o pedido no banco
            $response = json_decode($response);
            $pedido = new Pedido();
            $pedido->produto_id = $produto_id;
            $pedido->user_id = $user_id;
            $pedido->valor_total = $preco;
            $pedido->valor_parcela = $response->value;
            $pedido->metodo_pagamento = $response->billingType;
            $pedido->data_vencimento = $response->dueDate;
            $pedido->numero_parcela_cartao = $parcelas_cartao;
            $pedido->status_pedido_asaas = $response->status;
            $pedido->save();

            $msg = 'Pedido realizado com sucesso';
        }

        $json = array(
            'status' => $httpcode,
            'pedido_id' => $pedido->id,
            'msg' => $msg
        );

        return $json;
    }
}

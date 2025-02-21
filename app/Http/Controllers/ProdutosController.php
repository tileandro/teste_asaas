<?php

namespace App\Http\Controllers;

use App\Models\Produtos;
use Illuminate\Http\Request;

class ProdutosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $produtos = Produtos::Orderby('nome')->Paginate(4);
        return view('home', ['page' => 'Lista de Produtos', 'produtos' => $produtos, 'request' => $request]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('produtos.create', ['page' => 'Criar Produto']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'nome' => ['required', 'string', 'min:3', 'max:100'],
                'preco' => ['required', 'string'],
                'imagem' => ['required', 'file', 'mimes:png,jpg,jpeg,webp', 'max:2048']
            ],
            [
                'required' => 'Obrigatório o preenchimento desse campo',
                'mimes' => 'Arquivos aceitos .png, .jpg, .jpeg e webp',
                'min' => 'Mínimo de caracter permitido :min',
                'max' => 'Tamanho máximo permitido de :max'
            ]
        );

        $path = 'img';
        $name = now()->timestamp . "_{$request->imagem->getClientOriginalName()}";
        $file = $request->file('imagem');
        $file->move(public_path($path), $name);

        $produto = new Produtos();
        $produto->nome = $request->nome;
        $produto->preco = str_replace(',', '.', str_replace('.', '', $request->preco));
        $produto->imagem = $path . '/' . $name;
        $produto->save();

        return back()->with('success', 'Produto "' . $request->input('nome') . '" cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
}

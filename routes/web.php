<?php

use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Produtos;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'ProdutosController@index');
Route::resource('/produtos', 'ProdutosController');
Route::resource('/finalizar-compra', 'FinalizarCompraController');
Route::resource('/pedido', 'PedidoController');

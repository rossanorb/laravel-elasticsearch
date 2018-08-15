<?php

namespace App\Http\Controllers;

use Elasticsearch\Client;
use Illuminate\Http\Request;

use App\Http\Requests;

class ClientsController extends Controller
{
    protected $elasticParams = [];
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->elasticParams['index'] = env('ES_INDEX');
        $this->elasticParams['type'] = 'clients';
        //$this->elasticParams['client'] = ['ignore' => [400, 404]];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $clients = $this->client->search($this->elasticParams);
        return view('clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request)
    {
        date_default_timezone_set('America/Sao_Paulo');
        $this->elasticParams['id']  =  microtime(date('Y-m-d H:i:s')); // gambiarra para gerar id

        $data = $request->all();
        unset($data['_token']);
        $this->elasticParams['body'] = $data;
        $this->elasticParams['refresh'] = true;
        $this->client->create($this->elasticParams);
        return redirect()->route('clients.index');
    }    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('clients.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){}

}

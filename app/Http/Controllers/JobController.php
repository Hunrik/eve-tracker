<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\SellList;
use App\Items;
use App\Requests\JobRequest;
class JobController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
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
    public function create(Request $request)
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
        $db = new SellList();
        $db->user_id = Auth::user()->id;
        $db->typeName = $request->name;
        $db->typeId = Items::findByName($request->name)->first()->id;
        $db->quantity = $request->quantity;
        $db->left = $request->quantity;
        $db->price = $request->price;
        $db->save();
        return redirect('/orders')->with('status', 'Added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = SellList::findOrFail($id);
        $this->authorize($order);

        $orders = $order->WalletJournals;
        if(!$orders) abort(404);
        $quantity = 0;
        foreach($orders as $item) {
            $quantity += $item->quantity;
        }

        return view('partials/modal_editOrder',[
            'order' => $order,
            'quantity' => $quantity
            ]);
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
        $order = SellList::findOrFail($id);
        $this->authorize($order);
        $order->quantity = $request->input('quantity');
        $order->left = $request->input('left');
        $order->price = $request->input('price');
        $order->save();
        return redirect('/orders')->with('status', 'Saved!');
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

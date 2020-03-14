<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cart;
use App\ResponseHandler;
use App\FileManager;
use App\Http\Resources\CartResource;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    	
    private $cart;
    private $respHandler;
    private $fileManager;
    public function __construct() {
        $this->cart = new cart();
        $this->respHandler = new ResponseHandler();
        $this->fileManager = new FileManager();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cart = $this->cart->get();
        if ($cart->count() > 0) {
            return $this->respHandler->send(200, "Successfully get cart", CartResource::collection($cart));
        } else {
            return $this->respHandler->notFound("cart");
        }
 
    }
 
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'username' => 'required|string',
            'foods_id' => 'required|string',
            'qty' => 'required|string'
        ]);
 
        if ($validate->fails()) {
            return $this->respHandler->validateError($validate->errors());
        }
 
        $input = $request->all();
        if (!$this->cart->isExists($request->username,$request->foods_id)) {
 
            $createData = $this->cart->create($input);
            if ($createData) {
                return $this->respHandler->send(200, "Successfully create cart", new CartResource($createData));
            } else {
                return $this->respHandler->internalError();
            }
 
        } else {
            return $this->respHandler->exists("cart");
        }
    }
 
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if ($this->cart->isExistsById($id)) {
            $cart = $this->cart->find($id);
            return $this->respHandler->send(200, "Successfully get Cart", new CartResource($cart));
        } else {
            return $this->respHandler->notFound("cart");
        }
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
        $validate = Validator::make($request->all(), [
            'username' => 'required|string',
            'foods_id' => 'required|string',
            'qty' => 'required|string'
        ]);
 
        if ($validate->fails()) {
            return $this->respHandler->validateError($validate->errors());
        }
 
        $input = $request->all();
        if ($this->cart->isExistsById($id)) {
            $cart = $this->cart->find($id);
 
            $updateData = $cart->update($input);
 
            if ($updateData) {
                return $this->respHandler->send(200, "Successfully update cart");
            } else {
                return $this->respHandler->internalError();
            }
        } else {
            return $this->respHandler->notFound("cart");
        }
    }
 
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($username)
    {
        if ($this->cart->isExistsByUsername($username)) {
            $cart = $this->cart->where('username', $username)->get();
            foreach ($cart as $row) {
                $row->delete();
            }
            
            return $this->respHandler->send(200, "Successfully delete cart");
        } else {
            return $this->respHandler->notFound("cart");
        }
    }
}

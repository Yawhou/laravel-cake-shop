<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Nette\Schema\ValidationException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class CartController extends Controller
{
    /**
     * Adds a product to the cart immediately.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function buynow(Request $request): RedirectResponse
    {
        // Retrieve the product by ID
        $product = Product::with('category')->findOrFail($request->input('product_id'));
        $cart = [];

        // Validate the request
        try {
            $this->validate($request, [
                'product_id'=>'required|numeric'
            ]);
        }
        catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->with('message', $e);
        }

        // Add product to cart
        if (session()->has('cart'))
        {
            try {
                $cart = session()->get('cart');
            } catch (NotFoundExceptionInterface | ContainerExceptionInterface $e) {
                return redirect()->back()->with('message', $e);

            }

            // Check if the product already exists in the cart
            if (array_key_exists($product->id, $cart['products']))
            {
                $cart['products'][$product->id]['quantity']+=1;
            }
            else{
                $cart['products'][$product->id] = [
                    'product_id'=> $product->id,
                    'title' => $product->title,
                    'image_prod' => $product->image_prod,
                    'category' => $product->category->category_name,
                    'quantity' => 1,
                    'price' => ($product->sale_price != null && $product->sale_price > 0) ? $product->sale_price : $product->price

                ];
            }

        }
        else {
            // If cart doesn't exist, create a new one
            $cart['products'] = [
                $product->id => [
                    'product_id'=> $product->id,
                    'title' => $product->title,
                    'image_prod' => $product->image_prod,
                    'category' => $product->category->category_name,
                    'quantity' => 1,
                    'price' => ($product->sale_price != null && $product->sale_price > 0) ? $product->sale_price : $product->price
                ]
            ];
        }

        // Save the cart to session
        session(['cart'=> $cart]);

        // Flash success message and redirect to cart
        session()->flash('message', $product->title.' Added To Cart Successfully!');
        session()->flash('type', 'success');

        return redirect()->route('cart.show');
    }

    /**
     * Adds a product to the cart.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function addToCart(Request $request): RedirectResponse
    {
        // Similar logic to buynow method, but doesn't redirect to cart.show

        // Retrieve the product by ID
        $product = Product::with('category')->findOrFail($request->input('product_id'));
        $cart = [];

        // Validate the request
        try {
            $this->validate($request, [
                'product_id'=>'required|numeric'
            ]);
        }
        catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->with('message', $e);
        }

        if (session()->has('cart'))
        {
            try {
                $cart = session()->get('cart');
            } catch (NotFoundExceptionInterface | ContainerExceptionInterface $e) {
                return redirect()->back()->with('message', $e);

            }


            if (array_key_exists($product->id, $cart['products']))
            {
                $cart['products'][$product->id]['quantity']+=1;

            }
            else{
                $cart['products'][$product->id] = [
                        'product_id'=> $product->id,
                        'title' => $product->title,
                        'image_prod' => $product->image_prod,
                        'category' => $product->category->category_name,
                        'quantity' => 1,
                        'price' => ($product->sale_price != null && $product->sale_price > 0) ? $product->sale_price : $product->price
                ];
            }

        }
        else {
            $cart['products'] = [
                $product->id => [
                    'product_id'=> $product->id,
                    'title' => $product->title,
                    'image_prod' => $product->image_prod,
                    'category' => $product->category->category_name,
                    'quantity' => 1,
                    'price' => ($product->sale_price != null && $product->sale_price > 0) ? $product->sale_price : $product->price
                ]
            ];
        }

        session(['cart'=> $cart]);

        session()->flash('message', $product->title.' Added To Cart Successfully!');
        session()->flash('type', 'success');

        return redirect()->back();
    }


    /**
     * Display the cart contents.
     *
     * @return Application|Factory|View|RedirectResponse
     */
    public function show()
    {
        $data = [];
        try {
            $data['cart'] = session()->has('cart') ? session()->get('cart') : [];
        } catch (NotFoundExceptionInterface | ContainerExceptionInterface $e) {
            return redirect()->back()->with('message', $e);
        }
        if($data['cart'] != null) {
            $product_price = 0;
            $total_quantity = 0;
            foreach (session('cart.products') as $key => $product) {

                $price = (int)$product['quantity'];
                $quan = (int)$product['price'];
                $product_price += ($price) * ($quan);
                $total_quantity += (int)$product['quantity'];

                session(['cart.products.' . $key . '.product_total' => ($price) * ($quan)]);
            }
            session(['cart.quantity_total' => $total_quantity]);
            session(['cart.total' => $product_price]);
            try {
                $data['cart'] = session()->get('cart');
            } catch (NotFoundExceptionInterface | ContainerExceptionInterface $e) {
                return redirect()->back()->with('message', $e);
            }

        }
        return view('cart.cart', $data);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return Application|Factory|View|RedirectResponse
     */
    public function checkout()
    {

        $data = [];
        try {
            $data['cart'] = session()->has('cart') ? session()->get('cart') : [];
        }
        catch (NotFoundExceptionInterface | ContainerExceptionInterface $e) {
            return redirect()->back()->with('message', $e);
        }
        if($data['cart']==null)
        {
            return view('cart.cart', $data);
        }
        else {

            }
            return view('cart.checkout', $data);

    }

    /**
     * Remove a product from the cart.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Request $request): RedirectResponse
    {
        try {
            $this->validate($request, [
                'product_id'=>'required|numeric'
            ]);
        }
        catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->with('message', $e);
        }
        try {
            $cart = session()->get('cart');
        } catch (NotFoundExceptionInterface | ContainerExceptionInterface $e) {
            return redirect()->back()->with('message', $e);

        }
        $title = $request->input('title');
        unset($cart['products'][$request->input('product_id')]);
        session(['cart'=> $cart]);
        session()->flash('message', $title.' Removed From Cart Successfully!');
        session()->flash('type', 'success');

        return redirect()->back();
    }

    /**
     * Process the order.
     *
     * @param Request $request
     * @return Application|Factory|View|RedirectResponse
     */
    public function processOrder(Request $request)
    {
        try {
            // Create an order with the submitted data
            $order = Order::create([
                'user_id' => auth()->user()->id,
                'customer_name' => auth()->user()->name,
                'customer_phone_number' => auth()->user()->phone_number,
                'address' => $request->input('address'),
                'city' => $request->input('city'),
                'postal_code'=> $request->input('zip'),
                'paid_amount'=> 0,
                'payment_details'=>$request->input('paymentMethod'),
                'total_amount' => session('cart.total')+150
            ]);
            foreach (session('cart.products') as $key => $product)
            {
                $order->products()->create([
                    'product_id'=> $key,
                    'quantity' => $product['quantity'],
                    'price' => $product['product_total']

                ]);

            }

            $data['cart']= session()->get('cart');
            $data['order']= $order;
            $data['order_products']= OrderProduct::findOrFail($order->id);

            session()->flash('message','Your Order Is Being Processed!');
            session()->flash('type','success');
            return \view('cart.orderdetails', $data);
        }
        catch(Exception $e){

            session()->flash('message',$e->getMessage());
            session()->flash('type','danger');

            return redirect()->back();
        }
    }
}

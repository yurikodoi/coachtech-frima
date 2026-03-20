<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExhibitionRequest;
use App\Models\Item;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class ItemController extends Controller
{

    public function store(ExhibitionRequest $request)
    {
    $imagePath = null;

    if ($request->hasFile('item_image')) {
        $path = $request->file('item_image')->store('item_images', 'public');
        $imagePath = $path;
    }

    $item = Item::create([
        'user_id'     => Auth::id(),
        'name'        => $request->name,
        'brand'       => $request->brand,
        'description' => $request->description,
        'price'       => $request->price,
        'condition'   => $request->condition,
        'image_url'   => $imagePath,
    ]);

    $item->categories()->attach($request->category_id);

    return redirect()->route('item.index')->with('message', '商品を出品しました');
    }

    public function index(Request $request)
    {
        $currentUserId = Auth::id();
        $searchKeyword = $request->input('keyword');
        $selectedTab   = $request->query('tab', 'index');

        $itemQuery = Item::query();

        if ($currentUserId) {
            $itemQuery->where('user_id', '!=', $currentUserId);
        }

        if ($searchKeyword) {
            $itemQuery->where('name', 'LIKE', "%{$searchKeyword}%");
        }

        if ($selectedTab === 'mylist') {
            if (!$currentUserId) {
                $items = collect(); 
            } else {
                $items = $itemQuery->whereHas('likes', function ($query) use ($currentUserId) {
                    $query->where('user_id', $currentUserId);
                })->get();
            }
        } else {
            $items = $itemQuery->get();
        }

        return view('index', [
            'items'         => $items,
            'searchKeyword' => $searchKeyword,
            'selectedTab'   => $selectedTab,
        ]);
    }


    public function show($item_id)
    {
        $item = Item::findOrFail($item_id);

        return view('item_detail', [
            'item' => $item,
        ]);
    }

    public function create()
    {
        $categories = \App\Models\Category::all();

        return view('item.create', [
            'categories' => $categories
        ]);
    }

    public function showPurchase($item_id)
    {
        $item = Item::findOrFail($item_id);

        $user = Auth::user();

        return view('item_purchase', [
            'item' => $item,
            'user' => $user,
        ]);
    }

    public function storePurchase(Request $request, $itemId)
    {
        $item = Item::findOrFail($itemId);
        $user = Auth::user();

        $paymentMethod = $request->payment_method; 

        $newAddress = session('new_shipping');

        Order::create([
        'user_id'              => $user->id,
        'item_id'              => $item->id,
        'shipping_postal_code' => $newAddress['postcode'] ?? $user->postcode,
        'shipping_address'     => $newAddress['address']  ?? $user->address,
        'shipping_building'    => $newAddress['building'] ?? $user->building,
        ]);

    session()->forget('new_shipping');

        Stripe::setApiKey(config('services.stripe.secret'));

        $checkout_session = Session::create([
            'payment_method_types' => [$paymentMethod === 'card' ? 'card' : 'konbini'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $item->name,
                    ],
                    'unit_amount' => $item->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('purchase.success', ['item_id' => $item->id]),
            'cancel_url' => route('purchase.show', ['itemId' => $item->id]),
            'customer_email' => $user->email,
            ]);

        return redirect($checkout_session->url, 303);
    }

public function successPurchase(Request $request, $itemId)
{
    $item = Item::findOrFail($itemId);

    $item->update(['is_sold' => true]);

    return redirect()->route('item.index')->with('message', '購入が完了しました！');
}
}


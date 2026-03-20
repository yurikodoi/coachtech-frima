<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class PurchaseController extends Controller
{
    public function showPurchasePage($itemId)
    {
        $item = Item::find($itemId);
        if (!$item) {
            abort(404);
        }
        $user = Auth::user();
        return view('item_purchase', compact('item', 'user'));
    }
    public function storePurchase(Request $request, $itemId)
    {
        $user = Auth::user();
        $item = Item::find($itemId);
        if (!$item || (isset($item->isSold) && $item->isSold)) {
            return redirect()->back()->with('errorMessage', 'この商品は購入できません。');
        }
        if (!$request->payment_method) {
            return redirect()->back()->with('errorMessage', '支払い方法を選択してください。');
        }
        $new_address = session('new_shipping');
        $order = Order::create([
            'user_id'              => $user->id,
            'item_id'              => $itemId,
            'shipping_postal_code' => $new_address['postal_code'] ?? $user->postcode,
            'shipping_address'     => $new_address['address']     ?? $user->address,
            'shipping_building'    => $new_address['building']    ?? $user->building,
        ]);
        session()->forget('new_shipping');
        return redirect()->route('item.index')->with('message', '購入が完了しました');
    }
}
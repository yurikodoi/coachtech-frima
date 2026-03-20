<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProfileRequest;
use App\Models\Order;
class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $items = $user->items;
        $orders = Order::where('user_id', $user->id)->with('item')->get();
        return view('profile.index', compact('user', 'items', 'orders'));
    }
    public function edit()
    {
        return view('profile.edit', ['user' => Auth::user()]);
    }
    public function update(ProfileRequest $request)
    {
        $user = Auth::user();
        $data = $request->validated();
        unset($data['image']);
        $user->fill($data);
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('profiles', 'public');
            $user->image = $path;
        }
        $user->save();
        return redirect('/')->with('message', 'プロフィールを更新しました');
    }
    public function updateShippingAddress(Request $request, $itemId)
    {
        $request->validate(['shipping_postal_code' => 'required', 'shipping_address' => 'required']);
        session(['new_shipping' => ['postal_code' => $request->shipping_postal_code, 'address' => $request->shipping_address, 'building' => $request->shipping_building]]);
        return redirect()->route('item.purchase', ['item_id' => $itemId]);
    }
}
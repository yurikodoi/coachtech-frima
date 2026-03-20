<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Http\Requests\AddressRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    /**
     * 送付先住所変更画面を表示する
     */
    /**
     * 送付先住所変更画面を表示する
     */
    public function editAddressPage($itemId)
    {
        $item = Item::find($itemId);

        if (!$item) {
            abort(404);
        }

        $user = Auth::user();

        return view('profile.address', compact('item', 'user', 'itemId'));
    }

    public function updateAddress(Request $request, $itemId)
    {
        $request->validate([
            'postcode' => 'required|regex:/^\d{3}-\d{4}$/',
            'address'  => 'required|string|max:255',
            'building' => 'nullable|string|max:255',
        ], [
            'postcode.required' => '郵便番号を入力してください',
            'postcode.regex'    => '郵便番号は000-0000の形式で入力してください',
            'address.required'  => '住所を入力してください',
        ]);

        $user = Auth::user();
        session(['new_shipping' => $request->only(['postcode', 'address', 'building'])]);

        return redirect()->route('purchase.show', ['itemId' => $itemId])
                ->with('message', '配送先を変更しました');
    }

}
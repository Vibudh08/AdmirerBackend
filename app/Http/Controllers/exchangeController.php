<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;

class exchangeController extends Controller
{

public function exchangeStatus(Request $request)
{
    $orderId = $request->input('orderid');
    $productId = $request->input('productid');
      
    $order_count = DB::table('refund_order')
        ->where('order_id', $orderId)
        ->where('od_p_id', $productId)
        ->count();

    if ($order_count < 1) {

    return response()->json(['status' => 0]);
        
    }else{
    $order_status = DB::table('refund_order')
    ->where('order_id', $orderId)
    ->where('od_p_id', $productId)
    ->first();    

    return response()->json(['status' => $order_status->status]);  

    }
}

public function store(Request $request)
{
    $id=Auth::user()->id;
    $comment = $request->input('comment');
    $orderId = $request->input('orderid');
    $productId = $request->input('productid');
                 
    // Validate
    if (!$comment || !$request->hasFile('images')) {
        return response()->json(['status' => 'error', 'message' => 'Invalid request data'], 400);
    }

    $imageNames = [];

    foreach ($request->file('images') as $image) {
        $imageName = time() . '_' . $image->getClientOriginalName();
        $image->move(public_path('uploads/refund'), $imageName); // Move to public/uploads/refund
        $imageNames[] = $imageName;
    }

    $order_count = DB::table('refund_order')
        ->where('order_id', $orderId)
        ->where('od_p_id', $productId)
        ->count();

    if ($order_count < 1) {
        DB::table('refund_order')->insert([
            'order_id'       => $orderId,
            'od_p_id'        => $productId,
            'return_reason'  => $comment,
            'return_image'   => implode(', ', $imageNames),
            'status'         => '1', 
            'price'          => '476',
        ]);
    }

    $order_status = DB::table('refund_order')
        ->where('order_id', $orderId)
        ->where('od_p_id', $productId)
        ->first();

    return response()->json(['status' => $order_status->status]);
}

    
    public function sendMail(Request $request)
    {
        $request->validate([
            'to' => 'required|email',
            'message' => 'required|string'
        ]);
      
        Mail::to($request->to)->send(new TestMail($request->message));

        return response()->json(['message' => 'Mail sent successfully!']);
    }

}

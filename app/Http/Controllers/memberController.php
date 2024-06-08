<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\price;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MemberController extends Controller
{
    public function index()
    {
        $users = Member::paginate(10);
        return view('member', compact('users'));
    }

    public function calculate_mamber()
    {
        $users = Member::paginate(40);
        $prices = price::all();
        $totalMoney = Member::sum('money');
        return view('calculate', compact('users', 'prices', 'totalMoney'));
    }

    public function destroy($id)
    {
        Member::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'ลบข้อมูลสมาชิกเรียบร้อยแล้ว');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'weapon' => 'required',
        ]);

        Member::create([
            'name' => $request->name,
            'weapon' => $request->weapon,
        ]);

        return redirect()->back()->with('success', 'เพิ่มข้อมูลสมาชิกเรียบร้อยแล้ว');
    }

    public function edit(Request $request, $id){
        $edit = Member::find($id);
        $edit->name = $request->name;
        $edit->weapon = $request->weapon;
        $edit->save();
        
        return redirect('/member')->with('success', 'แก้ไขข้อมูลสมาชิกเรียบร้อยแล้ว');
    }
    
    public function calculate(Request $request)
    {
        try {
            $user_ids = json_decode($request->input('user_ids'), true);
            $cement = $request->input('cement');
            $oil = $request->input('oil');
            $electricity = $request->input('electricity');
            $total = $request->input('total');
            $perUser = $request->input('perUser');
    
            $price = $cement * 3000 + $oil * 5000 + $electricity * 6000;
            $userCount = count($user_ids);
    
            $sum = $price / $userCount;
    
            foreach ($user_ids as $id) {
                $user = Member::find($id);
                if ($user) {
                    $user->money += $sum;
                    $user->save();
                }
            }
    
            return response()->json([
                'message' => 'Calculation and saving completed successfully.',
                'total_price' => $perUser,
                'user_count' => $userCount,
                'user_ids' => $user_ids,
                'sum_per_user' => $sum
            ]);
        } catch (\Exception $e) {
            Log::error('Error in calculation: ' . $e->getMessage());
            return response()->json([
                'message' => 'An error occurred. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

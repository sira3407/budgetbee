<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\AccountTypes;
use App\Models\Record;
use App\Models\Category;

class AccountController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function get()
    {
        $accounts = Account::all();

        return response()->json($accounts);
    }

    public function getById($id)
    {
        $account = Account::find($id);

        return response()->json($account);
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'type_id' => 'required|integer|exists:App\Models\AccountTypes,id',
            'color' => 'required|string',
            'initial_balance' => 'required|numeric'
        ]);
        
        $data = $request->only('name', 'type_id', 'color', 'initial_balance');
        
        $data['user_id'] = $request->user()->id;
        
        $account = new Account();
        $account->fill($data);
        $account->save();

        return response()->json($account);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'type_id' => 'required|integer',
            'color' => 'required',
            "initial_balance" => 'required'
        ]);

        $data = $request->only('name', 'type_id', 'color', 'initial_balance');

        $account = Account::find($id);
        $account->fill($data);
        $account->save();

        return response()->json($account);
    }

    public function delete($id)
    {
        $account = Account::find($id);
        if (is_object($account)) {
            $account->delete();
        }

        return response()->json([]);
    }

    public function getTypes()
    {
        $types = AccountTypes::all();

        return response()->json($types);
    }

    public function getRecords(Request $request, $id)
    {
        $records = Record::where('from_account_id', $id)
            ->where('user_id', $request->user()->id);
        
        $page = $request->query('page');
        if ($page > 0) {
            $perPage = 20;
            $records->skip(($page - 1) * $perPage)
                ->take($perPage);
        }
        
        $data = $records->orderByDesc('date')
        ->get();

        return response()->json($data);
    }


    public function getLastRecords(Request $request, $id, $number)
    {
        $record = Record::where('from_account_id', $id)
            ->where('user_id', $request->user()->id)
            ->orderByDesc('date')
            ->limit($number)
            ->get();

        return response()->json($record);
    }

    public function adjustBalance(Request $request, $id)
    {
        $this->validate($request, [
            'balance' => 'required'
        ]);

        $data = $request->only('balance');

        $account = Account::find($id);

        $amount = $data['balance'] - $account->balance;
        $type = ($amount > 0) ? 'income' : 'expense';

        $newRecord = new Record();
        $newRecord->fill([
            'date' => date('Y-m-d'),
            'user_id' => $request->user()->id,
            'from_account_id' => $account->id,
            'amount' => $amount,
            'type' => $type,
            'category_id' => 44
        ]);
        $newRecord->save();

        return response()->json();
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreColumnRequest;
use App\Models\Card;
use App\Models\Column;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Spatie\DbDumper\Databases\MySql;

class ColumnController extends Controller
{
    public function index(Request $request)
    {
        if ($request->access_token != '42gA1S5') abort(403);
        return response()->json([
            'columns' => Column::getColumnsWithCards($request)
        ]);
    }

    public function store(StoreColumnRequest $request)
    {
        $column = Column::query()->create($request->only(['title']));
        $column['cards'] = [];
        return response()->json([
            'column' => $column
        ]);
    }

    public function update(Request $request)
    {
        foreach ($request['columns'] as $column) {
            $order = 1;
            foreach ($column['cards'] as $card) {
                $card_db = Card::query()->find($card['id']);
                if ($card_db) {
                    $card_db->update([
                        'column_id' => $column['id'],
                        'order' => $order,
                    ]);
                    $order++;
                }
            }
        }
        return response()->json([
            'columns' => Column::getColumnsWithCards($request)
        ]);
    }

    public function delete(Column $column)
    {
        $column->cards()->delete();
        $column->delete();
        return response()->json([]);
    }

    public function exportSql()
    {
        try {
            MySql::create()
                ->setDbName('bemo')
                ->setUserName('root')
                ->dumpToFile(storage_path('app/public/dump.sql'));
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Can\'t export database'
            ]);
        }
        return response()->json([
            'file_url' => Storage::url('dump.sql')
        ]);
    }
}

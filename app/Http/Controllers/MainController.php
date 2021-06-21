<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Storage;
use App\Models\Move;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class MainController extends Controller
{
    //

    public function index()
    {
        $user = Auth::user();
        $userInfo = [];
        $userInfo['fio'] = $user->lastname . ' ' . $this->first_letter($user->firstname); 
        $userInfo['store_id'] = $user->store_id;
        // dd($userInfo);
        if ($user->is_admin) {
            $view = 'admin'; 
            $list = Storage::select('*')->get();
        } else {
            $view = 'manager';
            $list = Storage::select('*')->where('store_id', $user->store_id)->get();
        }

        
        $userInfo['items'] = $list;
        
        return view($view, ['userInfo' => $userInfo]);
    }

    public function inlist()
    {
        $address = (Auth::user()->store_id == 1) ? 'Астана' : 'Алматы';
        $list = Move::select('*')->where('address', $address)->get();
        // dd($list);
        $tmt = [];
        if (isset($list) && sizeof($list)) {
            foreach ($list as $value) {
                if ($value['status'] == 'Отправлено') {
                    $tmt[] = $value;
                }
            }
        }

        return view('incoming', ['list' => $tmt]);
    }

    public function move(Request $request)
    {
        $list = Move::select('*')->get();
        $tmt = [];
        if (sizeof($list) && (isset($request->daterange1) || isset($request->daterange2))) {
            $dateFrom = $request->daterange1;
            $dateto = $request->daterange2;
            foreach ($list as $value) {
                $send_date = date('d.m.Y', strtotime($value['send_date']));
                if ($send_date >= $dateFrom && $send_date <= $dateto) {
                    $tmt[] = $value;
                }
            }
            $list = $tmt;
        } 
        // dd($list);
        if (isset($request['checkbox']) && $request['checkbox'] == 'true') {
            $file = $this->export_excel($list);
            return response()->download($file, 'test.xlsx');
        }
        

        return view('move', ['list' => $list]);
    }

    public function save($id)
    {
        $user = Auth::user();
        $move = Move::find($id);
        $move->reciver_fio = $user->lastname . ' ' . $user->firstname . ' ' . $user->patronymic;
        $move->accept_date = date('Y-m-d');
        $move->status = 'Получено';
        $move->save();
        
        $storage_id = (int)Move::select('storage_id')->where('id', $id)->get()[0]['storage_id'];
        $this->edit($storage_id, (int)$user->store_id);
        // dd($list);
        
        return redirect('/incoming');
    }

    public function create(Request $request)
    {
        Storage::create([
            'name' => $request->name,
            'series' => $request->series,
            'number' => $request->number,
            'store_id' => Auth::user()->store_id
        ]);

        return redirect('/store');
    }

    public function send($id)
    {
        $user = Auth::user();
        $store_read = Storage::select('*')->where('id', $id)->get()[0];
        //dd($store_read['name']);
        Move::create([
            'storage_id' => (int)$id,
            'name' => $store_read['name'],
            'series' => $store_read['series'],
            'number' => $store_read['number'],
            'sender_fio' => $user->lastname . ' ' . $user->firstname . ' ' . $user->patronymic,
            'reciver_fio' => null, 
            'send_date' => date('Y-m-d'),
            'accept_date' => null,
            'status' => 'Отправлено',
            'address' => ($store_read['store_id'] == 1) ? 'Алматы' : 'Астана',
        ]);
        $this->edit($id, 3);
        return redirect('/store');
    }

    private function edit($id, $store_id)
    {
        $storage = Storage::find($id);
        $storage->store_id = $store_id;
        $storage->save();
        return redirect('/store');
    }

    private function export_excel($data)
    {
        $file_path= __DIR__.'/../../../storage/files/template.xlsx';
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'id');
        $sheet->setCellValue('B1', 'Наименование');
        $sheet->setCellValue('D1', 'Адрес');
        $sheet->setCellValue('E1', 'Серийный номер	');
        $sheet->setCellValue('F1', 'Инвентарный номер');
        $i = 2;
        foreach ($data as $item){
            $sheet->setCellValue('A'. $i, $item->id);
            $sheet->setCellValue('B'. $i, $item->name);
            $sheet->setCellValue('D'. $i, $item->address);
            $sheet->setCellValue('E'. $i, $item->series);
            $sheet->setCellValue('F'. $i, $item->number);
            $i++;
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save($file_path);
        return $file_path;
    }

    private function first_letter($str, $encoding = "UTF-8") {
        return mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding);
    }
}

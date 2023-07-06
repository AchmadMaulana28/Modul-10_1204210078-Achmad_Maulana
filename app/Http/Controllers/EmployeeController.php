<?php

namespace App\Http\Controllers;


    // Memanggil dan menambahkan perintah dari validation database
    use Illuminate\Support\Facades\Validator;

    // Memanggil dan menambahakan 
    // perintah dari script js swetAlert
    use RealRashid\SweetAlert\Facades\Alert;

    // Memanggil dan menambahkan perintah 
    // dari storage pada facctory database
    use Illuminate\Support\Facades\Storage;

    // Memanggil dan menambahkan perintah 
    // dari microsofy Excell
    use Maatwebsite\Excel\Facades\Excel;

    // Fungsi dari fungsi dibawah ini adalah 
    // mengexport data Employee
    use App\Exports\EmployeesExport;

    // Fungsi dari fungsi perintah dibawah ini adalah 
    // meminta fungsi request
    use Illuminate\Http\Request;

    // Fungsi dari fungsi perintah dibawah ini adalah 
    // memanggil suport dari nama fungsi "STR"
    use Illuminate\Support\Str;

    // Fungsi dari fungsi perintah dibawah ini adalah 
    // memanggil serta menambahkan model dari Employee 
    // dan mengexksport ke dalam file PDF
    use App\Models\Employee;
    use PDF;

    // Fungsi dari fungsi perintah dibawah ini adalah 
    // memanggil serta menambahkan model dari Position 
    use App\models\Position;





/**
 
    *|================================================|
    *|     Class - class berfungsi untuk pemanggilan  | 
    *|  dari EmployeeController                       |
    *|================================================|
    
**/ 

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $pageTitle = 'Employee List';

        confirmDelete();

        // ELOQUENT
        $employees = Employee::all();
        return view('employee.index',[
            'pageTitle' => $pageTitle,
            'employees' => $employees
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //Menambah Barang
        $pageTitle = 'Create Employee';

        // ELOQUENT
        $positions = Position::all();

        return view('employee.create',
        compact(
                'pageTitle',
                'positions'
                )
            );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // Fungsi dari function ini adalah untuk menambahkan
        //  -alert ketika data dimasukan.

        Alert::success('Menambahkan Data Sukses', 'Data Employee Anda Berhasil Ditambahkan.');
        

        //Pesan -> Notification from Emplloyee

        $messages = [
            'required' => ':Attribute harus diisi.',
            'email' => 'Isi :attribute dengan format yang benar',
            'numeric' => 'Isi :attribute dengan angka'
        ];

        $validator = Validator::make($request->all(), [
            'firstName' => 'required',
            'lastName' => 'required',
            'email' => 'required|email',
            'age' => 'required|numeric',
        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Get File
        $file = $request->file('cv');

        if ($file != null) {
            $originalFilename = $file->getClientOriginalName();
            $encryptedFilename = $file->hashName();

            // Store File
            $file->store('public/files');
        }

        // ELOQUENT
        $employee = New Employee;
        $employee->firstname = $request->firstName;
        $employee->lastname = $request->lastName;
        $employee->email = $request->email;
        $employee->age = $request->age;
        $employee->position_id = $request->position;

        if ($file != null) {
            $employee->original_filename = $originalFilename;
            $employee->encrypted_filename = $encryptedFilename;
        }

        $employee->save();

        return redirect()->route('employees.index');
    }

    /**
     * Menampilkan Halaman dari employee.show
     */
    public function show(string $id)
    {

        $pageTitle = 'Employee Detail';

        // ELOQUENT
        $employee = Employee::find($id);

        return view('employee.show', compact('pageTitle', 'employee'));
    }

    /**
     * Mengubah Tampilan pada Bagian Barang
     */

    public function edit(string $id)
    {

        $pageTitle = 'Edit Employee';

        // ELOQUENT
        $positions = Position::all();
        $employee = Employee::find($id);

        return view('employee.edit', compact('pageTitle', 'positions', 'employee'));

    }


    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, string $id)
    {

        // Fungsi dari function ini adalah untuk menambahkan
        //  -alert ketika data berhasil diubah.

        Alert::success('Perubahan Data Berhasil Dilakukan');
        

        // Meng-udpdate barang yang sudah di edit pada bagian edit Employee

        $messages = [
            'required' => ':Attribute harus diisi.',
            'email' => 'Isi :attribute dengan format yang benar',
            'numeric' => 'Isi :attribute dengan angka'
        ];

        $validator = Validator::make($request->all(), [
            'firstName' => 'required',
            'lastName' => 'required',
            'email' => 'required|email',
            'age' => 'required|numeric',
        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }


        // Get File
        $file = $request->file('cv');

        if ($file != null) {
            $originalFilename = $file->getClientOriginalName();
            $encryptedFilename = $file->hashName();

            // Store File
            $file->store('public/files');
        }

         // ELOQUENT
        $employee = New Employee;
        $employee->firstname = $request->firstName;
        $employee->lastname = $request->lastName;
        $employee->email = $request->email;
        $employee->age = $request->age;
        $employee->position_id = $request->position;

        if ($file != null)
            {
                $employee->original_filename = $originalFilename;
                $employee->encrypted_filename = $encryptedFilename;
            }

        $employee->save();

        return redirect()->route('employees.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Fungsi dari function ini adalah untuk menambahkan
        //  -alert ketika data berhasil dihapus.

        Alert::success('Data Anda Berhasil Dihapus');


        //Buton untuk menghapus table barang

        // ELOQUENT
    Employee::find($id)->delete();

    return redirect()->route('employees.index');
    }

    public function downloadFile($employeeId)
    {
        $employee = Employee::find($employeeId);
        $encryptedFilename = 'public/files/'.$employee->encrypted_filename;
        $downloadFilename = Str::lower($employee->firstname.'_'.$employee->lastname.'_cv.pdf');

        if(Storage::exists($encryptedFilename,)){

            return Storage::download($encryptedFilename, $downloadFilename);

        }
    }

    public function getData(Request $request)
    {
        $employees = Employee::with('position');

        if ($request->ajax()) {
            return datatables()->of($employees)
                ->addIndexColumn()
                ->addColumn('actions', function($employee) {
                    return view('employee.actions', compact('employee'));
                })
                ->toJson();
        }
    }

    
    public function exportExcel()
    {
        return Excel::download(new EmployeesExport, 'employees.xlsx');
    }

    public function exportPdf()
    {
        $employees = Employee::all();

        $pdf = PDF::loadView('employee.export_pdf', compact('employees'));

        return $pdf->download('employees.pdf');
    }
}

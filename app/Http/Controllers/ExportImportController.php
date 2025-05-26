<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\UsersExport;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;

class ExportImportController extends Controller
{
    /**
     * Exporte les utilisateurs vers un fichier Excel.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export()
    {
        return Excel::download(new UsersExport, 'utilisateurs.xlsx');
    }

    /**
     * Importe les utilisateurs depuis un fichier Excel.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function import(Request $request)
    {
        // Validation du fichier
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        // Importation des données
        Excel::import(new UsersImport, $request->file('file'));

        // Redirection avec un message de succès
        return redirect()->route('utilisateurs.index')->with('success', 'Users successfully imported.');
    }
}

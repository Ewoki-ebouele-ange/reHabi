<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\CreateRequest;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;


class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function construct(){
        $this->middleware('AdminMiddleware');
    }

    public function index()
    {
        $users = \App\Models\User::paginate(6);
        return view("admin.users.index", [
            'users' => $users,
        ]);
    }

    public function addi() {
        return view("admin.users.add-user");
    }

    public function store(Request $request){
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
            return redirect()->route('admin')->with('success', "L'employé a bien été ajouté");
    }

    public function edit(User $user): View{
        return view("admin.users.edit-user",[
            'user'=> $user
        ]);
    }

    public function update(User $employe, CreateRequest $request){
        $employe->update($request->validated());
        return redirect()->route('user')->with('success', "L'utilisateur a bien été modifié");
    }

    public function showFormImport(): View {
        return view('admin.users.import-user');
    }

    public function showFormExport(): View {
        return view('admin.users.export-user');
    }

    public function import(Request $request) {

        // 1. Validation du fichier uploadé. Extension ".xlsx" autorisée
    	$this->validate($request, [
    		'fichier' => 'bail|required|file|mimes:xlsx'
    	]);
    	// 2. On déplace le fichier uploadé vers le dossier "public" pour le lire
    	$fichier = $request->fichier->move(public_path(), $request->fichier->hashName());
        // 3. $reader : L'instance Spatie\SimpleExcel\SimpleExcelReader
    	$reader = SimpleExcelReader::create($fichier);
        // On récupère le contenu (les lignes) du fichier
        $rows = $reader->getRows();
        // $rows est une Illuminate\Support\LazyCollection

        // 4. Ajouter les colonnes `created_at` et `updated_at` à chaque ligne
        $currentTimestamp = Carbon::now(); // Récupérer le timestamp actuel
        $rows = $rows->map(function ($row) use ($currentTimestamp) {
            $row['created_at'] = $currentTimestamp;
            $row['updated_at'] = $currentTimestamp;
            return $row;
        });

        // 5. On insère toutes les lignes dans la base de données
        $status = User::insert($rows->toArray());
        // Si toutes les lignes sont insérées
    	if ($status) {
            // 6. On supprime le fichier uploadé
            $reader->close(); // On ferme le $reader
            File::delete($fichier);
            // unlink($fichier);
            // 7. Retour vers le formulaire avec un message $msg
            return redirect()->route('employe')->with('success', "Importation reussie");
        } else { abort(500); }

        // On prend 10 lignes
        /*$reader->take(10);

        // On filtre les lignes en s'assurant que l'adresse email est correcte
        //$rows = $reader->getRows()->filter(function ($ligne) {
            return filter_var($ligne['email'], FILTER_VALIDATE_EMAIL) === true;
        });*/
    }

        // Exporter les données
    public function export (Request $request) {

    	// 1. Validation des informations du formulaire
    	$this->validate($request, [ 
    		'name' => 'bail|required|string',
    		'extension' => 'bail|required|string|in:xlsx,csv'
    	]);

    	// 2. Le nom du fichier avec l'extension : .xlsx ou .csv
    	$file_name = $request->name.".".$request->extension;

    	// 3. On récupère données de la table "clients"
    	$employes = User::select("name", "email")->get();

    	// 4. $writer : Objet Spatie\SimpleExcel\SimpleExcelWriter
    	$writer = SimpleExcelWriter::streamDownload($file_name);

 		// 5. On insère toutes les lignes au fichier Excel $file_name
    	$writer->addRows($employes->toArray());

        // 6. Lancer le téléchargement du fichier
        $writer->toBrowser();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $use = User::findOrfail($user->id);
        $use->delete();

        return redirect('/admin/users')->with('success','Utilisateur ' .$use->name. ' supprimé avec succès');
    }

}

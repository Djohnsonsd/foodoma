<?php

namespace App\Http\Controllers;

use App\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Zipper;

class ModuleController extends Controller
{

    public function modules()
    {
        $modules = Module::get();

        return view('admin.modules', array(
            'modules' => $modules,
        ));
    }

    /**
     * @param Request $request
     */
    public function installModule(Request $request)
    {
        // dd($request->all());

        //get the uploaded zip file
        $zipper = new Zipper;
        $zipper = Zipper::make($request->zipfile);
        // dd($files);
        $valid = false;
        foreach ($zipper->listFiles() as $key => $value) {
            if ($value === 'module-manifest.json') {
                $valid = true;
            }
        }

        //module has a manifest.json file, the module is valid
        if ($valid) {
            //get the manifest file content to insert in db
            $manifest = $zipper->getFileContent('module-manifest.json');
            $manifest = json_decode($manifest);
        }

        //check if the module is already installed
        $module = Module::where('name', $manifest->name)->first();

        if ($module) {
            //module already exists, update the module version
            $module->name = $manifest->name;
            $module->description = $manifest->description;
            $module->version = $manifest->version;
            $module->save();
        } else {
            //create new module entry
            $mod = new Module();
            $mod->name = $manifest->name;
            $mod->description = $manifest->description;
            $mod->version = $manifest->version;
            $mod->save();
        }

        //extract to the root directory of the application (base path)
        $zipper->extractTo(base_path());

        //run module installation php file
        // process installation file
        $installationFile = base_path('module-install.php');
        include $installationFile;
        if (main()) {
            dd('Success');
        } else {
            dd('Failed');
        }
        // unlink($installationFile);
        File::delete($installationFile);

        //rediect to getModules route...
    }

    /**
     * @param $id
     */
    public function disableModule($id)
    {
        $module = Module::where('id', $id)->first();

        if ($module) {
            $module->toggleActive()->save();
            return redirect()->back()->with(['success', 'Operation Successful']);
        } else {
            return redirect()->back()->with(['message', 'Something went wrong!!!']);
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Artisan;

class ToolsController extends Controller
{
    public static $base_image_path = 'uploads/images/';

    public static function file_upload($id, $folder_name)
    {
        // getting all of the post data
        $files = array(
            'files' => Input::file('files'),
            'id' => $id,
        );

        // setting up rules
        $rules = array(
            'files' => 'array',
            'files.*' => 'file|mimes:jpeg,bmp,png',//mimes:jpeg,bmp,png and for max size max:10000
            'id' => 'numeric|exists:plates'
        );

        // doing the validation, passing post data, rules and the messages
        $validator = Validator::make($files, $rules);
        if ($validator->fails()) {
            ResponseController::set_errors(true);
            ResponseController::set_messages($validator->errors()->toArray());
            return ResponseController::response('BAD REQUEST');
        }

        $plate = Plate::find($id);
        // validar que el plato pertenezca al usuario actual
        if ($plate->meson->id_user != Auth::user()->id) {
            ResponseController::set_errors(true);
            ResponseController::set_messages("registro restringido");
            return ResponseController::response('BAD REQUEST');
        }

        $id_plate_array = str_split($id, 1);
        $destinationPath = "uploads/$folder_name/"; // upload path
        foreach ($id_plate_array as $n) {
            $destinationPath .= "$n/";
        }

        foreach (Input::file('files') as $file) {

            $extension = $file->getClientOriginalExtension();
            $extension = 'jpg';
            $fileName = date("Ymdhis") . rand(11111, 99999);

            // uploading file to given path
            if (!$file->isValid() || !$file->move($destinationPath, "$fileName.$extension")) {
                //TODO: eliminar las imagenes que fueron almacenadas($uploaded_files)

                ResponseController::set_messages("error guardando el archivo");
                return ResponseController::response('BAD REQUEST');
            }
            $uploaded_files[] = [
                'name' => $fileName,
            ];
            ResponseController::set_messages("imagen $fileName.$extension alamacenada");

            $image = new PlateImage();
            $image->name = $fileName;
            $image->id_plate = $id;
            $image->save();
        }

        ResponseController::set_messages("Todos los archivos fueron guardados en " . $destinationPath);
        return ResponseController::response('OK');
    }

    function migrate($key)
    {
        if ($key == "appSetup_key_gogogo") {
            try {
                Artisan::call('migrate', ['--path' => 'database/migrations', '--force' => true]);
                ResponseController::set_messages('migraciones procesadas');
                Return ResponseController::response('OK');
            } catch (\Exception $e) {
                ResponseController::set_errors(true);
                ResponseController::set_messages("Error procesando las migraciones");
                ResponseController::set_messages($e->getMessage());
            }
        } else {
            App::abort(404);
        }
    }

    function seed($key)
    {
        if ($key == "appSetup_key_gogogo") {
            try {
                Artisan::call('db:seed');
                ResponseController::set_messages('seeds procesados');
                Return ResponseController::response('OK');
            } catch (\Exception $e) {
                ResponseController::set_errors(true);
                ResponseController::set_messages("Error procesando los seeds");
                ResponseController::set_messages($e->getMessage());
            }
        } else {
            App::abort(404);
        }
    }

    public static function base64_to_file($base64_string, $output_file, $directory_path = 'default')
    {
        $dir_array = explode('/', $directory_path);

        $base_directories = ['uploads', 'images'];
        $base_path = '';
        foreach ($base_directories as $directory_name) {
            $base_path .= $directory_name . '/';
            if (!file_exists($base_path)) {
                mkdir($base_path);
            }
        }

        $current_dirname = '';
        foreach ($dir_array as $directory_name) {
            $current_dirname .= $directory_name . '/';
            if (!file_exists($base_path . $current_dirname)) {
                mkdir($base_path . $current_dirname);
            }
        }

        if (!file_exists(self::$base_image_path . $directory_path)) {
            mkdir(self::$base_image_path . $directory_path);
        }

        $path = $base_path . $directory_path;

        // open the output file for writing
        $ifp = fopen(public_path($path . DIRECTORY_SEPARATOR . $output_file), 'wb');

        // split the string on commas
        // $data[ 0 ] == "data:image/png;base64"
        // $data[ 1 ] == <actual base64 string>
        $data = explode(',', $base64_string);

        // we could add validation here with ensuring count( $data ) > 1
        fwrite($ifp, base64_decode($data[1]));

        // clean up the file resource
        fclose($ifp);

        return $output_file;
    }

    public static function rmdir($dirname)
    {
        try {
            Storage::deleteDirectory($dirname);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        return true;
    }

    public static function remove_main_function_names($array)
    {
        $removeKeys = [
            'relation_names',
            'store',
            'update',
            'destroy',
            'middleware',
            'getMiddleware',
            'callAction',
            '__call',
            'authorize',
            'authorizeForUser',
            'parseAbilityAndArguments',
            'normalizeGuessedAbilityName',
            'authorizeResource',
            'resourceAbilityMap',
            'resourceMethodsWithoutModels',
            'dispatch',
            'dispatchNow',
            'validateWith',
            'validate',
            'extractInputFromRules',
            'validateWithBag',
            'getValidationFactory',

            "methods",
            "__construct",
            "bootIfNotBooted",
            "boot",
            "bootTraits",
            "clearBootedModels",
            "fill",
            "forceFill",
            "removeTableFromKey",
            "newInstance",
            "newFromBuilder",
            "on",
            "onWriteConnection",
            "all",
            "with",
            "load",
            "loadMissing",
            "increment",
            "decrement",
            "incrementOrDecrement",
            "incrementOrDecrementAttributeValue",
            "update",
            "push",
            "save",
            "saveOrFail",
            "finishSave",
            "performUpdate",
            "setKeysForSaveQuery",
            "getKeyForSaveQuery",
            "performInsert",
            "insertAndSetId",
            "destroy",
            "delete",
            "forceDelete",
            "performDeleteOnModel",
            "query",
            "newQuery",
            "newQueryWithoutScopes",
            "newQueryWithoutScope",
            "newEloquentBuilder",
            "newBaseQueryBuilder",
            "newCollection",
            "newPivot",
            "toArray",
            "toJson",
            "jsonSerialize",
            "fresh",
            "refresh",
            "replicate",
            "is",
            "isNot",
            "getConnection",
            "getConnectionName",
            "setConnection",
            "resolveConnection",
            "getConnectionResolver",
            "setConnectionResolver",
            "unsetConnectionResolver",
            "getTable",
            "setTable",
            "getKeyName",
            "setKeyName",
            "getQualifiedKeyName",
            "getKeyType",
            "setKeyType",
            "getIncrementing",
            "setIncrementing",
            "getKey",
            "getQueueableId",
            "getQueueableConnection",
            "getRouteKey",
            "getRouteKeyName",
            "resolveRouteBinding",
            "getForeignKey",
            "getPerPage",
            "setPerPage",
            "__get",
            "__set",
            "offsetExists",
            "offsetGet",
            "offsetSet",
            "offsetUnset",
            "__isset",
            "__unset",
            "__call",
            "__callStatic",
            "__toString",
            "__wakeup",
            "attributesToArray",
            "addDateAttributesToArray",
            "addMutatedAttributesToArray",
            "addCastAttributesToArray",
            "getArrayableAttributes",
            "getArrayableAppends",
            "relationsToArray",
            "getArrayableRelations",
            "getArrayableItems",
            "getAttribute",
            "getAttributeValue",
            "getAttributeFromArray",
            "getRelationValue",
            "getRelationshipFromMethod",
            "hasGetMutator",
            "mutateAttribute",
            "mutateAttributeForArray",
            "castAttribute",
            "getCastType",
            "setAttribute",
            "hasSetMutator",
            "isDateAttribute",
            "fillJsonAttribute",
            "getArrayAttributeWithValue",
            "getArrayAttributeByKey",
            "castAttributeAsJson",
            "asJson",
            "fromJson",
            "asDate",
            "asDateTime",
            "isStandardDateFormat",
            "fromDateTime",
            "asTimestamp",
            "serializeDate",
            "getDates",
            "getDateFormat",
            "setDateFormat",
            "hasCast",
            "getCasts",
            "isDateCastable",
            "isJsonCastable",
            "getAttributes",
            "setRawAttributes",
            "getOriginal",
            "only",
            "syncOriginal",
            "syncOriginalAttribute",
            "syncChanges",
            "isDirty",
            "isClean",
            "wasChanged",
            "hasChanges",
            "getDirty",
            "getChanges",
            "originalIsEquivalent",
            "append",
            "setAppends",
            "getMutatedAttributes",
            "cacheMutatedAttributes",
            "getMutatorMethods",
            "observe",
            "getObservableEvents",
            "setObservableEvents",
            "addObservableEvents",
            "removeObservableEvents",
            "registerModelEvent",
            "fireModelEvent",
            "fireCustomModelEvent",
            "filterModelEventResults",
            "retrieved",
            "saving",
            "saved",
            "updating",
            "updated",
            "creating",
            "created",
            "deleting",
            "deleted",
            "flushEventListeners",
            "getEventDispatcher",
            "setEventDispatcher",
            "unsetEventDispatcher",
            "addGlobalScope",
            "hasGlobalScope",
            "getGlobalScope",
            "getGlobalScopes",
            "hasOne",
            "morphOne",
            "belongsTo",
            "morphTo",
            "morphEagerTo",
            "morphInstanceTo",
            "getActualClassNameForMorph",
            "guessBelongsToRelation",
            "hasMany",
            "hasManyThrough",
            "morphMany",
            "belongsToMany",
            "morphToMany",
            "morphedByMany",
            "guessBelongsToManyRelation",
            "joiningTable",
            "touches",
            "touchOwners",
            "getMorphs",
            "getMorphClass",
            "newRelatedInstance",
            "getRelations",
            "getRelation",
            "relationLoaded",
            "setRelation",
            "setRelations",
            "getTouchedRelations",
            "setTouchedRelations",
            "touch",
            "updateTimestamps",
            "setCreatedAt",
            "setUpdatedAt",
            "freshTimestamp",
            "freshTimestampString",
            "usesTimestamps",
            "getCreatedAtColumn",
            "getUpdatedAtColumn",
            "getHidden",
            "setHidden",
            "addHidden",
            "getVisible",
            "setVisible",
            "addVisible",
            "makeVisible",
            "makeHidden",
            "getFillable",
            "fillable",
            "getGuarded",
            "guard",
            "unguard",
            "reguard",
            "isUnguarded",
            "unguarded",
            "isFillable",
            "isGuarded",
            "totallyGuarded",
            "fillableFromArray",
            "bootSoftDeletes",
            "runSoftDelete",
            "restore",
            "trashed",
            "restoring",
            "restored",
            "isForceDeleting",
            "getDeletedAtColumn",
            "getQualifiedDeletedAtColumn"
        ];

        foreach ($removeKeys as $del_val) {

            if (($key = array_search($del_val, $array)) !== false) {
                unset($array[$key]);
            }
        }

        return $array;
    }
}

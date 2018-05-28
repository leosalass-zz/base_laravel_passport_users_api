<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;
use Validator;
use Illuminate\Http\Request;

class GenericController extends Controller
{
    public static function store(Request $request, $model, $response_name = 'object', $counter = false, $counter_name = 'values', $attach = [], $base64_image_field_name = null)
    {

        if (isset($request->id)) {
            $object = $model::find($request->id);
            $counter = false;
            ResponseController::set_status_code('OK');
        } else if ($object = $model::create($request->all())) {
            ResponseController::set_messages("registro creado");
            ResponseController::set_data([$response_name . '_id' => $object->id]);
            ResponseController::set_status_code('CREATED');

        } else {
            ResponseController::set_errors(true);
            ResponseController::set_messages("Error creando el registro");
            ResponseController::set_status_code('BAD REQUEST');
            return ResponseController::response();
        }

        if ($counter) {
            try {
                $object->parent->$counter_name++;
                $object->parent->save();

            } catch (\Exception $e) {

                $model::destroy($object->id);
                ResponseController::set_errors(true);
                ResponseController::set_messages("error sumando el contador");
                ResponseController::set_messages("el registro ha sido eliminado");
                ResponseController::set_messages($e->getMessage());
                ResponseController::set_status_code('BAD REQUEST');
                return ResponseController::response();
            }
        }

        /*
         * Para los attachs que NO tienes datos extra en la tabla pivote:
         * attach[MODEL_RELATION_FUNCTION_NAME][OTHER_FOREIGNKEY][] = OTHER_ID
         * EJ: attach[variation_values][variation_value_id][] = 1
         */
        if (isset($request->attach)) {
            $attach = [];
            $attach_cont = 0;

            foreach (array_keys($request->attach) as $function_name) {
                $ralation_elements = $request['attach'][$function_name];
                foreach ($ralation_elements as $element) {
                    $attach[$attach_cont] = [
                        'function' => $function_name,
                        'relateds' => $element,
                    ];
                }
                $attach_cont++;
            }

            foreach ($attach as $a) {

                $function = $a['function'];
                $relateds = $a['relateds'];

                try {
                    $object->$function()->attach($relateds);
                    ResponseController::set_messages(["attached $function " => $relateds]);
                    ResponseController::set_status_code('CREATED');
                } catch (\Exception $e) {
                    ResponseController::set_errors(true);
                    ResponseController::set_messages(['error attaching ' . $function => $relateds]);
                    ResponseController::set_messages($e->getMessage());
                    ResponseController::set_status_code('BAD REQUEST');
                }
            }

        }

        /*
         * attach[STR_model_relation_name][group_id][related_id][STR_pivot_column_name] = column_value
         *
         * EJ:
         * id:1
         * attachs[products][0][101][variation_id]:1
         * attachs[products][1][101][price]:5700
         * attachs[products][0][101][discount_rate]:0
         *
         * attachs[products][1][101][variation_id]:2
         * attachs[products][1][101][price]:8900
         * attachs[products][1][101][discount_rate]:0
         */
        if (isset($request->attach_data)) {

            foreach (array_keys($request->attach_data) as $function_name) {

                foreach ($request->attach_data[$function_name] as $relateds) {
                    try {
                        $object->$function_name()->attach($relateds);
                        ResponseController::set_messages(["attached $function_name " => $relateds]);
                        ResponseController::set_status_code('CREATED');
                    } catch (\Exception $e) {
                        ResponseController::set_errors(true);
                        ResponseController::set_messages(['error attaching $function_name' => $relateds]);
                        ResponseController::set_messages($relateds);
                        ResponseController::set_messages($e->getMessage());
                        ResponseController::set_status_code('BAD REQUEST');
                    }
                }

            }
        }


        /*
         * detach[relation_name][] = PIVOT_ID
         */
        if (isset($request->detach)) {
            $rel = $request->detach;
            foreach (array_keys($request->detach) as $relation_name) {
                foreach ($request['detach'][$relation_name] as $related) {
                    try {
                        $object->$relation_name()->wherePivot('id', $related)->detach();
                        ResponseController::set_messages("$relation_name -> detached $related");
                    } catch (\Exception $e) {
                        ResponseController::set_errors(true);
                        ResponseController::set_messages("error sumando el contador");
                        ResponseController::set_messages("el registro ha sido eliminado");
                        ResponseController::set_messages($e->getMessage());
                        ResponseController::set_status_code('BAD REQUEST');
                        return ResponseController::response();
                    }
                }
            }
        }

        /*
         * base64_image[TABLE_FIELD_NAME][] = BASE64_IMAGE_FORMAT
         */
        if (isset($request->base64_image)) {

            $keys = array_keys($request->base64_image);

            $model_name_array = explode('\\', $model);
            $model_name = strtolower($model_name_array[1]);
            $directory_path = "$model_name/id/$object->id";
            $extension = 'jpg';

            foreach ($keys as $field_name) {
                foreach ($request->base64_image[$field_name] as $base64_image) {

                    $file_name = date("Ymdhis") . rand(11111, 99999);
                    $full_name = "$file_name.$extension";
                    $url = URL::to('/') . DIRECTORY_SEPARATOR . ToolsController::$base_image_path . $directory_path . DIRECTORY_SEPARATOR . $full_name;

                    try {
                        ToolsController::base64_to_file($base64_image, $full_name, $directory_path);
                        ResponseController::set_messages("la imagen $full_name fue guardada en $directory_path");
                        ResponseController::set_messages("url: $url");
                        $object->$field_name = $url;
                        $object->save();
                        ResponseController::set_messages("registro actualizado con el nombre de la imagen");
                    } catch (\Exception $e) {
                        ResponseController::set_errors(true);
                        ResponseController::set_messages($e->getMessage());
                    }

                    $request['image'] = "$file_name.$extension";

                }
            }
        }

        /*
         * one_to_many[FUNCTION_NAME][MANUAL_OBJECT_ID][FIELD_NAME] = FIELD_VALUE;
         * one_to_many[FUNCTION_NAME][MANUAL_OBJECT_ID][base64_image][FIELD_NAME] = BASE64_IMAGE_FORMAT;
         * otm_ is one to many
         */
        if (isset($request->one_to_many)) {
            $function_names = array_keys($request->one_to_many);

            foreach ($function_names as $function_name) {

                foreach ($request->one_to_many[$function_name] as $index => $request_img) {

                    $field_names = array_keys($request->one_to_many[$function_name][$index]);

                    $fields = [];
                    $otm_base64 = null;

                    foreach ($field_names as $field_name) {

                        if ($field_name != 'base64_image') {
                            $fields[$field_name] = $request->one_to_many[$function_name][$index][$field_name];
                        } else {

                            $otm_img_field_names = array_keys($request->one_to_many[$function_name][$index][$field_name]);

                            foreach ($otm_img_field_names as $otm_img_field_name) {

                                $otm_base64 = [
                                    'field_name' => $otm_img_field_name,
                                    'base64_image' => $request->one_to_many[$function_name][$index][$field_name][$otm_img_field_name],
                                ];
                            }
                        }
                    }

                    try {
                        $otm_Object = $object->$function_name()->create($fields);
                        ResponseController::set_messages("registro de imagen($otm_Object->id) agregado a la bdd");

                        if (!is_null($otm_base64)) {
                            $otm_model = explode('\\', strtolower(get_class($object->$function_name()->getRelated())));
                            $otm_model = $otm_model[1];
                            $directory_path = $otm_model;
                            $directory_path .= "/id/$otm_Object->id";
                            $file_name = date("Ymdhis") . rand(11111, 99999);
                            $extension = 'jpg';
                            $full_name = "$file_name.$extension";

                            ToolsController::base64_to_file($otm_base64['base64_image'], $full_name, $directory_path);

                            $field_name = $otm_base64['field_name'];
                            $full_image = $directory_path . DIRECTORY_SEPARATOR . $full_name;
                            //$otm_Object->$field_name = $full_name;
                            $otm_Object->$field_name = $full_image;
                            $otm_Object->save();
                            //ResponseController::set_messages("imágen $otm_Object->id, almacenada como $full_name en $directory_path");
                            ResponseController::set_messages("imágen $otm_Object->id, almacenada como $full_image");
                        }

                    } catch (\Exception $e) {
                        ResponseController::set_errors(true);
                        ResponseController::set_messages($e->getMessage());
                        ResponseController::set_messages($e->getCode());
                        ResponseController::set_messages($e->getFile());
                        ResponseController::set_messages($e->getLine());
                    }
                }
            }
        }

        return ResponseController::response();
    }

    public
    static function get_only($model, $id, $response_name = 'object', $table, $relations = [])
    {
        $validator = Validator::make(['id' => $id], [
            'id' =>
                [
                    'required',
                    Rule::exists($table, 'id')->where(function ($query) {
                        $query->where('deleted_at', null);
                    })
                ],
        ]);

        if ($validator->fails()) {
            ResponseController::set_errors(true);
            ResponseController::set_messages($validator->errors()->toArray());
            ResponseController::set_status_code('BAD REQUEST');
            return ResponseController::response();
        }


        $object = $model::find($id);
        $relations = $model::relation_names();
        $object['relations'] = $relations;
        foreach ($relations as $relation_name) {
            $object[$relation_name] = $object->$relation_name;
        }

        ResponseController::set_data([$response_name => $object]);
        ResponseController::set_status_code('OK');
        return ResponseController::response();
    }

    public
    static function get($model, $response_name = 'object', $relations = [], $where_array = [])
    {
        if (count($where_array) > 0) {
            $objects_tmp = $model::where($where_array['field'], $where_array['value'])->get();
        } else {
            if(isset($_GET['page'])) {
                $objects_tmp = $model::paginate(2);
            }else{
                $objects_tmp = $model::all();
            }
        }

        $key = "data";
        $objects_tmp['data'] = '';
        unset($objects_tmp[$key]);
        $objects = $objects_tmp;

        //$objects = [];
        /*foreach ($objects_tmp as $index => $object) {
            foreach ($relations as $relation_name) {
                $object[$relation_name] = $object->$relation_name()->get();
            }
            $objects[] = $object;
        }*/

        ResponseController::set_data([$response_name => $objects]);
        ResponseController::set_status_code('OK');
        return ResponseController::response();
    }

    public
    static function update(Request $request, $model, $request_exceptions_array, $response_name = 'object_id')
    {
        try {
            $object = $model::where('id', $request->id);
            $object->update($request->except($request_exceptions_array));
        } catch (\Exception $e) {
            ResponseController::set_errors(true);
            ResponseController::set_messages("error actualizando el registro");
            ResponseController::set_messages($e->getMessage());
            ResponseController::set_status_code('BAD REQUEST');
            return ResponseController::response();
        }

        ResponseController::set_messages("registro actualizado");
        ResponseController::set_status_code('OK');
        return ResponseController::response();
    }

    public
    static function destroy($id, $model, $counter = false, $counter_name = 'values')
    {
        if ($counter) {
            try {
                $object = $model::find($id);
                $object->parent->$counter_name--;
                $object->parent->save();
            } catch (\Exception $e) {
                ResponseController::set_errors(true);
                ResponseController::set_messages("error restando el contador");
                ResponseController::set_messages("el registro no ha sido eliminado");
                ResponseController::set_messages($e->getMessage());
                ResponseController::set_status_code('BAD REQUEST');
                return ResponseController::response();
            }
        }

        try {
            $model::destroy($id);
        } catch (\Exception $e) {
            ResponseController::set_errors(true);
            ResponseController::set_messages("error eliminado el registro");
            ResponseController::set_messages($e->getMessage());
            ResponseController::set_status_code('BAD REQUEST');
            return ResponseController::response();
        }

        ResponseController::set_messages("registro eliminado");
        ResponseController::set_status_code('OK');
        return ResponseController::response();
    }

    public
    static function get_relations($validation_table, $validation_id, $model_name, $function_name)
    {
        $validator = Validator::make(['id' => $validation_id], [
            'id' =>
                [
                    'required',
                    Rule::exists($validation_table, 'id')->where(function ($query) {
                        $query->where('deleted_at', null);
                    })
                ],
        ]);

        if ($validator->fails()) {
            ResponseController::set_errors(true);
            ResponseController::set_messages($validator->errors()->toArray());
            ResponseController::set_status_code('BAD REQUEST');
            return ResponseController::response();
        }

        try {
            $object = $model_name::find($validation_id);
            ResponseController::set_data([$function_name => $object->$function_name()->get()]);
            ResponseController::set_status_code('OK');
            return ResponseController::response();
        } catch (\Exception $e) {
            ResponseController::set_errors(true);
            ResponseController::set_messages("error obteniendo los registros");
            ResponseController::set_messages($e->getMessage());
            ResponseController::set_status_code('BAD REQUEST');
            return ResponseController::response();
        }
    }

    public
    static function get_childs($table, $id, $model, $function_name)
    {

        $validator = Validator::make(['id' => $id], [
            'id' =>
                [
                    'required',
                    Rule::exists($table, 'id')->where(function ($query) {
                        $query->where('deleted_at', null);
                    })
                ],
        ]);

        if ($validator->fails()) {
            ResponseController::set_errors(true);
            ResponseController::set_messages($validator->errors()->toArray());
            ResponseController::set_status_code('BAD REQUEST');
            return ResponseController::response();
        }

        try {
            $object = $model::find($id);
            ResponseController::set_data([$function_name => $object->$function_name()->get()]);
            ResponseController::set_status_code('OK');
            return ResponseController::response();
        } catch (\Exception $e) {
            ResponseController::set_errors(true);
            ResponseController::set_messages("error obteniendo los registros");
            ResponseController::set_messages($e->getMessage());
            ResponseController::set_status_code('BAD REQUEST');
            return ResponseController::response();
        }
    }
}

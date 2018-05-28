<?php
namespace App\Http\Controllers;

class ResponseController extends Controller
{
    private static $status_codes = [
        /*
         * SUCCESS
         */
        'OK' => '200',
        'CREATED' => '201',
        'NON-AUTHORITATIVE INFORMATION' => '203',
        'NO CONTENT' => '204',
        'RESET CONTENT' => '204',
        'PARTIAL CONTENT' => '206',
        'MULTY STATUS' => '207',
        'ALLREADY REPORTED' => '208',

        /*
         * REDIRECTIONS
         */
        'NOT MODIFIED' => '304',

        /*
         * CLIENT ERRORS
         */
        'BAD REQUEST' => '400',
        'UNAUTHORIZED' => '401',
        'FORBIDEN' => '403',
        'NOT FOUD' => '404',
        'METHOD NOT ALLOWED' => '405',
        'NOT ACCEPTABLE' => '406',
        'GONE' => '410',
        'IM A TEAPOT' => '418',
        'TO MANY REQUEST' => '429',

        /*
         * SERVER ERRORS
         */
        'INTERNAL SERVER ERROR' => '500',
        'NOT IMPLEMENTED' => '501',
        'SERVICE UNAVAILABLE' => '503',

        //https://en.wikipedia.org/wiki/List_of_HTTP_status_codes
    ];
    private static $response =
        [
            'errors' => false,
            'success' => false,
            'status_code' => null,
            'messages' => [],
            'data' => []
        ];

    public static function error_codes()
    {
        return self::$status_codes;
    }

    public static function set_errors($errors)
    {
        self::$response['errors'] = $errors;
        self::$response['success'] = false;
    }

    public static function get_errors()
    {
        return self::$response['errors'];
    }

    public static function set_status_code($status_code)
    {
        self::$response['status_code'] = $status_code;
    }

    public static function set_messages($messges)
    {
        self::$response['messages'][] = $messges;
    }

    public static function set_data($data)
    {
        self::$response['data'] = array_merge($data, self::$response['data']);
    }

    public static function get_data()
    {
        self::$response['data'];
    }

    public static function response($status = null)
    {
        if (!self::$response['errors']) {
            self::$response['success'] = true;
        }

        if(is_null($status)){
            $status = self::$response['status_code'];
        }
        return response()->json(self::$response, self::$status_codes[$status]);
    }

    public static function has_errors()
    {
        return self::$response['errors'];
    }

    public static function preprint($data, $die = false)
    {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        if ($die) {
            die;
        }
    }
}

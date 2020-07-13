<?php


namespace App\Http\Utils;


use Google\Cloud\Firestore\FirestoreClient;
use Google\Cloud\Storage\StorageClient;

class Utils
{
    public static function makeResponse($data = [], $message = '')
    {
        return response()->json([
            'message' => $message,
            'data' => $data
        ]);
    }

    public static function genOTP($digits = 3)
    {
        return '' . rand(pow(10, $digits - 1), pow(10, $digits) - 1);
    }

    public static function getNumberOfMonths($date1, $date2) {
        $ts1 = strtotime($date1);
        $ts2 = strtotime($date2);

        $year1 = date('Y', $ts1);
        $year2 = date('Y', $ts2);

        $month1 = date('m', $ts1);
        $month2 = date('m', $ts2);

        $diff = (($year2 - $year1) * 12) + ($month2 - $month1);
        return $diff;
    }

    public static function logActivity($performed, $caused, $log = '', $property = []) {
        activity()
            ->performedOn($performed)
            ->causedBy($caused)
            ->withProperties($property)
            ->log($log);
    }
}

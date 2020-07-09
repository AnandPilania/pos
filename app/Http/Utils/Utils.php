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

    public static function getFirestoreDB() {
        $db = new FirestoreClient([
            'projectId' => 'pick-it-apps',
        ]);
        return $db;
    }

    public static function getCategoryDBRef() {
        $db = self::getFirestoreDB();
        return $db->collection("category");
    }

    public static function getProductDBRef() {
        $db = self::getFirestoreDB();
        return $db->collection("product");
    }

    public static function getStorage() {
        $storage = new StorageClient([
            'keyFilePath' => 'E:\Projects\20190726_flutter_web_Omni\Project\Web\Omni_Web\1.json',
            'projectId' => 'pick-it-apps',
        ]);
        return $storage;
    }

    public static function getBucket() {
        $storage = self::getStorage();
        return $storage->bucket('pick-it-apps.appspot.com');
    }
}

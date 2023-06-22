<?php
namespace app\components;
use Yii;
use yii\base\Component;

class Logger extends Component
{
    public static function createLog($log_type, $description, $natid=null )
    {
        $user_ip = yii::$app->request->getUserIp();
        $date = time();
        $date = \app\components\Jdf::jdate("Y-m-d(H:i)", $date);

        $user = null;
        if(empty($natid))
        {
            $user = yii::$app->user->identity;
            $natid = null;
            if(!empty($user))
            {
                $natid = $user->getNatId();
                $user = $user->getUserName();
            }
        }
        else
        {
            //get name
            $user = \app\models\UserUsers::find()->select(["CONCAT(name, lastname)"])->where(['natid'=>$natid])->scalar();
        }

        //date:ip:user:natid:title:description
        $log = ["date"=>$date, "ip"=>$user_ip, "user"=>$user, "national_id"=>$natid, "type"=>$log_type, "desc"=>$description];
        $log = json_encode($log);
        return $log;
    }

    public static function listLogs()
    {
        $path = dirname(__DIR__); //pc
        $path = $path."/runtime/logs/pc_logs/";
        $logArray = scandir($path);
        $logFileList = [];
        foreach($logArray as $item)
        {
            if(str_contains($item,'.log'))
                array_push($logFileList, $item);
        }

        return $logFileList;
    }

    public function parseLog($fileName, $offset=0, $count=0)
    { // convert log file to array
        // starts line from $offset
        // count 0 means to the end

        $path = dirname(__DIR__); //pc
        $path = $path."/runtime/logs/pc_logs/".$fileName;
        $array = [];
        $max_line = count(file($path));
        if($offset > $max_line) return [];
        if($count == 0) $count = $max_line;

        $file = new \SplFileObject($path);
        $fileIterator = new \LimitIterator($file, $offset, $count);
        foreach($fileIterator as $line)
        {
            $temp = json_decode($line);
            if(empty($temp)) continue;
            $a = [];
            foreach($temp as $key=>$value)
            {
                $a[$key] = $value;
            }
            array_push($array, $a);
        }

        return $array;
    }

    public static function fileInfo($fileName)
    {
        $path = dirname(__DIR__); //pc
        $path = $path."/runtime/logs/pc_logs/".$fileName;
        $fileSize = fileSize($path);
        $fileSize = round($fileSize/1024, 2);
        if($fileSize > 1024)
            $fileSize = round($fileSize/1024, 2)." MB";
        else
            $fileSize .= " kB";
        $count = count(file($path));
        return ['size'=>$fileSize, 'lines'=>$count];
    }
}

?>
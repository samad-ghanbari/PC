<?php
namespace app\components;
use Yii;
use yii\base\Component;

abstract class Array2CSV extends Component
{
    public static function getCSV($array, $outputName)
    {
        if(!str_ends_with($outputName, ".csv"))
            $outputName .= ".csv";

        $header = [];
        $csv = "";
        $i = 0;
        foreach($array as $rec)
        {
            if($i == 0) //get header
            {
                foreach($rec as $key=>$value)
                {
                   array_push($header, $key);
                }
                $i++;
                foreach($header as $h)
                {
                    $csv .= $h;
                    $csv .= ',';
                }
                $csv .="\n";
            }

            foreach($rec as $key=>$value)
            {
                $csv .= $value.', ';
            }
            $csv .= "\n";
        }


        // Redirect output to a client’s web browser (CSV)
        header('Content-Type:  text/csv');
        header('Content-Disposition: attachment;filename="'.$outputName);
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        return $csv;
    }
}
?>
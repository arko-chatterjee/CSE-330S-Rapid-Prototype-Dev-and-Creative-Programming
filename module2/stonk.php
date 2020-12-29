<?php
    include "/home/arko/module2_things/info.php";
    function callStock($ticker){ //From https://support.liveagent.com/061754-How-to-make-REST-calls-in-PHP
        //uses curl to get the stock data from the TDAmeritrade api based on the ticker symbol and using an externally declared api key
        if( preg_match('/^[A-Za-z_-]*$/', (string)$ticker)){
            include "/home/arko/module2_things/info.php";
            $ticker = (string)strtoupper($ticker); //TODO: don't forget to replace api key
            $service_url = sprintf('https://api.tdameritrade.com/v1/marketdata/%s/quotes?apikey=%s',$ticker, $apiKey);
            $curl = curl_init($service_url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            $curl_response = curl_exec($curl);
            if ($curl_response === false) {
                $info = curl_getinfo($curl);
                curl_close($curl);
                die('error occured during curl exec. Additioanl info: ' . var_export($info));
            }
            curl_close($curl);
            $decoded = json_decode($curl_response);
            if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
                die('error occured: ' . $decoded->response->errormessage);
            }
            $js = json_decode($curl_response, true);
            if (empty($js)){
                return 'INVALID';
            }
            $newDesc = str_replace(' - Common Stock', "", $js[$ticker]['description']);
            return array($newDesc, $js[$ticker]['bidPrice']);
        } else {
            return 'INVALID';
        }
    } //end citation

    //each user has own _stonks.txt file which has the list of all stocks that were added
    //this function adds the specified ticker to the user's file
    function removeStock($ticker, $username){
        $ticker = (string)strtoupper($ticker);
        $filePath = sprintf('/home/arko/module2_things/%s_stonks.txt', $username);
        $str = file_get_contents($filePath);
        $removed = sprintf("%s,",$ticker);
        $str=str_replace("$removed", "", $str);
        file_put_contents($filePath, $str);
        return true;
    }

    //this function removes (all instances) of the specified ticker in the file and thus the chart
    function addStock($ticker, $username){
        $ticker = (string)strtoupper($ticker);
        $filePath = sprintf('/home/arko/module2_things/%s_stonks.txt', $username);
        $str = file_get_contents($filePath);
        $str .= sprintf("%s,",$ticker);
        file_put_contents($filePath, $str);
        return true;
    }
?>
<?php
    namespace Dandylion;
    use Curl\Curl;
    class ProductDetails {
        public static function url($url){
            $curl = new Curl();
            $curl->setUserAgent('ProductDetails/7.36.3');
            $curl->get($url);
            $html = $curl->response;
            if($html){
                $dom = new \DOMDocument();
                @$dom->loadHTML($html);
                $xpath = new \DOMXPath($dom);
                $data = $xpath->query("//meta[starts-with(@property, 'og:')]");
                //$data = $xpath->query("//meta");
                $response = [];
                for($i=0;$i<$data->length;$i++){
                    $name = str_replace('og:', '', $data->item($i)->getAttribute('property'));
                    $response[$name] = trim($data->item($i)->getAttribute('content'));
                }
                return (object)$response;
            } else {
                return null;
            }
        }
    }
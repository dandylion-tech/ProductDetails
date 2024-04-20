<?php
    namespace Dandylion;
    use Curl\Curl;
    class ProductDetails {
        public static function url($url){
            $curl = new Curl();
            $curl->setUserAgent('ProductDetails/1');
            $curl->get($url);
            $html = $curl->response;
            if($html){
                $dom = new \DOMDocument();
                @$dom->loadHTML($html);
                $xpath = new \DOMXPath($dom);
                $meta_data = $xpath->query("//meta[starts-with(@property, 'og:')]");
                $data = [];
                for($i=0;$i<$meta_data->length;$i++){
                    $name = str_replace('og:', '', $meta_data->item($i)->getAttribute('property'));
                    $data[$name] = trim($meta_data->item($i)->getAttribute('content'));
                }
                $image_url = $data["image:secure_url"]??$data["image"];
                if(preg_match("/^\/\//",$image_url)){
                    $image_url = "https:".$image_url;
                }
                $price = $data["price:amount"]??null;
                if($price==null){
                    $item = $xpath->query('//li[@itemprop="price"]')->item(0);
                    if($item!==null){
                        $price = $item->getAttribute("content");
                    }
                }

                $response = [
                    "title"=>$data["title"],
                    "link"=>$data["url"],
                    "currency"=>$data["price:currency"],
                    "price"=>$price,
                    "image_url"=>$image_url
                ];
                return (object)$response;
            } else {
                return null;
            }
        }
    }
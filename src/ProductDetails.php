<?php
    namespace Dandylion;
    use Curl\Curl;
    class ProductDetails {
        public static function url($url){
            $curl = new Curl();
            $curl->get($url);
            $html = $curl->response;
            if($html){
                $dom = new \DOMDocument();
                @$dom->loadHTML($html);
                $xpath = new \DOMXPath($dom);
                $title = trim($xpath->query('//title')->item(0)->nodeValue);
                $title = $title;
                $description = $xpath->query('//meta[@name="description"]')->item(0)->getAttribute('content');
                $image = $xpath->query('//meta[@property="og:image"]')->item(0)->getAttribute('content');
                $price = $xpath->query('//meta[@property="og:price:amount"]')->item(0)->getAttribute('content');
                $currency = $xpath->query('//meta[@property="og:price:currency"]')->item(0)->getAttribute('content');
                $response = [
                    'title' => $title,
                    'description' => $description,
                    'image' => $image,
                    'price' => $price,
                    'currency' => $currency
                ];
                return $response;
            } else {
                return null;
            }
        }
    }
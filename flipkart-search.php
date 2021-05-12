<?php
    $keyword = $_GET['key'];
    $keyword = preg_replace('/\s+/', '+', $keyword);
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://affiliate-api.flipkart.net/affiliate/1.0/search.json?query=$keyword&resultCount=5&inStock=true",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "Fk-Affiliate-Id: EnterAffiliateTag",
            "Fk-Affiliate-Token: SecretKey",
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    $details = json_decode($response, true);
    //echo $response;
    $products = $details['products'];
        foreach($products as $product){
            if (isset($product['productBaseInfoV1'])) {
            $productInfo = $product['productBaseInfoV1'];
            $title = $productInfo['title'];
            $image = $productInfo['imageUrls']['400x400'];
            $price = getPrice($productInfo);
            $oldprice = getPriceOld($productInfo);
            $pro_link = $productInfo['productUrl'];
            if($productInfo['productDescription'] != 'NA'){
                $productInfo = $productInfo['productDescription'];
            }else{
                $productInfo = '';
                $keySpecs = $product['categorySpecificInfoV1']['keySpecs'];
                foreach($keySpecs as $keySpec){
                    $productInfo .= "<li>". $keySpec ."</li>";
            }
        }
            $Seller = 'Flipkart';
            echo $title ."<img align='right' src=$image height='120' width='80' /><br>". "Description: " .$productInfo ."<br>". "Deal Price: " .$price ."<br>". "Real Price: " .$oldprice ."<br>". "Product Link: " .$pro_link ."<hr />";
        }
    }

function getPrice($r)
{
    if (!empty($r['flipkartSellingPrice'])) {
        $flipkartSellingPrice = (float) $r['flipkartSellingPrice']['amount'];
    } else {
        $flipkartSellingPrice = 0;
    }

    if (!empty($r['flipkartSpecialPrice'])) {
        $flipkartSpecialPrice = (float) $r['flipkartSpecialPrice']['amount'];
    } else {
        $flipkartSpecialPrice = 0;
    }
    if ($flipkartSellingPrice && $flipkartSellingPrice < $flipkartSpecialPrice) {
        return $flipkartSellingPrice;
    } elseif ($flipkartSpecialPrice) {
        return $flipkartSpecialPrice;
    } else {
        return $flipkartSellingPrice;
    }

}

function getPriceOld($r)
{
    if (empty($r['maximumRetailPrice'])) {
        return 0;
    }

    $maximumRetailPrice = (float) $r['maximumRetailPrice']['amount'];
    if ($maximumRetailPrice && $maximumRetailPrice > getPrice($r)) {
        return $maximumRetailPrice;
    } else {
        return 0;
    }

}
?>

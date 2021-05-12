<?php
$pid = $_GET['id'];
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://affiliate-api.flipkart.net/affiliate/1.0/product.json?id=$pid",
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
    if (isset($details['productBaseInfoV1'])) {
        $product = $details['productBaseInfoV1'];
        $title = $product['title'];
        $pro_link = $product['productUrl'];
        $Seller = 'Flipkart';
        $description = '';
        if($product['productDescription'] != 'NA'){
            $description = $product['productDescription'];
        }
        if(isset($details['categorySpecificInfoV1']['keySpecs'])){
            $keySpecs = $details['categorySpecificInfoV1']['keySpecs'];
            foreach($keySpecs as $keySpec){
                $description .= "<li>". $keySpec ."</li>";
            }
        }
            $productInfo =  $description;

    }
    echo $productInfo;
    ?>
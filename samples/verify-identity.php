<?php
  // We should receive similar data structure:
  // $_POST = [
  //   "givenname" => "John Martin",
  //   "lastname" => "McDonald",
  //   "nationality" => "German",
  //   "passportno" => "01124456",
  //   "usertoken" => "32c04cd0-eef4-4656-85c6-132a02861c53",
  // ];

  print_r($_POST);

  function http_request($method, $url, $data)
  {
    $json_data = json_encode($data);
    // echo "======================================================================\r\n";
    // print_r($url);
    // print_r($data);
    // print_r($json_data);

    $options = array(
      CURLOPT_URL            => $url,
      CURLOPT_POST           => 1,      // make a HTTP POST request
      CURLOPT_POSTFIELDS     => $json_data,  // payload of POST request
      CURLOPT_HTTPHEADER     => array(  // set headers
        "Accept: application/json",
        "Content-Type: application/json; charset=UTF-8",
      ),
      CURLOPT_RETURNTRANSFER => true,   // return response
      CURLOPT_HEADER         => false,  // don't return headers
      CURLOPT_FOLLOWLOCATION => true,   // follow redirects
      CURLOPT_MAXREDIRS      => 10,     // stop after 10 redirects
      CURLOPT_ENCODING       => "",     // handle compressed
      CURLOPT_USERAGENT      => "",     // name of client
      CURLOPT_AUTOREFERER    => true,   // set referrer on redirect
      CURLOPT_CONNECTTIMEOUT => 120,    // time-out on connect
      CURLOPT_TIMEOUT        => 120,    // time-out on response
    );

    $curl = curl_init();
    curl_setopt_array($curl, $options);

    $result = curl_exec($curl);
    curl_close($curl);
    return $result;
  }

  function create_payload($value) {
    $payload = array(
      "partnerId" => "<<< INSERT YOUR PARTNER ID >>>",
      "apiKey" => "<<< INSERT YOUR API KEY >>>",
      "userToken" => $_POST["usertoken"],
      "value" => $value,
    );

    return $payload;
  }

  $API_ROOT = "https://api.authenteq.com";
  $API_CLAIM_ROOT = $API_ROOT."/api/v1/claims";

  $givenname_result = http_request("POST", $API_CLAIM_ROOT.'/givenname', create_payload($_POST["givenname"]));
  var_dump($givenname_result);

  $lastname_result = http_request("POST", $API_CLAIM_ROOT.'/lastname', create_payload($_POST["lastname"]));
  var_dump($lastname_result);

  $nationality_result = http_request("POST", $API_CLAIM_ROOT.'/nationality', create_payload($_POST["nationality"]));
  var_dump($nationality_result);

  $passportno_result = http_request("POST", $API_CLAIM_ROOT.'/passportno', create_payload($_POST["passportno"]));
  var_dump($passportno_result);

?>

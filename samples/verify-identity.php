<?php
  // We should receive similar data structure:
  // $_POST = [
  //   "givenname" => "John Martin",
  //   "lastname" => "McDonald",
  //   "nationality" => "German",
  //   "passportno" => "01124456",
  //   "usertoken" => "b0c0e38a-f3b0-4aba-9f6c-5c67baf64a52",
  // ];

  print_r($_POST);

  function http_request($method, $url, $data = false)
  {
    $curl = curl_init();

    switch ($method)
    {
      case "POST":
          curl_setopt($curl, CURLOPT_POST, 1);
          if ($data)
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
          break;
      case "PUT":
          curl_setopt($curl, CURLOPT_PUT, 1);
          break;
      default:
          if ($data)
              $url = sprintf("%s?%s", $url, http_build_query($data));
    }

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($curl);
    curl_close($curl);
    return $result;
  }

  fucntion create_payload($key, $value) {
    $payload = [
      "partnerId" => "<<< INSERT YOUR PARTNER ID >>>",
      "apiKey" => "<<< INSERT YOUR API KEY >>>",
      "userToken" => $_POST["usertoken"],
      $key => $value,
    ];

    return $payload;
  }

  $API_ROOT = "https://api.authenteq.com";
  $API_VERIFY_CLAIM_ROOT = $API_ROOT + "/api/v1/claims";

  $givenname_result = http_request("POST", $API_VERIFY_CLAIM_ROOT+'/givenname', create_payload("givenname", $_POST["givenname"]));
  var_dump($givenname_result);

  $lastname_result = http_request("POST", $API_VERIFY_CLAIM_ROOT+'/lastname', create_payload("lastname", $_POST["lastname"]));
  var_dump($lastname_result);

  $nationality_result = http_request("POST", $API_VERIFY_CLAIM_ROOT+'/nationality', create_payload("nationality", $_POST["nationality"]));
  var_dump($nationality_result);

  $passportno_result = http_request("POST", $API_VERIFY_CLAIM_ROOT+'/passportno', create_payload("passportno", $_POST["passportno"]));
  var_dump($passportno_result);

?>

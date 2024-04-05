<?php
class AccessToken
{
    public function getAccessToken($clientId, $clientSecret, $rootUrl)
    {
        $authUrl = $rootUrl . "/v1/oauth2/token";
        $authCredentials = base64_encode($clientId . ":" . $clientSecret);
        $authHeaders = [
            "Content-Type: application/x-www-form-urlencoded",
            "Authorization: Basic " . $authCredentials
        ];
        $authData = "grant_type=client_credentials";
        $authCurl = curl_init();
        curl_setopt($authCurl, CURLOPT_URL, $authUrl);
        curl_setopt($authCurl, CURLOPT_POST, true);
        curl_setopt($authCurl, CURLOPT_POSTFIELDS, $authData);
        curl_setopt($authCurl, CURLOPT_HTTPHEADER, $authHeaders);
        curl_setopt($authCurl, CURLOPT_RETURNTRANSFER, true);
        $authResult = curl_exec($authCurl);
        curl_close($authCurl);
        $httpStatus = curl_getinfo($authCurl, CURLINFO_HTTP_CODE);
        $authData = json_decode($authResult, true);
        if ($httpStatus != 200) {
            http_response_code($httpStatus);
            echo json_encode(array(
                "code" => $httpStatus,
                "error" => $authData['error_description']
            ));
            return false;
        }
        return $authData['access_token'];
    }
}

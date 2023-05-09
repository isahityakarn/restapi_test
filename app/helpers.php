<?php
function sah($data = null)
{
    echo '<pre>';
    print_r($data ? $data : 'fine the route');
    echo '</pre>';
    die();
}


function prepareResult($status, $data, $errors, $msg, $status_code, $pagination = [])
{
    return response()->json(['status' => $status, 'data' => $data, 'message' => $msg, 'errors' => $errors, 'pagination' => $pagination], $status_code);
}


function getUser()
{
    return auth('api')->user();
}

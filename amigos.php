<?php 
require_once 'fb/app/start.php';
/* PHP SDK v4.0.0 */
/* make the API call */
$request = new FacebookRequest(
  $session,
  'GET',
  '/me/friends'
);
$response = $request->execute();
$graphObject = $response->getGraphObject();
/* handle the result */

 ?>
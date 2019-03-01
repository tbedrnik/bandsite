<?php
/**
 * Skript pro zjištění údajů o trase
 * Volá se POSTem s proměnnou *trg* = cílová destinace
 * Využívá:
 ** Google Distance Matrix API pro výpočet vzdálenosti
 ** Apify Webcrawler pro zjištění ceny benzínu
 *
 * @package Bandsite Manager
 */
if($_POST) {
  require("../../db/db.php");
  $gkey = getValue("google_key_distance_matrix");
  $target = urlencode($_POST["trg"]);
  $origin = urlencode(getValue("band_origin"));
  $url = "https://maps.google.com/maps/api/distancematrix/json?origins=$origin&destinations=$target&key=$gkey";
  $json = file_get_contents($url);
  $data = json_decode($json);
  if($data->status == "OK"){
    if($data->rows[0]->elements[0]->status == "OK") {
      $dataBenzinu = json_decode(file_get_contents("https://api.apify.com/v1/tQdCtY8dhEiruAi3Z/crawlers/QHmaxgF75GbquoEqj/lastExec/results?token=BeXMyMqpCaDXWM46R5rcWmehD&format=json&simplified=1"));
      $cost = ceil($data->rows[0]->elements[0]->distance->value/1000*getValue("car_consumption")/100*$dataBenzinu[0]->natural95);
      $sendback = array(
        "text"  =>  "The trip is ".$data->rows[0]->elements[0]->distance->text." long and will take ".$data->rows[0]->elements[0]->duration->text." and will cost you $cost,- CZK (one way)",
        "address"  =>  $data->destination_addresses[0],
        "status"   =>  1
      );
    } else {
      $sendback = array(
        "status" => 0
      );
    }
  }
  else {
    $sendback = array(
      "status" => 0
    );
  }
  echo json_encode($sendback);
  die();
}

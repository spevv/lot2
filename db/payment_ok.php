<?
include("PG_Signature.php");

/*
 * Секретный ключ магазина в системе Platron (выдается при подключении магазина к Platron)
 */
$MERCHANT_SECRET_KEY = "xisywonixawypasa";


$arrParams = $_GET;
$thisScriptName = PG_Signature::getOurScriptName();
var_dump($thisScriptName);
if ( !PG_Signature::check($arrParams['pg_sig'], $thisScriptName, $arrParams, $MERCHANT_SECRET_KEY) )
    die("Bad signature");
?>
Спасибо, Ваша оплата принята! Ждите доставки...

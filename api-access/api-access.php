<?php
/* Plugin Name: API Access
 * Plugin URI:  http://example.com/twitter-demo/
 * Description: Retrieves bitcoin data using bitcoin API 
 * Version:     1.0.0
 * Author:      Annysia Dupaya
 * Author URI:  github.com/chinadupaya
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */
$result= 0;
class Demo{ 
    public function get_result(){
        return $this->convert_values();
    }
    private function convert_values(){
        $secretKey = 'add-your-key';
        $publicKey = 'add-your-public-key';
        $timestamp = time();
        $payload = $timestamp . '.' . $publicKey;
        $hash = hash_hmac('sha256', $payload, $secretKey, true);
        $keys = unpack('H*', $hash);
        $hexHash = array_shift($keys);
        $signature = $payload . '.' . $hexHash;
    
        $tickerUrl = "https://apiv2.bitcoinaverage.com/indices/local/ticker/BTCUSD"; // request URL
        $aHTTP = array(
        'http' =>
            array(
            'method'  => 'GET',
            )
        );
        $aHTTP['http']['header']  = "X-Signature: " . $signature;
        $context = stream_context_create($aHTTP);
        $content = file_get_contents($tickerUrl, false, $context);
        $json_content = json_decode($content,true);
        return $json_content['last'];
    }
}
$demo = new Demo();
$result = $demo->get_result();
?>


<script src="http://code.jquery.com/jquery-1.11.2.min.js" type="text/javascript"></script>
<script>
jQuery(document).ready(function(){
    var calculatorContent='<span class="date"><a href="https://bitsusd.com/calculator/" title="Permalink to " rel="bookmark"><time class="entry-date" datetime=""></time></a></span><span class="categories-links"><a href="https://bitsusd.com/category/non-classe/" rel="category tag">Non classé</a></span><span class="author vcard"><a class="url fn n" href="https://bitsusd.com/author/admin3/" title="View all posts by " rel="author"></a></span> </div></header><div class="entry-content"><p style="text-align: center;"><strong>bits, mbtc, satoshis, btc, usd</strong> calculator tool</p><p style="text-align: center;"><span class="st">Find out the current </span>Bitcoin unit <span class="st">value with easy-to-use </span>converter. Please enter an amount to convert.</p><center><input id="valueA" size="20" type="text" name="valueA" min="0" value = "0" oninput="displayResult()" /><select id="currencyA" name="curA" style="height: 34px; background: #FAFAFA;" onchange="displayResult()" ><option value="1000000">Bits</option><option value="1000">mBTC</option><option value="100000000">Satoshis</option><option value="1">BTC</option><option value="USD">USD</option></select> =<input id="result" size="20" type="text"/><select id="currencyB" name="curB" style="height: 34px; background: #FAFAFA;" onchange="displayResult()"><option value="1000000">Bits</option><option value="1000">mBTC</option><option value="100000000">Satoshis</option><option value="1">BTC</option><option selected ="selected" value="USD">USD</option></select></center><p style="text-align: center;"><em>Prices come from <a href="https://bitcoinaverage.com/#USD" target="_blank" rel="noopener noreferrer">Bitcoin Average</a></em></p>'
    jQuery('#site-content').append(calculatorContent); 

});
</script>
<script>
    var phpResult =<?php echo $result; ?>;
    function displayResult() { 
        var value = document.getElementById("valueA").value
        var start = document.getElementById("currencyA").value; 
        var end = document.getElementById("currencyB").value;
        if ((start == "USD") || (end == "USD")){ 
            if (start == "USD"){
                start = phpResult;
            }
            if(end == "USD"){   
                end = phpResult;
            }
        }
        document.getElementById("result").value = value/start*end;
    }
</script>
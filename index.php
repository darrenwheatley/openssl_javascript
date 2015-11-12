<?php
  // Code based on article http://stackoverflow.com/questions/16505963/encrypt-with-cryptico-js-decrypt-with-openssl
  if (isset($_POST["codeEnc"]))
  {
    // Process the input
    $keyPrivate = openssl_get_privatekey(file_get_contents('private2048.key'));
    openssl_private_decrypt(base64_decode($_POST["codeEnc"]), $decrypted, $keyPrivate);
    echo "Error Strong: ".openssl_error_string()."<br>\n";
    echo "Decrypted code: ".$decrypted."<br>\n";
  }
  // Set up the encryption
  $keyCert = openssl_get_publickey(trim(file_get_contents('public2048.pem')));
  $detail = openssl_pkey_get_details($keyCert);
  $n = base64_encode($detail['rsa']['n']);
  $e = bin2hex($detail['rsa']['e']);
?>
  <html>
    <head><title>openssl test</title></head>
    <body>
      <form name="test" id="test" method="post" action="?">
        <input id="code" name="code" type="text" value='Change this string'>
        <input id="codeEnc" name="codeEnc" type="hidden">
        <input id="submit_code" name="submit_code" type="button" value="Go" onClick="submitPage('test');">
      </form>
      <script src="modified_cryptico.js"></script>
      <script>
        function submitPage(formName)
        {
          // I had to change the next line because the output was {$n}|{$e} instead of the public key... php101 but I don't usually code this way. Perhaps this is the issue
          //var publicKey = '{$n}|{$e}';
          var publicKey = '<?php echo $n; ?>|<?php echo $e; ?>';
          var pCode = document.getElementById('code');
          var pCodeEnc = document.getElementById('codeEnc');
          encrypted = cryptico.encrypt(pCode.value, publicKey);
          pCodeEnc.value = encrypted.cipher;
          pCode.value = '';
          document.forms[0].submit();
        }
      </script>
    </body>
  </html>

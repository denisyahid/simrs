/*
 * JavaScript client-side example using jsrsasign
 */

// #########################################################
// #             WARNING   WARNING   WARNING               #
// #########################################################
// #                                                       #
// # This file is intended for demonstration purposes      #
// # only.                                                 #
// #                                                       #
// # It is the SOLE responsibility of YOU, the programmer  #
// # to prevent against unauthorized access to any signing #
// # functions.                                            #
// #                                                       #
// # Organizations that do not protect against un-         #
// # authorized signing will be black-listed to prevent    #
// # software piracy.                                      #
// #                                                       #
// # -QZ Industries, LLC                                   #
// #                                                       #
// #########################################################

/**
 * Depends:
 *     - jsrsasign-latest-all-min.js
 *     - qz-tray.js
 *
 * Steps:
 *
 *     1. Include jsrsasign 8.0.4 into your web page
 *        <script src="https://cdn.rawgit.com/kjur/jsrsasign/c057d3447b194fa0a3fdcea110579454898e093d/jsrsasign-all-min.js"></script>
 *
 *     2. Update the privateKey below with contents from private-key.pem
 *
 *     3. Include this script into your web page
 *        <script src="path/to/sign-message.js"></script>
 *
 *     4. Remove or comment out any other references to "setSignaturePromise"
 */
var privateKey = "-----BEGIN PRIVATE KEY-----\n" +
"MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDNth6L6kyU4/l+\n" +
"rHPSq0RI3TVwKHpqyu+6QFiIs9hI6lX5PJ8JEjMM+9Kbh1bk4UfnWJFtv7DVoGCx\n" +
"Knd3YmaPxqmtUjI83KKlOaz41rxoljKm1hTH+wrL1BynboGlml8layK6h7aMe87H\n" +
"QqpWB9+ateDqMUfortCHoC1GwxYYA/tg+LTkoh7vJK3S4EUbLD3PPTHvgmMRghSc\n" +
"sjEL5AA0ACjItiWkowAt2MtBzN8YLMsHL3nRkia0OTJ0KfcynFRSBYD8Cs0q0ju2\n" +
"+yZosJGNemkDHNRkd6H/V6ae0nzR+PJFJwphNu5gKN6fAlFSZqwkIu1xqhstPoVk\n" +
"tqREGManAgMBAAECggEBAKYAb0oNOttz1ORKGfVJTANYr0ThBVikhyvPvSIjr0e4\n" +
"AsTsDJJvY0748A2d+5sbyMCCEml0JSlfEGgnktx+RbGEaWx6RhwjGAUpM9JixwvH\n" +
"GPzVCVQP27h8ZNsgK/MNTfaOLCpGViOrzUOzIm3mUKUyXFvghNzaJWEs+xx9VZju\n" +
"OunPRAGy8UGs+7V9fIw9Rg1bdi1mvkZu/qPdj5b4ieD1FF8qyuBy61jZcI058asy\n" +
"DGfzftTRNBTbi8Ym0slKRr+LjPYjahP2hmGeauqW1KvzAh/qpyn+OQSkKfXjvYM/\n" +
"OkvEmcbrKxsfms/izHYEDMvmBmeky/WvXNVi4CW+fZECgYEA8FuYsM3gWipMweug\n" +
"EBo8U/leVlXBDwK13vFD44MwY2fgsWWni+Mykup1j8GSAaDlaakb4AWe5qc/1/3f\n" +
"05j2EJSa/dGQCGFNuifJwEyBhsnnTTWo1DAP970S9wwzJozH6SUqoY1R/GG3rdb/\n" +
"R/B7sm7aip4PbeXk5KoP6w82OG0CgYEA2xlOy0PnyrDZwRTBoApUK6elKPzqT3kP\n" +
"5TlxhQijWA231RSV09FE19DOirCdO0B8DsR5oI16LDkpLOnZ6Phk7Q4n5MECv/MY\n" +
"buSvDHAcfxbpdu1eR9er840RuJhpyJJJ/vlm7JxM33P37w7e1kMFzsqnpciK3pA5\n" +
"SkW5Hrh89uMCgYAva8LqTxQOdf4C3HwN8T8pyi9ElVKrpXtAaxVb08w+MIZyOX4e\n" +
"6UpXFg7vt98Ylyfr+sNXJYS+OQ0tjhcU5j7V8pvJrAjgvmHZcnQpjm6Xq0oJP6b5\n" +
"hioAVLtjJnMJDgl7BCwvB3S/eIAGuj5PcTDWeWyAoMKmig7o1myhkbS+2QKBgQCW\n" +
"7rE8D7RvCjOH8l+Me3EOfbemK8zSIKjVlSPhrFiyQQkBzeOE/qW5MowGOLKn7b1I\n" +
"gwrykmO2cU4vNY27Etqb/2N2D4xwZOvRANKh8919o3ADUHPhc+5toiGyE7TTygsV\n" +
"jgPcPbQrPv1ufsT9v0AR+8NPzn3z4lDFHJDxW9AiVwKBgHJjrXrtf0/z22M9Nryo\n" +
"mPH5B8YEgMJZ5OoVhPcWbukRESRZpe1E/MiY4L/WO2YZrSk4ppsmJHLSooqf6yxh\n" +
"9dxZx1hOwEzR0lBf4Zm6kfH3kUKEf80zuRVFWkkwm3YcaYiXYiPyEie3fpIFtfYo\n" +
"QChzVLntqMrdvXS/QhrU4IuQ\n" +
"-----END PRIVATE KEY-----\n";

qz.security.setSignatureAlgorithm("SHA512"); // Since 2.1
qz.security.setSignaturePromise(function(toSign) {
    return function(resolve, reject) {
        try {
            var pk = KEYUTIL.getKey(privateKey);
            var sig = new KJUR.crypto.Signature({"alg": "SHA512withRSA"});  // Use "SHA1withRSA" for QZ Tray 2.0 and older
            sig.init(pk);
            sig.updateString(toSign);
            var hex = sig.sign();
            console.log("DEBUG: \n\n" + stob64(hextorstr(hex)));
            resolve(stob64(hextorstr(hex)));
        } catch (err) {
            console.error(err);
            reject(err);
        }
    };
});

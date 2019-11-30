# Purchase

## Request

### Fields

#### `TPE`

> TPE Virtual Number

- Type: `String`
- Required: `true`
- Validation: `[A-Za-z0-9]{7}`
- Example: `1234567`

#### `version`

> Payment system version

- Type: `String`
- Required: `true`
- Value: `3.0`

#### `date`

> Order date

- Type: `String`
- Required: `true`
- Validation: `DD/MM/YYYY:HH:MM:SS`
- Example: `24/05/2019:10:00:25`

#### `montant`

> Order amount with taxes

- Type: `String`
- Required: `true`
- Validation: `[0-9]+(\.[0-9]{1,2})?[A-Z]{3}`
- Example: `95.25EUR` `42EUR` `56.54USD`

#### `reference`

> Order unique reference

It is recommended to limit reference length to 12 characters to keep it from being cut on the admin panel.

- Type: `String`
- Required: `true`
- Validation: `^[\x20-\x7E]{1,50}$`
- Example: `REF7896543`

#### `lgue`

> Determine iframe language

- Type: `String`
- Required: `true`
- Values: `DE` `EN` `ES` `FR` `IT` `JA` `NL` `PT` `SV`

#### `MAC`

> Seal used to certify payload

- Type: `String`
- Required: `true`
- Validation: `[A-Fa-f]{40}`
- Example: `f97861e0f3e296b7eece2cfd86dc46c43ac88049`

#### `contexte_commande`

> Contains base64 encode order details 

- Type: `String`
- Required: `true`
- (object)
    - `billing`:
        - Type: [billing](#billing)
        - Required: `false`
    - `shipping`:
        - Type: [shipping](#shipping)
        - Required: `false`
    - `shoppingCart`:
        - Type: [shoppingCart](#shoppingcart)
        - Required: `false`
    - `client`:
        - Type: [client](#client)
        - Required: `false`
        
#### `societe`

> The company code

- Type: `String`
- Required: `true`
- Example: `maSociete`

#### `texte-libre`

> Order resume

- Type: `String`
- Required: `false`
- Validation: `+{1,3200}`
- Example: `Livraison relais colis rue des tourterelles`

#### `email`

> Customer email, allow to receive receipt. If not provided automatic redirect is disabled

- Type: `String`
- Required: `false`
- Validation: `email`

#### `url_retour_ok`

> Redirect URL if payment is accepted

- Type: `String`
- Required: `false`
- Validation: `+{1,2048}`
- Example: `http://url.retour.com/ok.cgi?ref=REF001`

#### `url_retour_err`

> Redirect URL if payment is denied

- Type: `String`
- Required: `false`
- Validation: `+{1,2048}`
- Example: `http://url.retour.com/ko.cgi?ref=REF001`

#### `3dsdebrayable`

> Force 3DSecure V1.0 protocol

- Type: `String`
- Required: `false`
- Values:
    - `0`: `false`
    - `1`: `true`
    
#### `ThreeDSecureChallenge`

> Preference for 3DSecure challenge

- Type: `String`
- Required: `false`
- Values:
    - `no_preference`
    - `challenge_preferred`
    - `challenge_mandated`
    - `no_challenge_requested`
    - `no_challenge_requested_strong_authentication`
    - `no_challenge_requested_trusted_third_party`
    - `no_challenge_requested_risk_analysis`
    
#### `libelleMonetique`

> Configure payment shop label

- Type: `String`
- Required: `false`
- Validation: `[A-Z a-z0-9]{1,32}`
- Example: `MonCommerce`

#### `libelleMonetiqueLocalite`

> Configure payment locality label

- Type: `String`
- Required: `false`
- Validation: `[-A-Za-z0-9 ]+\[-A-Z a-z0-9]*\[A-Za-z]{3}`
- Example: `Strasbourg\67000\FRA` `Strasbourg\\FRA`

#### `desactivemoyenpaiement`

> Hide payment methods

- Type: `String`
- Required: `false`
- Values:
    - `1euro`
    - `3xcb`
    - `4xcb`
    - `paypal`
    - `lyfpay`
- Example: `lyfpay`

#### `aliascb`

> Customer card alias name

- Type: `String`
- Required: `false`
- Validation: `[a-zA-Z0-9]{1,64}`
- Example: `monClientRef001`

#### `forcesaisiecb`

> Force card form with customer card

- Type: `String`
- Required: `false`
- Values:
    - `0`: `false`
    - `1`: `true`

#### `protocole`

> Please explain

- Type: `String`
- Required: `false`
- Values:
    - `1euro`
    - `3xcb`
    - `4xcb`
    - `paypal`
    - `lyfpay`
- Example: `lyfpay`

#### Payment by instalment

##### `nbrech`

> The number of instalments

- Type: `Integer`
- Required: `false`
- Values: `2` `3` `4`

##### `dateech[N]`

> The execution dates where `N` is the number of instalments

- Type: `String`
- Required: `false`
- Validation: `DD/MM/YYYY`
- Example: `24/05/2019`

##### `montantech[N]`

> The amounts where `N` is the number of instalments

- Type: `String`
- Required: `false`
- Validation: `[0-9]+(\.[0-9]{1,2})?[A-Z]{3}`
- Example: `33.50EUR` `42EUR`

##### `numero_dossier`

> Please explain

- Type: `String`
- Required: `false`
- Validation: `[A-Z0-9]{1,12}`
- Example: `20150901PRE1`

#### Cofidis payment method (3xCB, 4xCB)

> These details are used to pre-fill cofidis form

##### `civiliteclient`

> Customer civility

- Type: `String`
- Required: `false`
- Values:
    - `MR`
    - `MME`
    - `MLLE`

##### `nomclient`

> Customer last name

- Type: `String`
- Required: `false`
- Validation: `[a-zA-Záàâäãåçéèêëíìîïñóòôöõúùûüýÿ-]{1,50}`
- Example: `Dupont`

##### `prenomclient`

> Customer first name

- Type: `String`
- Required: `false`
- Validation: `[a-zA-Záàâäãåçéèêëíìîïñóòôöõúùûüýÿ-]{1,50}`
- Example: `Thomas`

##### `adresseclient`

> Customer address

- Type: `String`
- Required: `false`
- Validation: `.{1,100}`
- Example: `20 rue des champs`

##### `complementadresseclient`

> Customer address

- Type: `String`
- Required: `false`
- Validation: `.{1,50}`
- Example: `Appartement B`

##### `codepostalclient`

> Customer postal code

- Type: `String`
- Required: `false`
- Validation: `[a-zA-Z0-9]{1,10}`
- Example: `67200`

##### `villeclient`

> Customer city

- Type: `String`
- Required: `false`
- Validation: `[a-zA-Z]{1,50}`
- Example: `Strasbourg`

##### `paysclient`

> Customer country

- Type: `String`
- Required: `false`
- Validation: `[a-zA-Z]{2}`
- Example: `FR`

##### `telephonefixeclient`

> Customer home phone number

- Type: `String`
- Required: `false`
- Validation: `[a-zA-Z]{2}`
- Example: `0312345678`

##### `telephonemobileclient`

> Customer mobile phone number

- Type: `String`
- Required: `false`
- Validation: `[a-zA-Z]{2}`
- Example: `0312345678`

##### `departementnaissanceclient`

> Customer birth department number

- Type: `String`
- Required: `false`
- Validation: `[0-9]{2,3}`
- Example: `67`

##### `datenaissanceclient`

> Customer birth date

- Type: `String`
- Required: `false`
- Validation: `[0-9]{8}` `YYYYMMDD`
- Example: `19900103`

##### `prescore`

> Cofidis pre score

- Type: `String`
- Required: `false`
- Validation: `[0-9]`
- Example: `1234567`

## Response

### Fields

#### `code-retour`

> Payment result

- Type: `String`
- Required: `true`
- Values:
    - `payetest`
    - `paiement`
    - `annulation`
    - `paiement_pf[N]`
    - `Annulation_pf[N]`
    
#### `MAC`

> Seal used to certify payload

- Type: `String`
- Required: `true`
- Validation: `[A-F]{40}`
- Example: `f97861e0f3e296b7eece2cfd86dc46c43ac88049`

#### `TPE`

> TPE Virtual Number

- Type: `String`
- Required: `true`
- Validation: `[A-Za-z0-9]{7}`
- Example: `1234567`

#### `montant`

> Order amount with taxes

- Type: `String`
- Required: `true`
- Validation: `[0-9]+(\.[0-9]{1,2})?[A-Z]{3}`
- Example: `95.25EUR` `42EUR` `56.54USD`

#### `reference`

> Order unique reference

- Type: `String`
- Required: `true`
- Validation: `^[\x20-\x7E]{1,50}$`
- Example: `REF7896543`

#### `texte-libre`

> Request field `texte-libre`

- Type: `String`
- Required: `true`
- Validation: `.{1.3200}`
- Example: `Livraison relais colis rue des tourterelles`

#### `date`

> Payment authorization date

- Type: `String`
- Required: `true`
- Validation: `DD/MM/YYYY_a_HH:MM:SS`
- Example: `24/05/2019_a_10:00:25`

#### `cvx`

> Indicates whether CVC code has been submitted or not

- Type: `String`
- Required: `true`
- Values:
    - `oui`: `true`
    - `non`: `false`
    
#### `vld`

> Expiring date of the payment card

- Type: `String`
- Required: `true`
- Validation: `MMYY`
- Example: `1019`

#### `brand`

> Payment card brand

- Type: `String`
- Required: `true`
- Values:
    - `AM`: American Express
    - `CB`: GIE CB
    - `MC`: Mastercard
    - `VI`: Visa
    - `na`: Not available (default)

#### `numauto`

> Authorization number provided by customer bank's if payment has been authorized

- Type: `String`
- Required: `true`
- Example: `000002`

#### `authentification`

> Base64 encoded JSON authentication details

- Type: [authentification](#authentification)
- Required: `true`

#### `usage`

> Card type

- Type: `String`
- Required: `true`
- Values:
    - `credit`
    - `debit`
    - `prepaye`
    - `inconnu`
    
#### `typecompte`

> Account type associated to card

- Type: `String`
- Required: `true`
- Values:
    - `particulier`
    - `commercial`
    - `inconnu`
    
#### `ecard`

> Virtual card

- Type: `String`
- Required: `true`
- Values:
    - `oui`: `true`
    - `non`: `false`
    
#### `motifrefus`

> Reason of denied payment

- Type: `String`
- Required: `true`
- Values:
    - `Appel Phonie`
    - `Refus`
    - `Interdit`
    - `filtrage`
    - `scoring`
    - `3DSecure`

#### `originecb`

> Bank country of card

- Type: `String`
- Required: `true`
- Validation: [ISO 3166-1](https://en.wikipedia.org/wiki/ISO_3166-1)
- Example: `FR`

#### `bincb`

> Bank BIN code of card

- Type: `String`
- Required: `true`

#### `hpancb`

> HMAC-SHA1 of card number

- Type: `String`
- Required: `true`

#### `ipclient`

> IP address of customer

- Type: `String`
- Required: `true`

#### `montantech`

> The amount of payment if payment by instalment

- Type: `String`
- Required: `true`

#### `numero_dossier`

> Please explain

- Type: `String`
- Required: `true`
- Validation: `[A-Z0-9]{1,12}`
- Example: `20150901PRE1`

#### `typefacture`

> Please explain

- Type: `String`
- Required: `true` if `TPE` is in pre-authorized mode
- Value: `preauto`

#### `filtragecause`

> Detailed reason of denied payment if `motifrefus` is `filtrage`

- Type: `Interger`
- Values: `1` to `16`

#### `filtragevaleur`

> Value causing the denial of payment if `motifrefus` is `filtrage`

- Type: `String`

#### `filtrage_etat`

> Indicates if `filtrage` is in mode `information`

- Type: `String`
- Value: `information`

#### `cbenregistree`

> Indicates if card has been saved as an alias

- Type: `String`
- Values:
    - `0`: `false`
    - `1`: `true`
    
#### `cbmasquee`

> Card mask

- Type: `String`
- Example: `123456******1234`

#### `modepaiement`

> Payment method

- Type: `String`
- Values:
    - `CB`
    - `paypal`
    - `1euro`
    - `3xcb`
    - `4xcb`
    - `audiotel`

## Models

### `billing`

> Billing address details

- `civility`:
    - Type: `String`
    - Required: `false`
    - Validation: `[A-Za-z]{1,32}`
    - Example: `M` `Mme`
- `name`:
    - Type: `String`
    - Required: `false`
    - Validation: `+{1,45}`
- `firstName`:
    - Type: `String`
    - Required: `false`
    - Validation: `+{1,45}`
- `lastName`:
    - Type: `String`
    - Required: `false`
    - Validation: `+{1,45}`
- `middleName`:
    - Type: `String`
    - Required: `false`
    - Validation: `+{1,150}`
- `address`:
    - Type: `String`
    - Required: `false`
    - Validation: `+{1,255}`
- `addressLine1`:
    - Type: `String`
    - Required: `true`
    - Validation: `+{1,50}`
- `addressLine2`:
    - Type: `String`
    - Required: `false`
    - Validation: `+{1,50}`
- `addressLine3`:
    - Type: `String`
    - Required: `false`
    - Validation: `+{1,50}`
- `city`:
    - Type: `String`
    - Required: `true`
    - Validation: `+{1,50}`
- `postalCode`:
    - Type: `String`
    - Required: `true`
    - Validation: `+{1,10}`
- `country`:
    - Type: `String`
    - Required: `true`
    - Validation: [ISO 3166-1 alpha-2](https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2)
- `stateOrProvince`:
    - Type: `String`
    - Required: `true` if country is `US` or `CA`
    - Validation: [ISO 3166-2:US](https://fr.wikipedia.org/wiki/ISO_3166-2:US) [ISO 3166-2:CA](https://fr.wikipedia.org/wiki/ISO_3166-2:CA)
- `countrySubdivision`: 
    - Type: `String`
    - Required: `false`
    - Validation: [ISO 3166-2](https://en.wikipedia.org/wiki/ISO_3166-2)
- `email`:
    - Type: `String`
    - Required: `false`
    - Validation: `email`
- `phone`:
    - Type: `String`
    - Required: `false`
    - Validation: `\+[0-9]{2}-[0-9]{1,18}`
    - Example: `+33-612345678`
- `mobilePhone`:
    - Type: `String`
    - Required: `false`
    - Validation: `\+[0-9]{2}-[0-9]{1,18}`
    - Example: `+33-612345678`
- `homePhone`:
    - Type: `String`
    - Required: `false`
    - Validation: `\+[0-9]{2}-[0-9]{1,18}`
    - Example: `+33-612345678`
- `workPhone`:
    - Type: `String`
    - Required: `false`
    - Validation: `\+[0-9]{2}-[0-9]{1,18}`
    - Example: `+33-612345678`
    
### `shipping`

> Shipping address details

- `civility`:
    - Type: `String`
    - Required: `false`
    - Validation: `[A-Za-z]{1,32}`
    - Example: `M` `Mme`
- `name`:
    - Type: `String`
    - Required: `false`
    - Validation: `+{1,45}`
- `firstName`:
    - Type: `String`
    - Required: `false`
    - Validation: `+{1,45}`
- `lastName`:
    - Type: `String`
    - Required: `false`
    - Validation: `+{1,45}`
- `middleName`:
    - Type: `String`
    - Required: `false`
    - Validation: `+{1,150}`
- `address`:
    - Type: `String`
    - Required: `false`
    - Validation: `+{1,255}`
- `addressLine1`:
    - Type: `String`
    - Required: `true`
    - Validation: `+{1,50}`
- `addressLine2`:
    - Type: `String`
    - Required: `false`
    - Validation: `+{1,50}`
- `addressLine3`:
    - Type: `String`
    - Required: `false`
    - Validation: `+{1,50}`
- `city`:
    - Type: `String`
    - Required: `true`
    - Validation: `+{1,50}`
- `postalCode`:
    - Type: `String`
    - Required: `true`
    - Validation: `+{1,10}`
- `country`:
    - Type: `String`
    - Required: `true`
    - Validation: [ISO 3166-1 alpha-2](https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2)
- `stateOrProvince`:
    - Type: `String`
    - Required: `true` if country is `US` or `CA`
    - Validation: [ISO 3166-2:US](https://fr.wikipedia.org/wiki/ISO_3166-2:US) [ISO 3166-2:CA](https://fr.wikipedia.org/wiki/ISO_3166-2:CA)
- `countrySubdivision`: 
    - Type: `String`
    - Required: `false`
    - Validation: [ISO 3166-2](https://en.wikipedia.org/wiki/ISO_3166-2)
- `email`:
    - Type: `String`
    - Required: `false`
    - Validation: `email`
- `phone`:
    - Type: `String`
    - Required: `false`
    - Validation: `\+[0-9]{2}-[0-9]{1,18}`
    - Example: `+33-612345678`
- `shipIndicator`:
    - Type: `String`
    - Required: `false`
    - Values:
        - `digital_goods`
        - `travel_and_event`
        - `billing_address`
        - `verified_address`
        - `another_address`
        - `pick-up`
        - `other`
- `deliveryTimeframe`:
    - Type: `String`
    - Required: `false`
    - Values:
        - `same_day`
        - `two_day`
        - `three_day`
        - `long`
        - `other`
        - `none`
- `firstUseDate`:
    - Type: `String`
    - Required: `false`
    - Validation: `YYYY-MM-DD`
- `firstUseDate`:
    - Type: `String`
    - Required: `false`
    - Validation: `YYYY-MM-DD`
- `matchBillingAddress`:
    - Type: `Boolean`
    - Required: `false`

### `shoppingCart`

> Customer cart details

- `giftCardAmount`:
    - Type: `Integer`
    - Required: `false`
    - Validation: `[0-9]{1,12}`
    - Example: `2220`
- `giftCardCount`:
    - Type: `Integer`
    - Required: `false`
    - Validation: `[0-9]{1,2}`
    - Example: `2` `11`
- `giftCardCurrency`:
    - Type: `String`
    - Required: `false`
    - Validation: [ISO 4217](https://en.wikipedia.org/wiki/ISO_4217)
- `preOrderDate`:
    - Type: `String`
    - Required: `false`
    - Validation: `YYYY-MM-DD`
- `preorderIndicator`:
    - Type: `Boolean`
    - Required: `false`
- `reorderIndicator`:
    - Type: `Boolean`
    - Required: `false`
- `shoppingCartItems`: (array)
    - (object)
        - `name`: *undocumented*
            - Type: `String`
            - Required: `false`
        - `description`:
            - Type: `String`
            - Required: `false`
            - Validation: `+{1,2048}`
        - `productCode`:
            - Type: `String`
            - Required: `false`
            - Values:
                - `adult_content`
                - `coupon`
                - `default`
                - `electronic_good`
                - `electronic_software`
                - `gift_certificate`
                - `handling_only`
                - `service`
                - `shipping_and_handling`
                - `shipping_only`
                - `subscription`
        - `imageURL`:
            - Type: `String`
            - Required: `false`
            - Validation: `+{1,2000}`
        - `unitPrice`:
            - Type: `Integer`
            - Required: `true`
            - Validation: `[0-9]{1,12}`
        - `quantity`:
            - Type: `Integer`
            - Required: `true`
            - Validation: `[0-9]{1,12}`
        - `productSKU`:
            - Type: `String`
            - Required: `false`
            - Validation: `+{1,255}`
        - `productRisk`:
            - Type: `String`
            - Required: `false`
            - Values:
                - `low`
                - `normal`
                - `high`

### `client`

> Customer details

- `civility`:
    - Type: `String`
    - Required: `false`
    - Validation: `[A-Za-z]{1,32}`
    - Example: `M` `Mme`
- `name`:
    - Type: `String`
    - Required: `false`
    - Validation: `+{1,45}`
- `firstName`:
    - Type: `String`
    - Required: `false`
    - Validation: `+{1,45}`
- `lastName`:
    - Type: `String`
    - Required: `false`
    - Validation: `+{1,45}`
- `middleName`:
    - Type: `String`
    - Required: `false`
    - Validation: `+{1,150}`
- `address`:
    - Type: `String`
    - Required: `false`
    - Validation: `+{1,255}`
- `addressLine1`:
    - Type: `String`
    - Required: `false`
    - Validation: `+{1,50}`
- `addressLine2`:
    - Type: `String`
    - Required: `false`
    - Validation: `+{1,50}`
- `addressLine3`:
    - Type: `String`
    - Required: `false`
    - Validation: `+{1,50}`
- `city`:
    - Type: `String`
    - Required: `false`
    - Validation: `+{1,50}`
- `postalCode`:
    - Type: `String`
    - Required: `false`
    - Validation: `+{1,10}`
- `country`:
    - Type: `String`
    - Required: `false`
    - Validation: [ISO 3166-1 alpha-2](https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2)
- `stateOrProvince`:
    - Type: `String`
    - Required: `false` if country is `US` or `CA`
    - Validation: [ISO 3166-2:US](https://fr.wikipedia.org/wiki/ISO_3166-2:US) [ISO 3166-2:CA](https://fr.wikipedia.org/wiki/ISO_3166-2:CA)
- `countrySubdivision`: 
    - Type: `String`
    - Required: `false`
    - Validation: [ISO 3166-2](https://en.wikipedia.org/wiki/ISO_3166-2)
- `email`:
    - Type: `String`
    - Required: `false`
    - Validation: `email`
- `birthLastName`:
    - Type: `String`
    - Required: `false`
    - Validation: `+{1,45}`
- `birthCity`:
    - Type: `String`
    - Required: `false`
    - Validation: `+{1,50}`
- `birthPostalCode`:
    - Type: `String`
    - Required: `false`
    - Validation: `+{1,10}`
- `birthCountry`:
    - Type: `String`
    - Required: `false`
    - Validation: [ISO 3166-1 alpha-2](https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2)
- `birthStateOrProvince`:
    - Type: `String`
    - Required: `false` if country is `US` or `CA`
    - Validation: [ISO 3166-2:US](https://fr.wikipedia.org/wiki/ISO_3166-2:US) [ISO 3166-2:CA](https://fr.wikipedia.org/wiki/ISO_3166-2:CA)
- `birthCountrySubdivision`:
    - Type: `String`
    - Required: `false`
    - Validation: [ISO 3166-2](https://en.wikipedia.org/wiki/ISO_3166-2)
- `birthdate`:
    - Type: `String`
    - Required: `false`
    - Validation: `YYYY-MM-DD`
- `phone`:
    - Type: `String`
    - Required: `false`
    - Validation: `\+[0-9]{2}-[0-9]{1,18}`
    - Example: `+33-612345678`
- `nationalIDNumber`:
    - Type: `String`
    - Required: `false`
    - Validation: `+{1,255}`
- `suspiciousAccountActivity`:
    - Type: `Boolean`
    - Required: `false`
- `authenticationMethod`:
    - Type: `String`
    - Required: `false`
    - Values:
        - `guest`
        - `own_credentials`
        - `federated_id`
        - `issuer_credentials`
        - `third_party_authentication`
        - `fido`
- `authenticationTimestamp`:
    - Type: `String`
    - Required: `false`
    - Validation: `YYYY-MM-DDTHH:mm:SSZ`
- `priorAuthenticationMethod`:
    - Type: `String`
    - Required: `false`
    - Values:
        - `frictionless`
        - `challenge`
        - `AVS_verified`
        - `other`
- `priorAuthenticationTimestamp`:
    - Type: `String`
    - Required: `false`
    - Validation: `YYYY-MM-DDTHH:mm:SSZ`
- `paymentMeanAge`:
    - Type: `String`
    - Required: `false`
    - Validation: `YYYY-MM-DD`
- `lastYearTransactions`:
    - Type: `Integer`
    - Required: `false`
- `last24HoursTransactions`:
    - Type: `Integer`
    - Required: `false`
- `addCardNbLast24Hours`:
    - Type: `Integer`
    - Required: `false`
- `last6MonthsPurchase`:
    - Type: `Integer`
    - Required: `false`
- `lastPasswordChange`:
    - Type: `Integer`
    - Required: `false`
- `accountAge`:
    - Type: `String`
    - Required: `false`
    - Validation: `YYYY-MM-DD`
- `lastAccountModification`:
    - Type: `String`
    - Required: `false`
    - Validation: `YYYY-MM-DD`

### `authentification`

- `status`:
    - Type: `String`
    - Required: `true`
    - Values:
        - `authenticated`
        - `authentication_not_performed`
        - `not_authenticated`
        - `authentication_rejected`
        - `authentication_attempted`
        - `not_enrolled`
        - `disabled`
- `protocol`:
    - Type: `String`
    - Required: `true`
    - Value: `3DSecure`
- `version`:
    - Type: `String`
    - Required: `true`
    - Values:
        - `1.0.2`
        - `2.1.0`
- `details`: (object)
    - `liabilityShift`:
        - Type: `String`
        - Required: `true` if `version` is `^2.0`
        - Values:
            - `Y`
            - `N`
            - `NA`
    - `VERes`:
        - Type: `String`
        - Required: `true` if `version` is `^1.0`
        - Values:
            - `Y`
            - `N`
            - `U`
    - `PARes`:
        - Type: `String`
        - Required: `true` if `version` is `^1.0`
        - Values:
            - `Y`
            - `N`
            - `U`
            - `A`
    - `ARes`:
        - Type: `String`
        - Required: `true` if `version` is `^2.0`
        - Values:
            - `Y`
            - `R`
            - `C`
            - `U`
            - `A`
            - `N`
    - `CRes`:
        - Type: `String`
        - Required: `true` if `version` is `^2.0`
        - Values:
            - `Y`
            - `N`
    - `merchantPreference`:
        - Type: `String`
        - Required: `true`
        - Values:
            - `no_preference`
            - `challenge_preferred`
            - `challenge_mandated`
            - `no_challenge_requested`
            - `no_challenge_requested_strong_authentication`
            - `no_challenge_requested_trusted_third_party`
            - `no_challenge_requested_risk_analysis`
    - `transactionID`:
        - Type: `String`
        - Required: `true` if `version` is `^2.0`
        - Validation: `UUID`
    - `status3DS`:
        - Type: `Integer`
        - Required: `true` if `version` is `^1.0`
        - Values:
            - `-1`
            - `1`
            - `4`
    - `disablingReason`:
        - Type: `String`
        - Required: `true` if `version` is `^1.0`
        - Values:
            - `commercant`
            - `seuilnonatteint`
            - `scoring`
        
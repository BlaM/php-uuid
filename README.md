# php-uuid
Random and Pseudo-Random UUIDs

Generate [RFC4122|https://www.ietf.org/rfc/rfc4122.txt] Version 4 (random) UUIDs.

uuid() can return "not really random" UUIDs which - however - don't follow the rules of other types in RFC4122:

    define ('UUID_MODE_RANDOM',             0);
    define ('UUID_MODE_TIMED',              1);
    define ('UUID_MODE_TYPED',              2);
    define ('UUID_MODE_USE_REMOTE_ADDR',    4);


    $u = uuid(UUID_MODE_TIMED);

Will include the unix timestamp (ms) in the first 8 octets - however in another order than specified in the RFC. RFC starts with least significant bytes, this function starts with highes significant bytes, resulting in "always increasing" ids. That means they will appear in the order created when inserted into database.


    $u = uuid(UUID_MODE_TYPED, $type);

Will overwrite the first 2 hex octets with the value specified in $type (value between 0 and 255).


    $u = uuid(UUID_MODE_USE_REMOTE_ADDR);

Will put the remote addess ($_SERVER['REMOTE_ADDR'] - IPv4 address) into the "node" part of the UUID:
00000000-0000-0000-0000-111111110000
                        

Feel free to combine the flags

    $u = uuid(UUID_MODE_TIMED | UUID_MODE_USE_REMOTE_ADDR);

Will return a uuid with both timestamp and remote address in the UUID;




    $u = str2uuid('hello there');

Uses a sha1 checksum to return a UUID. Will always be the same for the same string.


    if (!is_uuid($u)) {
        echo 'something is wrong';
    } 

Test if a string is formatted like an UUID.

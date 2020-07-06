if ! [ -f "config/jwt/private.pem" ]; then
    rm -rf config/jwt
    mkdir -p config/jwt
    openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096 -pass pass:05c14b1e
    openssl pkey -passin pass:05c14b1e -in config/jwt/private.pem -out config/jwt/public.pem -pubout
    echo "jwt keys generated"
else 
    echo "jwt keys already exist"
fi
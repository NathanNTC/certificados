<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Aliases
    |--------------------------------------------------------------------------
    |
    | Aqui você pode definir aliases que o Laravel usará em sua aplicação.
    |
    */
    'aliases' => [
        'Pdf' => Barryvdh\DomPDF\Facade\Pdf::class,
        'QrCode' => SimpleSoftwareIO\QrCode\Facades\QrCode::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | Este valor é o nome da sua aplicação. Este valor é usado quando o 
    | framework precisa do nome da aplicação, como em notificações.
    |
    */
    'name' => env('APP_NAME', 'Laravel'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | Aqui você pode definir qual o ambiente em que sua aplicação está sendo
    | executada. Isso pode afetar como os serviços são configurados.
    |
    */
    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | Quando o modo de depuração está ativado, mensagens detalhadas de erro
    | serão mostradas. Desative em produção!
    |
    */
    'debug' => (bool) env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | Este valor define a URL base para sua aplicação.
    |
    */
    'url' => env('APP_URL', 'http://localhost'),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Aqui você pode definir o fuso horário padrão.
    |
    */
    'timezone' => env('APP_TIMEZONE', 'UTC'),

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | O locale define a configuração de idioma padrão usada pela aplicação.
    |
    */
    'locale' => env('APP_LOCALE', 'en'),

    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),

    /*
    |--------------------------------------------------------------------------
    | Faker Locale
    |--------------------------------------------------------------------------
    |
    | Este valor define o idioma para os dados gerados pelo Faker.
    |
    */
    'faker_locale' => env('APP_FAKER_LOCALE', 'en_US'),

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | Esta chave é usada pelo serviço de criptografia.
    |
    */
    'cipher' => 'AES-256-CBC',

    'key' => env('APP_KEY'),

    'previous_keys' => array_filter(
        explode(',', env('APP_PREVIOUS_KEYS', ''))
    ),

    /*
    |--------------------------------------------------------------------------
    | Maintenance Mode Driver
    |--------------------------------------------------------------------------
    |
    | Aqui você pode configurar o driver e o local para o modo de manutenção.
    |
    */
    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store' => env('APP_MAINTENANCE_STORE', 'database'),
    ],
];

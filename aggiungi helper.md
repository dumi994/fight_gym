1. Crea il file helper in app/Helpers/Helper.php

2. Definisci la funzione

3. Registra il file in composer.json

{
"autoload": {
"files": [
"app/Helpers/MyHelper.php"
]
}
}

4. Composer dump-autoload

5. Aggiungi il file helper nel file AppServiceProvider:
   Apri app/Providers/AppServiceProvider.php e aggiungi il file helper nel metodo boot:
   public function boot()
   {
   require_once base_path('app/Helpers/MyHelper.php');
   }

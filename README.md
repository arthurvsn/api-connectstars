# api-connectstars
API for website connectstars.

# Instruções

Para iniciar o projeto, tenha PHP 5.6 ou superior instalado.
Tenha também o laravel a partir da versão 5.4.

<ul>
  <li>
    Baixe o projeto
  </li>
  <li>
    Crie o arquivo .env de acordo com o .evn.example e adicione as informações do seu banco de dados
  </li>
  <li>
    Rode o comando para gerar a chave da sua aplicação:
    <pre>$ php artisan key:generate</pre>
  </li>
  <li>
    Rode o comando para gerar as tabelas no banco:
    <pre>$ php artisan migrate</pre>
  </li>
  <li>
    Para iniciar o servidor rode o comando:
    <pre>$ php artisan serve</pre>
  </li>
</ul>

# Para saber as rotas da aplicação
<pre>$ php artisan route:list</pre>

Author: Arthur Vinicius & Denner Parreiras

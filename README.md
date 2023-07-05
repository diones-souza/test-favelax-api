# Teste Prático - Desenvolvedor Backend

## O Teste:
API REST para cadastro de colaboradores.
A ideia é criar um sistema capaz de cadastrar colaboradores e escalas de trabalho (por exemplo, "Escala Padrão - 09:00 às 18:00"). 
A API Contém:
- Autenticação (via Token usando Laravel Passport)
- ACL para controlar os níveis de acesso.
- Listagem de colaboradores.
- Listagem de escalas de trabalho
- Cadastro de uma escala. Basta conter o nome da escala (Ex. "Escala Padrão - 09:00 às 18:00").
- Cadastro de um colaborador.
- Edição de dados de um Colaborador.
- Exclusão de um colaborador.
- Possibilidade de buscar colaboradores pelo nome, documento, pela escala de trabalho
- A stack foi a seguinte:
- Linguagem PHP
- Desenvolvimento do backend com o framework abaixo:
- Laravel (PHP)

- Utilizei um banco de dados relacional, PostgreSQL.
- A única rota pública da aplicação foi para o login
- Cada colaborador só pôde ter uma escala vinculada

## Desafio extra:
Aplicações em grande escala exigem um processamento e uma arquitetura bem trabalhados e organizados para suportar a quantidade de carga. O desafio extra consistia em receber um registro de ponto com as seguintes informações:

- Horário do registro
- Latitude e longitude do registro
- Foto (selfie) do registro - opcional

Essas informações são enviadas para uma fila e, em seguida, consumidas e gravadas no banco de dados:
- Utilizei um endpoint na API para enviar as informações de registro para uma fila.
- Essa fila será consumida e as informações gravadas no banco de dados.
- Dei preferência para rodar tudo usando o Docker Compose

## Docker - Project Setup Laravel

```sh
cp .env.example .env
docker-compose up -d --build
docker-compose exec app composer install
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan l5-swagger:generate
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed
docker-compose exec app php artisan passport:install
```

### To user admin

```sh
{
'email' => "administrador",
'password' => "password"
}
```

### Test battery

```sh
docker-compose exec app php artisan key:generate --env=testing
docker-compose exec app php artisan migrate --env=testing
docker-compose exec app php artisan test
```

Open [http://localhost:8000/api/docs](http://localhost:8000/api/docs) with your browser to see the result.

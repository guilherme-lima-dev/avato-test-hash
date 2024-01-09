
# Avato Test




## Documentação da API

#### Retorna todos os resultados

```http
  GET /results
```

| Parâmetro   | Tipo       | Descrição                           |
| :---------- | :--------- | :---------------------------------- |
| `page` | `int` | Numero da página |
| `maxPerPage` | `int` | limite de itens da página |
| `lessThanAttempts` | `int` | filtro pelo numero de tentativas (menos de x) |
| `attempts` | `int` | filtra por essa quantidade exata de tentativas (igual a x) |
| `alias` | `string` | filtra pelo alias do comando |

#### Calcula o hash

```http
  POST /calculate-hash/${inputString}
```

| Parâmetro   | Tipo       | Descrição                                   |
| :---------- | :--------- | :------------------------------------------ |
| `inputString`      | `string` | **Obrigatório**. A string que você quer gerar o hash |




## Execução

Para rodar o projeto

##### Na pasta raiz do projeto rode
```bash
  docker compose up -d
```
##### Entre no container
```bash
  docker compose exec php zsh
```
##### Rode o arquivo de deploy
```bash
  ./deploy.sh
```
##### Suba o servidor
```bash
  php -S 0.0.0.0:8000 -t public
```

## Sobre o command
```bash
  php bin/console avato:test string --requests=100 --alias=teste100
```
Você pode enviar um alias para ele salvar no banco e ficar mais facil de encontrar qual foi o teste que você fez.
 


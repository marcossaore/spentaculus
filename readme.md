<!-- [![Coverage Status](https://coveralls.io/repos/github/marcossaore/abborah/badge.svg)](https://coveralls.io/github/marcossaore/abborah) -->

# Spentaculus

O Spentaculus é a solução definitiva para simplificar a gestão financeira pessoal. Nosso app intuitivo e eficiente permite que você gerencie suas despesas de forma fácil e organizada. Seja para controlar seus gastos diários, monitorar investimentos ou planejar seu orçamento mensal, o Spentaculus oferece uma experiência completa.

## Documentação 

A documentação utilizada foi o Thunder Client, é possivel exportar a collection e o enviroment armazenados dentro da pasta [docs](docs).

## Tecnologias utilizadas

* Laravel
* PEST
* Quasar
* Docker
* Sail
* Typescript
* MySQL
* Github Actions

## Sobre o projeto

O projeto usa o sail, portanto é necessário ter o docker instalado, foram criados scripts para facilitar a execução de comandos. Instale o php 8.2 e composer na sua máquina apenas para ser mais convenientes rodar os comandos do composer.

## Dossiê

Durante o projeto escrevi um [dossiê](https://docs.google.com/document/d/1IqLXbV0MbzBIFeUwwAHYttYwkzqXoEcKBx23G5b8XJg/edit?usp=sharing) com as minhas principais dúvidas e consternações.

## Como rodar o projeto

    composer up // roda o app
    composer queue:work // executa a fila
    composer migrate // executa as migrations
    composer app:dev // executar o app front end

### Como rodar os testes

    composer test:unit // testes unitários
    composer test:feat // testes features
    composer test // executa todos os testes
    composer test:coverage // executa todos os testes


>  This is a challenge by [Coodesh](https://coodesh.com/)




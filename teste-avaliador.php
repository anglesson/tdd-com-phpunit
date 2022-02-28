<?php

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use Alura\Leilao\Service\Avaliador;

require 'vendor/autoload.php';

// Arrrange - Given
$leilao = new Leilao('Fiat 147 0KM');

$maria = new Usuario('Maria');
$joao = new Usuario('Joao');

$leilao->recebeLance(new Lance($joao, 2000));
$leilao->recebeLance(new Lance($maria, 2500));

// Act - When
$leiloeiro = new Avaliador();
$leiloeiro->avalia($leilao);

$maiorValor = $leiloeiro->getMaiorValor();

// Assert - Then

$valorEsperado = 2500;

if ($valorEsperado == $maiorValor) {
    echo 'Teste OK';
} else {
    echo 'Teste FAILED!';
}




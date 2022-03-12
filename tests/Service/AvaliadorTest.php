<?php

namespace Alura\Leilao\Tests\Service;

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use Alura\Leilao\Service\Avaliador;
use PHPUnit\Framework\TestCase;

class AvaliadorTest extends TestCase
{
    private Avaliador $leiloeiro;

    protected function setUp(): void
    {
        $this->leiloeiro = new Avaliador();
    }

    /**
     * @dataProvider leilaoEmOrdemAleatoria
     * @dataProvider leilaoEmOrdemCrescente
     * @dataProvider leilaoEmOrdemDecrescente
     */
    public function testAvaliadorDeveEncontrarOMaiorValorDeLances(Leilao $leilao)
    {

        // Act - When
        $this->leiloeiro->avalia($leilao);

        $maiorValor = $this->leiloeiro->getMaiorValor();

        // Assert - Then
        $this->assertEquals(2500, $maiorValor);
    }

    /**
     * @dataProvider leilaoEmOrdemAleatoria
     * @dataProvider leilaoEmOrdemCrescente
     * @dataProvider leilaoEmOrdemDecrescente
     */
    public function testAvaliadorDeveEncontrarOMenorValorDeLances(Leilao $leilao)
    {

        $this->leiloeiro->avalia($leilao);

        $menorValor = $this->leiloeiro->getMenorValor();

        // Assert - Then
        $this->assertEquals(1700, $menorValor);
    }

    /**
     * @dataProvider leilaoEmOrdemAleatoria
     * @dataProvider leilaoEmOrdemCrescente
     * @dataProvider leilaoEmOrdemDecrescente
     */
    public function testAvaliadorDeveBuscar3MaioresValores(Leilao $leilao)
    {
        $this->leiloeiro->avalia($leilao);

        $maiores =  $this->leiloeiro->getMaioresLances();
        static::assertCount(3, $maiores);
        static ::assertEquals(2500, $maiores[0]->getValor());
        static ::assertEquals(2000, $maiores[1]->getValor());
        static ::assertEquals(1700, $maiores[2]->getValor());
    }

    public function testLeilaoVazioNaoPodeSalvarAvaliado()
    {
        $this->expectException(\DomainException::class);
        $leiao = new Leilao('Fusca Azul');
        $this->leiloeiro->avalia($leiao);
    }

    public function testLeilaoFinalizadoNaoPodeSerFinalizado()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Leilão já finalizado');
        $leilao = new Leilao('Fiat 147 0KM');
        $leilao->recebeLance(new Lance(new Usuario("Ang"), 2000));
        $leilao->finaliza();

        $this->leiloeiro->avalia($leilao);
    }

    public function leilaoEmOrdemCrescente(): array
    {
        $leilao = new Leilao('Fiat 147 0KM');

        $maria = new Usuario('Maria');
        $joao = new Usuario('Joao');
        $ana = new Usuario('Ana');

        $leilao->recebeLance(new Lance($ana, 1700));
        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($maria, 2500));

        return [[$leilao]];
    }

    public function leilaoEmOrdemDecrescente(): array
    {
        $leilao = new Leilao('Fiat 147 0KM');

        $maria = new Usuario('Maria');
        $joao = new Usuario('Joao');
        $ana = new Usuario('Ana');

        $leilao->recebeLance(new Lance($maria, 2500));
        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($ana, 1700));

        return [[$leilao]];
    }

    public function leilaoEmOrdemAleatoria(): array
    {
        $leilao = new Leilao('Fiat 147 0KM');

        $maria = new Usuario('Maria');
        $joao = new Usuario('Joao');
        $ana = new Usuario('Ana');

        $leilao->recebeLance(new Lance($maria, 2500));
        $leilao->recebeLance(new Lance($ana, 1700));
        $leilao->recebeLance(new Lance($joao, 2000));

        return [[$leilao]];
    }
}
<?php

namespace Alura\Leilao\Model;

use phpDocumentor\Reflection\Types\Collection;
use phpDocumentor\Reflection\Types\True_;

class Leilao
{
    /** @var Lance[] */
    private $lances;
    /** @var string */
    private $descricao;
    private bool $finalizado;

    public function __construct(string $descricao)
    {
        $this->descricao = $descricao;
        $this->lances = [];
        $this->finalizado = false;
    }

    public function recebeLance(Lance $lance)
    {
        if (!empty($this->lances) && $this->ehDoUltimoUsuario($lance))
        {
            throw new \DomainException('Usuário não pode propor 2 lances consecutivos');
        }

        $totalLancesUsuario = $this->quantidadeDeLancesPorUsuario($lance->getUsuario());

        if ($totalLancesUsuario >= 5)
        {
            throw new \DomainException('Usuário não pode propor mais de 5 lances por leilao');
        }

        $this->lances[] = $lance;
    }

    /**
     * @return Lance[]
     */
    public function getLances(): array
    {
        return $this->lances;
    }

    public function finaliza()
    {
        $this->finalizado = true;
    }

    private function ehDoUltimoUsuario(Lance $lance): bool
    {
        return $lance->getUsuario() === $this->lances[count($this->lances) - 1]->getUsuario();
    }

    /**
     * @param Usuario $usuario
     * @return int
     */
    private function quantidadeDeLancesPorUsuario(Usuario $usuario): int
    {
        return array_reduce(
            $this->lances,
            function (int $totalAcumulado, Lance $lanceAtual) use ($usuario) {
                if ($lanceAtual->getUsuario() === $usuario) {
                    return $totalAcumulado + 1;
                }
                return $totalAcumulado;
            },
            0
        );
    }

    public function estaFinalizado(): bool
    {
        return $this->finalizado;
    }
}

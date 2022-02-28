<?php

namespace Alura\Leilao\Model;

use phpDocumentor\Reflection\Types\Collection;

class Leilao
{
    /** @var Lance[] */
    private $lances;
    /** @var string */
    private $descricao;

    public function __construct(string $descricao)
    {
        $this->descricao = $descricao;
        $this->lances = [];
    }

    public function recebeLance(Lance $lance)
    {
        if (!empty($this->lances) && $this->ehDoUltimoUsuario($lance))
        {
            return;
        }

        $totalLancesUsuario = $this->quantidadeDeLancesPorUsuario($lance->getUsuario());

        if ($totalLancesUsuario >= 5)
        {
            return;
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
}

<?php

namespace Tests\Unit;

use App\Enums\TipoMovimientoCodigo;
use PHPUnit\Framework\TestCase;

class TipoMovimientoCodigoTest extends TestCase
{
    public function test_cada_tipo_calcula_su_stock_resultante(): void
    {
        $this->assertSame(15, TipoMovimientoCodigo::ENTRADA->aplicar(10, 5));
        $this->assertSame(5, TipoMovimientoCodigo::SALIDA->aplicar(10, 5));
        $this->assertSame(7, TipoMovimientoCodigo::AJUSTE->aplicar(10, 7));
    }

    public function test_acepta_texto_en_minusculas(): void
    {
        $this->assertSame(TipoMovimientoCodigo::SALIDA, TipoMovimientoCodigo::desdeTexto(' salida '));
    }
}

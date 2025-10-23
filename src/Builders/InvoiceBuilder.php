<?php

namespace Rooberthh\Faktura\Builders;

use Illuminate\Database\Eloquent\Builder;
use Rooberthh\Faktura\Models\Invoice;
use Rooberthh\Faktura\Support\Enums\Provider;

/**
 * @extends Builder<Invoice>
 */
class InvoiceBuilder extends Builder
{
    /**
     * @param Provider $provider
     * @return Builder<Invoice>
     */
    public function provider(Provider $provider): Builder
    {
        $this->where('provider', $provider);

        return $this;
    }
}

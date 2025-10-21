<?php

namespace Rooberthh\Faktura\Tests\Stubs;

use Illuminate\Database\Eloquent\Model;
use Rooberthh\Faktura\Contracts\Billable;
use Rooberthh\Faktura\Support\Objects\Buyer;

class BillableUser extends Model implements Billable
{
    protected $table = 'billable_users';

    public function toBuyer(): Buyer
    {
        return new Buyer(
            name: 'name',
            address: 'address',
            city: 'city',
            postalCode: '54135',
            country: 'country',
            orgNumber: '199803199570',
            vatNumber: '12345678',
            externalId: $this->getExternalId(),
        );
    }

    public function getExternalId(): ?string
    {
        return 'my-test-external-id';
    }
}

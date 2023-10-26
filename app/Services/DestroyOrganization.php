<?php

namespace App\Services;

class DestroyOrganization extends BaseService
{
    public function execute(): void
    {
        auth()->user()->organization->delete();
    }
}

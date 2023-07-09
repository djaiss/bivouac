<?php

namespace App\Services;

use App\Models\Organization;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Http;

class ActivateLicenceKey extends BaseService
{
    private array $data;
    private Organization $organization;

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'licence_key' => 'required|string|max:255',
        ];
    }

    public function execute(array $data): Organization
    {
        $this->data = $data;
        $this->validate();
        $this->call();
        $this->activate();

        return $this->organization;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $user = User::findOrFail($this->data['user_id']);

        $this->organization = $user->organization;

        if ($this->organization->licence_key !== null) {
            throw new Exception(trans('Licence key already activated.'));
        }
    }

    private function call(): void
    {
        $response = Http::post('https://api.lemonsqueezy.com/v1/licenses/activate', [
            'license_key' => $this->data['licence_key'],
            'instance_name' => 'test',
        ]);

        $jsonData = $response->json();

        if (! $jsonData['activated']) {
            throw new Exception(trans('Invalid licence key.'));
        }
    }

    private function activate(): void
    {
        $this->organization->update([
            'licence_key' => $this->data['licence_key'],
        ]);
    }
}

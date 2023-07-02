<?php

namespace App\Services;

use App\Models\TeamType;
use App\Models\User;

class CreateTeamType extends BaseService
{
    private TeamType $teamType;
    private User $user;
    private array $data;

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'label' => 'required|string|max:255',
        ];
    }

    public function execute(array $data): TeamType
    {
        $this->data = $data;
        $this->validate();
        $this->create();

        return $this->teamType;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->user = User::findOrFail($this->data['user_id']);
    }

    private function create(): void
    {
        // determine the new position
        $newPosition = $this->user->organization->teamTypes()
            ->max('position');
        $newPosition++;

        $this->teamType = TeamType::create([
            'organization_id' => $this->user->organization_id,
            'label' => $this->data['label'],
            'position' => $newPosition,
        ]);
    }
}

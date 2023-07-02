<?php

namespace App\Services;

use App\Models\TeamType;
use App\Models\User;

class DestroyTeamType extends BaseService
{
    private TeamType $teamType;
    private array $data;

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'team_type_id' => 'required|integer|exists:team_types,id',
        ];
    }

    public function execute(array $data): void
    {
        $this->data = $data;
        $this->validate();

        $this->teamType->delete();
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $user = User::findOrFail($this->data['user_id']);
        $this->teamType = TeamType::where('organization_id', $user->organization_id)
            ->findOrFail($this->data['team_type_id']);
    }
}

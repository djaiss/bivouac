<?php

namespace App\Services;

use App\Models\TeamType;
use App\Models\User;

class UpdateTeamType extends BaseService
{
    private TeamType $teamType;
    private User $user;
    private array $data;

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'team_type_id' => 'required|integer|exists:team_types,id',
            'label' => 'required|string|max:255',
        ];
    }

    public function execute(array $data): TeamType
    {
        $this->data = $data;
        $this->validate();
        $this->edit();

        return $this->teamType;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->user = User::findOrFail($this->data['user_id']);
        $this->teamType = TeamType::where('organization_id', $this->user->organization_id)
            ->findOrFail($this->data['team_type_id']);
    }

    private function edit(): void
    {
        $this->teamType->label = $this->data['label'];
        $this->teamType->save();
    }
}

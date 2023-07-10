<?php

namespace App\Services;

use App\Exceptions\NotEnoughPermissionException;
use App\Models\Message;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MarkMessageAsRead extends BaseService
{
    private array $data;

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'message_id' => 'required|integer|exists:messages,id',
        ];
    }

    public function execute(array $data): void
    {
        $this->data = $data;
        $this->validate();
        $this->read();
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $user = User::findOrFail($this->data['user_id']);

        $message = Message::findOrFail($this->data['message_id']);

        $project = Project::where('organization_id', $user->organization_id)
            ->findOrFail($message->project_id);

        if ($project->users()->where('user_id', $user->id)->doesntExist() && ! $project->is_public) {
            throw new NotEnoughPermissionException;
        }
    }

    private function read(): void
    {
        $count = DB::table('message_read_status')
            ->where('message_id', $this->data['message_id'])
            ->where('user_id', $this->data['user_id'])
            ->count();

        if ($count > 0) {
            return;
        }

        DB::table('message_read_status')->insert([
            'message_id' => $this->data['message_id'],
            'user_id' => $this->data['user_id'],
            'created_at' => Carbon::now(),
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Events\ChatEvent;
use App\Models\Chat;
use App\Models\Doctor;
use App\Models\Message;
use App\Models\Patient;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatsController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function show(Request $request): JsonResponse
    {
        try {
            $chatId = $request->query('chat');
            $userId = Auth::user()->id;

            $messages = Message::query()
                ->where('chat_id', $chatId)
                ->with('user')
                ->get();

            foreach ($messages as $message) {
                $message->diffForHumans = $message->created_at->diffForHumans();
                if ($message->user_id == $userId) {
                    $message->right = true;
                }
            }

            return response()->json([
                'messages' => $messages,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function sendMessage(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            $chatId = $request->input('chat_id');
            $message = $request->input('message');
            $userId2 = $request->input('user_id2');

            $message = Message::query()
                ->create([
                    'chat_id' => $chatId,
                    'user_id' => $user->id,
                    'message' => $message,
                ]);

            event(new ChatEvent($userId2, $chatId));

            return response()->json([
                'message' => $message,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * @return JsonResponse
     */
    public function searchChatByDoctor(): JsonResponse
    {
        $doctorId = Auth::user()->doctor->id;
        $doctorUserId = Auth::user()->id;

        // get patients according to doctor id
        $patients = Patient::query()
            ->with([
                'user',
                'doctorPatient'
            ])
            ->whereHas('doctorPatient', function ($query) use ($doctorId) {
                $query->where('doctor_id', $doctorId);
            })
            ->get();

        $patientList = [];
        foreach ($patients as $patient) {
            $message = $this->setChats($doctorUserId, $patient->user->id);
            $patientList[] = (object)[
                'id' => $patient->user->id,
                'name' => $patient->name,
                'lastMessage' => $message,
            ];
        }

        return response()->json([
            'data' => $patientList,
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function searchChatByPatient(): JsonResponse
    {
        $patientId = Auth::user()->patient->id;
        $patientUserId = Auth::user()->id;

        // get doctors, according to doctor id
        $doctors = Doctor::query()
            ->with([
                'user',
                'patientDoctor'
            ])
            ->whereHas('patientDoctor', function ($query) use ($patientId) {
                $query->where('patient_id', $patientId);
            })
            ->get();

        $doctorList = [];
        foreach ($doctors as $doctor) {
            $message = $this->setChats($patientUserId, $doctor->user->id);
            $doctorList[] = (object)[
                'id' => $doctor->user->id,
                'name' => $doctor->name,
                'lastMessage' => $message,
            ];
        }

        return response()->json([
            'data' => $doctorList,
        ]);
    }

    /**
     * @param $user1Id
     * @param $user2Id
     * @return object|null
     */
    private function setChats($user1Id, $user2Id): ?object
    {
        try {
            $chat = Chat::query()
                ->where(function ($query) use ($user1Id, $user2Id) {
                    $query->where('user1_id', $user1Id)
                        ->where('user2_id', $user2Id);
                })
                ->orWhere(function ($query) use ($user1Id, $user2Id) {
                    $query->where('user1_id', $user2Id)
                        ->where('user2_id', $user1Id);
                })
                ->first();

            if ($chat) {
                $messages = Message::query()
                    ->where('chat_id', $chat->id)
                    ->orderBy('created_at', 'desc')
                    ->get();

                if (sizeof($messages) == 0) {
                    return (object)[
                        'id' => $chat->id,
                        'message' => '',
                        'created_at_text' => 'Start a conversation',
                    ];
                } else {
                    return (object)[
                        'id' => $chat->id,
                        'message' => \Str::limit($messages[0]->message, 30),
                        'created_at_text' => $messages[0]->created_at->diffForHumans(),
                    ];
                }
            } else {
                $chat = Chat::query()->create([
                    'user1_id' => $user1Id,
                    'user2_id' => $user2Id,
                ]);

                return (object)[
                    'id' => $chat->id,
                    'message' => '',
                    'created_at_text' => 'Start a conversation',
                ];
            }
        } catch (\Exception $e) {
            \Log::info('info', [$e->getMessage()]);
            return null;
        }
    }
}

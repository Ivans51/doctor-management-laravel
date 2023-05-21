<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Psr\Http\Message\StreamInterface;

class
UserController extends Controller
{
    /**
     * @return View|\Illuminate\Foundation\Application|Factory|Application
     */
    public function index(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        return view('pages.admin.admins.index');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function indexLimit(Request $request): JsonResponse
    {
        $limit = $request->limit ?? 10;
        $id = Role::query()->where('name', 'admin')->first()->id;

        $admins = User::query()
            ->where('role_id', $id)
            ->orderBy('created_at', 'desc')
            ->paginate($limit);

        $title = 'Delete User!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);

        return response()->json([
            'status' => 'success',
            'message' => 'Data retrieved successfully',
            'data' => $admins
        ]);
    }

    /**
     * Search user with parameter email and name
     * @param Request $request
     * @return JsonResponse
     */
    public function searchUser(Request $request): JsonResponse
    {
        $limit = $request->limit ?? 10;

        $users = User::query()
            ->where('name', 'LIKE', "%{$request->search}%")
            ->orWhere('email', 'LIKE', "%{$request->search}%")
            ->orderBy('created_at', 'desc')
            ->paginate($limit);

        return response()->json([
            'status' => 'success',
            'message' => 'Data retrieved successfully',
            'data' => $users
        ]);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $user = User::query()->findOrFail($id);
        return response()->json($user);
    }

    /**
     * @return Factory|View|Application
     */
    public function create(): Factory|View|Application
    {
        return view('pages.admin.admins.create');
    }

    /**
     * @param $id
     * @return Factory|View|Application
     */
    public function edit($id): Factory|View|Application
    {
        $user = User::query()->findOrFail($id);
        return view('pages.admin.admins.edit', compact('user'));
    }

    /**
     * Create user and password with bcrypt
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|unique:users,email',
            'password' => 'required|min:8|max:20|confirmed',
        ]);
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => Role::query()->where('name', 'admin')->first()->id,
            'password' => bcrypt($request->password),
        ]);
        return redirect()->back()->with('success', 'User created successfully');
    }

    /**
     * Update user and check is fiel password exist with bcrypt
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id): RedirectResponse
    {
        // validate fields
        $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|unique:users,email,' . $id,
            'password' => 'nullable|min:8|max:20',
        ]);

        $user = User::find($id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);
        if ($request->password) {
            $user->update([
                'password' => bcrypt($request->password),
            ]);
        }
        return redirect()->back()->with('success', 'User updated successfully');
    }

    /**
     * Delete user by id and check is user exist
     * @param $id
     * @return RedirectResponse
     */
    function destroy($id): RedirectResponse
    {
        User::query()->where('id', $id)->delete();

        return redirect()->back()->with('success', 'User deleted successfully');
    }

    /**ç
     * Send push notification to user
     * @param $fcmToken
     * @param $title
     * @param $body
     * @return StreamInterface
     * @throws GuzzleException
     */
    function sendPushNotification($fcmToken, $title, $body): StreamInterface
    {
        $client = new Client();
        $url = 'https://fcm.googleapis.com/fcm/send';

        $headers = [
            'Authorization' => 'key=BOCa32-4PYTqO-WGRzVnKFkBvwHRJNghBuaPezo1JtL47kYJGwhSFf-dJBA-1xkHcPudCBsLqQoUCtbjBsuczdw',
            'Content-Type' => 'application/json',
        ];

        $data = [
            'to' => $fcmToken,
            'notification' => [
                'title' => $title,
                'body' => $body,
            ],
        ];

        $response = $client->post($url, [
            'headers' => $headers,
            'json' => $data,
        ]);

        return $response->getBody();
    }

}

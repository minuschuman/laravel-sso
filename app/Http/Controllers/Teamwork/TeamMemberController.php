<?php

namespace App\Http\Controllers\Teamwork;

use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Mpociot\Teamwork\Facades\Teamwork;
use Mpociot\Teamwork\TeamInvite;

class TeamMemberController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the members of the given team.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $team = Team::findOrFail($id);
        if (request()->has('search')) {
            $searchTerm = request()->input('search');
            $users = $team->getUsersWithRolesQuery()->where('name', 'like', "%{$searchTerm}%");;
        } else {
            $users = $team->getUsersWithRolesQuery();
        }
        $users = $users->paginate(5);

        return view('teamwork.members.index', compact('users', 'team'))
            ->with('i', (request()->input('page', 1) - 1) * 5);;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $team_id
     * @param int $user_id
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function destroy($team_id, $user_id)
    {
        $teamModel = config('teamwork.team_model');
        $team = $teamModel::findOrFail($team_id);
        if (!auth()->user()->isOwnerOfTeam($team)) {
            abort(403);
        }

        $userModel = config('teamwork.user_model');
        $user = $userModel::findOrFail($user_id);
        if ($user->getKey() === auth()->user()->getKey()) {
            abort(403);
        }

        $user->detachTeam($team);

        return redirect(route('teams.index'));
    }

    /**
     * @param Request $request
     * @param int $team_id
     * @return $this
     */
    public function invite(Request $request, $team_id)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $teamModel = config('teamwork.team_model');
        $team = $teamModel::findOrFail($team_id);

        if (!Teamwork::hasPendingInvite($request->email, $team)) {
            Teamwork::inviteToTeam($request->email, $team, function ($invite) {
                Mail::send('teamwork.emails.invite', ['team' => $invite->team, 'invite' => $invite], function ($m) use ($invite) {
                    $m->to($invite->email)->subject('Invitation to join team ' . $invite->team->name);
                });
                // Send email to user
            });
        } else {
            return redirect()->back()->withErrors([
                'email' => 'The email address is already invited to the team.',
            ]);
        }

        return redirect(route('teams.members.show', $team->id));
    }

    /**
     * Resend an invitation mail.
     *
     * @param $invite_id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function resendInvite($invite_id)
    {
        $invite = TeamInvite::findOrFail($invite_id);
        Mail::send('teamwork.emails.invite', ['team' => $invite->team, 'invite' => $invite], function ($m) use ($invite) {
            $m->to($invite->email)->subject('Invitation to join team ' . $invite->team->name);
        });

        return redirect(route('teams.members.show', $invite->team));
    }

    public function create(Team $team)
    {
        $paginateSize = 5;
        settype($paginateSize, 'integer');
        $teamId = $team->id;
        $users = User::leftJoin('team_user', function ($join) use ($teamId) {
            $join->on('users.id', '=', 'team_user.user_id')
                ->where('team_user.team_id', '=', $teamId);
        })->paginate($paginateSize);
        return view('teamwork.members.create', compact('users', 'team'))
            ->with('i', (request()->input('page', 1) - 1) * $paginateSize);
    }

    public function store(Request $request, Team $team)
    {
        $userIds = json_decode($request->input('user_ids'));
        $users = User::whereIn('id', $userIds)->get();
        foreach ($users as $user) {
            // $user->attachTeam($team, ['role' => 'member', 'status' => 'active']);
            $user->teams()->syncWithoutDetaching([$team->id => ['role' => 'member', 'status' => 'active']]);
        }

        $memberIds = json_decode($request->input('member_ids'));
        $members = User::whereIn('id', $memberIds)->get();
        foreach ($members as $member) {
            $member->teams()->syncWithoutDetaching([$team->id => ['role' => 'admin', 'status' => 'active']]);
        }
        return redirect()->route('teams.members.index', $team);
    }
}

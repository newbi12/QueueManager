<?php

namespace App\Http\Controllers\Member;

use App\Employee;
use App\Job;
use App\Service\ChatClient;
use App\Service\Client;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class MemberController extends Controller
{

    private $clientService;

    /**
     * MemberController constructor.
     * @param Client $clientService
     */
    public function __construct(Client $clientService)
    {
        $this->clientService = $clientService;
    }

    public function requestTicket(Request $request)
    {
        if (!MemberHelper::isMember()) {
            return redirect()->route('categories.index');
        }

        $assign = $this->clientService->registerMemberInLine(MemberHelper::getMember()->Id, $request->input('jobId'));

        if ($assign) {
            return redirect()->route('member.ticket.show', $assign->Id);
        } else {
            return redirect()->route('member.errorOnTicket');
        }
    }

    public function myProfile()
    {
        return view('member.profile')
            ->with('tickets', MemberHelper::myTickets())
            ->with('member', MemberHelper::getMember())
            ->with('name', MemberHelper::myName())
            ->with('email', MemberHelper::myEmail());
    }

    public function show($ticketId)
    {
        if (!MemberHelper::isTheTicketMine($ticketId)) {
            return redirect()->route('categories.index');
        }

        $job = Job::find(MemberHelper::getTicket($ticketId)->JobId);

        return view('member.ticket.show')
            ->with('ticket', MemberHelper::getTicket($ticketId))
            ->with('jobAverageTime', $job->AverageWaitingTime)
            ->with('jobCurrentNumber', $job->LastNumber);
    }

    public function destroy($ticketId)
    {
        if (!MemberHelper::isTheTicketMine($ticketId)) {
            return redirect()->route('categories.index');
        }

        $this->clientService->discardTicket($ticketId, true);

        return redirect()->route('member.profile');
    }

    public function edit()
    {
        return view('member.edit')
            ->with('member', MemberHelper::getMember())
            ->with('name', MemberHelper::myName())
            ->with('email', MemberHelper::myEmail());
    }

    public function update(Request $request)
    {
        if (!(MemberHelper::isMember())) {
            return redirect()->route('categories.index');
        }

        $user = Auth::getUser();

        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
        ]);

        return redirect()->route('member.profile');
    }


//    /**
//     * A button with "check chat request" can solve this problem.
//     * It support ONLY 1 member
//     */
//    public function login(Request $request)
//    {
//        $me = MemberHelper::getMember();
//
//        $threadId = ChatClient::getThreadId(['MemberId' => $me->Id]);
//
//        if ($threadId == ChatClient::$ERROR) {
//            return ['status' => ChatClient::$ERROR];
//        } else {
//            return view('member.chat')
//                ->with('data',
//                    [
//                        'status' => 'ok',
//                        'data' => ChatClient::pull($threadId, ChatClient::$MEMBER, true),
//                        'memberName' => MemberHelper::myName(),
//                        'employeeName' => MemberHelper::getEmployeeNameWithThread($threadId),
//                    ]);
//        }
//    }

    public function login(){
        return view('member.chat');
    }
    /*
     * API
     */
    public function getChatHistory(Request $request)
    {
        $me = MemberHelper::getMember();

        $threadId = ChatClient::getThreadId(['MemberId' => $me->Id]);

        if ($threadId == ChatClient::$ERROR) {
            return ['status' => ChatClient::$ERROR];
        } else {
            return [
                'status' => 'ok',
                'data' => ChatClient::pull($threadId, ChatClient::$MEMBER),
                'memberName' => MemberHelper::myName(),
                'employeeName' => MemberHelper::getEmployeeNameWithThread($threadId),
            ];
        }
    }

    /*
     * API
     */
    public function postMessage(Request $request)
    {
        $me = MemberHelper::getMember();

        $threadId = ChatClient::getThreadId(['MemberId' => $me->Id]);

        if ($threadId == ChatClient::$ERROR) {
            return ['status' => ChatClient::$ERROR];
        } else {
            return [
                'status' => 'ok',
                'data' => ChatClient::push($threadId, ChatClient::$MEMBER, $request->input('body'))
            ];
        }
    }

    public function logout()
    {
        $me = MemberHelper::getMember();

        $threadId = ChatClient::getThreadId(['MemberId' => $me->Id]);

        ChatClient::requestEnd($threadId);

        return redirect()->route('member.profile');
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Zoom;
class ZoomController extends Controller
{
    public function createMeeting(Request $request)
    {
        $meetings = Zoom::createMeeting([
            "agenda" => 'your agenda',
            "topic" => 'your topic',
            "type" => 2, // 1 => instant, 2 => scheduled, 3 => recurring with no fixed time, 8 => recurring with fixed time
            "duration" => 60, // in minutes
            "timezone" => 'Africa/Cairo', // set your timezone to Africa/Cairo
            "password" => 'set your password',
            "start_time" => 'set your start time', // set your start time
            "template_id" => 'set your template id', // set your template id  Ex: "Dv4YdINdTk+Z5RToadh5ug==" from https://marketplace.zoom.us/docs/api-reference/zoom-api/meetings/meetingtemplates
            "pre_schedule" => false,  // set true if you want to create a pre-scheduled meeting
            "schedule_for" => 'set your schedule for profile email ', // set your schedule for
            "settings" => [
                'join_before_host' => false, // if you want to join before host set true otherwise set false
                'host_video' => false, // if you want to start video when host join set true otherwise set false
                'participant_video' => false, // if you want to start video when participants join set true otherwise set false
                'mute_upon_entry' => false, // if you want to mute participants when they join the meeting set true otherwise set false
                'waiting_room' => false, // if you want to use waiting room for participants set true otherwise set false
                'audio' => 'both', // values are 'both', 'telephony', 'voip'. default is both.
                'auto_recording' => 'none', // values are 'none', 'local', 'cloud'. default is none.
                'approval_type' => 0, // 0 => Automatically Approve, 1 => Manually Approve, 2 => No Registration Required
            ],

        ]);

        return response()->json($meetings); // Return a JSON response or customize as needed
    }


    public function showmeeting()
    {
        $meeting = Zoom::getMeeting($meetingId);
    }
    
    public function deleteMeeting()
    {
        $meeting = Zoom::deleteMeeting($meetingId);    }
}

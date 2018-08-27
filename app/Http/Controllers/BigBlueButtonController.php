<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\CreateMeetingParameters;
use BigBlueButton\Parameters\GetMeetingInfoParameters;
use BigBlueButton\Parameters\JoinMeetingParameters;

class BigBlueButtonController extends Controller
{
    public function createMeeting(){
        $meetingID = 'random-6202585';
        $meetingName = 'random-6202585';
        $attendee_password = 'ap';
        $moderator_password = 'mp';
        $duration = '';
        $urlLogout = '#';
        $isRecordingTrue = true;

        $bbb = new BigBlueButton();

        $createMeetingParams = new CreateMeetingParameters($meetingID, $meetingName);
        $createMeetingParams->setAttendeePassword($attendee_password);
        $createMeetingParams->setModeratorPassword($moderator_password);
        $createMeetingParams->setDuration($duration);
        $createMeetingParams->setLogoutUrl($urlLogout);
        if ($isRecordingTrue) {
            $createMeetingParams->setRecord(true);
            $createMeetingParams->setAllowStartStopRecording(true);
            $createMeetingParams->setAutoStartRecording(true);
        }

        //dd($bbb);
        $response = $bbb->createMeeting($createMeetingParams);
        //dd($response);
        if ($response->getReturnCode() == 'FAILED') {
            return 'Can\'t create room! please contact our administrator.';
        } else {
            // process after room created
            return $response->getMeetingId();
        }
    }

    public function getMeetingInfo($meetingID, $moderator_password){
        $bbb = new BigBlueButton();

        $getMeetingInfoParams = new GetMeetingInfoParameters($meetingID, '', $moderator_password);
        $response = $bbb->getMeetingInfo($getMeetingInfoParams);
        if ($response->getReturnCode() == 'FAILED') {
            // meeting not found or already closed
            echo 'failed';
        } else {
            // process $response->getRawXml();
            return $response->getRawXml();
        }
    }

    public function joinMeeting(){
        $meetingID = 'random-9340480';
        //$meetingID = $this->createMeeting();
        $name = 'random-9340480';
        $password = 'mp';

        $bbb = new BigBlueButton();

        // $moderator_password for moderator
        $joinMeetingParams = new JoinMeetingParameters($meetingID, $name, $password);
        $joinMeetingParams->setRedirect(true);
        $url = $bbb->getJoinMeetingURL($joinMeetingParams);
        //return redirect($url);
        return $url;
    }
}